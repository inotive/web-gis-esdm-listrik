<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class JalanAksesController extends Controller
{
    public function index()
    {
        $items = [
            ['nama' => 'Jalan Poros Utama', 'kondisi' => 'Baik', 'panjang' => '12.4 km', 'akses' => 'Darat'],
            ['nama' => 'Jalan Desa Maju', 'kondisi' => 'Sedang', 'panjang' => '4.1 km', 'akses' => 'Darat'],
            ['nama' => 'Sungai Kecil', 'kondisi' => 'Layak', 'panjang' => '-', 'akses' => 'Air'],
        ];
        return view('admin.jalan_akses.index', compact('items'));
    }
}
