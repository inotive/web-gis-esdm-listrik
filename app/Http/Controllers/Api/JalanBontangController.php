<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Bontang;
use Illuminate\Http\Request;

class JalanBontangController extends Controller
{
    /**
     * Convert geometry coordinates to WGS84
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
                // Bontang coordinates are already in WGS84
                break;

            case 'LineString':
                // Bontang coordinates are already in WGS84
                break;

            case 'Polygon':
                // Bontang coordinates are already in WGS84
                break;

            case 'MultiLineString':
                // Bontang coordinates are already in WGS84
                break;

            case 'MultiPolygon':
                // Bontang coordinates are already in WGS84
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