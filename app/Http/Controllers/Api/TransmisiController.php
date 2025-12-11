<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Transmisi;
use Illuminate\Http\JsonResponse;

class TransmisiController extends Controller
{
    /**
     * Return LN Transmisi sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Transmisi::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Transmisi $row) {
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
                    'OBJECTID'   => $row->objectid,
                    'NAMOBJ'     => $row->namobj,
                    'ORDE01'     => $row->orde01,
                    'ORDE02'     => $row->orde02,
                    'ORDE03'     => $row->orde03,
                    'ORDE04'     => $row->orde04,
                    'JNSRSR'     => $row->jnsrsr,
                    'STSJRN'     => $row->stsjrn,
                    'WADMPR'     => $row->wadmpr,
                    'REMARK'     => $row->remark,
                    'SBDATA'     => $row->sbdata,
                    'SHAPE_Leng' => $row->shape_leng,
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
