<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PT_Sistem_Infrastruktur_Energi_Samarinda;

class PtSistemInfrastrukturEnergiSamarindaController extends Controller
{
    /**
     * Return Sistem Infrastruktur Energi Samarinda sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Sistem_Infrastruktur_Energi_Samarinda::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Sistem_Infrastruktur_Energi_Samarinda $row) {
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
                    'WADMKK'     => $row->wadmkk,
                    'REMARK'     => $row->remark,
                    'SBDATA'     => $row->sbdata,
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