<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Batas_Kecamatan;
use Illuminate\Http\JsonResponse;

class LnBatasKecamatanController extends Controller
{
    /**
     * Return LN_BATAS_KECAMATAN sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Batas_Kecamatan::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Batas_Kecamatan $row) {
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
                    'FID_AR_BAT' => $row->fid_ar_bat,
                    'WADMPR'     => $row->wadmpr,
                    'WADMKK'     => $row->wadmkk,
                    'WADMKC'     => $row->wadmkc,
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
