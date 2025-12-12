<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PT1_Trafo_Gardu_Paser;

class Pt1TrafoGarduPaserController extends Controller
{
    /**
     * Return Gardu dan Trafo Paser sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT1_Trafo_Gardu_Paser::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT1_Trafo_Gardu_Paser $row) {
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
                    'Id'        => $row->id_prop,
                    'Name'      => $row->name,
                    'Descript'  => $row->descript,
                    'Type'      => $row->type,
                    'Comment'   => $row->comment,
                    'Symbol'    => $row->symbol,
                    'DateTimeS' => $row->datetimes,
                    'Elevation' => $row->elevation,
                    'Nama'      => $row->nama,
                    'Data'      => $row->data,
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