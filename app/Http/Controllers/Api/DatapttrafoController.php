<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PtTrafo;

class DatapttrafoController extends Controller
{
    /**
     * Return PT_Trafo_Berau as GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        $trafos = PtTrafo::with([
            'province', 'regency', 'district', 'village'
        ])->get();

        $features = $trafos->map(function ($trafo) {
            $geom = $trafo->geometry;

            // Jika geometry tersimpan sebagai string JSON di DB, decode dulu
            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            // Fallback: jika geometry kosong tapi ada long/lat → bikin Point
            if ((!$geom || !isset($geom['type'])) &&
                $trafo->longitudex !== null &&
                $trafo->latitudey !== null
            ) {
                $geom = [
                    'type'       => 'Point',
                    'coordinates'=> [(float) $trafo->longitudex, (float) $trafo->latitudey],
                ];
            }

            // Kalau tetap tidak ada geometry, skip
            if (!$geom || !isset($geom['type'])) {
                return null;
            }

            return [
                'type'       => 'Feature',
                'properties' => [
                    'id'          => $trafo->id,
                    'globalid'    => $trafo->globalid,
                    'assetnum'    => $trafo->assetnum,
                    'nama_trafo'  => $trafo->descriptio,
                    'classifica'  => $trafo->classifica,
                    'status'      => $trafo->status,
                    'kapasitas'   => $trafo->kapasitas,
                    'fasa_trafo'  => $trafo->fasa_trafo,
                    'jenis_traf'  => $trafo->jenis_traf,
                    'tegangan_t'  => $trafo->tegangan_t,
                    'peruntukan'  => $trafo->peruntukan,
                    'penyulang'   => $trafo->penyulang,
                    'location'    => $trafo->location,
                    'no_trafo'    => $trafo->no_trafo,
                    'status_kep'  => $trafo->status_kep,
                    'tap_change'  => $trafo->tap_change,
                    'th_buat'     => $trafo->th_buat,

                    // Wilayah (nama, bukan ID)
                    'kelurahan'   => $trafo->village->name  ?? $trafo->village->nama  ?? null,
                    'kecamatan'   => $trafo->district->name ?? $trafo->district->nama ?? null,
                    'kabupaten'   => $trafo->regency->name  ?? $trafo->regency->nama  ?? null,
                    'provinsi'    => $trafo->province->name ?? $trafo->province->nama ?? null,

                    // Alternatif key (kalau mau dipakai di frontend)
                    'village'     => $trafo->village->name  ?? $trafo->village->nama  ?? null,
                    'district'    => $trafo->district->name ?? $trafo->district->nama ?? null,
                    'regency'     => $trafo->regency->name  ?? $trafo->regency->nama  ?? null,

                    // Alamat tambahan kalau ada
                    'streetaddr'  => $trafo->streetaddr,
                    'city'        => $trafo->city,

                    // Koordinat WGS84 kalau nanti kamu isi
                    'latitude'    => $trafo->latitudey,
                    'longitude'   => $trafo->longitudex,
                ],
                'geometry'   => $geom,
            ];
        })
        ->filter() // buang null
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
