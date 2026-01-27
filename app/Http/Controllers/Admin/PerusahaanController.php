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

    // SHOW (Detail Perusahaan dengan Perizinan & Infrastruktur)
    public function show(Perusahaan $perusahaan)
    {
        $perusahaan->load('village.district.regency');

        // Load real perizinan data from database via relationship
        // We use the relationship defined in Perusahaan model: 'perizinans'
        $perizinanData = $perusahaan->perizinans()->orderBy('created_at', 'desc')->get();

        // Load infrastructure data
        $infrastrukturJaringans = $perusahaan->infrastrukturJaringans()->get();
        $gardus = $perusahaan->gardus()->get();
        $pembangkitLokals = $perusahaan->pembangkitLokals()->get();

        return view('admin.perusahaan.show', [
            'perusahaan' => $perusahaan,
            'perizinanData' => $perizinanData,
            'infrastrukturJaringans' => $infrastrukturJaringans,
            'gardus' => $gardus,
            'pembangkitLokals' => $pembangkitLokals,
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
