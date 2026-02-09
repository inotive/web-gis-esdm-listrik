<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\PerizinanListrik;
use App\Models\Dokumen;
use App\Models\RegVillage;
use App\Models\RegDistrict;
use App\Models\RegRegency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class PerusahaanController extends Controller
{
    public function __construct()
    {
        // View permissions
        $this->middleware('can:perusahaan.view')->only(['index']);
        $this->middleware('can:perusahaan.show')->only(['show']); // Detail has separate permission if needed, but often mapped to view/show

        // CUD permissions
        $this->middleware('can:perusahaan.create')->only(['create', 'store']);
        $this->middleware('can:perusahaan.edit')->only(['edit', 'update']);
        $this->middleware('can:perusahaan.delete')->only(['destroy']);
    }

    // INDEX
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q'); // Global search
        $regencyId = $request->get('regency_id');
        $districtId = $request->get('district_id');

        // Per-column filters
        $filterNama = $request->get('filter_nama');
        $filterAlamat = $request->get('filter_alamat');
        $filterKontak = $request->get('filter_kontak');
        $filterDesa = $request->get('filter_desa');
        $filterKecamatan = $request->get('filter_kecamatan');
        $filterKabupaten = $request->get('filter_kabupaten');
        $filterTanggal = $request->get('filter_tanggal');

        // Optional eager loading - village may be null for imported data
        $query = Perusahaan::with(['village.district.regency']);

        // Global search - search across multiple fields (only if no per-column filters)
        if ($q && !$filterNama && !$filterAlamat && !$filterKontak && !$filterDesa && !$filterKecamatan && !$filterKabupaten && !$filterTanggal) {
            $query->where(function ($query) use ($q) {
                $query->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('alamat', 'like', '%' . $q . '%')
                    ->orWhere('kontak', 'like', '%' . $q . '%')
                    ->orWhere('kabupaten_kota', 'like', '%' . $q . '%')
                    // Search in village name
                    ->orWhereHas('village', function ($subQuery) use ($q) {
                        $subQuery->where('name', 'like', '%' . $q . '%');
                    })
                    // Search in district name
                    ->orWhereHas('village.district', function ($subQuery) use ($q) {
                        $subQuery->where('name', 'like', '%' . $q . '%');
                    })
                    // Search in regency name
                    ->orWhereHas('village.district.regency', function ($subQuery) use ($q) {
                        $subQuery->where('name', 'like', '%' . $q . '%');
                    });
            });
        }

        // Per-column filters (take priority over global search)
        if ($filterNama) {
            $query->where('nama', 'like', '%' . $filterNama . '%');
        }

        if ($filterAlamat) {
            $query->where('alamat', 'like', '%' . $filterAlamat . '%');
        }

        if ($filterKontak) {
            $query->where('kontak', 'like', '%' . $filterKontak . '%');
        }

        if ($filterDesa) {
            $query->whereHas('village', function ($subQuery) use ($filterDesa) {
                $subQuery->where('name', 'like', '%' . $filterDesa . '%');
            });
        }

        if ($filterKecamatan) {
            $query->whereHas('village.district', function ($subQuery) use ($filterKecamatan) {
                $subQuery->where('name', 'like', '%' . $filterKecamatan . '%');
            });
        }

        if ($filterKabupaten) {
            $query->where(function ($subQuery) use ($filterKabupaten) {
                $subQuery->where('kabupaten_kota', 'like', '%' . $filterKabupaten . '%')
                    ->orWhereHas('village.district.regency', function ($q) use ($filterKabupaten) {
                        $q->where('name', 'like', '%' . $filterKabupaten . '%');
                    });
            });
        }

        if ($filterTanggal) {
            $query->whereDate('created_at', $filterTanggal);
        }

        // Filter by regency
        // Filter by regency
        if ($regencyId) {
            $query->where(function ($q) use ($regencyId) {
                // Check relationship
                $q->whereHas('village.district', function ($subQ) use ($regencyId) {
                    $subQ->where('regency_id', $regencyId);
                });

                // OR check string column if regency name matches
                $regency = RegRegency::find($regencyId);
                if ($regency) {
                    $q->orWhere('kabupaten_kota', 'like', '%' . $regency->name . '%');
                }
            });
        }

        // Filter by district
        if ($districtId) {
            $query->whereHas('village', function ($q) use ($districtId) {
                $q->where('district_id', $districtId);
            });
        }

        $perusahaans = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();


        // Untuk filter dropdown
        $regencies = RegRegency::orderBy('name')->get(['id', 'name']);
        $districts = $regencyId
            ? RegDistrict::where('regency_id', $regencyId)->orderBy('name')->get(['id', 'name'])
            : collect([]);

        return view('admin.perusahaan.index', [
            'title' => "Manajemen Data Perusahaan",
            'perusahaans' => $perusahaans,
            'regencies' => $regencies,
            'districts' => $districts,
        ]);
    }

    // SHOW (Detail Perusahaan dengan Perizinan, Permohonan, dan Dokumen)
    public function show(Perusahaan $perusahaan)
    {
        // Load relasi perusahaan
        $perusahaan->load([
            'village.district.regency',
            'perizinans.documents.dokumen',
            'permohonanUsers.permohonan',
            'permohonanUsers.user',
            'permohonanUsers.documents.dokumen',
        ]);

        // Ambil data perizinan dari PerizinanListrik berdasarkan nama_pemohon yang cocok dengan nama perusahaan
        $perizinanListriks = PerizinanListrik::where('nama_pemohon', 'LIKE', '%' . $perusahaan->nama . '%')
            ->orWhere('nama_pemohon', $perusahaan->nama)
            ->orderBy('tanggal_terbit', 'desc')
            ->get();

        $perizinanData = $perizinanListriks->map(function ($perizinan) {
            // Hitung status berdasarkan tanggal_akhir
            $status = 'Berakhir';
            if ($perizinan->tanggal_akhir) {
                $today = now();
                $tanggalAkhir = $perizinan->tanggal_akhir;
                if ($tanggalAkhir > $today->copy()->addDays(30)) {
                    $status = 'Aktif';
                } elseif ($tanggalAkhir >= $today && $tanggalAkhir <= $today->copy()->addDays(30)) {
                    $status = 'Mau Berakhir';
                }
            }

            return [
                'id' => $perizinan->id,
                'no_izin' => $perizinan->no_surat_izin ?? $perizinan->no_pengajuan ?? '-',
                'nama' => $perizinan->nama_pemohon,
                'jenis_izin' => $perizinan->jenis ?? '-',
                'tanggal_terbit' => $perizinan->tanggal_terbit,
                'tanggal_akhir' => $perizinan->tanggal_akhir,
                'lokasi' => $perizinan->lokasi ?? $perizinan->kabupaten_kota,
                'kapasitas' => $perizinan->kapasitas,
                'total_kapasitas' => $perizinan->total_kapasitas,
                'sifat_penggunaan' => $perizinan->sifat_penggunaan,
                'catatan' => $perizinan->catatan,
                'status' => $status,
            ];
        })->toArray();

        // Ambil data permohonan dari relasi permohonanUsers (pivot)
        $permohonanData = $perusahaan->permohonanUsers->map(function ($permohonanUser) {
            return [
                'id' => $permohonanUser->id,
                'jenis_permohonan' => $permohonanUser->permohonan->nama ?? $permohonanUser->permohonan->jenis_permohonan ?? '-',
                'status' => $permohonanUser->status,
                'keterangan' => $permohonanUser->keterangan,
                'user' => $permohonanUser->user->name ?? '-',
                'tanggal_pengajuan' => $permohonanUser->created_at,
                'documents' => $permohonanUser->documents,
            ];
        })->toArray();

        // Ambil dokumen dari perizinan_documents (dari relasi perizinans jika ada)
        $dokumenData = [];
        foreach ($perusahaan->perizinans as $perizinan) {
            foreach ($perizinan->documents as $doc) {
                $dokumenData[] = [
                    'id' => $doc->id,
                    'nama' => $doc->nama ?? $doc->dokumen->nama ?? '-',
                    'no_surat' => $doc->no_surat_izin_terbit,
                    'tanggal_terbit' => $doc->tanggal_terbit,
                    'tanggal_akhir' => $doc->tanggal_akhir,
                    'perizinan_nama' => $perizinan->nama ?? $perizinan->jenis ?? '-',
                    'dokumen' => $doc->dokumen,
                    'source' => 'perizinan',
                ];
            }
        }

        // Juga ambil dokumen dari permohonan user documents
        foreach ($perusahaan->permohonanUsers as $permohonanUser) {
            foreach ($permohonanUser->documents as $doc) {
                $dokumenData[] = [
                    'id' => $doc->id,
                    'nama' => $doc->nama ?? $doc->dokumen->nama ?? '-',
                    'no_surat' => null,
                    'tanggal_terbit' => null,
                    'tanggal_akhir' => $doc->masa_berlaku,
                    'perizinan_nama' => 'Dokumen Permohonan',
                    'dokumen' => $doc->dokumen,
                    'source' => 'permohonan',
                ];
            }
        }

        // Ambil dokumen dari tabel dokumens berdasarkan nama perusahaan
        // Buat keyword pencarian dari nama perusahaan (hapus prefix umum)
        $namaPerusahaan = $perusahaan->nama;
        $keywords = [];

        // Tambah nama lengkap sebagai keyword
        $keywords[] = $namaPerusahaan;

        // Hapus prefix seperti PT., PT, CV., CV, Tbk., dll dan buat keyword tambahan
        $cleanedName = preg_replace('/^(PT\.?\s*|CV\.?\s*)/i', '', $namaPerusahaan);
        $cleanedName = preg_replace('/\s*(Tbk\.?|\(.*\))\s*$/i', '', $cleanedName);
        $cleanedName = trim($cleanedName);
        if ($cleanedName && $cleanedName !== $namaPerusahaan) {
            $keywords[] = $cleanedName;
        }

        // Ambil kata utama dari nama (misal: "TRAKINDO UTAMA" dari "PT. TRAKINDO UTAMA")
        $words = explode(' ', $cleanedName);
        if (count($words) > 1) {
            // Kata pertama yang bukan umum (minimal 4 karakter)
            foreach ($words as $word) {
                if (strlen($word) >= 4 && !in_array(strtoupper($word), ['INDONESIA', 'UTAMA', 'JAYA', 'PRIMA', 'MANDIRI', 'SEJAHTERA', 'ABADI'])) {
                    $keywords[] = $word;
                    break;
                }
            }
        }

        // Cari dokumen yang cocok dengan keyword
        if (!empty($keywords)) {
            $dokumenQuery = Dokumen::query();

            $dokumenQuery->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    if (strlen($keyword) >= 3) {
                        $q->orWhere('nama', 'LIKE', '%' . $keyword . '%');
                    }
                }
            });

            $dokumenResults = $dokumenQuery->orderBy('tipe', 'desc') // Folder dulu
                ->orderBy('nama')
                ->limit(50) // Batasi hasil
                ->get();

            foreach ($dokumenResults as $dok) {
                $dokumenData[] = [
                    'id' => $dok->id,
                    'nama' => $dok->nama,
                    'no_surat' => null,
                    'tanggal_terbit' => $dok->created_at,
                    'tanggal_akhir' => null,
                    'perizinan_nama' => $dok->isFolder() ? 'Folder' : 'File Dokumen',
                    'dokumen' => $dok,
                    'source' => 'dokumen_db',
                    'tipe' => $dok->tipe,
                    'path' => $dok->path,
                    'size' => $dok->formatted_size,
                ];
            }
        }

        return view('admin.perusahaan.show', [
            'perusahaan' => $perusahaan,
            'perizinanData' => $perizinanData,
            'permohonanData' => $permohonanData,
            'dokumenData' => $dokumenData,
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        // Cek bahwa village_id valid jika diberikan
        if (!empty($data['village_id'])) {
            $village = RegVillage::find($data['village_id']);
            if (!$village) {
                throw ValidationException::withMessages([
                    'village_id' => ['Desa tidak ditemukan.']
                ]);
            }
        }

        Perusahaan::create($data);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil ditambahkan.');
    }

    // EDIT
    public function edit(Perusahaan $perusahaan)
    {
        $perusahaan->load('village.district.regency');

        $regencies = RegRegency::orderBy('name')->get(['id', 'name']);

        // Fix null pointer error - safely get regency_id and district_id
        $regencyId = null;
        $districtId = null;

        if ($perusahaan->village) {
            $districtId = $perusahaan->village->district_id;
            if ($perusahaan->village->district) {
                $regencyId = $perusahaan->village->district->regency_id;
            }
        }

        $districts = $regencyId
            ? RegDistrict::where('regency_id', $regencyId)->orderBy('name')->get(['id', 'name'])
            : collect([]);

        $villages = $districtId
            ? RegVillage::where('district_id', $districtId)->orderBy('name')->get(['id', 'name'])
            : collect([]);

        return view('admin.perusahaan.edit', compact('perusahaan', 'regencies', 'districts', 'villages'));
    }

    // UPDATE
    public function update(Request $request, Perusahaan $perusahaan)
    {
        $data = $this->validatedData($request);

        // Cek bahwa village_id valid jika diberikan
        if (!empty($data['village_id'])) {
            $village = RegVillage::find($data['village_id']);
            if (!$village) {
                throw ValidationException::withMessages([
                    'village_id' => ['Desa tidak ditemukan.']
                ]);
            }
        }

        $perusahaan->update($data);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    // DESTROY
    public function destroy(Perusahaan $perusahaan)
    {
        // Cek apakah perusahaan memiliki relasi ke Permohonan
        $hasPermohonan = false;
        try {
            // Cek jika ada kolom perusahaan_id di tabel permohonans
            if (Schema::hasColumn('permohonans', 'perusahaan_id')) {
                $hasPermohonan = Permohonan::where('perusahaan_id', $perusahaan->id)->exists();
            }
        } catch (\Exception $e) {
            // Jika kolom tidak ada, skip pengecekan
        }

        // Cek apakah perusahaan memiliki relasi ke PermohonanUser
        $hasPermohonanUser = false;
        try {
            // Cek jika ada kolom perusahaan_id di tabel permohonan_users
            if (Schema::hasColumn('permohonan_users', 'perusahaan_id')) {
                $hasPermohonanUser = PermohonanUser::where('perusahaan_id', $perusahaan->id)->exists();
            }
        } catch (\Exception $e) {
            // Jika kolom tidak ada, skip pengecekan
        }

        // Jika ada relasi, tampilkan error
        if ($hasPermohonan || $hasPermohonanUser) {
            $messages = [];
            if ($hasPermohonan) {
                $messages[] = 'Data perusahaan tidak dapat dihapus karena masih memiliki relasi dengan data Permohonan.';
            }
            if ($hasPermohonanUser) {
                $messages[] = 'Data perusahaan tidak dapat dihapus karena masih memiliki relasi dengan data Permohonan User.';
            }

            return redirect()->route('admin.perusahaan.index')
                ->with('error', implode(' ', $messages));
        }

        $perusahaan->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil dihapus.');
    }

    // ===== Helpers =====
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'village_id' => ['nullable', 'exists:reg_villages,id'],
            'nama' => ['required', 'string', 'max:255'],
            'nama_pimpinan' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'kontak' => ['nullable', 'string', 'max:255'],
            'kabupaten_kota' => ['nullable', 'string', 'max:255'],
        ], [], [
            'village_id' => 'Desa',
            'nama' => 'Nama Perusahaan',
            'nama_pimpinan' => 'Nama Pimpinan',
            'alamat' => 'Alamat',
            'kontak' => 'Kontak',
            'kabupaten_kota' => 'Kabupaten/Kota',
        ]);
    }

    // ====== Endpoints untuk cascading options (JSON) ======
    public function optionsRegencies(Request $request)
    {
        $q = $request->get('q');
        $items = RegRegency::when($q, fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->orderBy('name')->limit(100)->get(['id', 'name']);
        return response()->json($items);
    }

    public function optionsDistricts(Request $request)
    {
        $regencyId = $request->get('regency_id');
        $q = $request->get('q');

        $items = RegDistrict::when($regencyId, fn($qq) => $qq->where('regency_id', $regencyId))
            ->when($q, fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->orderBy('name')->limit(200)->get(['id', 'name']);
        return response()->json($items);
    }

    public function optionsVillages(Request $request)
    {
        $districtId = $request->get('district_id');
        $q = $request->get('q');

        $items = RegVillage::when($districtId, fn($qq) => $qq->where('district_id', $districtId))
            ->when($q, fn($qq) => $qq->where('name', 'like', "%{$q}%"))
            ->orderBy('name')->limit(300)->get(['id', 'name']);
        return response()->json($items);
    }
}
