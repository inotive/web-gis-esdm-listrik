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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PerizinanImport;
use App\Exports\PerizinanTemplateExport;

class PerizinanController extends Controller
{
    use UploadFile;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return redirect()->route('admin.permohonan.index', ['tab' => 'perizinan']);
    }

    /**
     * Show import form
     */
    public function import(Request $request)
    {
        return view('admin.perizinan.import', [
            'title' => 'Import Data Perizinan',
        ]);
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        return Excel::download(new PerizinanTemplateExport, 'template_import_perizinan.xlsx');
    }

    /**
     * Process import
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new PerizinanImport, $request->file('file'));
            return redirect()->route('admin.perizinan.index')->with('success', 'Data perizinan berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perusahaans = Perusahaan::orderBy('nama', 'asc')->get(['id', 'nama']);
        $existingJenis = Perizinan::distinct()->whereNotNull('jenis')->pluck('jenis')->toArray();
        $jenisPerizinan = collect(array_merge(Perizinan::DEFAULT_JENIS_PERIZINAN, $existingJenis))
            ->unique()
            ->sort()
            ->values();

        return view('admin.perizinan.create', [
            'title' => 'Tambah Perizinan',
            'perusahaans' => $perusahaans,
            'jenisPerizinan' => $jenisPerizinan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required', // Can be ID (int) or Name (string)
            'kontak' => 'nullable|string|max:255',
            'jenis' => 'required|string|max:255',
            'no_pengajuan' => 'nullable|string|max:255',
            'no_surat_keluar' => 'nullable|string|max:255',
            'no_surat_izin_terbit' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal',
            'lokasi' => 'nullable|string',
            'titik_koordinat' => 'nullable|string|max:255',
            'jumlah' => 'nullable|integer',
            'kapasitas' => 'nullable|numeric',
            'jumlah_kapasitas' => 'nullable|integer',
            'total_kapasitas_kva' => 'nullable|numeric',
            'jenis_penggunaan' => 'nullable|string|max:255',
            'sifat_penggunaan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'file_izin' => 'nullable|mimes:pdf|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Determine if nama_perusahaan is an ID or a Name
            $inputPerusahaan = $request->nama_perusahaan;
            $perusahaanId = null;
            $namaPerusahaan = null;

            if (is_numeric($inputPerusahaan)) {
                $existing = Perusahaan::find($inputPerusahaan);
                if ($existing) {
                    $perusahaanId = $existing->id;
                    $namaPerusahaan = $existing->nama; // Optional, or keep it null if we prefer relationship
                } else {
                     // Numeric but not found?? Treat as name if desired, or error. 
                     // Assuming it's just a name that happens to be numeric
                     $namaPerusahaan = $inputPerusahaan;
                }
            } else {
                 $existingByName = Perusahaan::where('nama', $inputPerusahaan)->first();
                 if ($existingByName) {
                     $perusahaanId = $existingByName->id;
                 } else {
                     $namaPerusahaan = $inputPerusahaan;
                 }
            }
            
            // Prepare data for creation
            unset($validated['nama_perusahaan']);
            $validated['perusahaan_id'] = $perusahaanId;
            $validated['nama_perusahaan'] = $namaPerusahaan;
            $validated['created_by'] = Auth::id();

            $perizinan = Perizinan::create($validated);

            // Handle File Upload
            if ($request->hasFile('file_izin')) {
                $file = $request->file('file_izin');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('perizinan_docs', $filename, 'public');

                PerizinanDocument::create([
                    'perizinan_id' => $perizinan->id,
                    'filename' => $filename,
                    'path' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'description' => 'File Izin Utama',
                ]);
            }

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
        $existingJenis = Perizinan::distinct()->whereNotNull('jenis')->pluck('jenis')->toArray();
        $jenisPerizinan = collect(array_merge(Perizinan::DEFAULT_JENIS_PERIZINAN, $existingJenis))
            ->unique()
            ->sort()
            ->values();

        return view('admin.perizinan.edit', [
            'title' => 'Edit Perizinan',
            'perizinan' => $perizinan,
            'perusahaans' => $perusahaans,
            'jenisPerizinan' => $jenisPerizinan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required', // Can be ID or Name
            'kontak' => 'nullable|string|max:255',
            'jenis' => 'required|string|max:255',
            'no_pengajuan' => 'nullable|string|max:255',
            'no_surat_keluar' => 'nullable|string|max:255',
            'no_surat_izin_terbit' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal',
            'lokasi' => 'nullable|string',
            'titik_koordinat' => 'nullable|string|max:255',
            'jumlah' => 'nullable|integer',
            'kapasitas' => 'nullable|numeric',
            'jumlah_kapasitas' => 'nullable|integer',
            'total_kapasitas_kva' => 'nullable|numeric',
            'jenis_penggunaan' => 'nullable|string|max:255',
            'sifat_penggunaan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'file_izin' => 'nullable|mimes:pdf|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Determine if nama_perusahaan is an ID or a Name
            $inputPerusahaan = $request->nama_perusahaan;
            $perusahaanId = null;
            $namaPerusahaan = null;

            if (is_numeric($inputPerusahaan)) {
                $existing = Perusahaan::find($inputPerusahaan);
                if ($existing) {
                    $perusahaanId = $existing->id;
                } else {
                     $namaPerusahaan = $inputPerusahaan;
                }
            } else {
                 $existingByName = Perusahaan::where('nama', $inputPerusahaan)->first();
                 if ($existingByName) {
                     $perusahaanId = $existingByName->id;
                 } else {
                     $namaPerusahaan = $inputPerusahaan;
                 }
            }

            // Prepare data
            unset($validated['nama_perusahaan']);
            $validated['perusahaan_id'] = $perusahaanId;
            // If we found a linked company, we might want to clear the manual name, or keep it as backup?
            // Let's clear manual name if linked, otherwise set it.
            $validated['nama_perusahaan'] = $perusahaanId ? null : $namaPerusahaan;

            $perizinan->update($validated);

            // Handle File Upload
            if ($request->hasFile('file_izin')) {
                $file = $request->file('file_izin');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('perizinan_docs', $filename, 'public');

                PerizinanDocument::create([
                    'perizinan_id' => $perizinan->id,
                    'filename' => $filename,
                    'path' => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'description' => 'File Izin Utama (Updated)',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.permohonan.index', ['tab' => 'perizinan'])
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

            return redirect()->route('admin.permohonan.index', ['tab' => 'perizinan'])
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

    public function getMapData()
    {
        // Removed status_kelistrikan and nama from select as they are dropped
        $data = Perizinan::select('id', 'titik_koordinat', 'lokasi', 'perusahaan_id', 'nama_perusahaan')
            ->with(['perusahaan:id,nama']) 
            ->whereNotNull('titik_koordinat')
            ->where('titik_koordinat', '!=', '') 
            ->get();

        $formattedData = $data->map(function($item) {
            $color = 'blue'; // Default color since status is removed

            $coords = array_map('trim', explode(',', $item->titik_koordinat));
            $lat = isset($coords[0]) && is_numeric($coords[0]) ? (float)$coords[0] : 0;
            $lng = isset($coords[1]) && is_numeric($coords[1]) ? (float)$coords[1] : 0;
            
            // Prefer linked company name, fallback to manual name
            $companyName = $item->perusahaan ? $item->perusahaan->nama : ($item->nama_perusahaan ?? 'Tanpa Nama');

            return [
                'id' => $item->id,
                'title' => $companyName,
                'lat' => $lat,
                'lng' => $lng,
                'color' => $color,
                'status_label' => 'Lokasi Izin', // Generic label
                'lokasi' => $item->lokasi
            ];
        });
        return response()->json($formattedData);
    }
}

