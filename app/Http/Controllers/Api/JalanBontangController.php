<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Bontang;
use Illuminate\Http\Request;

class JalanBontangController extends Controller
{
    /**
     * Convert TM-3° coordinates to WGS84 (latitude/longitude)
     * Bontang uses TM-3° with CM 117°E, FE: 500000m, FN: 0m
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
     * Convert geometry coordinates from TM-3° to WGS84
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

    public function index(Request $request)
    {
        $query = LN_Jalan_Bontang::query();

        // Apply filters based on request parameters
        if ($request->has('fungsi') && !empty($request->fungsi)) {
            $query->where('Fungsi', $request->fungsi);
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('Status', $request->status);
        }

        if ($request->has('kecamatan') && !empty($request->kecamatan)) {
            $query->where('Kecamatan', $request->kecamatan);
        }

        if ($request->has('panjang_min')) {
            $query->where('Panjang', '>=', $request->panjang_min);
        }

        if ($request->has('panjang_max')) {
            $query->where('Panjang', '<=', $request->panjang_max);
        }

        if ($request->has('tahun_data')) {
            $query->where('Thn_Data', $request->tahun_data);
        }

        $data = $query->get();

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
                        'Nm_Ruas' => $item->Nm_Ruas,
                        'Thn_Data' => $item->Thn_Data,
                        'Status' => $item->Status,
                        'Fungsi' => $item->Fungsi,
                        'Mendukung' => $item->Mendukung,
                        'Ura_Dukung' => $item->Ura_Dukung,
                        'Kd_Bd_PU' => $item->Kd_Bd_PU,
                        'Kd_Jns_Inf' => $item->Kd_Jns_Inf,
                        'Kd_Inf' => $item->Kd_Inf,
                        'Propinsi' => $item->Propinsi,
                        'Kab_Kota' => $item->Kab_Kota,
                        'Kecamatan' => $item->Kecamatan,
                        'Desa_Kel' => $item->Desa_Kel,
                        'Tk_Ruas_Aw' => $item->Tk_Ruas_Aw,
                        'Tk_Ruas_Ak' => $item->Tk_Ruas_Ak,
                        'Kd_Patok' => $item->Kd_Patok,
                        'Km_Awal' => $item->Km_Awal,
                        'Km_Akhir' => $item->Km_Akhir,
                        'Nm_Lintas' => $item->Nm_Lintas,
                        'Kon_Baik' => $item->Kon_Baik,
                        'Kon_Sdg' => $item->Kon_Sdg,
                        'Kon_Rgn' => $item->Kon_Rgn,
                        'Kon_Rusak' => $item->Kon_Rusak,
                        'Kon_Mntp' => $item->Kon_Mntp,
                        'Kon_T_Mntp' => $item->Kon_T_Mntp,
                        'Panjang' => $item->Panjang,
                        'Lbr_Keras' => $item->Lbr_Keras,
                        'LHRT' => $item->LHRT,
                        'VCR' => $item->VCR,
                        'Tipe_Jln' => $item->Tipe_Jln,
                        'MST' => $item->MST,
                        'Tipe_Keras' => $item->Tipe_Keras,
                        'Tanah_Kri' => $item->Tanah_Kri,
                        'Macadam' => $item->Macadam,
                        'Aspal' => $item->Aspal,
                        'Rigid' => $item->Rigid,
                        'Thn_Pen_Ak' => $item->Thn_Pen_Ak,
                        'Jns_Pen' => $item->Jns_Pen,
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