<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PT_Sistem_Infrastruktur_Energi_Balikpapan;
use Illuminate\Http\JsonResponse;

class PtSistemInfrastrukturEnergiBalikpapanController extends Controller
{
    /**
     * Return PT_Sistem_Infrastruktur_Energi_Balikpapan sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Sistem_Infrastruktur_Energi_Balikpapan::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Sistem_Infrastruktur_Energi_Balikpapan $row) {
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
                    'id'       => $row->id,
                    'OBJECTID' => $row->objectid,
                    'NAMOBJ'   => $row->namobj,
                    'ORDE01'   => $row->orde01,
                    'ORDE02'   => $row->orde02,
                    'ORDE03'   => $row->orde03,
                    'ORDE04'   => $row->orde04,
                    'JNSRSR'   => $row->jnsrsr,
                    'STSJRN'   => $row->stsjrn,
                    'WADMPR'   => $row->wadmpr,
                    'WADMKK'   => $row->wadmkk,
                    'REMARK'   => $row->remark,
                    'SBDATA'   => $row->sbdata,
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
