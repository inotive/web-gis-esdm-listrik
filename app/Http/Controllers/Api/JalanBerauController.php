<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Berau;
use Illuminate\Http\Request;

class JalanBerauController extends Controller
{
    /**
     * Convert DMS (Degrees, Minutes, Seconds) to Decimal Degrees
     */
    private function dmsToDecimal($dms)
    {
        // Handle format like "2° 8' 34.343\" N" or "117° 29' 48.450\" E"
        $pattern = '/(\d+)°\s*(\d+)\'\s*([\d.]+)"/';
        
        if (preg_match($pattern, $dms, $matches)) {
            $degrees = floatval($matches[1]);
            $minutes = floatval($matches[2]);
            $seconds = floatval($matches[3]);
            
            $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
            
            // Check if it's South or West (negative values)
            if (stripos($dms, 'S') !== false || stripos($dms, 'W') !== false) {
                $decimal = -$decimal;
            }
            
            return $decimal;
        }
        
        return floatval($dms);
    }

    /**
     * Extract latitude and longitude from coordinate string
     */
    private function extractLatLon($coordinateString)
    {
        if (!$coordinateString) {
            return [null, null];
        }

        // Split by comma to get lat and lon parts
        $parts = explode(',', $coordinateString);
        
        if (count($parts) < 2) {
            return [null, null];
        }
        
        $lat = trim($parts[0]);
        $lon = trim($parts[1]);
        
        // Convert DMS to decimal
        $latDec = $this->dmsToDecimal($lat);
        $lonDec = $this->dmsToDecimal($lon);
        
        return [$latDec, $lonDec];
    }

    /**
     * Convert geometry coordinates from DMS to Decimal
     */
    private function convertGeometryToDecimal($geometry)
    {
        if (!$geometry || !isset($geometry['type'])) {
            return $geometry;
        }

        $type = $geometry['type'];
        $coordinates = $geometry['coordinates'] ?? [];

        switch ($type) {
            case 'Point':
                // For points, we need to extract the coordinates from the DMS format
                if (is_string($coordinates[0])) {
                    [$lat, $lon] = $this->extractLatLon($coordinates[0]);
                    $geometry['coordinates'] = [$lon, $lat]; // GeoJSON format: [longitude, latitude]
                }
                break;

            case 'LineString':
                $geometry['coordinates'] = array_map(function($coord) {
                    if (is_string($coord[0])) {
                        [$lat, $lon] = $this->extractLatLon(implode(', ', $coord));
                        return [$lon, $lat]; // GeoJSON format: [longitude, latitude]
                    }
                    return $coord;
                }, $coordinates);
                break;

            case 'Polygon':
                $geometry['coordinates'] = array_map(function($ring) {
                    return array_map(function($coord) {
                        if (is_string($coord[0])) {
                            [$lat, $lon] = $this->extractLatLon(implode(', ', $coord));
                            return [$lon, $lat]; // GeoJSON format: [longitude, latitude]
                        }
                        return $coord;
                    }, $ring);
                }, $coordinates);
                break;

            case 'MultiLineString':
                $geometry['coordinates'] = array_map(function($line) {
                    return array_map(function($coord) {
                        if (is_string($coord[0])) {
                            [$lat, $lon] = $this->extractLatLon(implode(', ', $coord));
                            return [$lon, $lat]; // GeoJSON format: [longitude, latitude]
                        }
                        return $coord;
                    }, $line);
                }, $coordinates);
                break;

            case 'MultiPolygon':
                $geometry['coordinates'] = array_map(function($polygon) {
                    return array_map(function($ring) {
                        return array_map(function($coord) {
                            if (is_string($coord[0])) {
                                [$lat, $lon] = $this->extractLatLon(implode(', ', $coord));
                                return [$lon, $lat]; // GeoJSON format: [longitude, latitude]
                            }
                            return $coord;
                        }, $ring);
                    }, $polygon);
                }, $coordinates);
                break;
        }

        return $geometry;
    }

    public function index()
    {
        $data = LN_Jalan_Berau::all();

        $geojsonData = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($data as $item) {
            if ($item->geom) {
                $geometry = json_decode($item->geom, true);

                // Convert DMS coordinates to Decimal Degrees for WGS84
                $geometry = $this->convertGeometryToDecimal($geometry);

                $feature = [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $item->id,
                        'OBJECTID' => $item->OBJECTID,
                        'NO_RUAS' => $item->NO_RUAS,
                        'NAMA_RUAS' => $item->NAMA_RUAS,
                        'KAB_KOTA' => $item->KAB_KOTA,
                        'TTK_PNGKAL' => $item->TTK_PNGKAL,
                        'TTK_AKHIR' => $item->TTK_AKHIR,
                        'PANJANG' => $item->PANJANG,
                        'JKP_2' => $item->JKP_2,
                        'JKP_3' => $item->JKP_3,
                        'JKP_4' => $item->JKP_4,
                        'JLP' => $item->JLP,
                        'Jling_P' => $item->Jling_P,
                        'JAS' => $item->JAS,
                        'JKS' => $item->JKS,
                        'JLS' => $item->JLS,
                        'Jling_S' => $item->Jling_S,
                        'FUNGSI' => $item->FUNGSI,
                        'STATUS' => $item->STATUS,
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