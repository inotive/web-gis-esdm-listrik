<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembangkitLokal;
use App\Models\Wilayah;
use App\Models\RegProvince;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;
use Illuminate\Http\Request;

class PembangkitLokalController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim((string) $request->get('q'));
        $perPage = (int) $request->get('per_page', 10);

        $items = PembangkitLokal::query()
            ->with([
                'wilayah.village',
                'wilayah.district',
                'wilayah.regency.province',
            ])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('kapasitas_gardu', 'like', "%{$q}%")
                      ->orWhereHas('wilayah.village', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('wilayah.district', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('wilayah.regency', fn($qq) => $qq->where('name', 'like', "%{$q}%"))
                      ->orWhereHas('wilayah.regency.province', fn($qq) => $qq->where('name', 'like', "%{$q}%"));
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pembangkit_lokal.index', compact('items', 'q', 'perPage'));
    }

    public function create()
    {
        return view('admin.pembangkit_lokal.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kapasitas_gardu' => ['required','string','max:100'],
            'lokasi'          => ['nullable','string','max:255'], // label tampilan
            'wilayah_id'      => ['nullable','integer'],

            // hidden ids dari suggest (opsional; dipakai untuk findOrCreate wilayah)
            'province_id'     => ['nullable','string','max:10'],
            'regency_id'      => ['nullable','string','max:10'],
            'district_id'     => ['nullable','string','max:10'],
            'village_id'      => ['nullable','string','max:10'],
        ]);

        $wilayahId = $data['wilayah_id'] ?? null;

        if (!$wilayahId) {
            // Buat/cari wilayah berdasar kombinasi id yang tersedia
            $w = $this->findOrCreateWilayah(
                $data['regency_id'] ?? null,
                $data['district_id'] ?? null,
                $data['village_id'] ?? null
            );
            $wilayahId = $w?->id;
        }

        PembangkitLokal::create([
            'wilayah_id'      => $wilayahId,
            'kapasitas_gardu' => $data['kapasitas_gardu'],
        ]);

        return redirect()->route('admin.pembangkit.index')->with('success', 'Pembangkit lokal berhasil ditambahkan.');
    }

    public function edit(PembangkitLokal $pembangkit)
    {
        $pembangkit->load(['wilayah.village', 'wilayah.district', 'wilayah.regency.province']);
        // Prelabel lokasi untuk ditampilkan di input
        $lokasi = $pembangkit->lokasiLabel();

        return view('admin.pembangkit_lokal.edit', compact('pembangkit', 'lokasi'));
    }

    public function update(Request $request, PembangkitLokal $pembangkit)
    {
        $data = $request->validate([
            'kapasitas_gardu' => ['required','string','max:100'],
            'lokasi'          => ['nullable','string','max:255'],
            'wilayah_id'      => ['nullable','integer'],
            'province_id'     => ['nullable','string','max:10'],
            'regency_id'      => ['nullable','string','max:10'],
            'district_id'     => ['nullable','string','max:10'],
            'village_id'      => ['nullable','string','max:10'],
        ]);

        $wilayahId = $data['wilayah_id'] ?? null;

        if (!$wilayahId) {
            $w = $this->findOrCreateWilayah(
                $data['regency_id'] ?? null,
                $data['district_id'] ?? null,
                $data['village_id'] ?? null
            );
            $wilayahId = $w?->id;
        }

        $pembangkit->update([
            'wilayah_id'      => $wilayahId,
            'kapasitas_gardu' => $data['kapasitas_gardu'],
        ]);

        return redirect()->route('admin.pembangkit.index')->with('success', 'Pembangkit lokal berhasil diperbarui.');
    }

    public function destroy(PembangkitLokal $pembangkit)
    {
        $pembangkit->delete();
        return redirect()->route('admin.pembangkit.index')->with('success', 'Pembangkit lokal berhasil dihapus.');
    }

    /**
     * Endpoint suggest lokasi (autocomplete)
     * GET admin/pembangkit-lokal/location-suggest?q=...
     * Response: [{ label, value, type, ids:{...}, wilayah_id }]
     */
    public function locationSuggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $limit = max(1, min((int)$request->get('limit', 10), 20));
        if ($q === '') return response()->json([]);

        $results = [];
        $push = function(array $row) use (&$results, $limit) {
            foreach ($results as $r) if ($r['label'] === $row['label']) return;
            if (count($results) < $limit) $results[] = $row;
        };

        // Villages
        $villages = RegVillage::with(['district.regency.province'])
            ->where('name', 'like', "%{$q}%")->orderBy('name')->limit($limit)->get();
        foreach ($villages as $v) {
            $label = "{$v->name}, ".($v->district->name ?? '—').", ".($v->district->regency->name ?? '—').", ".($v->district->regency->province->name ?? '—');

            $wil = Wilayah::where('village_id', $v->id)->first();
            if (!$wil) {
                // fallback: buat sementara label tetap bisa dipilih, wilayah_id null (akan dibuat di store)
                $wilId = null;
            } else $wilId = $wil->id;

            $push([
                'label' => $label,
                'value' => $label,
                'type'  => 'village',
                'ids'   => [
                    'province_id' => $v->district->regency->province->id ?? null,
                    'regency_id'  => $v->district->regency->id ?? null,
                    'district_id' => $v->district->id ?? null,
                    'village_id'  => $v->id,
                ],
                'wilayah_id' => $wilId,
            ]);
        }

        // Districts
        if (count($results) < $limit) {
            $districts = RegDistrict::with(['regency.province'])
                ->where('name', 'like', "%{$q}%")->orderBy('name')->limit($limit)->get();
            foreach ($districts as $d) {
                $label = "{$d->name}, ".($d->regency->name ?? '—').", ".($d->regency->province->name ?? '—');

                $wil = Wilayah::where('district_id', $d->id)->whereNull('village_id')->first(); // baris wilayah per kecamatan (jika ada)
                $push([
                    'label' => $label,
                    'value' => $label,
                    'type'  => 'district',
                    'ids'   => [
                        'province_id' => $d->regency->province->id ?? null,
                        'regency_id'  => $d->regency->id ?? null,
                        'district_id' => $d->id,
                        'village_id'  => null,
                    ],
                    'wilayah_id' => $wil?->id,
                ]);
            }
        }

        // Regencies
        if (count($results) < $limit) {
            $regencies = RegRegency::with(['province'])
                ->where('name', 'like', "%{$q}%")->orderBy('name')->limit($limit)->get();
            foreach ($regencies as $r) {
                $label = "{$r->name}, ".($r->province->name ?? '—');

                $wil = Wilayah::where('regency_id', $r->id)
                        ->whereNull('district_id')->whereNull('village_id')->first(); // baris wilayah per kabupaten (jika ada)
                $push([
                    'label' => $label,
                    'value' => $label,
                    'type'  => 'regency',
                    'ids'   => [
                        'province_id' => $r->province->id ?? null,
                        'regency_id'  => $r->id,
                        'district_id' => null,
                        'village_id'  => null,
                    ],
                    'wilayah_id' => $wil?->id,
                ]);
            }
        }

        // Provinces
        if (count($results) < $limit) {
            $provinces = RegProvince::where('name', 'like', "%{$q}%")
                ->orderBy('name')->limit($limit)->get();
            foreach ($provinces as $p) {
                $label = "{$p->name}";
                // Tidak ada province_id di tabel wilayah → tidak bisa map 1:1 ke wilayah
                $push([
                    'label'      => $label,
                    'value'      => $label,
                    'type'       => 'province',
                    'ids'        => [
                        'province_id' => $p->id,
                        'regency_id'  => null,
                        'district_id' => null,
                        'village_id'  => null,
                    ],
                    'wilayah_id' => null,
                ]);
            }
        }

        return response()->json($results);
    }

    /**
     * Utility: cari atau buat baris wilayah dari kombinasi id yang diberikan.
     */
    protected function findOrCreateWilayah(?string $regencyId, ?string $districtId, ?string $villageId): ?Wilayah
    {
        if (!$regencyId && !$districtId && !$villageId) {
            return null; // tidak ada info lokasi => biarkan null
        }

        $query = Wilayah::query()
            ->when($regencyId, fn($q) => $q->where('regency_id', $regencyId))
            ->when($districtId, fn($q) => $q->where('district_id', $districtId))
            ->when($villageId, fn($q) => $q->where('village_id', $villageId));

        $found = $query->first();
        if ($found) return $found;

        // Buat baru minimal dengan kunci wilayah yang ada
        return Wilayah::create([
            'regency_id' => $regencyId,
            'district_id'=> $districtId,
            'village_id' => $villageId,
            'lat'        => null,
            'lng'        => null,
            'polygon_geojson' => null,
        ]);
    }
}
