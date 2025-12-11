<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AR_Batas_Kaltim_Kabupaten_Kota;
use Illuminate\Http\JsonResponse;

class ArBatasKaltimKabupatenKotaController extends Controller
{
    /**
     * Return AR_BATAS_KALTIM_KABUPATEN_KOTA sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = AR_Batas_Kaltim_Kabupaten_Kota::whereNotNull('geometry')->get();

        $features = $rows->map(function (AR_Batas_Kaltim_Kabupaten_Kota $row) {
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
                    'WADMPR'      => $row->wadmpr,
                    'WADMKK'      => $row->wadmkk,
                    'Shape_Leng'  => $row->shape_leng,
                    'Shape_Area'  => $row->shape_area,
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
