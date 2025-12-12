<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN2_SUTM_Paser;
use Illuminate\Http\JsonResponse;

class Ln2SutmPaserController extends Controller
{
    /**
     * Return LN2 SUTM Paser sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN2_SUTM_Paser::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN2_SUTM_Paser $row) {
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
                    'layer'       => $row->layer,
                    'path'        => $row->path,
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
