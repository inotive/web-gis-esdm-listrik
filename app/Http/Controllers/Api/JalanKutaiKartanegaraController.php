<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_KutaiKartanegara;
use Illuminate\Http\Request;

class JalanKutaiKartanegaraController extends Controller
{
    /**
     * Convert geometry coordinates (if needed) to WGS84
     */
    private function convertGeometryToWGS84($geometry)
    {
        if (!$geometry || !isset($geometry['type'])) {
            return $geometry;
        }

        $type = $geometry['type'];
        $coordinates = $geometry['coordinates'] ?? [];

        switch ($type) {
            case 'Point':
                break;

            case 'LineString':
                break;

            case 'Polygon':
                break;

            case 'MultiLineString':
                break;

            case 'MultiPolygon':
                break;
        }

        return $geometry;
    }

    public function index()
    {
        $data = LN_Jalan_KutaiKartanegara::all();

        $geojsonData = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($data as $item) {
            if ($item->geom) {
                $geometry = json_decode($item->geom, true);

                // Convert coordinates to WGS84 if needed
                $geometry = $this->convertGeometryToWGS84($geometry);

                $feature = [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $item->id,
                        'NO_LAMA' => $item->NO_LAMA,
                        'NO_BARU' => $item->NO_BARU,
                        'NAMA_LAMA' => $item->NAMA_LAMA,
                        'NAMA_BARU' => $item->NAMA_BARU,
                        'P_Km' => $item->P_Km,
                        'KECAMATAN' => $item->KECAMATAN,
                        'URUT' => $item->URUT,
                        'PANGKAL' => $item->PANGKAL,
                        'UJUNG' => $item->UJUNG,
                        'KOOR_PANGK' => $item->KOOR_PANGK,
                        'KOOR_UJUNG' => $item->KOOR_UJUNG,
                        'LEBAR_M' => $item->LEBAR_M,
                        'FUNGSI' => $item->FUNGSI,
                        'HISTORY' => $item->HISTORY,
                        'Panjang' => $item->Panjang,
                    ],
                    'geometry' => $geometry
                ];

                $geojsonData['features'][] = $feature;
            }
        }

        return response()->json($geojsonData);
    }
}