<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PT_Gardu_Hubung_Kutim;
use Illuminate\Http\JsonResponse;

class PtGarduHubungKutimController extends Controller
{
    /**
     * Return PT_GARDU_HUBUNG_KUTIM sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Gardu_Hubung_Kutim::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Gardu_Hubung_Kutim $row) {
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
                    'id'        => $row->id,
                    'NAMA'      => $row->nama,
                    'GlobalID'  => $row->globalid,
                    'ORIG_FID'  => $row->orig_fid,
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
