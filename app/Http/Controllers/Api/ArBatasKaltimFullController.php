<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AR_Batas_Kaltim_Full_KK_KC_KD;
use Illuminate\Http\JsonResponse;

class ArBatasKaltimFullController extends Controller
{
    /**
     * Return AR_BATAS_KALTIM_FULL_KK_KC_KD sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = AR_Batas_Kaltim_Full_KK_KC_KD::whereNotNull('geometry')->get();

        $features = $rows->map(function (AR_Batas_Kaltim_Full_KK_KC_KD $row) {
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
                    'WADMPR'      => $row->wadmpr,
                    'WADMKK'      => $row->wadmkk,
                    'WADMKC'      => $row->wadmkc,
                    'WADMKD'      => $row->wadmkd,
                    'NAMOBJ'      => $row->namobj,
                    'TIPADM'      => $row->tipadm,
                    'REMARK'      => $row->remark,
                    'UUPP'        => $row->uupp,
                    'LUASWH'      => $row->luaswh,
                    'Luas'        => $row->luas,
                    'Shape_Leng'  => $row->shape_leng,
                    'Shape_Area'  => $row->shape_area,
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
