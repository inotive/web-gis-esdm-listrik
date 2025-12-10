<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_SUTR_Kutim;
use Illuminate\Http\JsonResponse;

class SutrKutimController extends Controller
{
    /**
     * Return LN SUTR Kutim sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_SUTR_Kutim::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_SUTR_Kutim $row) {
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
                    'globalid_1'  => $row->globalid_1,
                    'shape_Leng'  => $row->shape_leng,
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
