<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perusahaan;
use App\Models\RegVillage;
use App\Models\RegDistrict;
use App\Models\RegRegency;
use Illuminate\Http\Request;
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

        $query = Perusahaan::with(['village.district.regency']);

        // Search by name
        if ($q) {
            $query->where('nama', 'like', '%' . $q . '%');
        }

        // Filter by regency
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
            'title'     => "Manajemen Data Perusahaan",
            'perusahaans' => $perusahaans,
            'regencies' => $regencies,
            'districts' => $districts,
            'villages'  => $villages,
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        // Cek bahwa village_id valid
        $village = RegVillage::find($data['village_id']);
        if (!$village) {
            throw ValidationException::withMessages([
                'village_id' => ['Desa tidak ditemukan.']
            ]);
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

        // Cek bahwa village_id valid
        $village = RegVillage::find($data['village_id']);
        if (!$village) {
            throw ValidationException::withMessages([
                'village_id' => ['Desa tidak ditemukan.']
            ]);
        }

        $perusahaan->update($data);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    // DESTROY
    public function destroy(Perusahaan $perusahaan)
    {
        $perusahaan->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil dihapus.');
    }

    // ===== Helpers =====
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'village_id' => ['required', 'exists:reg_villages,id'],
            'nama'       => ['required', 'string', 'max:255'],
            'alamat'     => ['nullable', 'string'],
        ], [], [
            'village_id' => 'Desa',
            'nama'       => 'Nama Perusahaan',
            'alamat'     => 'Alamat',
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
