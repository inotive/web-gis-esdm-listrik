<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DataGarduController extends Controller
{
    public function index()
    {
        // dummy data
        $items = [
            ['nama' => 'Gardu A-01', 'kapasitas' => '250 kVA', 'lokasi' => 'Kec. A, Kab. A', 'status' => 'Aktif'],
            ['nama' => 'Gardu B-03', 'kapasitas' => '400 kVA', 'lokasi' => 'Kec. B, Kab. B', 'status' => 'Perawatan'],
            ['nama' => 'Gardu C-07', 'kapasitas' => '160 kVA', 'lokasi' => 'Kec. C, Kab. C', 'status' => 'Aktif'],
        ];
        return view('admin.data_gardu.index', compact('items'));
    }
}
