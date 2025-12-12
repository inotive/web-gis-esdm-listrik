<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Sistem_Jaringan_Energi_Kutim;
use Illuminate\Http\JsonResponse;

class SistemJaringanEnergiKutimController extends Controller
{
    /**
     * Return Sistem Jaringan Energi Kutim sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Sistem_Jaringan_Energi_Kutim::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Sistem_Jaringan_Energi_Kutim $row) {
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
                    'id'          => $row->id,
                    'OBJECTID'    => $row->objectid,
                    'classifica'  => $row->classifica,
                    'GlobalID'    => $row->globalid,
                    'Shape_Leng'  => $row->shape_leng,
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
