<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PtHasilLokasiSurveiEsdm;
use Illuminate\Http\Request;

class HasilLokasiSurveiEsdmController extends Controller
{
    /**
     * Convert UTM coordinates to WGS84 (latitude/longitude)
     * Default UTM zone for Indonesia: typically Zone 48-54S
     * Using Zone 50S as example, adjust based on your data's location
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
                if (is_array($coordinates) && count($coordinates) == 2) {
                    $geometry['coordinates'] = $this->utmToLatLng($coordinates[0], $coordinates[1]);
                }
                break;

            case 'LineString':
                $geometry['coordinates'] = array_map(function($coord) {
                    if (is_array($coord) && count($coord) == 2) {
                        return $this->utmToLatLng($coord[0], $coord[1]);
                    }
                    return $coord;
                }, $coordinates);
                break;

            case 'Polygon':
                $geometry['coordinates'] = array_map(function($ring) {
                    return array_map(function($coord) {
                        if (is_array($coord) && count($coord) == 2) {
                            return $this->utmToLatLng($coord[0], $coord[1]);
                        }
                        return $coord;
                    }, $ring);
                }, $coordinates);
                break;

            case 'MultiPoint':
                $geometry['coordinates'] = array_map(function($coord) {
                    if (is_array($coord) && count($coord) == 2) {
                        return $this->utmToLatLng($coord[0], $coord[1]);
                    }
                    return $coord;
                }, $coordinates);
                break;

            case 'MultiLineString':
                $geometry['coordinates'] = array_map(function($line) {
                    return array_map(function($coord) {
                        if (is_array($coord) && count($coord) == 2) {
                            return $this->utmToLatLng($coord[0], $coord[1]);
                        }
                        return $coord;
                    }, $line);
                }, $coordinates);
                break;

            case 'MultiPolygon':
                $geometry['coordinates'] = array_map(function($polygon) {
                    return array_map(function($ring) {
                        return array_map(function($coord) {
                            if (is_array($coord) && count($coord) == 2) {
                                return $this->utmToLatLng($coord[0], $coord[1]);
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
        $data = PtHasilLokasiSurveiEsdm::all();

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
                        'Lokasi' => $item->Lokasi,
                        'NAMOBJ' => $item->NAMOBJ,
                        'LUASWH' => $item->LUASWH,
                        'TIPADM' => $item->TIPADM,
                        'WADMKC' => $item->WADMKC,
                        'WADMKD' => $item->WADMKD,
                        'WADMKK' => $item->WADMKK,
                        'WADMPR' => $item->WADMPR,
                        'Status' => $item->Status,
                        'Kode_Kota' => $item->Kode_Kota,
                        'Kode_L' => $item->Kode_L,
                        'Lokasi_Ke' => $item->Lokasi_Ke,
                        'Kodifikasi' => $item->Kodifikasi,
                        'DUSUN' => $item->DUSUN,
                        'JUMLAH_RT' => $item->JUMLAH_RT,
                        'KET_RT' => $item->KET_RT,
                        'J_Pnddk' => $item->J_Pnddk,
                        'J_KK' => $item->J_KK,
                        'J_BRumah' => $item->J_BRumah,
                        'J_BFasum' => $item->J_BFasum,
                        'Ket_BFasum' => $item->Ket_BFasum,
                        'S_L_Kom' => $item->S_L_Kom,
                        'N_S_L' => $item->N_S_L,
                        'K_S_L' => $item->K_S_L,
                        'S_P_L' => $item->S_P_L,
                        'W_NYALA' => $item->W_NYALA,
                        'L_NYALA' => $item->L_NYALA,
                        'T_SL' => $item->T_SL,
                        'Knd_S_L' => $item->Knd_S_L,
                        'Koor_X' => $item->Koor_X,
                        'Koor_Y' => $item->Koor_Y,
                        'PR_Prov' => $item->PR_Prov,
                        'K_Hutan' => $item->K_Hutan,
                        'Izin_Lain' => $item->Izin_Lain,
                        'Potensi' => $item->Potensi,
                        'R_JUTAMA' => $item->R_JUTAMA,
                        'R_JLISTRIK' => $item->R_JLISTRIK,
                        'K_Jalan' => $item->K_Jalan,
                        'L_Jalan' => $item->L_Jalan,
                        'P_Jalan' => $item->P_Jalan,
                        'PENYULANG' => $item->PENYULANG,
                        'R_S_L' => $item->R_S_L,
                        'KENDALA' => $item->KENDALA,
                        'I_IUPT' => $item->I_IUPT,
                        'I_PPBH' => $item->I_PPBH,
                        'I_IUPK' => $item->I_IUPK,
                        'J_Gardu' => $item->J_Gardu,
                        'B_Gardu' => $item->B_Gardu,
                        'S_L_P' => $item->S_L_P,
                        'K_RPLTS' => $item->K_RPLTS,
                        'Panjang' => $item->Panjang,
                        'Tiang' => $item->Tiang,
                        'Biaya' => $item->Biaya,
                        'Skor_A' => $item->Skor_A,
                        'Skor_J' => $item->Skor_J,
                        'K_PR' => $item->K_PR,
                        'K_Izin' => $item->K_Izin,
                        'K_Hutan_1' => $item->K_Hutan_1,
                        'S_Arah' => $item->S_Arah,
                        'S_Potensi' => $item->S_Potensi,
                        'S_J_P' => $item->S_J_P,
                        'T_S' => $item->T_S,
                        'Cek' => $item->Cek,
                        'Priorita_1' => $item->Priorita_1,
                        'B_PLTS' => $item->B_PLTS,
                    ],
                    'geometry' => $geometry
                ];

                $geojsonData['features'][] = $feature;
            }
        }

        return response()->json($geojsonData);
    }
}