<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_SUTM_Berau;
use Illuminate\Http\JsonResponse;

class SutmBerauController extends Controller
{
    /**
     * Return LN SUTM Berau sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_SUTM_Berau::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_SUTM_Berau $row) {
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
                    'globalid'    => $row->globalid,
                    'assetgroup'  => $row->assetgroup,
                    'assettype'   => $row->assettype,
                    'tglgambar'   => $row->tglgambar,
                    'usergambar'  => $row->usergambar,
                    'tglupdate'   => $row->tglupdate,
                    'userupdate'  => $row->userupdate,
                    'assetnum'    => $row->assetnum,
                    'classifica'  => $row->classifica,
                    'descriptio'  => $row->descriptio,
                    'installdat'  => $row->installdat,
                    'location'    => $row->location,
                    'manufactur'  => $row->manufactur,
                    'prioritas'   => $row->prioritas,
                    'vendor1'     => $row->vendor1,
                    'bahan_kawa'  => $row->bahan_kawa,
                    'fasa_jarin'  => $row->fasa_jarin,
                    'hantaran_n'  => $row->hantaran_n,
                    'jenis_kabe'  => $row->jenis_kabe,
                    'jenis_kond'  => $row->jenis_kond,
                    'kode_peral'  => $row->kode_peral,
                    'mainline'    => $row->mainline,
                    'panjang_ha'  => $row->panjang_ha,
                    'posisi_fas'  => $row->posisi_fas,
                    'sirkuit'     => $row->sirkuit,
                    'status_kep'  => $row->status_kep,
                    'tegangan_j'  => $row->tegangan_j,
                    'tingkat_is'  => $row->tingkat_is,
                    'ukuran_kaw'  => $row->ukuran_kaw,
                    'status'      => $row->status,
                    'tujdnumber'  => $row->tujdnumber,
                    'serialnum'   => $row->serialnum,
                    'enabled'     => $row->enabled,
                    'globalid_1'  => $row->globalid_1,
                    'created_us'  => $row->created_us,
                    'created_da'  => $row->created_da,
                    'last_edite'  => $row->last_edite,
                    'last_edi_1'  => $row->last_edi_1,
                    'penyulang'   => $row->penyulang,
                    'relationsh'  => $row->relationsh,
                    'lrm'         => $row->lrm,
                    'kode_hanta'  => $row->kode_hanta,
                    'operatingd'  => $row->operatingd,
                    'owner_peme'  => $row->owner_peme,
                    'ownersysid'  => $row->ownersysid,
                    'startmeasu'  => $row->startmeasu,
                    'endmeasure'  => $row->endmeasure,
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
