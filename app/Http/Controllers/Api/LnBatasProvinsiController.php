<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Batas_Provinsi;
use Illuminate\Http\JsonResponse;

class LnBatasProvinsiController extends Controller
{
    /**
     * Return LN_BATAS_PROVINSI sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Batas_Provinsi::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Batas_Provinsi $row) {
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
                    'FID_Export' => $row->fid_export,
                    'WADMPR'     => $row->wadmpr,
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
