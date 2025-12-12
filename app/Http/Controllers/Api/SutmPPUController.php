<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_SUTM_PPU;
use Illuminate\Http\JsonResponse;

class SutmPPUController extends Controller
{
    /**
     * Return LN SUTM PPU sebagai GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $rows = LN_SUTM_PPU::whereNotNull('geometry')->get();

        $features = $rows->map(function (LN_SUTM_PPU $row) {
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
                    'OBJECTID'     => $row->objectid,
                    'No_Ruas'      => $row->no_ruas,
                    'Kode_Kelas'   => $row->kode_kelas,
                    'Nama_Jalan'   => $row->nama_jalan,
                    'Nama_Pangk'   => $row->nama_pangk,
                    'Nama_Ujung'   => $row->nama_ujung,
                    'Titk_Penge'   => $row->titk_penge,
                    'Titik_Peng'   => $row->titik_peng,
                    'Panjang'      => $row->panjang,
                    'Lebar'        => $row->lebar,
                    'Aspal'        => $row->aspal,
                    'Rijit'        => $row->rijit,
                    'Perkerasan'   => $row->perkerasan,
                    'Tanah'        => $row->tanah,
                    'Kondisi'      => $row->kondisi,
                    'Th_Pekerja'   => $row->th_pekerja,
                    'Ket'          => $row->ket,
                    'SHAPE_Leng'   => $row->shape_leng,
                    'ID'           => $row->legacy_id,
                    'FOTO'         => $row->foto,
                    'Status_Jal'   => $row->status_jal,
                    'Fungsi_Jal'   => $row->fungsi_jal,
                    'Sistem_Jal'   => $row->sistem_jal,
                    'Nama'         => $row->nama,
                    'Dana'         => $row->dana,
                    'Nama_Jal_1'   => $row->nama_jal_1,
                    'KODE_RTRW'    => $row->kode_rtrw,
                    'statusRTRW'   => $row->statusrtrw,
                    'Shape_Le_1'   => $row->shape_le_1,
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
