<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\PtGardu;

class DataptgarduController extends Controller
{
    /**
     * Return PT Gardu as GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        // eager load relasi wilayah
        $gardus = PtGardu::with(['province', 'regency', 'district', 'village'])
            ->whereNotNull('geometry')
            ->get();

        $features = $gardus->map(function ($gardu) {
            $geom = $gardu->geometry;

            // geometry di DB bisa berupa string JSON → decode
            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            // Kalau geometry kosong, coba fallback ke point dari lat/long
            if (!$geom || !isset($geom['type'])) {
                if (!is_null($gardu->longitudex) && !is_null($gardu->latitudey)) {
                    $geom = [
                        'type'        => 'Point',
                        'coordinates' => [(float) $gardu->longitudex, (float) $gardu->latitudey],
                    ];
                } else {
                    // Skip jika benar-benar tidak ada geometry
                    return null;
                }
            }

            return [
                'type'       => 'Feature',
                'properties' => [
                    'id'          => $gardu->id,
                    'assetnum'    => $gardu->assetnum,
                    'classifica'  => $gardu->classifica,
                    'descriptio'  => $gardu->descriptio,
                    'location'    => $gardu->location,
                    'type_gardu'  => $gardu->type_gardu,
                    'status'      => $gardu->status,
                    'prioritas'   => $gardu->prioritas,
                    'penyulang'   => $gardu->penyulang,
                    'owner_peme'  => $gardu->owner_peme,
                    'no_slo'      => $gardu->no_slo,
                    'sloactived'  => optional($gardu->sloactived)?->format('Y-m-d'),
                    'streetaddr'  => $gardu->streetaddr,
                    'city'        => $gardu->city,

                    // Wilayah (nama, bukan ID)
                    'village'     => $gardu->village->name  ?? $gardu->village->nama  ?? null,
                    'district'    => $gardu->district->name ?? $gardu->district->nama ?? null,
                    'regency'     => $gardu->regency->name  ?? $gardu->regency->nama  ?? null,
                    'province'    => $gardu->province->name ?? $gardu->province->nama ?? null,

                    // Koordinat
                    'latitude'    => $gardu->latitudey,
                    'longitude'   => $gardu->longitudex,
                ],
                'geometry'   => $geom,
            ];
        })
        ->filter()   // buang null
        ->values()
        ->toArray();

        return response()->json(
            [
                'type'     => 'FeatureCollection',
                'features' => $features,
            ],
            200,
            [
                'Content-Type'              => 'application/json',
                'Access-Control-Allow-Origin' => '*',
            ]
        );
    }
}
