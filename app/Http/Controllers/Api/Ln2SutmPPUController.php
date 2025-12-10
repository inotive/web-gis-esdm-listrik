<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN2_SUTM_PPU;
use Illuminate\Http\JsonResponse;

class Ln2SutmPPUController extends Controller
{
    /**
     * Return LN2 SUTM PPU sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN2_SUTM_PPU::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN2_SUTM_PPU $row) {
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
                    'FID_Jalan'   => $row->fid_jalan,
                    'NAMOBJ'      => $row->namobj,
                    'FCODE'       => $row->fcode,
                    'REMARK'      => $row->remark,
                    'METADATA'    => $row->metadata,
                    'SRS_ID'      => $row->srs_id,
                    'ARHRJL'      => $row->arhrjl,
                    'AUTRJL'      => $row->autrjl,
                    'FGSRJL'      => $row->fgsrjl,
                    'JARRJL'      => $row->jarrjl,
                    'JPARJL'      => $row->jparjl,
                    'KLLRJL'      => $row->kllrjl,
                    'KONRJL'      => $row->konrjl,
                    'KPMSTR'      => $row->kpmstr,
                    'LKONOF'      => $row->lkonof,
                    'LKSBSP'      => $row->lksbsp,
                    'LKSRTA'      => $row->lksrta,
                    'LLHRRT'      => $row->llhrrt,
                    'LOCRJL'      => $row->locrjl,
                    'LBRBHJ'      => $row->lbrbhj,
                    'LBRJLN'      => $row->lbrjln,
                    'MATRJL'      => $row->matrjl,
                    'MEDRJL'      => $row->medrjl,
                    'SPCRJL'      => $row->spcrjl,
                    'STARJL'      => $row->starjl,
                    'TOLRJL'      => $row->tolrjl,
                    'UTKRJL'      => $row->utkrjl,
                    'VLCPRT'      => $row->vlcprt,
                    'WLYRJL'      => $row->wlyrjl,
                    'TGL_SK'      => $row->tgl_sk,
                    'JLNLYG'      => $row->jlnlyg,
                    'KLSRJL'      => $row->klsrjl,
                    'JalanListr'  => $row->jalanlistr,
                    'FID_BatasP'  => $row->fid_batasp,
                    'WADMKC'      => $row->wadmkc,
                    'WADMKD'      => $row->wadmkd,
                    'WADMKK'      => $row->wadmkk,
                    'WADMPR'      => $row->wadmpr,
                    'SHAPE_Leng'  => $row->shape_leng,
                    'Panjang'     => $row->panjang,
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
