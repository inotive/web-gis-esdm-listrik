<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PtRencanaPembangkitTenagaListrikBontangController extends Controller
{
    /**
     * Convert UTM Zone 50S coordinates to WGS84 (latitude/longitude)
     */
    private function utmToWGS84($easting, $northing, $zone = 50, $isNorthernHemisphere = false)
    {
        $k0 = 0.9996;
        $a = 6378137.0;
        $e = 0.081819191;
        $e1sq = 0.006739497;

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
     * Convert geometry coordinates recursively
     */
    private function convertGeometry($geometry)
    {
        if (!isset($geometry['type'])) {
            return $geometry;
        }

        $type = $geometry['type'];
        $coordinates = $geometry['coordinates'] ?? [];

        switch ($type) {
            case 'Point':
                if (is_array($coordinates) && count($coordinates) >= 2) {
                    $geometry['coordinates'] = $this->utmToWGS84($coordinates[0], $coordinates[1], 50, true);
                }
                break;

            case 'LineString':
                $geometry['coordinates'] = array_map(function($coord) {
                    return is_array($coord) && count($coord) >= 2
                        ? $this->utmToWGS84($coord[0], $coord[1], 50, true)
                        : $coord;
                }, $coordinates);
                break;

            case 'Polygon':
                $geometry['coordinates'] = array_map(function($ring) {
                    return array_map(function($coord) {
                        return is_array($coord) && count($coord) >= 2
                            ? $this->utmToWGS84($coord[0], $coord[1], 50, true)
                            : $coord;
                    }, $ring);
                }, $coordinates);
                break;

            case 'MultiLineString':
                $geometry['coordinates'] = array_map(function($line) {
                    return array_map(function($coord) {
                        return is_array($coord) && count($coord) >= 2
                            ? $this->utmToWGS84($coord[0], $coord[1], 50, true)
                            : $coord;
                    }, $line);
                }, $coordinates);
                break;

            case 'MultiPolygon':
                $geometry['coordinates'] = array_map(function($polygon) {
                    return array_map(function($ring) {
                        return array_map(function($coord) {
                            return is_array($coord) && count($coord) >= 2
                                ? $this->utmToWGS84($coord[0], $coord[1], 50, true)
                                : $coord;
                        }, $ring);
                    }, $polygon);
                }, $coordinates);
                break;
        }

        return $geometry;
    }

    public function index(Request $request)
    {
        $path = public_path('assets/infrastruktur/PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang.json');

        if (!file_exists($path)) {
            return response()->json([
                'error' => 'File PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang.json tidak ditemukan'
            ], 404);
        }

        $data = json_decode(file_get_contents($path), true);

        // Convert coordinates from UTM to WGS84
        if (isset($data['features'])) {
            foreach ($data['features'] as &$feature) {
                if (isset($feature['geometry'])) {
                    $feature['geometry'] = $this->convertGeometry($feature['geometry']);
                }
            }
        }

        return response()->json($data);
    }
}
