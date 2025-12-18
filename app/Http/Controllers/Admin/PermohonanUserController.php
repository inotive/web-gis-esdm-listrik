<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\PermohonanUserDocument;
use App\Models\PermohonanQuestion;
use App\Models\Dokumen;
use App\Helpers\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PermohonanUserController extends Controller
{
    use UploadFile;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $permohonanId)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');


        // Validate permohonan belongs to user's role
        $userRole = Auth::user()->roles()->first()->name ?? null;
        $permohonan = Permohonan::where('id', $permohonanId)
            ->firstOrFail();

        if ($userRole == 'superadmin' || $userRole == 'admin') {
            $query = PermohonanUser::with('permohonan')->where('permohonan_id', $permohonanId);
        } elseif (in_array($userRole, ['desa', 'perusahaan'])) {
            $query = Auth::user()->permohonanUsers()->with('permohonan')->where('permohonan_id', $permohonanId);
        }

        // Search by permohonan name or status
        if ($q) {
            $query->where(function($query) use ($q) {
                $query->whereHas('permohonan', function($query) use ($q) {
                    $query->where('nama', 'like', '%' . $q . '%');
                })->orWhere('status', 'like', '%' . $q . '%');
            });
        }

        $permohonanUsers = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.permohonan-user.index', [
            'title' => 'Permohonan Saya',
            'permohonanUsers' => $permohonanUsers,
            'permohonanId' => $permohonanId,
            'permohonan' => $permohonan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($permohonanId)
    {
        $userRole = Auth::user()->roles()->first()->name ?? null;

        // Validate permohonan belongs to user's role
        $permohonan = Permohonan::with(['questions.options' => function($query) {
            $query->orderBy('id');
        }])->where('id', $permohonanId)
            ->where('jenis_permohonan', $userRole)
            ->firstOrFail();

        // Sort questions by urutan
        $permohonan->questions = $permohonan->questions->sortBy('urutan')->values();

        return view('admin.permohonan-user.create', [
            'title' => 'Buat Permohonan Baru',
            'permohonanId' => $permohonanId,
            'permohonan' => $permohonan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $permohonanId)
    {
        $validated = $request->validate([
            'jawaban' => 'nullable|array',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|string|in:pending,proses,selesai,ditolak',
        ]);

        // Validate permohonan belongs to user's role
        $userRole = Auth::user()->roles()->first()->name ?? null;
        $permohonan = Permohonan::with('questions.options')
            ->where('id', $permohonanId)
            ->where('jenis_permohonan', $userRole)
            ->firstOrFail();

        // Validate required questions
        $requiredQuestions = $permohonan->questions->where('wajib', true);
        $jawaban = $validated['jawaban'] ?? [];

        foreach ($requiredQuestions as $question) {
            if (!isset($jawaban[$question->id]) || empty($jawaban[$question->id])) {
                return back()
                    ->withInput()
                    ->withErrors(['jawaban.' . $question->id => 'Pertanyaan "' . $question->pertanyaan . '" wajib diisi.']);
            }
        }

        // Process checkbox answers (convert array values to integers)
        foreach ($jawaban as $key => $value) {
            if (is_array($value)) {
                $jawaban[$key] = array_map('intval', $value);
            } elseif (is_numeric($value)) {
                $jawaban[$key] = (int) $value;
            }
        }

        DB::beginTransaction();
        try {
            $permohonanUser = PermohonanUser::create([
                'permohonan_id' => $permohonanId,
                'user_id' => Auth::id(),
                'status' => $validated['status'] ?? 'pending',
                'jawaban' => $jawaban,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.permohonan-user.index', $permohonanId)
                ->with('success', 'Permohonan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Check access: admin can see all, user can only see their own
        $userRole = Auth::user()->roles()->first()->name ?? null;
        $isAdmin = in_array($userRole, ['admin', 'superadmin']);

        if (!$isAdmin && $permohonanUser->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only show detail if status is selesai (for non-admin users)
        // Admin can see all status
        if (!$isAdmin && $permohonanUser->status !== 'selesai') {
            return redirect()->route('admin.permohonan-user.index', $permohonanId)
                ->withErrors(['error' => 'Detail hanya dapat dilihat untuk permohonan yang sudah selesai.']);
        }

        $permohonanUser->load([
            'permohonan.questions.options' => function($query) {
                $query->orderBy('id');
            },
            'documents.dokumen',
            'user'
        ]);

        $permohonanUser->permohonan->questions = $permohonanUser->permohonan->questions->sortBy('urutan')->values();

        $jawaban = $permohonanUser->jawaban ?? [];

        return view('admin.permohonan-user.show', [
            'title' => 'Detail Permohonan',
            'permohonanUser' => $permohonanUser,
            'jawaban' => $jawaban,
            'permohonanId' => $permohonanId,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Ensure user can only edit their own permohonan
        if ($permohonanUser->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $permohonanUser->load(['permohonan.questions.options' => function($query) {
            $query->orderBy('id');
        }]);

        $permohonanUser->permohonan->questions = $permohonanUser->permohonan->questions->sortBy('urutan')->values();

        $jawaban = $permohonanUser->jawaban ?? [];

        // Convert option IDs to strings for comparison in view
        foreach ($jawaban as $key => $value) {
            if (is_array($value)) {
                $jawaban[$key] = array_map('strval', $value);
            } else {
                $jawaban[$key] = (string) $value;
            }
        }

        return view('admin.permohonan-user.edit', [
            'title' => 'Edit Permohonan',
            'permohonanUser' => $permohonanUser,
            'jawaban' => $jawaban,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Ensure user can only update their own permohonan
        if ($permohonanUser->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'jawaban' => 'nullable|array',
            'keterangan' => 'nullable|string',
            'status' => 'nullable|string|in:pending,proses,selesai,ditolak',
        ]);

        // Get permohonan to validate questions
        $permohonan = $permohonanUser->permohonan;
        $permohonan->load('questions.options');

        // Validate required questions
        $requiredQuestions = $permohonan->questions->where('wajib', true);
        $jawaban = $validated['jawaban'] ?? [];

        foreach ($requiredQuestions as $question) {
            if (!isset($jawaban[$question->id]) || empty($jawaban[$question->id])) {
                return back()
                    ->withInput()
                    ->withErrors(['jawaban.' . $question->id => 'Pertanyaan "' . $question->pertanyaan . '" wajib diisi.']);
            }
        }

        // Process checkbox answers (convert array values to integers)
        foreach ($jawaban as $key => $value) {
            if (is_array($value)) {
                $jawaban[$key] = array_map('intval', $value);
            } elseif (is_numeric($value)) {
                $jawaban[$key] = (int) $value;
            }
        }

        DB::beginTransaction();
        try {
            $permohonanUser->update([
                'jawaban' => $jawaban,
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => $validated['status'] ?? $permohonanUser->status,
            ]);

            DB::commit();

            return redirect()->route('admin.permohonan-user.index', $permohonanId)
                ->with('success', 'Permohonan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Ensure user can only delete their own permohonan
        if ($permohonanUser->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $permohonanUser->delete();

        return redirect()->route('admin.permohonan-user.index', $permohonanId)
            ->with('success', 'Permohonan berhasil dihapus.');
    }

    /**
     * Approve permohonan user (admin/superadmin only)
     */
    public function approve(Request $request, $permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Only admin/superadmin can approve
        $userRole = Auth::user()->roles()->first()->name ?? null;
        if (!in_array($userRole, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'keterangan' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*.file' => 'required_with:documents.*.nama|file|max:10240',
            'documents.*.nama' => 'required_with:documents.*.file|string|max:255',
            'documents.*.masa_berlaku' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            // Update status and keterangan
            $permohonanUser->update([
                'status' => 'selesai',
                'keterangan' => $validated['keterangan'] ?? $permohonanUser->keterangan,
            ]);

            // Handle document uploads
            if ($request->has('documents') && is_array($request->documents)) {
                foreach ($request->documents as $docData) {
                    // Only process if both file and nama are provided
                    if (isset($docData['file']) && $docData['file']->isValid() && !empty($docData['nama'])) {
                        $file = $docData['file'];

                        // Upload file dan simpan ke tabel Dokumen
                        $fileName = $this->storeFile($file, 'permohonan-documents');

                        // Simpan dokumen ke tabel Dokumen
                        $dokumen = Dokumen::create([
                            'nama' => $file->getClientOriginalName(),
                            'tipe' => 'file',
                            'parent_id' => null, // Dokumen permohonan tidak memiliki parent folder
                            'path' => $fileName,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'user_id' => Auth::id(),
                        ]);

                        // Simpan relasi ke PermohonanUserDocument
                        PermohonanUserDocument::create([
                            'user_id' => Auth::id(),
                            'permohonan_user_id' => $permohonanUser->id,
                            'nama' => $docData['nama'],
                            'masa_berlaku' => !empty($docData['masa_berlaku']) ? $docData['masa_berlaku'] : null,
                            'dokumen_id' => $dokumen->id,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.permohonan-user.show', [$permohonanId, $permohonanUser->id])
                ->with('success', 'Permohonan berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject permohonan user (admin/superadmin only)
     */
    public function reject(Request $request, $permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Only admin/superadmin can reject
        $userRole = Auth::user()->roles()->first()->name ?? null;
        if (!in_array($userRole, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'keterangan' => 'required|string',
        ]);

        $permohonanUser->update([
            'status' => 'ditolak',
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('admin.permohonan-user.index', $permohonanId)
            ->with('success', 'Permohonan berhasil ditolak.');
    }

    /**
     * Update status to proses (admin/superadmin only)
     */
    public function progress(Request $request, $permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Only admin/superadmin can update to proses
        $userRole = Auth::user()->roles()->first()->name ?? null;
        if (!in_array($userRole, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        $permohonanUser->update([
            'status' => 'proses',
            'keterangan' => $validated['keterangan'] ?? $permohonanUser->keterangan,
        ]);

        return redirect()->route('admin.permohonan-user.index', $permohonanId)
            ->with('success', 'Status permohonan berhasil diupdate menjadi Proses.');
    }

    /**
     * Cancel permohonan (user only - only for their own permohonan)
     */
    public function cancel($permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Only user can cancel their own permohonan
        if ($permohonanUser->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only can cancel if status is pending
        if ($permohonanUser->status !== 'pending') {
            return redirect()->route('admin.permohonan-user.index', $permohonanId)
                ->withErrors(['error' => 'Hanya permohonan dengan status Pending yang dapat dibatalkan.']);
        }

        $permohonanUser->delete();

        return redirect()->route('admin.permohonan-user.index', $permohonanId)
            ->with('success', 'Permohonan berhasil dibatalkan.');
    }

    /**
     * Add document to permohonan user (admin/superadmin only)
     */
    public function addDocument(Request $request, $permohonanId, PermohonanUser $permohonanUser)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Only admin/superadmin can add document
        $userRole = Auth::user()->roles()->first()->name ?? null;
        if (!in_array($userRole, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'masa_berlaku' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');

            // Upload file dan simpan ke tabel Dokumen
            $fileName = $this->storeFile($file, 'permohonan-documents');

            $folderDocumen = Dokumen::where('nama', 'Dokumen Permohonan')->where('tipe', 'folder')->where('user_id', $permohonanUser->user_id)->first();
            if (!$folderDocumen) {
                $folderDocumen = Dokumen::create([
                    'nama' => 'Dokumen Permohonan',
                    'tipe' => 'folder',
                    'parent_id' => null,
                    'user_id' => $permohonanUser->user_id,
                ]);
            }
            // Simpan dokumen ke tabel Dokumen
            $dokumen = Dokumen::create([
                'nama' => $file->getClientOriginalName(),
                'tipe' => 'file',
                'parent_id' => $folderDocumen->id,
                'path' => $fileName,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'user_id' => $permohonanUser->user_id,
            ]);

            // Simpan relasi ke PermohonanUserDocument
            PermohonanUserDocument::create([
                'user_id' => $permohonanUser->user_id,
                'permohonan_user_id' => $permohonanUser->id,
                'nama' => $validated['nama'],
                'masa_berlaku' => !empty($validated['masa_berlaku']) ? $validated['masa_berlaku'] : null,
                'dokumen_id' => $dokumen->id,
            ]);

            DB::commit();

            return redirect()->route('admin.permohonan-user.show', [$permohonanId, $permohonanUser->id])
                ->with('success', 'Dokumen berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete document from permohonan user (admin/superadmin only)
     */
    public function deleteDocument($permohonanId, PermohonanUser $permohonanUser, PermohonanUserDocument $document)
    {
        // Ensure permohonan_id matches
        if ($permohonanUser->permohonan_id != $permohonanId) {
            abort(404);
        }

        // Ensure document belongs to permohonan user
        if ($document->permohonan_user_id != $permohonanUser->id) {
            abort(404);
        }

        // Only admin/superadmin can delete document
        $userRole = Auth::user()->roles()->first()->name ?? null;
        if (!in_array($userRole, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Delete file from storage if exists
            if ($document->dokumen && $document->dokumen->path) {
                // Path sudah relatif ke folder permohonan-documents
                $filePath = storage_path('app/public/permohonan-documents/' . $document->dokumen->path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete dokumen record (will cascade delete permohonan_user_document)
            if ($document->dokumen) {
                $document->dokumen->delete();
            }

            // Delete permohonan user document record
            $document->delete();

            DB::commit();

            return redirect()->route('admin.permohonan-user.show', [$permohonanId, $permohonanUser->id])
                ->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
