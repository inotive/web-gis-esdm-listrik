<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LN_Jalan_Balikpapan;
use Illuminate\Http\Request;

class JalanBalikpapanController extends Controller
{
    public function index()
    {
        $data = LN_Jalan_Balikpapan::all();
        
        $geojsonData = [
            'type' => 'FeatureCollection',
            'features' => []
        ];
        
        foreach ($data as $item) {
            if ($item->geom) {
                $geometry = json_decode($item->geom, true);
                
                $feature = [
                    'type' => 'Feature',
                    'properties' => [
                        'id' => $item->id,
                        'Kecamatan' => $item->Kecamatan,
                        'F18' => $item->F18,
                        'F19' => $item->F19,
                        'F20' => $item->F20,
                        'F21' => $item->F21,
                        'KODE_RUAS' => $item->KODE_RUAS,
                        'NAMA_RUAS' => $item->NAMA_RUAS,
                        'TAHUN_DATA' => $item->TAHUN_DATA,
                        'FUNGSI' => $item->FUNGSI,
                        'LEBAR' => $item->LEBAR,
                        'PANJANG' => $item->PANJANG,
                        'KOORD_X_AW' => $item->KOORD_X_AW,
                        'KOORD_Y_AW' => $item->KOORD_Y_AW,
                        'KOORD_X_AK' => $item->KOORD_X_AK,
                        'KOORD_Y_AK' => $item->KOORD_Y_AK,
                        'Shape_Le_1' => $item->Shape_Le_1,
                    ],
                    'geometry' => $geometry
                ];
                
                $geojsonData['features'][] = $feature;
            }
        }
        
        return response()->json($geojsonData);
    }
    
    public function show($id)
    {
        $item = LN_Jalan_Balikpapan::find($id);
        
        if (!$item) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        
        $geometry = json_decode($item->geom, true);
        
        $feature = [
            'type' => 'Feature',
            'properties' => [
                'id' => $item->id,
                'Kecamatan' => $item->Kecamatan,
                'F18' => $item->F18,
                'F19' => $item->F19,
                'F20' => $item->F20,
                'F21' => $item->F21,
                'KODE_RUAS' => $item->KODE_RUAS,
                'NAMA_RUAS' => $item->NAMA_RUAS,
                'TAHUN_DATA' => $item->TAHUN_DATA,
                'FUNGSI' => $item->FUNGSI,
                'LEBAR' => $item->LEBAR,
                'PANJANG' => $item->PANJANG,
                'KOORD_X_AW' => $item->KOORD_X_AW,
                'KOORD_Y_AW' => $item->KOORD_Y_AW,
                'KOORD_X_AK' => $item->KOORD_X_AK,
                'KOORD_Y_AK' => $item->KOORD_Y_AK,
                'Shape_Le_1' => $item->Shape_Le_1,
            ],
            'geometry' => $geometry
        ];
        
        return response()->json($feature);
    }
}