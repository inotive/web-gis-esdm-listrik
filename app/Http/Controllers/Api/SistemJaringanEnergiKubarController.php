<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Sistem_Jaringan_Energi_Kubar;
use Illuminate\Http\JsonResponse;

class SistemJaringanEnergiKubarController extends Controller
{
    /**
     * Return Sistem Jaringan Energi Kubar sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Sistem_Jaringan_Energi_Kubar::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Sistem_Jaringan_Energi_Kubar $row) {
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
                    'Shape_Leng'  => $row->shape_leng,
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
