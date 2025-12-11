<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\DataJalanProvinsi;

class DatajalanprovinsiController extends Controller
{
    /**
     * Return data jalan provinsi sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = DataJalanProvinsi::whereNotNull('geometry')->get();

        $features = $rows->map(function (DataJalanProvinsi $row) {
            $geom = $row->geometry;

            // Jika geometry tersimpan sebagai string, decode dulu
            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            if (!$geom || !isset($geom['type'])) {
                return null; // skip jika invalid
            }

            return [
                'type'       => 'Feature',
                'properties' => [
                    'id'          => $row->id,
                    'OBJECTID'    => $row->objectid,
                    'Kl_Dat_Das'  => $row->kl_dat_das,
                    'Nm_Ruas'     => $row->nm_ruas,
                    'Thn_Data'    => $row->thn_data,
                    'Status'      => $row->status,
                    'Fungsi'      => $row->fungsi,
                    'Mendukung'   => $row->mendukung,
                    'Propinsi'    => $row->propinsi,
                    'Kab_Kot'     => $row->kab_kot,
                    'Kecamatan'   => $row->kecamatan,
                    'Desa_Kel'    => $row->desa_kel,
                    'Panjang'     => $row->panjang,
                    'panjangjal'  => $row->panjangjal,
                    'Status_J_1'  => $row->status_j_1,
                ],
                'geometry'   => $geom,
            ];
        })
        ->filter()
        ->values()
        ->toArray();

        return response()->json(
            [
                'type'     => 'FeatureCollection',
                'features' => $features,
            ],
            200,
            [
                'Content-Type'                => 'application/json',
                'Access-Control-Allow-Origin' => '*',
            ]
        );
    }
}
