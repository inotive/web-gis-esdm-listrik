<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PT_Gardu_Berau;
use Illuminate\Http\JsonResponse;

class PtGarduBerauController extends Controller
{
    /**
     * Return PT Gardu Berau sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Gardu_Berau::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Gardu_Berau $row) {
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
                    'id'           => $row->id,
                    'globalid'     => $row->globalid,
                    'assetgroup'   => $row->assetgroup,
                    'assettype'    => $row->assettype,
                    'tglgambar'    => $row->tglgambar,
                    'usergambar'   => $row->usergambar,
                    'tglupdate'    => $row->tglupdate,
                    'userupdate'   => $row->userupdate,
                    'assetnum'     => $row->assetnum,
                    'classifica'   => $row->classifica,
                    'descriptio'   => $row->descriptio,
                    'installdat'   => $row->installdat,
                    'location'     => $row->location,
                    'prioritas'    => $row->prioritas,
                    'vendor1'      => $row->vendor1,
                    'jenis_pela'   => $row->jenis_pela,
                    'kode_peral'   => $row->kode_peral,
                    'status_kep'   => $row->status_kep,
                    'status_rc'    => $row->status_rc,
                    'type_gardu'   => $row->type_gardu,
                    'status'       => $row->status,
                    'tujdnumber'   => $row->tujdnumber,
                    'globalid_1'   => $row->globalid_1,
                    'created_us'   => $row->created_us,
                    'created_da'   => $row->created_da,
                    'last_edite'   => $row->last_edite,
                    'last_edi_1'   => $row->last_edi_1,
                    'parent_loc'   => $row->parent_loc,
                    'operatingd'   => $row->operatingd,
                    'formatteda'   => $row->formatteda,
                    'streetaddr'   => $row->streetaddr,
                    'city'         => $row->city,
                    'penyulang'    => $row->penyulang,
                    'longitudex'   => $row->longitudex,
                    'latitudey'    => $row->latitudey,
                    'Shape_Leng'   => $row->shape_leng,
                    'Shape_Area'   => $row->shape_area,
                    'ORIG_FID'     => $row->orig_fid,
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
