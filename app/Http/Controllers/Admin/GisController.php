<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class GisController extends Controller
{
    public function index()
    {
        // titik awal peta (Samarinda)
        $map = [
            'lat' => -0.502106, // contoh
            'lng' => 117.153709,
            'zoom' => 11,
        ];
        // titik dummy
        $markers = [
            ['nama' => 'Gardu A-01', 'lat' => -0.49, 'lng' => 117.18],
            ['nama' => 'PLTS Desa L', 'lat' => -0.53, 'lng' => 117.11],
            ['nama' => 'Pemukiman Tanpa Listrik', 'lat' => -0.50, 'lng' => 117.22],
        ];
        return view('admin.gis.index', compact('map', 'markers'));
    }
}
