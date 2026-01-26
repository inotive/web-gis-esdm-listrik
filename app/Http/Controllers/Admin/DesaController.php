<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegVillage;
use App\Models\RegDistrict;
use App\Models\RegRegency;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DesaController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');
        $regencyId = $request->get('regency_id');
        $districtId = $request->get('district_id');

        $query = RegVillage::with(['district.regency', 'dataBerlistrik']);

        // Search by name
        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        // Filter by regency
        if ($regencyId) {
            $query->whereHas('district', function ($q) use ($regencyId) {
                $q->where('regency_id', $regencyId);
            });
        }

        // Filter by district
        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        $desas = $query->orderBy('name', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Untuk filter dropdown
        $regencies = RegRegency::orderBy('name')->get(['id', 'name']);
        $districts = $regencyId
            ? RegDistrict::where('regency_id', $regencyId)->orderBy('name')->get(['id', 'name'])
            : collect([]);

        // Fetch status berlistrik data from ImportedJsonFeature
        $desaNames = $desas->pluck('name')->toArray();

        // Fetch features matching the names for the specific category
        // Note: Using whereJsonContains or similar might be slow or not supported on all DBs for array values in JSON.
        // Since we have a pagination of 10-100, we can fetch by iterating OR just fetch all for this page.
        // A simple LIKE query or whereIn on a virtual column would be ideal, but for portability/simplicity with small batch:
        // We will fetch based on the assumption we can filter by 'propertis->desa'.

        $statusFeatures = \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
            ->get()
            ->filter(function ($feature) use ($desaNames) {
                // Determine matching key from properties
                // User example had "Desa" and "Nama_Desa".
                $props = $feature->properties;
                $name = $props['Nama_Desa'] ?? $props['Desa'] ?? null;

                return $name && in_array($name, $desaNames);
            })
            ->keyBy(function ($feature) {
                $props = $feature->properties;
                return $props['Nama_Desa'] ?? $props['Desa'];
            });

        // Pass map of [desa_name => feature]
        $statusMap = $statusFeatures->map(function ($feature) {
            return $feature->properties;
        });

        return view('admin.desa.index', [
            'title'     => "Manajemen Data Desa",
            'desas'     => $desas,
            'regencies' => $regencies,
            'districts' => $districts,
            'statusMap' => $statusMap,
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        // Cek bahwa district_id valid
        $district = RegDistrict::find($data['district_id']);
        if (!$district) {
            throw ValidationException::withMessages([
                'district_id' => ['Kecamatan tidak ditemukan.']
            ]);
        }

        // Generate ID: district_id (6 digit) + nomor urut (4 digit)
        $data['id'] = $this->generateVillageId($data['district_id']);

        RegVillage::create($data);

        return redirect()->route('admin.desa.index')->with('success', 'Data desa berhasil ditambahkan.');
    }

    // EDIT
    public function edit(RegVillage $desa)
    {
        $desa->load('district.regency');

        $regencies = RegRegency::orderBy('name')->get(['id', 'name']);
        $districts = RegDistrict::where('regency_id', $desa->district->regency_id ?? null)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.desa.edit', compact('desa', 'regencies', 'districts'));
    }

    // UPDATE
    public function update(Request $request, RegVillage $desa)
    {
        $data = $this->validatedData($request);

        // Cek bahwa district_id valid
        $district = RegDistrict::find($data['district_id']);
        if (!$district) {
            throw ValidationException::withMessages([
                'district_id' => ['Kecamatan tidak ditemukan.']
            ]);
        }

        // Update Desa
        $desa->update($data);

        // Update ImportedJsonFeature properties
        // We look for the feature associated with THIS desa (using its previous name or current name if we assume 1-1 link)
        // Since we just updated the name in $desa, we should check if there was a linked feature.
        // It's ambiguous if the name changed, so we rely on the fact that usually features are matched by name.
        // But if name changed, we might lose the link unless we find it by old name.
        // For now, let's assume we find by the *old* name (available if we hadn't updated yet, but we just did).
        // Actually, $desa was loaded before update. But 'update' changes the instance attributes in memory too.
        // However, we can use `getOriginal('name')` to be safe if needed, but since we already updated:

        $originalName = $desa->getOriginal('name');
        $newName = $data['name'];
        $statusBerlistrik = $request->input('status_berlistrik');

        if ($statusBerlistrik) {
            // Find feature by old name to preserve link if name changed
            $feature = \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->where(function ($q) use ($originalName) {
                    $q->where('properties->Nama_Desa', $originalName)
                        ->orWhere('properties->Desa', $originalName);
                })
                ->first();

            if ($feature) {
                $props = $feature->properties;

                // Update Status
                $props['StatusDesa'] = $statusBerlistrik;

                // Update Name if changed to keep link alive
                if (isset($props['Nama_Desa'])) {
                    $props['Nama_Desa'] = $newName;
                }
                if (isset($props['Desa'])) {
                    $props['Desa'] = $newName;
                }

                $feature->properties = $props;
                $feature->save();
            }
        }

        return redirect()->route('admin.desa.index')->with('success', 'Data desa dan status berlistrik berhasil diperbarui.');
    }

    // DESTROY
    public function destroy(RegVillage $desa)
    {
        $desa->delete();
        return redirect()->route('admin.desa.index')->with('success', 'Data desa berhasil dihapus.');
    }

    // ===== Helpers =====
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'district_id' => ['required', 'exists:reg_districts,id'],
            'name'        => ['required', 'string', 'max:255'],
        ], [], [
            'district_id' => 'Kecamatan',
            'name'        => 'Nama Desa',
        ]);
    }

    /**
     * Generate ID untuk desa baru
     * Format: district_id (6 digit) + nomor urut (4 digit) = 10 digit
     * Contoh: 640101 + 2009 = 6401012009
     */
    private function generateVillageId(string $districtId): string
    {
        // Cari ID terakhir untuk district ini
        $lastVillage = RegVillage::where('district_id', $districtId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastVillage) {
            // Extract nomor urut dari 4 digit terakhir
            $lastId = $lastVillage->id;
            $lastSequence = (int) substr($lastId, -4);
            $newSequence = $lastSequence + 1;
        } else {
            // Jika belum ada desa untuk district ini, mulai dari 2001
            $newSequence = 2001;
        }

        // Format: district_id + nomor urut (4 digit dengan leading zero)
        return $districtId . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
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

    /**
     * Get electricity statistics for visualization
     */
    public function getElectricityStats()
    {
        $plnCount = \DB::table('perizinan_listriks')
            ->where('status_kelistrikan', 'berlistrik_pln')
            ->count();

        $nonPlnCount = \DB::table('perizinan_listriks')
            ->where('status_kelistrikan', 'berlistrik_non_pln')
            ->count();

        $noElectricityCount = \DB::table('perizinan_listriks')
            ->where('status_kelistrikan', 'tidak_berlistrik')
            ->count();

        $total = \DB::table('perizinan_listriks')
            ->whereNotNull('status_kelistrikan')
            ->count();

        return response()->json([
            'pln' => $plnCount,
            'non_pln' => $nonPlnCount,
            'no_electricity' => $noElectricityCount,
            'total' => $total
        ]);
    }
}
