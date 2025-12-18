<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\RegVillage;
use App\Models\RegDistrict;
use App\Models\RegRegency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class PerusahaanController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');
        $regencyId = $request->get('regency_id');
        $districtId = $request->get('district_id');
        $villageId = $request->get('village_id');
        $kabupatenKota = $request->get('kabupaten_kota');

        // Optional eager loading - village may be null for imported data
        $query = Perusahaan::with(['village.district.regency']);

        // Search by name or kontak
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('nama', 'like', '%' . $q . '%')
                    ->orWhere('kontak', 'like', '%' . $q . '%');
            });
        }

        // Filter by kabupaten_kota (string) for imported data
        if ($kabupatenKota) {
            $query->where('kabupaten_kota', 'like', '%' . $kabupatenKota . '%');
        }

        // Filter by regency (from village relationship)
        if ($regencyId) {
            $query->whereHas('village.district', function ($q) use ($regencyId) {
                $q->where('regency_id', $regencyId);
            });
        }

        // Filter by district
        if ($districtId) {
            $query->whereHas('village', function ($q) use ($districtId) {
                $q->where('district_id', $districtId);
            });
        }

        // Filter by village
        if ($villageId) {
            $query->where('village_id', $villageId);
        }

        $perusahaans = $query->orderBy('nama', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Untuk filter dropdown
        $regencies = RegRegency::orderBy('name')->get(['id', 'name']);
        $districts = $regencyId
            ? RegDistrict::where('regency_id', $regencyId)->orderBy('name')->get(['id', 'name'])
            : collect([]);
        $villages = $districtId
            ? RegVillage::where('district_id', $districtId)->orderBy('name')->get(['id', 'name'])
            : collect([]);

        return view('admin.perusahaan.index', [
            'title' => "Manajemen Data Perusahaan",
            'perusahaans' => $perusahaans,
            'regencies' => $regencies,
            'districts' => $districts,
            'villages' => $villages,
        ]);
    }

    // SHOW (Detail Perusahaan dengan Perizinan)
    public function show(Perusahaan $perusahaan)
    {
        $perusahaan->load('village.district.regency');

        // Data perizinan contoh (nantinya bisa dari database/relasi)
        $perizinanData = [
            [
                'no_izin' => 'IUPTL/2024/001',
                'jenis_izin' => 'Izin Usaha Penyediaan Tenaga Listrik (IUPTL)',
                'tanggal_terbit' => '2024-01-15',
                'tanggal_berlaku' => '2029-01-15',
                'status' => 'Aktif',
                'keterangan' => 'Izin untuk penyediaan tenaga listrik untuk kepentingan umum',
            ],
            [
                'no_izin' => 'IMB/2023/045',
                'jenis_izin' => 'Izin Mendirikan Bangunan (IMB)',
                'tanggal_terbit' => '2023-06-20',
                'tanggal_berlaku' => null,
                'status' => 'Aktif',
                'keterangan' => 'Izin pembangunan gardu induk',
            ],
            [
                'no_izin' => 'AMDAL/2023/012',
                'jenis_izin' => 'Analisis Mengenai Dampak Lingkungan (AMDAL)',
                'tanggal_terbit' => '2023-03-10',
                'tanggal_berlaku' => '2028-03-10',
                'status' => 'Aktif',
                'keterangan' => 'Dokumen AMDAL untuk pembangunan PLTU',
            ],
            [
                'no_izin' => 'SLO/2022/089',
                'jenis_izin' => 'Sertifikat Laik Operasi (SLO)',
                'tanggal_terbit' => '2022-09-01',
                'tanggal_berlaku' => '2024-09-01',
                'status' => 'Perlu Diperpanjang',
                'keterangan' => 'Sertifikat kelayakan operasi instalasi listrik',
            ],
        ];

        return view('admin.perusahaan.show', [
            'perusahaan' => $perusahaan,
            'perizinanData' => $perizinanData,
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
        $districts = RegDistrict::where('regency_id', $perusahaan->village->district->regency_id ?? null)
            ->orderBy('name')
            ->get(['id', 'name']);
        $villages = RegVillage::where('district_id', $perusahaan->village->district_id ?? null)
            ->orderBy('name')
            ->get(['id', 'name']);

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
            'alamat' => ['nullable', 'string'],
            'kontak' => ['nullable', 'string', 'max:255'],
            'kabupaten_kota' => ['nullable', 'string', 'max:255'],
        ], [], [
            'village_id' => 'Desa',
            'nama' => 'Nama Perusahaan',
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
