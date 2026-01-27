<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Balikpapan;
use App\Models\LN_Jalan_Berau;
use App\Models\LN_Jalan_Bontang;
use App\Models\LN_Jalan_Kubar;
use App\Models\LN_Jalan_KutaiKartanegara;
use App\Models\LN_Jalan_Kutim;
use App\Models\LN_Jalan_Paser;
use App\Models\LN_Jalan_PPU;
use App\Models\LN_Jalan_Samarinda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JalanAksesController extends Controller
{
    public function index(Request $request)
    {
        // Define all jalan models with their kabupaten names and column mappings
        $jalanModels = [
            'Balikpapan' => [
                'model' => LN_Jalan_Balikpapan::class,
                'columns' => [
                    'kecamatan' => 'Kecamatan',
                    'fungsi' => 'FUNGSI',
                    'nama_jalan' => 'NAMA_RUAS',
                    'panjang' => 'PANJANG',
                ]
            ],
            'Berau' => [
                'model' => LN_Jalan_Berau::class,
                'columns' => [
                    'kecamatan' => 'NULL',
                    'fungsi' => 'FUNGSI',
                    'nama_jalan' => 'NAMA_RUAS',
                    'panjang' => 'PANJANG',
                ]
            ],
            'Bontang' => [
                'model' => LN_Jalan_Bontang::class,
                'columns' => [
                    'kecamatan' => 'Kecamatan',
                    'fungsi' => 'Fungsi',
                    'nama_jalan' => 'Nm_Ruas',
                    'panjang' => 'Panjang',
                ]
            ],
            'Kutai Barat' => [
                'model' => LN_Jalan_Kubar::class,
                'columns' => [
                    'kecamatan' => 'NULL',
                    'fungsi' => 'Fungsi',
                    'nama_jalan' => 'Nm_Ruas',
                    'panjang' => 'Panjang',
                ]
            ],
            'Kutai Kartanegara' => [
                'model' => LN_Jalan_KutaiKartanegara::class,
                'columns' => [
                    'kecamatan' => 'KECAMATAN',
                    'fungsi' => 'FUNGSI',
                    'nama_jalan' => 'NAMA_BARU',
                    'panjang' => 'Panjang',
                ]
            ],
            'Kutai Timur' => [
                'model' => LN_Jalan_Kutim::class,
                'columns' => [
                    'kecamatan' => 'Kecamatan',
                    'fungsi' => 'Fungsi',
                    'nama_jalan' => 'Nm_Ruas',
                    'panjang' => 'Panjang',
                ]
            ],
            'Paser' => [
                'model' => LN_Jalan_Paser::class,
                'columns' => [
                    'kecamatan' => 'Kecamatan',
                    'fungsi' => 'Fungsi',
                    'nama_jalan' => 'Nm_Ruas',
                    'panjang' => 'Panjang',
                ]
            ],
            'Penajam Paser Utara' => [
                'model' => LN_Jalan_PPU::class,
                'columns' => [
                    'kecamatan' => 'NULL',
                    'fungsi' => 'NULL',
                    'nama_jalan' => 'Name',
                    'panjang' => 'Shape_Leng',
                ]
            ],
            'Samarinda' => [
                'model' => LN_Jalan_Samarinda::class,
                'columns' => [
                    'kecamatan' => 'Kecamatan',
                    'fungsi' => 'Fungsi',
                    'nama_jalan' => 'Nm_Ruas',
                    'panjang' => 'Panjang',
                ]
            ],
        ];

        // Build union query to combine all tables
        $queries = [];
        foreach ($jalanModels as $kabupaten => $config) {
            $modelClass = $config['model'];
            $columns = $config['columns'];

            $query = $modelClass::query()
                ->select(
                    DB::raw("'{$kabupaten}' as kabupaten_kota"),
                    DB::raw("{$columns['kecamatan']} as kecamatan"),
                    DB::raw("{$columns['fungsi']} as fungsi_jal"),
                    DB::raw("{$columns['nama_jalan']} as nama_jln"),
                    DB::raw("{$columns['panjang']} as panjang"),
                    DB::raw("NULL as sumber"),
                    'id'
                );

            // Apply filters
            if ($request->filled('q')) {
                $query->where($columns['nama_jalan'], 'like', '%' . $request->q . '%');
            }

            if ($request->filled('kecamatan') && $columns['kecamatan'] !== 'NULL') {
                $query->where($columns['kecamatan'], $request->kecamatan);
            }

            if ($request->filled('fungsi') && $columns['fungsi'] !== 'NULL') {
                $query->where($columns['fungsi'], $request->fungsi);
            }

            $queries[] = $query;
        }

        // Combine all queries with UNION
        $combinedQuery = $queries[0];
        for ($i = 1; $i < count($queries); $i++) {
            $combinedQuery = $combinedQuery->union($queries[$i]);
        }

        // Apply kabupaten filter after union
        if ($request->filled('kabupaten')) {
            $combinedQuery = DB::table(DB::raw("({$combinedQuery->toSql()}) as combined"))
                ->mergeBindings($combinedQuery->getQuery())
                ->where('kabupaten_kota', $request->kabupaten);
        } else {
            $combinedQuery = DB::table(DB::raw("({$combinedQuery->toSql()}) as combined"))
                ->mergeBindings($combinedQuery->getQuery());
        }

        // Order and paginate
        $perPage = $request->input('per_page', 10);
        $jalan = $combinedQuery
            ->orderBy('kabupaten_kota')
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        // Get unique values for filters from all tables
        $kabupatenList = collect(array_keys($jalanModels))->sort()->values();

        // Get unique kecamatan from all tables
        $kecamatanList = collect();
        foreach ($jalanModels as $config) {
            $modelClass = $config['model'];
            $kecamatanCol = $config['columns']['kecamatan'];

            if ($kecamatanCol !== 'NULL') {
                $kecamatanList = $kecamatanList->merge(
                    $modelClass::select(DB::raw("{$kecamatanCol} as kecamatan"))
                        ->distinct()
                        ->whereNotNull($kecamatanCol)
                        ->pluck('kecamatan')
                );
            }
        }
        $kecamatanList = $kecamatanList->unique()->sort()->values();

        // Get unique fungsi from all tables
        $fungsiFungsi = collect();
        foreach ($jalanModels as $config) {
            $modelClass = $config['model'];
            $fungsiCol = $config['columns']['fungsi'];

            if ($fungsiCol !== 'NULL') {
                $fungsiFungsi = $fungsiFungsi->merge(
                    $modelClass::select(DB::raw("{$fungsiCol} as fungsi"))
                        ->distinct()
                        ->whereNotNull($fungsiCol)
                        ->pluck('fungsi')
                );
            }
        }
        $fungsiFungsi = $fungsiFungsi->unique()->sort()->values();

        // Sumber is not available in these tables, so we'll provide an empty collection
        $sumberList = collect();

        return view('admin.jalan_akses.index', compact('jalan', 'kabupatenList', 'kecamatanList', 'fungsiFungsi', 'sumberList'));
    }
}
