<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PtHasilLokasiSurveiEsdm;
use Illuminate\Http\Request;

class HasilLokasiSurveiEsdmController extends Controller
{
    /**
     * Convert Web Mercator (EPSG:3857) coordinates to WGS84 (EPSG:4326)
     */
    private function webMercatorToWGS84($x, $y)
    {
        $originShift = 2 * M_PI * 6378137 / 2.0; // 20037508.342789244

        $longitude = ($x / $originShift) * 180.0;
        $latitude = ($y / $originShift) * 180.0;
        $latitude = 180 / M_PI * (2 * atan(exp($latitude * M_PI / 180.0)) - M_PI / 2.0);

        return [$longitude, $latitude];
    }

    /**
     * Convert geometry coordinates from Web Mercator to WGS84
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
                    $geometry['coordinates'] = $this->webMercatorToWGS84($coordinates[0], $coordinates[1]);
                }
                break;

            case 'LineString':
                $geometry['coordinates'] = array_map(function($coord) {
                    if (is_array($coord) && count($coord) == 2) {
                        return $this->webMercatorToWGS84($coord[0], $coord[1]);
                    }
                    return $coord;
                }, $coordinates);
                break;

            case 'Polygon':
                $geometry['coordinates'] = array_map(function($ring) {
                    return array_map(function($coord) {
                        if (is_array($coord) && count($coord) == 2) {
                            return $this->webMercatorToWGS84($coord[0], $coord[1]);
                        }
                        return $coord;
                    }, $ring);
                }, $coordinates);
                break;

            case 'MultiPoint':
                $geometry['coordinates'] = array_map(function($coord) {
                    if (is_array($coord) && count($coord) == 2) {
                        return $this->webMercatorToWGS84($coord[0], $coord[1]);
                    }
                    return $coord;
                }, $coordinates);
                break;

            case 'MultiLineString':
                $geometry['coordinates'] = array_map(function($line) {
                    return array_map(function($coord) {
                        if (is_array($coord) && count($coord) == 2) {
                            return $this->webMercatorToWGS84($coord[0], $coord[1]);
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
                                return $this->webMercatorToWGS84($coord[0], $coord[1]);
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