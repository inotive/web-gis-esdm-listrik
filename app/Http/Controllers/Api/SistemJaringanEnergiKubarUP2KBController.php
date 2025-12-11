<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Sistem_Jaringan_Energi_Kubar_UP2KB;
use Illuminate\Http\JsonResponse;

class SistemJaringanEnergiKubarUP2KBController extends Controller
{
    /**
     * Return Sistem Jaringan Energi Kubar UP2KB sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Sistem_Jaringan_Energi_Kubar_UP2KB::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Sistem_Jaringan_Energi_Kubar_UP2KB $row) {
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
                    'descriptio' => $row->descriptio,
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
