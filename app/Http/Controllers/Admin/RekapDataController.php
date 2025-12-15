<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekapDataController extends Controller
{
    /**
     * Menampilkan halaman rekap data rasio desa berlistrik dan rasio elektrifikasi
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', 2024);

        // ==========================================
        // TAB 1: DATA DESA BERLISTRIK
        // ==========================================
        $rekapData = [
            [
                'no' => 'I',
                'kabupaten_kota' => 'Balikpapan',
                'jumlah_desa' => 34,
                'jumlah_kk' => 218833,
                'jumlah_penduduk' => 644315,
                'desa_berlistrik_pln' => 34,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 34,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 193587,
                'kk_berlistrik_non_pln' => 0,
                'kk_berlistrik_jumlah' => 193587,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 25246,
                'rasio_elektrifikasi' => 88.46,
            ],
            [
                'no' => 'II',
                'kabupaten_kota' => 'Berau',
                'jumlah_desa' => 110,
                'jumlah_kk' => 72644,
                'jumlah_penduduk' => 223556,
                'desa_berlistrik_pln' => 66,
                'desa_berlistrik_non_pln' => 44,
                'desa_berlistrik_jumlah' => 110,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 51135,
                'kk_berlistrik_non_pln' => 6671,
                'kk_berlistrik_jumlah' => 57806,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 14838,
                'rasio_elektrifikasi' => 79.57,
            ],
            [
                'no' => 'III',
                'kabupaten_kota' => 'Kutai Kartanegara',
                'jumlah_desa' => 237,
                'jumlah_kk' => 214437,
                'jumlah_penduduk' => 676735,
                'desa_berlistrik_pln' => 214,
                'desa_berlistrik_non_pln' => 22,
                'desa_berlistrik_jumlah' => 236,
                'desa_belum_berlistrik' => 1,
                'kk_berlistrik_pln' => 166398,
                'kk_berlistrik_non_pln' => 8802,
                'kk_berlistrik_jumlah' => 175200,
                'rasio_desa_berlistrik' => 99.58,
                'jumlah_kk_belum_berlistrik' => 39237,
                'rasio_elektrifikasi' => 81.70,
            ],
            [
                'no' => 'IV',
                'kabupaten_kota' => 'Samarinda',
                'jumlah_desa' => 59,
                'jumlah_kk' => 246941,
                'jumlah_penduduk' => 777073,
                'desa_berlistrik_pln' => 59,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 59,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 244523,
                'kk_berlistrik_non_pln' => 0,
                'kk_berlistrik_jumlah' => 244523,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 2418,
                'rasio_elektrifikasi' => 99.02,
            ],
            [
                'no' => 'V',
                'kabupaten_kota' => 'Kutai Timur',
                'jumlah_desa' => 141,
                'jumlah_kk' => 113573,
                'jumlah_penduduk' => 419756,
                'desa_berlistrik_pln' => 70,
                'desa_berlistrik_non_pln' => 71,
                'desa_berlistrik_jumlah' => 141,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 55727,
                'kk_berlistrik_non_pln' => 33126,
                'kk_berlistrik_jumlah' => 88853,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 24720,
                'rasio_elektrifikasi' => 78.23,
            ],
            [
                'no' => 'VI',
                'kabupaten_kota' => 'Bontang',
                'jumlah_desa' => 15,
                'jumlah_kk' => 55505,
                'jumlah_penduduk' => 178718,
                'desa_berlistrik_pln' => 15,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 15,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 46400,
                'kk_berlistrik_non_pln' => 0,
                'kk_berlistrik_jumlah' => 46400,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 9105,
                'rasio_elektrifikasi' => 83.60,
            ],
            [
                'no' => 'VII',
                'kabupaten_kota' => 'Penajam Paser Utara',
                'jumlah_desa' => 54,
                'jumlah_kk' => 52519,
                'jumlah_penduduk' => 169428,
                'desa_berlistrik_pln' => 54,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 54,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 38389,
                'kk_berlistrik_non_pln' => 2842,
                'kk_berlistrik_jumlah' => 41231,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 11288,
                'rasio_elektrifikasi' => 78.51,
            ],
            [
                'no' => 'VIII',
                'kabupaten_kota' => 'Paser',
                'jumlah_desa' => 144,
                'jumlah_kk' => 84326,
                'jumlah_penduduk' => 258022,
                'desa_berlistrik_pln' => 106,
                'desa_berlistrik_non_pln' => 37,
                'desa_berlistrik_jumlah' => 143,
                'desa_belum_berlistrik' => 1,
                'kk_berlistrik_pln' => 57956,
                'kk_berlistrik_non_pln' => 5793,
                'kk_berlistrik_jumlah' => 63749,
                'rasio_desa_berlistrik' => 99.31,
                'jumlah_kk_belum_berlistrik' => 20577,
                'rasio_elektrifikasi' => 75.60,
            ],
            [
                'no' => 'IX',
                'kabupaten_kota' => 'Kutai Barat',
                'jumlah_desa' => 194,
                'jumlah_kk' => 48495,
                'jumlah_penduduk' => 161111,
                'desa_berlistrik_pln' => 113,
                'desa_berlistrik_non_pln' => 77,
                'desa_berlistrik_jumlah' => 190,
                'desa_belum_berlistrik' => 4,
                'kk_berlistrik_pln' => 31410,
                'kk_berlistrik_non_pln' => 10236,
                'kk_berlistrik_jumlah' => 41646,
                'rasio_desa_berlistrik' => 97.94,
                'jumlah_kk_belum_berlistrik' => 6849,
                'rasio_elektrifikasi' => 85.88,
            ],
            [
                'no' => 'X',
                'kabupaten_kota' => 'Mahakam Ulu',
                'jumlah_desa' => 50,
                'jumlah_kk' => 9027,
                'jumlah_penduduk' => 28231,
                'desa_berlistrik_pln' => 16,
                'desa_berlistrik_non_pln' => 29,
                'desa_berlistrik_jumlah' => 45,
                'desa_belum_berlistrik' => 5,
                'kk_berlistrik_pln' => 1588,
                'kk_berlistrik_non_pln' => 2689,
                'kk_berlistrik_jumlah' => 4277,
                'rasio_desa_berlistrik' => 90.00,
                'jumlah_kk_belum_berlistrik' => 4750,
                'rasio_elektrifikasi' => 47.38,
            ],
        ];

        // Hitung total untuk Tab 1
        $total = [
            'jumlah_desa' => collect($rekapData)->sum('jumlah_desa'),
            'jumlah_kk' => collect($rekapData)->sum('jumlah_kk'),
            'jumlah_penduduk' => collect($rekapData)->sum('jumlah_penduduk'),
            'desa_berlistrik_pln' => collect($rekapData)->sum('desa_berlistrik_pln'),
            'desa_berlistrik_non_pln' => collect($rekapData)->sum('desa_berlistrik_non_pln'),
            'desa_berlistrik_jumlah' => collect($rekapData)->sum('desa_berlistrik_jumlah'),
            'desa_belum_berlistrik' => collect($rekapData)->sum('desa_belum_berlistrik'),
            'kk_berlistrik_pln' => collect($rekapData)->sum('kk_berlistrik_pln'),
            'kk_berlistrik_non_pln' => collect($rekapData)->sum('kk_berlistrik_non_pln'),
            'kk_berlistrik_jumlah' => collect($rekapData)->sum('kk_berlistrik_jumlah'),
            'jumlah_kk_belum_berlistrik' => collect($rekapData)->sum('jumlah_kk_belum_berlistrik'),
        ];

        // Hitung rasio total
        $total['rasio_desa_berlistrik'] = $total['jumlah_desa'] > 0
            ? round(($total['desa_berlistrik_jumlah'] / $total['jumlah_desa']) * 100, 2)
            : 0;
        $total['rasio_elektrifikasi'] = $total['jumlah_kk'] > 0
            ? round(($total['kk_berlistrik_jumlah'] / $total['jumlah_kk']) * 100, 2)
            : 0;

        // ==========================================
        // TAB 2: DATA INFRASTRUKTUR (Sederhana)
        // Kolom: Kota/Kabupaten, Jumlah IUPTLS, Rekomtek SKTP, Jumlah Kapasitas (kVA)
        // ==========================================
        $infrastrukturData = [
            [
                'kabupaten_kota' => 'Kota Balikpapan',
                'jumlah_iuptls' => 10,
                'rekomtek_sktp' => 11,
                'jumlah_kapasitas' => 18223,
            ],
            [
                'kabupaten_kota' => 'Kab. Berau',
                'jumlah_iuptls' => 6,
                'rekomtek_sktp' => 3,
                'jumlah_kapasitas' => 25703.36,
            ],
            [
                'kabupaten_kota' => 'Kab. Kutai Kartanegara',
                'jumlah_iuptls' => 12,
                'rekomtek_sktp' => 3,
                'jumlah_kapasitas' => 43971.5,
            ],
            [
                'kabupaten_kota' => 'Kota Samarinda',
                'jumlah_iuptls' => 6,
                'rekomtek_sktp' => 7,
                'jumlah_kapasitas' => 10275.5,
            ],
            [
                'kabupaten_kota' => 'Kab. Kutai Timur',
                'jumlah_iuptls' => 4,
                'rekomtek_sktp' => 4,
                'jumlah_kapasitas' => 10702.5,
            ],
            [
                'kabupaten_kota' => 'Kota Bontang',
                'jumlah_iuptls' => 2,
                'rekomtek_sktp' => 0,
                'jumlah_kapasitas' => 7891.04,
            ],
            [
                'kabupaten_kota' => 'Kab. Penajam Paser Utara',
                'jumlah_iuptls' => 1,
                'rekomtek_sktp' => 0,
                'jumlah_kapasitas' => 880,
            ],
            [
                'kabupaten_kota' => 'Kab. Paser',
                'jumlah_iuptls' => 3,
                'rekomtek_sktp' => 0,
                'jumlah_kapasitas' => 13110,
            ],
            [
                'kabupaten_kota' => 'Kab. Kutai Barat',
                'jumlah_iuptls' => 0,
                'rekomtek_sktp' => 0,
                'jumlah_kapasitas' => 1170,
            ],
            [
                'kabupaten_kota' => 'Kab. Mahakam Ulu',
                'jumlah_iuptls' => 17,
                'rekomtek_sktp' => 14,
                'jumlah_kapasitas' => 43782,
            ],
        ];

        // Hitung total untuk Tab 2
        $totalInfra = [
            'jumlah_iuptls' => collect($infrastrukturData)->sum('jumlah_iuptls'),
            'rekomtek_sktp' => collect($infrastrukturData)->sum('rekomtek_sktp'),
            'jumlah_kapasitas' => collect($infrastrukturData)->sum('jumlah_kapasitas'),
        ];

        return view('admin.rekap_data.index', compact(
            'rekapData',
            'total',
            'infrastrukturData',
            'totalInfra',
            'tahun'
        ));
    }
}
