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
     * Display a listing of the resource.
     */
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
            })->where(function($q) use ($search) {
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
                $query->orderBy('tipe', 'desc')->orderBy('nama', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('tipe', 'desc')->orderBy('nama', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('tipe', 'desc')->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('tipe', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'size_asc':
                $query->orderBy('tipe', 'desc')->orderBy('size', 'asc');
                break;
            case 'size_desc':
                $query->orderBy('tipe', 'desc')->orderBy('size', 'desc');
                break;
            default:
                $query->orderBy('tipe', 'desc')->orderBy('nama', 'asc');
        }

        $dokumens = $query->get();

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
            'files.*' => 'file|max:10240|mimes:doc,docx,xlsx,jpg,jpeg,png,pdf', // Max 10MB, allowed types
            'parent_id' => 'nullable|exists:dokumens,id',
        ], [
            'files.*.mimes' => 'Tipe file yang diperbolehkan: doc, docx, xlsx, jpg, jpeg, png, pdf.',
            'files.*.max' => 'Ukuran file maksimal adalah 10MB per file.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $parentId = $request->parent_id ?: null;
        $uploadedCount = 0;

        foreach ($request->file('files') as $file) {
            // Check if file name already exists in same parent
            $existing = Dokumen::where('parent_id', $parentId)
                ->where('nama', $file->getClientOriginalName())
                ->where('tipe', 'file')
                ->first();

            if ($existing) {
                continue; // Skip duplicate
            }

            // Store file using trait method
            $fileName = $this->storeFile($file, 'dokumen');
            $filePath = 'dokumen/' . $fileName;

            Dokumen::create([
                'nama' => $file->getClientOriginalName(),
                'tipe' => 'file',
                'parent_id' => $parentId,
                'path' => $fileName,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'user_id' => auth()->id(),
            ]);

            $uploadedCount++;
        }

        $redirectUrl = route('admin.dokumen.index');
        if ($request->folder) {
            $redirectUrl = route('admin.dokumen.index', ['folder' => $request->folder]);
        }
        if ($request->sort) {
            $redirectUrl .= (strpos($redirectUrl, '?') !== false ? '&' : '?') . 'sort=' . $request->sort;
        }

        if ($uploadedCount > 0) {
            return redirect($redirectUrl)->with('success', $uploadedCount . ' file berhasil diupload.');
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
