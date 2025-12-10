<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\LN_Sistem_Jaringan_Energi_Kukar;

class SistemJaringanEnergiKukarController extends Controller
{
    /**
     * Return Sistem Jaringan Energi Kukar sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Sistem_Jaringan_Energi_Kukar::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Sistem_Jaringan_Energi_Kukar $row) {
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
