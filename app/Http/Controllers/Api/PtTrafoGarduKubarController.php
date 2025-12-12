<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PT_Trafo_Gardu_Kubar;

class PtTrafoGarduKubarController extends Controller
{
    /**
     * Return Trafo Gardu Kubar sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Trafo_Gardu_Kubar::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Trafo_Gardu_Kubar $row) {
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
                    'id'          => $row->id_prop,
                    'Name'        => $row->name,
                    'descriptio'  => $row->descriptio,
                    'timestamp'   => $row->timestamp,
                    'begin'       => $row->begin,
                    'end'         => $row->end,
                    'altitudeMo'  => $row->altitudemo,
                    'tessellate'  => $row->tessellate,
                    'extrude'     => $row->extrude,
                    'visibility'  => $row->visibility,
                    'drawOrder'   => $row->draworder,
                    'icon'        => $row->icon,
                    'KAPASITAS'   => $row->kapasitas,
                    'FEEDER'      => $row->feeder,
                    'ZONA'        => $row->zona,
                    'NILAI_PENT'  => $row->nilai_pent,
                    'LATITUDE'    => $row->latitude,
                    'LONGITUDE'   => $row->longitude,
                    'layer'       => $row->layer,
                    'path'        => $row->path,
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