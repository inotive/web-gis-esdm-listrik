<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PemukimanTanpaListrikController extends Controller
{
    public function index()
    {
        $items = [
            ['desa' => 'Long Pelay', 'kk' => 76, 'koor' => "0.123, 116.789", 'ket' => 'Akses darat 4 jam'],
            ['desa' => 'Muara Lesan Hulu', 'kk' => 54, 'koor' => "-0.234, 117.012", 'ket' => 'Akses sungai'],
            ['desa' => 'Sinduung Indah', 'kk' => 33, 'koor' => "-0.498, 116.332", 'ket' => 'Terpencil'],
        ];
        return view('admin.pemukiman_tanpa_listrik.index', compact('items'));
    }
}
