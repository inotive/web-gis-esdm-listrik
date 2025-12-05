<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataBerlistrikController extends Controller
{
    public function index(Request $request)
    {
        $path = public_path('assets/data_berlistrik.json');

        if (!file_exists($path)) {
            return response()->json([
                'error' => 'File data_berlistrik.json tidak ditemukan'
            ], 404);
        }

        $json = file_get_contents($path);

        // Kembalikan apa adanya, karena sudah format GeoJSON
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
