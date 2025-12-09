<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\LN_Rencana_Jaringan_Listrik_Bontang;

class JaringanlistrikbontangController extends Controller
{
    /**
     * Return rencana jaringan listrik Bontang sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Rencana_Jaringan_Listrik_Bontang::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Rencana_Jaringan_Listrik_Bontang $row) {
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
                    'Id'          => $row->source_id,
                    'Rencana'     => $row->rencana,
                    'fungsi_eks'  => $row->fungsi_eks,
                    'fungsi_ren'  => $row->fungsi_ren,
                    'Keterangan'  => $row->keterangan,
                    'Sumber'      => $row->sumber,
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
