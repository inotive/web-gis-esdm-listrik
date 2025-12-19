<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Asset;

class AssetController extends Controller
{
    /**
     * Return assets as GeoJSON FeatureCollection
     */
    public function index(): JsonResponse
    {
        // Eager-load semua relasi yang dibutuhkan oleh frontend
        $assets = Asset::with([
            'unitKerja', 'regency', 'district', 'village', 'province', 'dokumenUtama'
        ])
        ->whereNotNull('geojson')
        ->get();

        $features = $assets->map(function ($asset) {
            $geom = $asset->geojson;

            // Jika geometry tersimpan sebagai string JSON di DB, decode dulu
            if (is_string($geom)) {
                $geom = json_decode($geom, true);
            }

            // Validasi minimal geometry
            if (!$geom || !isset($geom['type'])) {
                return null; // skip jika invalid
            }

            // Tautan sertifikat: pakai file_url (jika file di storage publik) atau link_sertif
            $sertifikatUrl = $asset->dokumenUtama
                ? ($asset->dokumenUtama->file_url ?: $asset->dokumenUtama->link_sertif)
                : null;

            return [
                'type' => 'Feature',
                'properties' => [
                    'id'             => $asset->id,
                    'kode_asset'     => $asset->kode_asset,
                    'nama_asset'     => $asset->nama_asset,
                    'unit_kerja'     => $asset->unitKerja->nama_unit ?? null,
                    'penggunaan_spma'=> $asset->penggunaan_spma,

                    // Wilayah (nama, bukan ID)
                    'kelurahan'      => $asset->village->name  ?? $asset->village->nama  ?? null,
                    'kecamatan'      => $asset->district->name ?? $asset->district->nama ?? null,
                    'kabupaten'      => $asset->regency->name  ?? $asset->regency->nama  ?? null,
                    'provinsi'       => $asset->province->name ?? $asset->province->nama ?? null,

                    // Alternatif key (kompatibel dengan frontend lama)
                    'village'        => $asset->village->name  ?? $asset->village->nama  ?? null,
                    'district'       => $asset->district->name ?? $asset->district->nama ?? null,
                    'regency'        => $asset->regency->name  ?? $asset->regency->nama  ?? null,

                    // Detail lain
                    'alamat'         => $asset->alamat,
                    'nomor_hak'      => $asset->nomor_hak,
                    'jenis_hak'      => $asset->jenis_hak,
                    'latitude'       => $asset->latitude,
                    'longitude'      => $asset->longitude,
                    'luas_m2'        => $asset->luas_m2,

                    // Sertifikat & tanggal
                    'sertifikat_url' => $sertifikatUrl,
                    'tgl_sertif'     => optional($asset->dokumenUtama?->tgl_sertif)?->format('Y-m-d'),
                    'tahun'          => optional($asset->dokumenUtama?->tgl_sertif)?->format('Y'), // <<< tahun dari tgl_sertif
                ],
                'geometry' => $geom,
            ];
        })
        ->filter()     // buang null
        ->values()
        ->toArray();

        return response()->json(
            [
                'type' => 'FeatureCollection',
                'features' => $features,
            ],
            200,
            [
                'Content-Type' => 'application/json',
                'Access-Control-Allow-Origin' => '*', // jika perlu akses lintas domain
            ]
        );
    }
}
