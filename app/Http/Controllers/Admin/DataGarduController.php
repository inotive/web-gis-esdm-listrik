<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gardu;
use Illuminate\Http\Request;

// NEW: import model wilayah
use App\Models\RegProvince;
use App\Models\RegRegency;
use App\Models\RegDistrict;
use App\Models\RegVillage;

class DataGarduController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim((string) $request->get('q'));
        $jenis   = (string) $request->get('jenis');
        $perPage = (int) $request->get('per_page', 10);

        $items = Gardu::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('lokasi', 'like', "%{$q}%");
                });
            })
            ->when($jenis !== '', function ($query) use ($jenis) {
                $query->where('jenis_gardu_distribusi', $jenis);
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $jenisOptions = [
            'Gardu Tiang',
            'Gardu Portal',
            'Gardu Beton',
            'Gardu Compact',
            'Lainnya',
        ];

        return view('admin.data_gardu.index', compact('items', 'jenisOptions', 'q', 'jenis', 'perPage'));
    }

    public function create()
    {
        $jenisOptions = ['Gardu Tiang','Gardu Portal','Gardu Beton','Gardu Compact','Lainnya'];
        return view('admin.data_gardu.create', compact('jenisOptions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'                   => ['required','string','max:150'],
            'lokasi'                 => ['nullable','string','max:255'],
            'jenis_gardu_distribusi' => ['required','string','max:100'],

            // id wilayah diisi otomatis saat user memilih saran lokasi (boleh null kalau tidak pilih)
            'province_id'            => ['nullable','string','max:10'],
            'regency_id'             => ['nullable','string','max:10'],
            'district_id'            => ['nullable','string','max:10'],
            'village_id'             => ['nullable','string','max:10'],
        ]);

        Gardu::create($data);

        return redirect()->route('admin.gardu.index')->with('success', 'Data gardu berhasil ditambahkan.');
    }

    public function edit(Gardu $gardu)
    {
        $jenisOptions = ['Gardu Tiang','Gardu Portal','Gardu Beton','Gardu Compact','Lainnya'];
        return view('admin.data_gardu.edit', compact('gardu', 'jenisOptions'));
    }

    public function update(Request $request, Gardu $gardu)
    {
        $data = $request->validate([
            'nama'                   => ['required','string','max:150'],
            'lokasi'                 => ['nullable','string','max:255'],
            'jenis_gardu_distribusi' => ['required','string','max:100'],
            'province_id'            => ['nullable','string','max:10'],
            'regency_id'             => ['nullable','string','max:10'],
            'district_id'            => ['nullable','string','max:10'],
            'village_id'             => ['nullable','string','max:10'],
        ]);

        $gardu->update($data);

        return redirect()->route('admin.gardu.index')->with('success', 'Data gardu berhasil diperbarui.');
    }

    public function destroy(Gardu $gardu)
    {
        $gardu->delete();
        return redirect()->route('admin.gardu.index')->with('success', 'Data gardu berhasil dihapus.');
    }

    /**
     * NEW: Suggest lokasi berdasarkan q (ambil dari kelurahan/kecamatan/kabupaten/provinsi)
     * Response: [{ label, value, type, ids: {province_id, regency_id, district_id, village_id} }]
     */
    public function locationSuggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $limit = (int) $request->get('limit', 10);
        $limit = max(1, min($limit, 20));

        if ($q === '') {
            return response()->json([]);
        }

        $results = [];
        $push = function(array $row) use (&$results, $limit) {
            // hindari duplikat label
            foreach ($results as $r) {
                if ($r['label'] === $row['label']) return;
            }
            if (count($results) < $limit) $results[] = $row;
        };

        // 1) Kelurahan/Desa
        $villages = RegVillage::with(['district.regency.province'])
            ->where('name', 'like', "%{$q}%")
            ->orderBy('name')->limit($limit)->get();
        foreach ($villages as $v) {
            $label = "{$v->name}, ".($v->district->name ?? '—').", ".($v->district->regency->name ?? '—').", ".($v->district->regency->province->name ?? '—');
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
            ]);
        }

        // 2) Kecamatan
        if (count($results) < $limit) {
            $districts = RegDistrict::with(['regency.province'])
                ->where('name', 'like', "%{$q}%")
                ->orderBy('name')->limit($limit)->get();
            foreach ($districts as $d) {
                $label = "{$d->name}, ".($d->regency->name ?? '—').", ".($d->regency->province->name ?? '—');
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
                ]);
            }
        }

        // 3) Kabupaten/Kota
        if (count($results) < $limit) {
            $regencies = RegRegency::with(['province'])
                ->where('name', 'like', "%{$q}%")
                ->orderBy('name')->limit($limit)->get();
            foreach ($regencies as $r) {
                $label = "{$r->name}, ".($r->province->name ?? '—');
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
                ]);
            }
        }

        // 4) Provinsi
        if (count($results) < $limit) {
            $provinces = RegProvince::query()
                ->where('name', 'like', "%{$q}%")
                ->orderBy('name')->limit($limit)->get();
            foreach ($provinces as $p) {
                $label = "{$p->name}";
                $push([
                    'label' => $label,
                    'value' => $label,
                    'type'  => 'province',
                    'ids'   => [
                        'province_id' => $p->id,
                        'regency_id'  => null,
                        'district_id' => null,
                        'village_id'  => null,
                    ],
                ]);
            }
        }

        return response()->json($results);
    }
}
