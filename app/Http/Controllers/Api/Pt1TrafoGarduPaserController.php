<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Pt1TrafoGarduPaserController extends Controller
{
    public function index(Request $request)
    {
        $path = public_path('assets/infrastruktur/PT1_Trafo_Gardu_Paser.json');

        if (!file_exists($path)) {
            return response()->json([
                'error' => 'File PT1_Trafo_Gardu_Paser.json tidak ditemukan'
            ], 404);
        }

        $json = file_get_contents($path);

        // Kembalikan apa adanya, karena sudah format GeoJSON
        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
