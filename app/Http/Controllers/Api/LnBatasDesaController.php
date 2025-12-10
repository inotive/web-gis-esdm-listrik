<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Batas_Desa;
use Illuminate\Http\JsonResponse;

class LnBatasDesaController extends Controller
{
    /**
     * Return LN_BATAS_DESA sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_Batas_Desa::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_Batas_Desa $row) {
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
                    'FID_AR_BAT'  => $row->fid_ar_bat,
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
