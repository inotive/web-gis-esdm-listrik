<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PT2_Trafo_Gardu_Paser;

class Pt2TrafoGarduPaserController extends Controller
{
    /**
     * Return Gardu Induk Paser sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT2_Trafo_Gardu_Paser::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT2_Trafo_Gardu_Paser $row) {
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
                    'id'         => $row->id_prop,
                    'Name'       => $row->name,
                    'descriptio' => $row->descriptio,
                    'timestamp'  => $row->timestamp,
                    'begin'      => $row->begin,
                    'end'        => $row->end,
                    'altitudeMo' => $row->altitudemo,
                    'tessellate' => $row->tessellate,
                    'extrude'    => $row->extrude,
                    'visibility' => $row->visibility,
                    'drawOrder'  => $row->draworder,
                    'icon'       => $row->icon,
                    'TES_1'      => $row->tes_1,
                    'TES_2'      => $row->tes_2,
                    'TES_4'      => $row->tes_4,
                    'TES_5'      => $row->tes_5,
                    'TES_6'      => $row->tes_6,
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