<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Asset;
use App\Models\UnitKerja;
use App\Models\KategoriAsset;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // ===== KPI ringkas
        $totalAsset     = Asset::count();
        $totalLuas      = (float) Asset::sum('luas_m2');
        $totalUnitKerja = UnitKerja::count();

        // ===== Top Kategori (opsional, tetap ada)
        $topKategori = Asset::leftJoin('kategori_asset as k', 'asset.kategori_id', '=', 'k.id')
            ->selectRaw('k.id, COALESCE(k.name, "Tanpa Kategori") as name, COUNT(asset.id) as total, COALESCE(SUM(asset.luas_m2),0) as total_luas')
            ->groupBy('k.id', 'k.name')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // ===== Aset per Kabupaten (untuk ringkasan/tabel)
        $assetByKabupaten = Asset::leftJoin('reg_regencies as kab', 'asset.reg_regencies_id', '=', 'kab.id')
            ->selectRaw('kab.id as regency_id, COALESCE(kab.name,"Tidak Ada") as kabupaten, COUNT(asset.id) as total, COALESCE(SUM(asset.luas_m2),0) as total_luas')
            ->groupBy('kab.id', 'kab.name')
            ->orderByDesc('total')
            ->get();

        // Chart bar (kalau mau dipakai)
        $chartKabupaten = $assetByKabupaten->take(12);

        // Ringkasan jumlah aset per kabupaten (untuk tabel)
        $jumlahAssetPerKab = $assetByKabupaten->map(function ($r) {
            return [
                'regency_id' => (int) $r->regency_id,
                'kabupaten'  => $r->kabupaten,
                'total'      => (int) $r->total,
                'total_luas' => (float) $r->total_luas,
            ];
        });

        // ====== DEMOGRAFI "ASAL TANAH" PER KABUPATEN (DINAMIS) ======
        // Ambil daftar "asal" yang sudah dinormalisasi ringan:
        //   - ganti '-' dan '_' => spasi
        //   - trim + lowercase
        $asalLabels = Asset::query()
            ->selectRaw("LOWER(TRIM(REPLACE(REPLACE(COALESCE(asal,''),'-',' '),'_',' '))) as asal_key")
            ->whereNotNull('asal')
            ->where('asal', '!=', '')
            ->distinct()
            ->pluck('asal_key')
            ->filter()
            ->values();

        // Hitung jumlah per (kabupaten x asal_key)
        $asalGroup = Asset::leftJoin('reg_regencies as kab', 'asset.reg_regencies_id', '=', 'kab.id')
            ->selectRaw('kab.id as regency_id, COALESCE(kab.name,"Tidak Ada") as kabupaten')
            ->selectRaw("LOWER(TRIM(REPLACE(REPLACE(COALESCE(asset.asal,''),'-',' '),'_',' '))) as asal_key")
            ->selectRaw('COUNT(asset.id) as total')
            ->groupBy('kab.id', 'kab.name', 'asal_key')
            ->get();

        // Bentuk struktur: satu item per kabupaten, dengan counts[asal_key] => total
        $asalPerKab = $asalGroup->groupBy('regency_id')->map(function ($rows) use ($asalLabels) {
            $first  = $rows->first();
            $counts = [];
            foreach ($asalLabels as $key) {
                $counts[$key] = 0;
            }
            foreach ($rows as $r) {
                $counts[$r->asal_key] = (int) $r->total;
            }
            return (object) [
                'regency_id' => $first->regency_id,
                'kabupaten'  => $first->kabupaten,
                'counts'     => $counts,
            ];
        })->values();

        // Skala maksimum untuk tinggi batang (hindari div/0)
        $maxAsal = max(
            1,
            collect($asalPerKab)->flatMap(function ($r) {
                return array_values($r->counts);
            })->max() ?? 1
        );

        // ===== (Opsional) Demografi kategori terpopuler (tetap ada bila dipakai tempat lain)
        $top3KategoriIds = Asset::selectRaw('kategori_id, COUNT(*) as total')
            ->groupBy('kategori_id')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('kategori_id')
            ->filter()
            ->values();

        $kategoriNames = KategoriAsset::whereIn('id', $top3KategoriIds)->pluck('name', 'id');

        $kategoriCountsPerKab = Asset::selectRaw('reg_regencies_id as regency_id, kategori_id, COUNT(*) as total')
            ->groupBy('regency_id', 'kategori_id')
            ->get();

        $demografi = $assetByKabupaten->map(function ($row) use ($kategoriCountsPerKab, $top3KategoriIds) {
            $entry = [
                'kabupaten'  => $row->kabupaten,
                'regency_id' => $row->regency_id,
            ];
            foreach ($top3KategoriIds as $kid) {
                $match = $kategoriCountsPerKab->first(function ($r) use ($row, $kid) {
                    return (int)$r->regency_id === (int)$row->regency_id && (int)$r->kategori_id === (int)$kid;
                });
                $entry["k{$kid}"] = $match->total ?? 0;
            }
            return $entry;
        });

        return view('admin.dashboard.index', compact(
            'totalAsset',
            'totalLuas',
            'totalUnitKerja',
            'topKategori',
            'chartKabupaten',
            'demografi',
            'top3KategoriIds',
            'kategoriNames',
            // Tambahan untuk tampilan kita
            'jumlahAssetPerKab',
            'asalLabels',
            'asalPerKab',
            'maxAsal'
        ));
    }

    // (Opsional) endpoint JSON
    public function stats()
    {
        $data = [
            'total_asset' => Asset::count(),
            'total_luas'  => Asset::sum('luas_m2'),
        ];
        return response()->json($data);
    }
}
