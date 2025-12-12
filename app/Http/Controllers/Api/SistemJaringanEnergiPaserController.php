<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Sistem_Jaringan_Energi_Paser;
use Illuminate\Http\JsonResponse;

class SistemJaringanEnergiPaserController extends Controller
{
    /**
     * Return Sistem Jaringan Energi Paser sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Sistem_Jaringan_Energi_Paser::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Sistem_Jaringan_Energi_Paser $row) {
            $geom = $row->geometry;

            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            if (!$geom || !isset($geom['type'])) {
                return null;
            }

            return [
                'type'       => 'Feature',
                'properties' => [
                    'id'         => $row->id,
                    'OBJECTID'   => $row->objectid,
                    'Jalan'      => $row->jalan,
                    'WADMKC'     => $row->wadmkc,
                    'WADMKD'     => $row->wadmkd,
                    'WADMKK'     => $row->wadmkk,
                    'Shape_Leng' => $row->shape_leng,
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
