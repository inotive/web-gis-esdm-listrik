<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PT_Trafo_Berau;

class PtTrafoBerauController extends Controller
{
    /**
     * Return Trafo Berau sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Trafo_Berau::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Trafo_Berau $row) {
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
                    'id'             => $row->id,
                    'OBJECTID'       => $row->objectid,
                    'globalid'       => $row->globalid,
                    'assetgroup'     => $row->assetgroup,
                    'assettype'      => $row->assettype,
                    'tglgambar'      => $row->tglgambar,
                    'usergambar'     => $row->usergambar,
                    'tglupdate'      => $row->tglupdate,
                    'userupdate'     => $row->userupdate,
                    'assetnum'       => $row->assetnum,
                    'classifica'     => $row->classifica,
                    'descriptio'     => $row->descriptio,
                    'installdat'     => $row->installdat,
                    'location'       => $row->location,
                    'manufactur'     => $row->manufactur,
                    'serialnum'      => $row->serialnum,
                    'status'         => $row->status,
                    'tujdnumber'     => $row->tujdnumber,
                    'vendor1'        => $row->vendor1,
                    'fasa_trafo'     => $row->fasa_trafo,
                    'jenis_traf'     => $row->jenis_traf,
                    'kapasitas'      => $row->kapasitas,
                    'kode_peral'     => $row->kode_peral,
                    'no_trafo'       => $row->no_trafo,
                    'owner_peme'     => $row->owner_peme,
                    'peruntukan'     => $row->peruntukan,
                    'posisi_fas'     => $row->posisi_fas,
                    'rujukan_ko'     => $row->rujukan_ko,
                    'status_kep'      => $row->status_kep,
                    'tap_change'     => $row->tap_change,
                    'tegangan_t'     => $row->tegangan_t,
                    'th_buat'        => $row->th_buat,
                    'prioritas'      => $row->prioritas,
                    'enabled'        => $row->enabled,
                    'globalid_1'     => $row->globalid_1,
                    'created_us'     => $row->created_us,
                    'created_da'     => $row->created_da,
                    'last_edite'     => $row->last_edite,
                    'last_edi_1'     => $row->last_edi_1,
                    'relationsh'     => $row->relationsh,
                    'kode_hanta'     => $row->kode_hanta,
                    'operatingd'     => $row->operatingd,
                    'ownersysid'     => $row->ownersysid,
                    'sourcestar'     => $row->sourcestar,
                    'sourceendm'     => $row->sourceendm,
                    'no_slo'         => $row->no_slo,
                    'sloactived'     => $row->sloactived,
                    'penyulang'      => $row->penyulang,
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