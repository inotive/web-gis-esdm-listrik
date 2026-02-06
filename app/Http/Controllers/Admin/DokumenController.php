<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UploadFile;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokumenController extends Controller
{
    use UploadFile;

    /**
     * Helper method for natural sorting with month name support
     */
    private function naturalSortKey($name)
    {
        // Map Indonesian month names to numbers for proper sorting
        $monthMap = [
            'JANUARI' => '01',
            'FEBRUARI' => '02',
            'MARET' => '03',
            'APRIL' => '04',
            'MEI' => '05',
            'JUNI' => '06',
            'JULI' => '07',
            'AGUSTUS' => '08',
            'SEPTEMBER' => '09',
            'OKTOBER' => '10',
            'NOVEMBER' => '11',
            'DESEMBER' => '12',
        ];

        // Check if the name is a month name (case insensitive)
        $upperName = strtoupper(trim($name));

        // Check for pattern like "10. OKTOBER" or "1. JANUARI"
        if (preg_match('/^(\d+)\.\s*(.+)$/', $upperName, $matches)) {
            $number = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $monthName = trim($matches[2]);

            if (isset($monthMap[$monthName])) {
                return $number . '_' . $monthMap[$monthName];
            }

            return $number . '_' . $monthName;
        }

        // Check if it's just a month name
        if (isset($monthMap[$upperName])) {
            return $monthMap[$upperName] . '_' . $upperName;
        }

        // For other names, return as is for natural sorting
        return $name;
    }

    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('can:dokumen.view')->only(['index', 'show']); 
        // Note: download logic is checked in method if needed, but generally view implies access
        // Removal of dokumen.download means we rely on view or open access? 
        // User asked to remove permission check for download, so we DON'T add it here.
        
        $this->middleware('can:dokumen.create')->only(['create', 'store', 'storeFolder', 'storeFiles']);
        $this->middleware('can:dokumen.edit')->only(['edit', 'update']);
        $this->middleware('can:dokumen.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $folderId = $request->get('folder');
        $sortBy = $request->get('sort', 'name_asc'); // Default: name ascending
        $search = $request->get('q', ''); // Search query
        $currentFolder = null;
        $isAdmin = Auth::user()->hasAnyRole(['superadmin', 'admin']) ? true : false;
        // Get current folder if navigating into a folder
        if ($folderId) {
            $currentFolder = Dokumen::where('id', $folderId)
                ->when(!$isAdmin, function ($query) {
                    return $query->where('user_id', auth()->id());
                })
                ->where('tipe', 'folder')
                ->firstOrFail();
        }

        // Get documents in current folder (or root if no folder)
        // If search is active, search in ALL folders recursively
        if (!empty($search)) {
            // Search in all folders (ignore current folder restriction when searching)
            $query = Dokumen::when(!$isAdmin, function ($query) {
                return $query->where('user_id', auth()->id());
            })->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('mime_type', 'like', '%' . $search . '%');
            })
                ->with(['user', 'children', 'parent']);
        } else {
            // Normal view: only show items in current folder
            $query = Dokumen::where('parent_id', $folderId ?: null)
                ->when(!$isAdmin, function ($query) {
                    return $query->where('user_id', auth()->id());
                })
                ->with(['user', 'children']);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'name_asc':
            case 'name_desc':
                // Get all items first for natural sorting
                $allItems = $query->get();

                // Separate folders and files
                $folders = $allItems->where('tipe', 'folder');
                $files = $allItems->where('tipe', 'file');

                // Natural sort folders
                $sortedFolders = $folders->sortBy(function ($item) {
                    return $this->naturalSortKey($item->nama);
                }, SORT_NATURAL | SORT_FLAG_CASE);

                // Natural sort files
                $sortedFiles = $files->sortBy(function ($item) {
                    return $this->naturalSortKey($item->nama);
                }, SORT_NATURAL | SORT_FLAG_CASE);

                // Reverse if descending
                if ($sortBy === 'name_desc') {
                    $sortedFolders = $sortedFolders->reverse();
                    $sortedFiles = $sortedFiles->reverse();
                }

                // Merge folders first, then files
                $sorted = $sortedFolders->merge($sortedFiles);

                // Manual pagination
                $perPage = 50;
                $currentPage = request()->get('page', 1);
                $offset = ($currentPage - 1) * $perPage;

                $paginatedItems = $sorted->slice($offset, $perPage)->values();

                $dokumens = new \Illuminate\Pagination\LengthAwarePaginator(
                    $paginatedItems,
                    $sorted->count(),
                    $perPage,
                    $currentPage,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

                $dokumens->appends([
                    'folder' => $folderId,
                    'sort' => $sortBy,
                    'q' => $search,
                ]);
                break;

            case 'date_asc':
                $query->orderBy('tipe', 'desc')->orderBy('created_at', 'asc');
                $dokumens = $query->paginate(50)->appends([
                    'folder' => $folderId,
                    'sort' => $sortBy,
                    'q' => $search,
                ]);
                break;
            case 'date_desc':
                $query->orderBy('tipe', 'desc')->orderBy('created_at', 'desc');
                $dokumens = $query->paginate(50)->appends([
                    'folder' => $folderId,
                    'sort' => $sortBy,
                    'q' => $search,
                ]);
                break;
            case 'size_asc':
                $query->orderBy('tipe', 'desc')->orderBy('size', 'asc');
                $dokumens = $query->paginate(50)->appends([
                    'folder' => $folderId,
                    'sort' => $sortBy,
                    'q' => $search,
                ]);
                break;
            case 'size_desc':
                $query->orderBy('tipe', 'desc')->orderBy('size', 'desc');
                $dokumens = $query->paginate(50)->appends([
                    'folder' => $folderId,
                    'sort' => $sortBy,
                    'q' => $search,
                ]);
                break;
            default:
                // Default: natural sort by name ascending
                $allItems = $query->get();

                $folders = $allItems->where('tipe', 'folder');
                $files = $allItems->where('tipe', 'file');

                $sortedFolders = $folders->sortBy(function ($item) {
                    return $this->naturalSortKey($item->nama);
                }, SORT_NATURAL | SORT_FLAG_CASE);

                $sortedFiles = $files->sortBy(function ($item) {
                    return $this->naturalSortKey($item->nama);
                }, SORT_NATURAL | SORT_FLAG_CASE);

                $sorted = $sortedFolders->merge($sortedFiles);

                $perPage = 50;
                $currentPage = request()->get('page', 1);
                $offset = ($currentPage - 1) * $perPage;

                $paginatedItems = $sorted->slice($offset, $perPage)->values();

                $dokumens = new \Illuminate\Pagination\LengthAwarePaginator(
                    $paginatedItems,
                    $sorted->count(),
                    $perPage,
                    $currentPage,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

                $dokumens->appends([
                    'folder' => $folderId,
                    'sort' => $sortBy,
                    'q' => $search,
                ]);
        }

        // Get breadcrumbs
        $breadcrumbs = [];
        if ($currentFolder) {
            $folder = $currentFolder;
            while ($folder) {
                array_unshift($breadcrumbs, $folder);
                $folder = $folder->parent;
            }
        }

        return view('admin.dokumen.index', [
            'title' => 'Manajemen Dokumen',
            'dokumens' => $dokumens,
            'currentFolder' => $currentFolder,
            'breadcrumbs' => $breadcrumbs,
            'sortBy' => $sortBy,
            'folderId' => $folderId,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created folder.
     */
    public function storeFolder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:dokumens,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $isAdmin = Auth::user()->hasAnyRole(['superadmin', 'admin']);

        // Check if folder name already exists in same parent
        $existing = Dokumen::where('parent_id', $request->parent_id ?: null)
            ->where('nama', $request->nama)
            ->where('tipe', 'folder')
            ->first();

        if ($existing) {
            return back()->withErrors(['nama' => 'Folder dengan nama ini sudah ada di lokasi ini.'])->withInput();
        }

        Dokumen::create([
            'nama' => $request->nama,
            'tipe' => 'folder',
            'parent_id' => $request->parent_id ?: null,
            'user_id' => auth()->id(),
        ]);

        $redirectUrl = route('admin.dokumen.index');
        if ($request->folder) {
            $redirectUrl = route('admin.dokumen.index', ['folder' => $request->folder]);
        }
        if ($request->sort) {
            $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'sort=' . $request->sort;
        }
        return redirect($redirectUrl)->with('success', 'Folder berhasil dibuat.');
    }

    /**
     * Store a newly uploaded file.
     * Allowed types: DOC, DOCX, XLSX, JPG, JPEG, PNG, PDF
     */
    public function storeFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'file|max:10240|mimes:doc,docx,xlsx,xls,ppt,pptx,jpg,jpeg,png,pdf', // Max 10MB, allowed types
            'parent_id' => 'nullable|exists:dokumens,id',
        ], [
            'files.*.mimes' => 'Tipe file yang diperbolehkan: doc, docx, xlsx, xls, ppt, pptx, jpg, jpeg, png, pdf.',
            'files.*.max' => 'Ukuran file maksimal adalah 10MB per file.',
        ]);

        if ($validator->fails()) {
            // Check if AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $parentId = $request->parent_id ?: null;
        $uploadedCount = 0;
        $skippedCount = 0;

        foreach ($request->file('files') as $file) {
            // Check if file name already exists in same parent
            $existing = Dokumen::where('parent_id', $parentId)
                ->where('nama', $file->getClientOriginalName())
                ->where('tipe', 'file')
                ->first();

            if ($existing) {
                $skippedCount++;
                continue; // Skip duplicate
            }

            // Store file using trait method
            $fileName = $this->storeFile($file, 'dokumen');

            // Path lengkap dengan folder dokumen
            $filePath = 'dokumen/' . $fileName;

            Dokumen::create([
                'nama' => $file->getClientOriginalName(),
                'tipe' => 'file',
                'parent_id' => $parentId,
                'path' => $filePath, // Simpan path lengkap
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'user_id' => auth()->id(),
            ]);

            $uploadedCount++;
        }

        // Check if AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            if ($uploadedCount > 0) {
                $message = $uploadedCount . ' file berhasil diupload.';
                if ($skippedCount > 0) {
                    $message .= ' ' . $skippedCount . ' file dilewati (duplikat).';
                }
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'uploaded' => $uploadedCount,
                    'skipped' => $skippedCount,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang berhasil diupload.',
            ], 400);
        }

        // Regular form submission
        $redirectUrl = route('admin.dokumen.index');
        if ($request->folder) {
            $redirectUrl = route('admin.dokumen.index', ['folder' => $request->folder]);
        }
        if ($request->sort) {
            $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'sort=' . $request->sort;
        }

        if ($uploadedCount > 0) {
            $message = $uploadedCount . ' file berhasil diupload.';
            if ($skippedCount > 0) {
                $message .= ' ' . $skippedCount . ' file dilewati (duplikat).';
            }
            return redirect($redirectUrl)->with('success', $message);
        }

        return redirect($redirectUrl)->withErrors(['files' => 'Tidak ada file yang berhasil diupload.']);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Dokumen $dokumen)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if name already exists in same parent
        $existing = Dokumen::where('parent_id', $dokumen->parent_id)
            ->where('nama', $request->nama)
            ->where('id', '!=', $dokumen->id)
            ->where('tipe', $dokumen->tipe)
            ->first();

        if ($existing) {
            return back()->withErrors(['nama' => 'Nama ini sudah digunakan di lokasi ini.'])->withInput();
        }

        $dokumen->update([
            'nama' => $request->nama,
        ]);

        $redirectUrl = route('admin.dokumen.index');
        if ($dokumen->parent_id) {
            $redirectUrl = route('admin.dokumen.index', ['folder' => $dokumen->parent_id]);
        }
        if ($request->sort) {
            $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'sort=' . $request->sort;
        } elseif ($request->get('sort')) {
            $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'sort=' . $request->get('sort');
        }

        return redirect($redirectUrl)->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokumen $dokumen)
    {
        // Delete file from storage if it's a file
        if ($dokumen->isFile() && $dokumen->path) {
            Storage::disk('public')->delete($dokumen->full_path);
        }

        // Delete will cascade to children due to foreign key constraint
        $parentId = $dokumen->parent_id;
        $dokumen->delete();

        $redirectUrl = route('admin.dokumen.index');
        if ($parentId) {
            $redirectUrl = route('admin.dokumen.index', ['folder' => $parentId]);
        }
        // if ($request->get('sort')) {
        //     $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'sort=' . $request->get('sort');
        // }

        return redirect($redirectUrl)->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Download file
     */
    public function download(Dokumen $dokumen)
    {
        if ($dokumen->isFolder()) {
            return back()->withErrors(['error' => 'Tidak dapat mengunduh folder.']);
        }

        $filePath = storage_path('app/public/' . $dokumen->full_path);

        if (!file_exists($filePath)) {
            return back()->withErrors(['error' => 'File tidak ditemukan.']);
        }

        return response()->download($filePath, $dokumen->nama);
    }
}
