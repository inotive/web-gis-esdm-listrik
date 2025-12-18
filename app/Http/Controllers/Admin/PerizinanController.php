<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\PerizinanDocument;
use App\Models\Perusahaan;
use App\Models\Dokumen;
use App\Helpers\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerizinanController extends Controller
{
    use UploadFile;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return redirect()->route('admin.permohonan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perusahaans = Perusahaan::orderBy('nama', 'asc')->get(['id', 'nama']);

        return view('admin.perizinan.create', [
            'title' => 'Tambah Perizinan',
            'perusahaans' => $perusahaans,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'kontak' => 'nullable|string|max:255',
            'jenis' => 'required|string|max:255',
            'no_pengajuan' => 'nullable|string|max:255',
            'no_surat_keluar' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'lokasi' => 'nullable|string',
            'titik_koordinat' => 'nullable|string|max:255',
            'jumlah_kapasitas' => 'nullable|integer',
            'total_kapasitas_kva' => 'nullable|numeric',
            'jenis_penggunaan' => 'nullable|string|max:255',
            'sifat_penggunaan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $perizinan = Perizinan::create($validated);

            DB::commit();

            return redirect()->route('admin.permohonan.index')
                ->with('success', 'Perizinan berhasil ditambahkan.');
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
    public function show(Perizinan $perizinan)
    {
        $perizinan->load(['perusahaan', 'documents.dokumen']);

        return view('admin.perizinan.show', [
            'title' => 'Detail Perizinan',
            'perizinan' => $perizinan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perizinan $perizinan)
    {
        $perusahaans = Perusahaan::orderBy('nama', 'asc')->get(['id', 'nama']);

        return view('admin.perizinan.edit', [
            'title' => 'Edit Perizinan',
            'perizinan' => $perizinan,
            'perusahaans' => $perusahaans,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'perusahaan_id' => 'required|exists:perusahaans,id',
            'kontak' => 'nullable|string|max:255',
            'jenis' => 'required|string|max:255',
            'no_pengajuan' => 'nullable|string|max:255',
            'no_surat_keluar' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'lokasi' => 'nullable|string',
            'titik_koordinat' => 'nullable|string|max:255',
            'jumlah_kapasitas' => 'nullable|integer',
            'total_kapasitas_kva' => 'nullable|numeric',
            'jenis_penggunaan' => 'nullable|string|max:255',
            'sifat_penggunaan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $perizinan->update($validated);

            DB::commit();

            return redirect()->route('admin.perizinan.index')
                ->with('success', 'Perizinan berhasil diperbarui.');
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
    public function destroy(Perizinan $perizinan)
    {
        DB::beginTransaction();
        try {
            $perizinan->delete();

            DB::commit();

            return redirect()->route('admin.perizinan.index')
                ->with('success', 'Perizinan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Add document to perizinan
     */
    public function addDocument(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'required|file|max:10240',
            'no_surat_izin_terbit' => 'nullable|string|max:255',
            'tanggal_terbit' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');

            // Upload file dan simpan ke tabel Dokumen
            $fileName = $this->storeFile($file, 'perizinan-documents');

            $folderDocumen = Dokumen::where('nama', 'Dokumen Perizinan')->where('tipe', 'folder')->where('user_id', $perizinan->perusahaan->id)->first();
            if (!$folderDocumen) {
                $folderDocumen = Dokumen::create([
                    'nama' => 'Dokumen Perizinan',
                    'tipe' => 'folder',
                    'parent_id' => null,
                    'user_id' => $perizinan->perusahaan->id,
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
                'user_id' => $perizinan->perusahaan->id,
            ]);

            // Simpan relasi ke PerizinanDocument
            PerizinanDocument::create([
                'perizinan_id' => $perizinan->id,
                'dokumen_id' => $dokumen->id,
                'nama' => $validated['nama'],
                'no_surat_izin_terbit' => $validated['no_surat_izin_terbit'] ?? null,
                'tanggal_terbit' => $validated['tanggal_terbit'] ?? null,
                'tanggal_akhir' => $validated['tanggal_akhir'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.perizinan.show', $perizinan->id)
                ->with('success', 'Dokumen berhasil ditambahkan.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete document from perizinan
     */
    public function deleteDocument(Perizinan $perizinan, PerizinanDocument $document)
    {
        // Ensure document belongs to perizinan
        if ($document->perizinan_id != $perizinan->id) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            // Delete file from storage if exists
            if ($document->dokumen && $document->dokumen->path) {
                $filePath = storage_path('app/public/perizinan-documents/' . $document->dokumen->path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete dokumen record (will cascade delete perizinan_document)
            if ($document->dokumen) {
                $document->dokumen->delete();
            }

            // Delete perizinan document record
            $document->delete();

            DB::commit();

            return redirect()->route('admin.perizinan.show', $perizinan->id)
                ->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}

