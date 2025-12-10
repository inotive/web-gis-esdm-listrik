<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PT_Pembangkit_Eksisting;
use Illuminate\Http\JsonResponse;

class PtPembangkitEksistingController extends Controller
{
    /**
     * Return PT_Pembangkit_Eksisting sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Pembangkit_Eksisting::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Pembangkit_Eksisting $row) {
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
                    'OBJECTID'  => $row->objectid,
                    'NAMOBJ'    => $row->namobj,
                    'ORDE01'    => $row->orde01,
                    'ORDE02'    => $row->orde02,
                    'ORDE03'    => $row->orde03,
                    'ORDE04'    => $row->orde04,
                    'JNSRSR'    => $row->jnsrsr,
                    'STSJRN'    => $row->stsjrn,
                    'WADMPR'    => $row->wadmpr,
                    'REMARK'    => $row->remark,
                    'SBDATA'    => $row->sbdata,
                    'J_Pmbngkt' => $row->j_pmbngkt,
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
