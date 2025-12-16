<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Kutim;
use Illuminate\Http\Request;

class JalanKutimController extends Controller
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
        $data = LN_Jalan_Kutim::all();

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
                        'Kl_Dat_Das' => $item->Kl_Dat_Das,
                        'No_Ruas' => $item->No_Ruas,
                        'Nm_Ruas' => $item->Nm_Ruas,
                        'Fungsi' => $item->Fungsi,
                        'Kecamatan' => $item->Kecamatan,
                        'Desa_Kel' => $item->Desa_Kel,
                        'Tk_Ruas_Aw' => $item->Tk_Ruas_Aw,
                        'Tk_Ruas_Ak' => $item->Tk_Ruas_Ak,
                        'Panjang' => $item->Panjang,
                        'Koord_X_Aw' => $item->Koord_X_Aw,
                        'Koord_Y_Aw' => $item->Koord_Y_Aw,
                        'Koord_X_Ak' => $item->Koord_X_Ak,
                        'Koord_Y_Ak' => $item->Koord_Y_Ak,
                    ],
                    'geometry' => $geometry
                ];

                $geojsonData['features'][] = $feature;
            }
        }

        return response()->json($geojsonData);
    }
}