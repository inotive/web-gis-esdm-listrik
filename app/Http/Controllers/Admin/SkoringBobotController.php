<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SkoringBobotController extends Controller
{
    public function index()
    {
        $items = [
            ['variabel' => 'Jumlah KK', 'bobot' => 0.25, 'keterangan' => 'Semakin banyak semakin prioritas'],
            ['variabel' => 'Jarak ke Gardu', 'bobot' => 0.20, 'keterangan' => 'Lebih jauh lebih prioritas'],
            ['variabel' => 'Akses Jalan', 'bobot' => 0.15, 'keterangan' => 'Sulit = prioritas'],
            ['variabel' => 'Biaya Per Sambungan', 'bobot' => 0.25, 'keterangan' => 'Lebih murah lebih prioritas'],
            ['variabel' => 'Kesiapan Lahan', 'bobot' => 0.15, 'keterangan' => 'Siap meningkat skor'],
        ];
        return view('admin.skoring_bobot.index', compact('items'));
    }
}
