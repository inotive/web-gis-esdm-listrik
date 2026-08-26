<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegVillage;
use App\Models\RegDistrict;
use App\Models\RegRegency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class DesaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:desa.view')->only(['index']);
        $this->middleware('can:desa.create')->only(['create', 'store']);
        $this->middleware('can:desa.edit')->only(['edit', 'update']);
        $this->middleware('can:desa.delete')->only(['destroy']);
    }

    // INDEX
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $q = $request->get('q');
        $regencyId = $request->get('regency_id');
        $districtId = $request->get('district_id');

        $filterNama = $request->get('filter_desa_nama');
        $filterKecamatan = $request->get('filter_desa_kecamatan');
        $filterKabupaten = $request->get('filter_desa_kabupaten');
        $filterStatus = $request->get('filter_desa_status');

        $query = RegVillage::with(['district.regency', 'dataBerlistrik']);

        // Search by name (Global)
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

        // Column Filters
        if ($filterNama) {
            $query->where('name', 'like', '%' . $filterNama . '%');
        }

        if ($filterKecamatan) {
            $query->whereHas('district', function ($q) use ($filterKecamatan) {
                $q->where('name', 'like', '%' . $filterKecamatan . '%');
            });
        }

        if ($filterKabupaten) {
            $query->whereHas('district.regency', function ($q) use ($filterKabupaten) {
                $q->where('name', 'like', '%' . $filterKabupaten . '%');
            });
        }

        if ($filterStatus) {
            // Find village names that match the status in ImportedJsonFeature
            $matchingFeatures = \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->where('properties->StatusDesa', 'like', '%' . $filterStatus . '%')
                ->get();

            $matchingNames = [];
            foreach ($matchingFeatures as $feature) {
                $props = $feature->properties;
                if (!empty($props['Nama_Desa'])) $matchingNames[] = $props['Nama_Desa'];
                if (!empty($props['Desa'])) $matchingNames[] = $props['Desa'];
            }

            // Filter by (Found in JSON Feature) OR (Found in Local Column)
            $query->where(function ($q) use ($matchingNames, $filterStatus) {
                if (!empty($matchingNames)) {
                    $q->whereIn('name', array_unique($matchingNames));
                }
                $q->orWhere('status_berlistrik', 'like', '%' . $filterStatus . '%');
            });
        }

        // Toolbar Filter: Status Listrik
        $statusToolbar = $request->get('status');
        if ($statusToolbar) {
            // Optimize: use chunk to process records in batches and prevent memory exhaustion
            $matchingNames = [];
            \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->select('id', 'properties')
                ->chunk(200, function ($features) use ($statusToolbar, &$matchingNames) {
                    foreach ($features as $feature) {
                        $s = strtoupper($feature->properties['StatusDesa'] ?? '');
                        
                        $matches = false;
                        if ($statusToolbar === 'Belum terlayani listrik') {
                            $matches = str_contains($s, 'BELUM') || str_contains($s, 'TIDAK');
                        } elseif ($statusToolbar === 'Berlistrik Non PLN') {
                            $matches = str_contains($s, 'NONPLN') || str_contains($s, 'NON PLN') || str_contains($s, 'NON-PLN');
                        } elseif ($statusToolbar === 'Terlayani Listrik PLN') {
                            $isDanger = str_contains($s, 'BELUM') || str_contains($s, 'TIDAK');
                            $isNonPln = str_contains($s, 'NONPLN') || str_contains($s, 'NON PLN') || str_contains($s, 'NON-PLN');
                            if (!$isDanger && !$isNonPln) {
                                $matches = str_contains($s, 'TERLAYANI') || str_contains($s, 'BERLISTRIK');
                            }
                        }
                        
                        if ($matches) {
                            $props = $feature->properties;
                            if (!empty($props['Nama_Desa'])) $matchingNames[] = $props['Nama_Desa'];
                            if (!empty($props['Desa'])) $matchingNames[] = $props['Desa'];
                        }
                    }
                });

            // Get all unique village names that have feature data in database
            $allFeatures = \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->select('properties')
                ->get();
            
            $allFeatureNames = [];
            foreach ($allFeatures as $feature) {
                $props = $feature->properties;
                if (!empty($props['Nama_Desa'])) $allFeatureNames[] = $props['Nama_Desa'];
                if (!empty($props['Desa'])) $allFeatureNames[] = $props['Desa'];
            }
            $allFeatureNames = array_unique($allFeatureNames);

            // Filter by (Found in JSON Feature) OR (Found in Local Column if not in JSON)
            $query->where(function ($q) use ($matchingNames, $allFeatureNames, $statusToolbar) {
                // Condition 1: If the village has a feature, its name must match the matching JSON features
                $q->where(function ($sub) use ($matchingNames, $allFeatureNames) {
                    $sub->whereIn('name', $allFeatureNames);
                    if (!empty($matchingNames)) {
                        $sub->whereIn('name', array_unique($matchingNames));
                    } else {
                        $sub->whereRaw('1 = 0');
                    }
                });

                // Condition 2: If the village does NOT have a feature, check the local status_berlistrik
                $q->orWhere(function ($sub) use ($allFeatureNames, $statusToolbar) {
                    if (!empty($allFeatureNames)) {
                        $sub->whereNotIn('name', $allFeatureNames);
                    }
                    
                    $sub->where(function ($localQ) use ($statusToolbar) {
                        if ($statusToolbar === 'Belum terlayani listrik') {
                            $localQ->where('status_berlistrik', 'like', '%Belum%')
                                ->orWhere('status_berlistrik', 'like', '%Tidak%');
                        } elseif ($statusToolbar === 'Berlistrik Non PLN') {
                            $localQ->where('status_berlistrik', 'like', '%NonPLN%')
                                ->orWhere('status_berlistrik', 'like', '%Non PLN%')
                                ->orWhere('status_berlistrik', 'like', '%Non-PLN%');
                        } elseif ($statusToolbar === 'Terlayani Listrik PLN') {
                            $localQ->where('status_berlistrik', 'like', '%Terlayani%')
                                ->orWhere('status_berlistrik', 'like', '%Berlistrik%');
                        } else {
                            $localQ->where('status_berlistrik', $statusToolbar);
                        }
                    });
                });
            });
        }

        $desas = $query->orderBy('created_at', 'desc')
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
        // Optimize: use chunk to process records in batches and prevent memory exhaustion
        $statusFeatures = collect([]);
        if (!empty($desaNames)) {
            \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->select('id', 'properties')
                ->chunk(200, function ($features) use ($desaNames, &$statusFeatures) {
                    foreach ($features as $feature) {
                        // Determine matching key from properties
                        $props = $feature->properties;
                        $name = $props['Desa'] ?? ($props['Nama_Desa'] ?? null);

                        if ($name && in_array($name, $desaNames)) {
                            // Use name as key to prevent duplicates
                            $statusFeatures[$name] = $feature;
                        }
                    }
                });
            
            // Convert to collection for consistency
            $statusFeatures = collect($statusFeatures);
        }

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

        // Add status_berlistrik if present (User Request: "ketika tambah data tambahkan status berlistrik untuk tabel desa saja")
        if ($request->has('status_berlistrik')) {
            $data['status_berlistrik'] = $request->input('status_berlistrik');
        }

        $generateId = $this->generateVillageId($data['district_id']);
        $data['id'] = $generateId;

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

        // Capture previous name for syncing
        $originalName = $desa->getOriginal('name');
        $newName = $data['name'];
        $statusBerlistrik = $request->input('status_berlistrik');

        // Update Desa (User Request: "ketika update update ke tabel desa")
        if ($statusBerlistrik) {
            $data['status_berlistrik'] = $statusBerlistrik;
        }

        $desa->update($data);

        // Update ImportedJsonFeature properties (User Request: "ketika data ada di tabel ImportedJsonFeature maka update juga d situ")
        // NOTE: only update if exists. Do NOT create if missing.
        if ($statusBerlistrik) {
            $feature = \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik')
                ->where(function ($q) use ($originalName, $newName) {
                    $q->where('properties->Nama_Desa', $originalName)
                        ->orWhere('properties->Desa', $originalName)
                        ->orWhere('properties->Nama_Desa', $newName)
                        ->orWhere('properties->Desa', $newName);
                })
                ->first();

            if ($feature) {
                $props = $feature->properties;

                // Update Status
                $props['StatusDesa'] = $statusBerlistrik;
                $feature->properties = $props;
                $feature->save();

                Cache::flush();
            }

            $feature = \App\Models\ImportedJsonFeature::where('sub_kategori', 'Status Desa Berlistrik dengan Bantuan')
                ->where(function ($q) use ($originalName, $newName) {
                    $q->where('properties->Nama_Desa', $originalName)
                        ->orWhere('properties->Desa', $originalName)
                        ->orWhere('properties->Nama_Desa', $newName)
                        ->orWhere('properties->Desa', $newName);
                })
                ->first();

            if ($feature) {
                $props = $feature->properties;

                // Update Status
                $props['Status_Des'] = $statusBerlistrik;
                $feature->properties = $props;
                $feature->save();

                Cache::flush();
            }
        }


        return redirect()->route('admin.desa.index')->with('success', 'Data desa berhasil diperbarui.');
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
            'status_berlistrik' => ['required', 'string', 'max:255'],
        ], [], [
            'district_id' => 'Kecamatan',
            'name'        => 'Nama Desa',
            'status_berlistrik' => 'Status Berlistrik',
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
