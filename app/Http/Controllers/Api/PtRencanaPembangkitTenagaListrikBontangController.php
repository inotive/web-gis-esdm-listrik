<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang;
use Illuminate\Http\JsonResponse;

class PtRencanaPembangkitTenagaListrikBontangController extends Controller
{
    /**
     * Return PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang::whereNotNull('geometry')->get();

        $features = $rows->map(function (PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang $row) {
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
                    'Id'          => $row->id_external,
                    'Nama'        => $row->nama,
                    'Arahan'      => $row->arahan,
                    'fungsi_eks'  => $row->fungsi_eks,
                    'fungsi_ren'  => $row->fungsi_ren,
                    'penjelasan'  => $row->penjelasan,
                    'Sumber'      => $row->sumber,
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
