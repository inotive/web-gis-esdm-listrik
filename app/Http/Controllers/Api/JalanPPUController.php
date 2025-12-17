<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_PPU;
use Illuminate\Http\Request;

class JalanPPUController extends Controller
{
    /**
     * PPU data is already in WGS84, no conversion needed
     */
    private function convertGeometryToWGS84($geometry)
    {
        // PPU coordinates are already in WGS84 format (longitude, latitude)
        // No conversion needed, return geometry as is
        return $geometry;
    }

    public function index()
    {
        $data = LN_Jalan_PPU::all();

        $geojsonData = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($data as $item) {
            if ($item->geom) {
                $geometry = json_decode($item->geom, true);

                // Convert UTM coordinates to WGS84 if needed
                $geometry = $this->convertGeometryToWGS84($geometry);

                $feature = [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $item->id,
                        'OID_' => $item->OID_,
                        'Name' => $item->Name,
                        'FolderPath' => $item->FolderPath,
                        'SymbolID' => $item->SymbolID,
                        'Clamped' => $item->Clamped,
                        'Shape_Leng' => $item->Shape_Leng,
                    ],
                    'geometry' => $geometry
                ];

                $geojsonData['features'][] = $feature;
            }
        }

        return response()->json($geojsonData);
    }
}