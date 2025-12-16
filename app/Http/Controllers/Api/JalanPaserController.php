<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Paser;
use Illuminate\Http\Request;

class JalanPaserController extends Controller
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
        $data = LN_Jalan_Paser::all();

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
                        'OBJECTID_1' => $item->OBJECTID_1,
                        'OBJECTID_2' => $item->OBJECTID_2,
                        'OBJECTID' => $item->OBJECTID,
                        'Kl_Dat_Das' => $item->Kl_Dat_Das,
                        'Nm_Ruas' => $item->Nm_Ruas,
                        'Thn_Data' => $item->Thn_Data,
                        'Status' => $item->Status,
                        'Fungsi' => $item->Fungsi,
                        'Mendukung' => $item->Mendukung,
                        'Ura_Dukung' => $item->Ura_Dukung,
                        'Kd_Bd_PU' => $item->Kd_Bd_PU,
                        'Kd_Jns_inf' => $item->Kd_Jns_inf,
                        'Kd_Inf' => $item->Kd_Inf,
                        'Propinsi' => $item->Propinsi,
                        'Kab_Kot' => $item->Kab_Kot,
                        'Kecamatan' => $item->Kecamatan,
                        'Desa_Kel' => $item->Desa_Kel,
                        'Tk_Ruas_Aw' => $item->Tk_Ruas_Aw,
                        'Tk_Ruas_Ak' => $item->Tk_Ruas_Ak,
                        'Kd_Patok' => $item->Kd_Patok,
                        'Nm_Lintas' => $item->Nm_Lintas,
                        'Km_Awal' => $item->Km_Awal,
                        'Km_Akhir' => $item->Km_Akhir,
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
                        'pnj' => $item->pnj,
                        'Id' => $item->Id_Paser,
                        'Shape_Leng' => $item->Shape_Leng,
                        'X_Ak' => $item->X_Ak,
                        'Y_Ak' => $item->Y_Ak,
                        'X_Aw' => $item->X_Aw,
                        'Y_Aw' => $item->Y_Aw,
                        'No' => $item->No,
                    ],
                    'geometry' => $geometry
                ];

                $geojsonData['features'][] = $feature;
            }
        }

        return response()->json($geojsonData);
    }
}