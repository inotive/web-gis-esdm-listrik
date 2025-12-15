<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LN_Jalan_Kabupaten_Berau;

class JalanKabupatenBerauController extends Controller
{
    public function index(Request $request)
    {
        $features = [];
        
        // Get all records from the database
        $records = LN_Jalan_Kabupaten_Berau::all();
        
        foreach ($records as $record) {
            $feature = [
                'type' => 'Feature',
                'geometry' => $record->geometry,
                'properties' => [
                    'OBJECTID' => $record->objectid,
                    'NO_RUAS' => $record->no_ruas,
                    'NAMA_RUAS' => $record->nama_ruas,
                    'KAB_KOTA' => $record->kab_kota,
                    'TTK_PNGKAL' => $record->ttk_pngkal,
                    'TTK_AKHIR' => $record->ttk_akhir,
                    'PANJANG' => $record->panjang,
                    'JKP_2' => $record->jkp_2,
                    'JKP_3' => $record->jkp_3,
                    'JKP_4' => $record->jkp_4,
                    'JLP' => $record->jlp,
                    'Jling_P' => $record->jling_p,
                    'JAS' => $record->jas,
                    'JKS' => $record->jks,
                    'JLS' => $record->jls,
                    'Jling_S' => $record->jling_s,
                    'FUNGSI' => $record->fungsi,
                    'STATUS' => $record->status,
                    'Shape_Leng' => $record->shape_leng,
                ]
            ];
            
            $features[] = $feature;
        }
        
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        return response()->json($geojson);
    }
}