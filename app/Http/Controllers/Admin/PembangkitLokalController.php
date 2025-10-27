<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PembangkitLokalController extends Controller
{
    public function index()
    {
        $items = [
            ['nama' => 'PLTD Sungai K', 'tipe' => 'PLTD', 'daya' => '2 MW', 'status' => 'Beroperasi'],
            ['nama' => 'PLTS Desa L', 'tipe' => 'PLTS', 'daya' => '250 kWp', 'status' => 'Uji Coba'],
            ['nama' => 'PLTMH Rimba', 'tipe' => 'PLTMH', 'daya' => '150 kW', 'status' => 'Beroperasi'],
        ];
        return view('admin.pembangkit_lokal.index', compact('items'));
    }
}
