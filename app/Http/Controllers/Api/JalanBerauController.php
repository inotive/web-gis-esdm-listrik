<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Berau;
use Illuminate\Http\Request;

class JalanBerauController extends Controller
{
    /**
     * Convert TM-3° coordinates to WGS84 (latitude/longitude)
     * Berau uses TM-3° with CM 117°E, False Easting: 200000m, False Northing: 1500000m
     */
    private function tmToLatLng($easting, $northing)
    {
        // TM-3° parameters for East Kalimantan
        $k0 = 0.9999; // Scale factor for TM-3°
        $a = 6378137.0; // WGS84 equatorial radius
        $e = 0.081819191; // WGS84 eccentricity
        $e2 = $e * $e;
        $e4 = $e2 * $e2;
        $e6 = $e4 * $e2;

        $centralMeridian = 117.0; // CM for this zone
        $falseEasting = 500000.0; // False easting
        $falseNorthing = 0.0; // No false northing

        // Remove false easting and northing
        $x = $easting - $falseEasting;
        $y = $northing - $falseNorthing;

        // Calculate footpoint latitude
        $M = $y / $k0;
        $mu = $M / ($a * (1 - $e2/4 - 3*$e4/64 - 5*$e6/256));

        $e1 = (1 - sqrt(1 - $e2)) / (1 + sqrt(1 - $e2));
        $e12 = $e1 * $e1;
        $e13 = $e12 * $e1;
        $e14 = $e13 * $e1;

        $phi1 = $mu + (3*$e1/2 - 27*$e13/32) * sin(2*$mu)
                    + (21*$e12/16 - 55*$e14/32) * sin(4*$mu)
                    + (151*$e13/96) * sin(6*$mu)
                    + (1097*$e14/512) * sin(8*$mu);

        $C1 = $e2 * pow(cos($phi1), 2) / (1 - $e2);
        $T1 = pow(tan($phi1), 2);
        $N1 = $a / sqrt(1 - $e2 * pow(sin($phi1), 2));
        $R1 = $a * (1 - $e2) / pow(1 - $e2 * pow(sin($phi1), 2), 1.5);
        $D = $x / ($N1 * $k0);

        // Calculate latitude
        $lat = $phi1 - ($N1 * tan($phi1) / $R1) * (
            $D*$D/2
            - (5 + 3*$T1 + 10*$C1 - 4*$C1*$C1 - 9*$e2) * pow($D, 4) / 24
            + (61 + 90*$T1 + 298*$C1 + 45*$T1*$T1 - 252*$e2 - 3*$C1*$C1) * pow($D, 6) / 720
        );

        // Calculate longitude
        $lon = ($D - (1 + 2*$T1 + $C1) * pow($D, 3) / 6
            + (5 - 2*$C1 + 28*$T1 - 3*$C1*$C1 + 8*$e2 + 24*$T1*$T1) * pow($D, 5) / 120)
            / cos($phi1);

        $latitude = $lat * 180 / M_PI;
        $longitude = $centralMeridian + $lon * 180 / M_PI;

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
                $geometry['coordinates'] = $this->tmToLatLng($coordinates[0], $coordinates[1]);
                break;

            case 'LineString':
                $geometry['coordinates'] = array_map(function($coord) {
                    return $this->tmToLatLng($coord[0], $coord[1]);
                }, $coordinates);
                break;

            case 'Polygon':
                $geometry['coordinates'] = array_map(function($ring) {
                    return array_map(function($coord) {
                        return $this->tmToLatLng($coord[0], $coord[1]);
                    }, $ring);
                }, $coordinates);
                break;

            case 'MultiLineString':
                $geometry['coordinates'] = array_map(function($line) {
                    return array_map(function($coord) {
                        return $this->tmToLatLng($coord[0], $coord[1]);
                    }, $line);
                }, $coordinates);
                break;

            case 'MultiPolygon':
                $geometry['coordinates'] = array_map(function($polygon) {
                    return array_map(function($ring) {
                        return array_map(function($coord) {
                            return $this->tmToLatLng($coord[0], $coord[1]);
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

                // Convert UTM coordinates to WGS84
                $geometry = $this->convertGeometryToWGS84($geometry);

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