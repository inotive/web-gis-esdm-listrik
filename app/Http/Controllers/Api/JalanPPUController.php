<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_PPU;
use Illuminate\Http\Request;

class JalanPPUController extends Controller
{
    /**
     * Convert UTM coordinates to WGS84 (latitude/longitude)
     * Assuming PPU is in UTM Zone 50S (Southern Hemisphere) like Balikpapan
     */
    private function utmToLatLng($easting, $northing, $zone = 50, $isNorthernHemisphere = false)
    {
        // UTM parameters
        $k0 = 0.9996;
        $a = 6378137.0; // WGS84 equatorial radius
        $e = 0.081819191; // WGS84 eccentricity
        $e1sq = 0.006739497;

        // Calculate latitude zone parameters
        $arc = $northing / $k0;

        if (!$isNorthernHemisphere) {
            $arc = (10000000 - $northing) / $k0;
        }

        $mu = $arc / ($a * (1 - pow($e, 2) / 4.0 - 3 * pow($e, 4) / 64.0 - 5 * pow($e, 6) / 256.0));

        $ei = (1 - pow(1 - $e * $e, 0.5)) / (1 + pow(1 - $e * $e, 0.5));

        $ca = 3 * $ei / 2 - 27 * pow($ei, 3) / 32;
        $cb = 21 * pow($ei, 2) / 16 - 55 * pow($ei, 4) / 32;
        $cc = 151 * pow($ei, 3) / 96;
        $cd = 1097 * pow($ei, 4) / 512;

        $phi1 = $mu + $ca * sin(2 * $mu) + $cb * sin(4 * $mu) + $cc * sin(6 * $mu) + $cd * sin(8 * $mu);

        $n0 = $a / pow(1 - pow($e * sin($phi1), 2), 0.5);
        $r0 = $a * (1 - $e * $e) / pow(1 - pow($e * sin($phi1), 2), 1.5);
        $fact1 = $n0 * tan($phi1) / $r0;

        $a1 = 500000 - $easting;
        $dd0 = $a1 / ($n0 * $k0);
        $fact2 = $dd0 * $dd0 / 2;

        $t0 = pow(tan($phi1), 2);
        $q0 = $e1sq * pow(cos($phi1), 2);
        $fact3 = (5 + 3 * $t0 + 10 * $q0 - 4 * $q0 * $q0 - 9 * $e1sq) * pow($dd0, 4) / 24;
        $fact4 = (61 + 90 * $t0 + 298 * $q0 + 45 * $t0 * $t0 - 252 * $e1sq - 3 * $q0 * $q0) * pow($dd0, 6) / 720;

        $lof1 = $a1 / ($n0 * $k0);
        $lof2 = (1 + 2 * $t0 + $q0) * pow($dd0, 3) / 6.0;
        $lof3 = (5 - 2 * $q0 + 28 * $t0 - 3 * pow($q0, 2) + 8 * $e1sq + 24 * pow($t0, 2)) * pow($dd0, 5) / 120;
        $delta_long = ($lof1 - $lof2 + $lof3) / cos($phi1);

        $zoneCM = 6 * $zone - 183;

        $latitude = 180 * ($phi1 - $fact1 * ($fact2 + $fact3 + $fact4)) / M_PI;
        $longitude = $zoneCM - $delta_long * 180 / M_PI;

        if (!$isNorthernHemisphere) {
            $latitude = -$latitude;
        }

        return [$longitude, $latitude];
    }

    /**
     * Convert geometry coordinates from UTM to WGS84
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
                $geometry['coordinates'] = $this->utmToLatLng($coordinates[0], $coordinates[1]);
                break;

            case 'LineString':
                $geometry['coordinates'] = array_map(function($coord) {
                    return $this->utmToLatLng($coord[0], $coord[1]);
                }, $coordinates);
                break;

            case 'Polygon':
                $geometry['coordinates'] = array_map(function($ring) {
                    return array_map(function($coord) {
                        return $this->utmToLatLng($coord[0], $coord[1]);
                    }, $ring);
                }, $coordinates);
                break;

            case 'MultiLineString':
                $geometry['coordinates'] = array_map(function($line) {
                    return array_map(function($coord) {
                        return $this->utmToLatLng($coord[0], $coord[1]);
                    }, $line);
                }, $coordinates);
                break;

            case 'MultiPolygon':
                $geometry['coordinates'] = array_map(function($polygon) {
                    return array_map(function($ring) {
                        return array_map(function($coord) {
                            return $this->utmToLatLng($coord[0], $coord[1]);
                        }, $ring);
                    }, $polygon);
                }, $coordinates);
                break;
        }

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