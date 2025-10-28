<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DataWilayahController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');
        $regencyId = $request->get('regency_id');
        $districtId = $request->get('district_id');

        $wilayah = Wilayah::with(['village', 'district', 'regency'])
            ->search($q)
            ->byRegency($regencyId)
            ->byDistrict($districtId)
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Untuk filter dropdown
        $regencies = RegRegency::orderBy('name')->get(['id','name']);
        $districts = $regencyId
            ? RegDistrict::where('regency_id', $regencyId)->orderBy('name')->get(['id','name'])
            : collect([]);

        return view('admin.data_wilayah.index', [
            'title'     => "Manajemen Data Wilayah",
            'wilayah'   => $wilayah,
            'regencies' => $regencies,
            'districts' => $districts,
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        // Cek hirarki (district milik regency, village milik district)
        $this->enforceHierarchy($data['regency_id'], $data['district_id'], $data['village_id']);

        Wilayah::create($data);

        return redirect()->route('admin.data-wilayah.index')->with('success', 'Data wilayah berhasil ditambahkan.');
    }

    // EDIT
    public function edit(Wilayah $wilayah)
    {
        $regencies = RegRegency::orderBy('name')->get(['id','name']);
        $districts = RegDistrict::where('regency_id', $wilayah->regency_id)->orderBy('name')->get(['id','name']);
        $villages  = RegVillage::where('district_id', $wilayah->district_id)->orderBy('name')->get(['id','name']);

        return view('admin.data_wilayah.edit', compact('wilayah','regencies','districts','villages'));
    }

    // UPDATE
    public function update(Request $request, Wilayah $wilayah)
    {
        $data = $this->validatedData($request);
        $this->enforceHierarchy($data['regency_id'], $data['district_id'], $data['village_id']);

        $wilayah->update($data);

        return redirect()->route('admin.data-wilayah.index')->with('success', 'Data wilayah berhasil diperbarui.');
    }

    // DESTROY
    public function destroy(Wilayah $wilayah)
    {
        $wilayah->delete();
        return redirect()->route('admin.data-wilayah.index')->with('success', 'Data wilayah berhasil dihapus.');
    }

    // ===== Helpers =====
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'regency_id'      => ['required','exists:reg_regencies,id'],
            'district_id'     => ['required','exists:reg_districts,id'],
            'village_id'      => ['required','exists:reg_villages,id'],
            'lat'             => ['nullable','numeric','between:-90,90'],
            'lng'             => ['nullable','numeric','between:-180,180'],
            'polygon_geojson' => ['nullable','string'], // simpan string GeoJSON
        ], [], [
            'regency_id'  => 'Kabupaten/Kota',
            'district_id' => 'Kecamatan',
            'village_id'  => 'Desa/Kelurahan',
            'lat'         => 'Latitude',
            'lng'         => 'Longitude',
        ]);
    }

    private function enforceHierarchy(string $regencyId, string $districtId, string $villageId): void
    {
        $okDistrict = RegDistrict::where('id', $districtId)->where('regency_id', $regencyId)->exists();
        if (!$okDistrict) {
            throw ValidationException::withMessages([
                'district_id' => ['Kecamatan tidak berada di dalam Kabupaten/Kota yang dipilih.']
            ]);
        }

        $okVillage = RegVillage::where('id', $villageId)->where('district_id', $districtId)->exists();
        if (!$okVillage) {
            throw ValidationException::withMessages([
                'village_id' => ['Desa/Kelurahan tidak berada di dalam Kecamatan yang dipilih.']
            ]);
        }
    }

    // ====== Endpoints untuk cascading options (JSON) ======
    public function optionsRegencies(Request $request)
    {
        $q = $request->get('q');
        $items = RegRegency::when($q, fn($qq)=>$qq->where('name','like',"%{$q}%"))
            ->orderBy('name')->limit(100)->get(['id','name']);
        return response()->json($items);
    }

    public function optionsDistricts(Request $request)
    {
        $regencyId = $request->get('regency_id');
        $q = $request->get('q');

        $items = RegDistrict::when($regencyId, fn($qq)=>$qq->where('regency_id',$regencyId))
            ->when($q, fn($qq)=>$qq->where('name','like',"%{$q}%"))
            ->orderBy('name')->limit(200)->get(['id','name']);
        return response()->json($items);
    }

    public function optionsVillages(Request $request)
    {
        $districtId = $request->get('district_id');
        $q = $request->get('q');

        $items = RegVillage::when($districtId, fn($qq)=>$qq->where('district_id',$districtId))
            ->when($q, fn($qq)=>$qq->where('name','like',"%{$q}%"))
            ->orderBy('name')->limit(300)->get(['id','name']);
        return response()->json($items);
    }
}
