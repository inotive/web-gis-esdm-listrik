<?php

namespace Database\Seeders;

use App\Models\RekapElektrifikasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RekapElektrifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Tahun 2018 (dari foto)
        $data2018 = [
            [
                'no_urut' => 'I',
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
                'no_urut' => 'II',
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
                'no_urut' => 'III',
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
                'no_urut' => 'IV',
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
                'no_urut' => 'V',
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
                'no_urut' => 'VI',
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
                'no_urut' => 'VII',
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
                'no_urut' => 'VIII',
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
                'no_urut' => 'IX',
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
                'no_urut' => 'X',
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

        // Data Tahun 2019 (dari foto)
        $data2019 = [
            [
                'no_urut' => 'I',
                'kabupaten_kota' => 'Balikpapan',
                'jumlah_desa' => 34,
                'jumlah_kk' => 223390,
                'jumlah_penduduk' => 667188,
                'desa_berlistrik_pln' => 34,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 34,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 204278,
                'kk_berlistrik_non_pln' => 0,
                'kk_berlistrik_jumlah' => 204278,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 19112,
                'rasio_elektrifikasi' => 91.44,
            ],
            [
                'no_urut' => 'II',
                'kabupaten_kota' => 'Berau',
                'jumlah_desa' => 110,
                'jumlah_kk' => 76511,
                'jumlah_penduduk' => 231079,
                'desa_berlistrik_pln' => 70,
                'desa_berlistrik_non_pln' => 40,
                'desa_berlistrik_jumlah' => 110,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 55766,
                'kk_berlistrik_non_pln' => 6900,
                'kk_berlistrik_jumlah' => 62666,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 13845,
                'rasio_elektrifikasi' => 81.90,
            ],
            [
                'no_urut' => 'III',
                'kabupaten_kota' => 'Kutai Kartanegara',
                'jumlah_desa' => 237,
                'jumlah_kk' => 215773,
                'jumlah_penduduk' => 692371,
                'desa_berlistrik_pln' => 221,
                'desa_berlistrik_non_pln' => 16,
                'desa_berlistrik_jumlah' => 237,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 176857,
                'kk_berlistrik_non_pln' => 9064,
                'kk_berlistrik_jumlah' => 185921,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 29852,
                'rasio_elektrifikasi' => 86.17,
            ],
            [
                'no_urut' => 'IV',
                'kabupaten_kota' => 'Samarinda',
                'jumlah_desa' => 59,
                'jumlah_kk' => 246856,
                'jumlah_penduduk' => 789601,
                'desa_berlistrik_pln' => 59,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 59,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 246842,
                'kk_berlistrik_non_pln' => 0,
                'kk_berlistrik_jumlah' => 246842,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 14,
                'rasio_elektrifikasi' => 99.99,
            ],
            [
                'no_urut' => 'V',
                'kabupaten_kota' => 'Kutai Timur',
                'jumlah_desa' => 141,
                'jumlah_kk' => 120252,
                'jumlah_penduduk' => 421251,
                'desa_berlistrik_pln' => 82,
                'desa_berlistrik_non_pln' => 59,
                'desa_berlistrik_jumlah' => 141,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 64499,
                'kk_berlistrik_non_pln' => 31122,
                'kk_berlistrik_jumlah' => 95621,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 24631,
                'rasio_elektrifikasi' => 79.52,
            ],
            [
                'no_urut' => 'VI',
                'kabupaten_kota' => 'Bontang',
                'jumlah_desa' => 15,
                'jumlah_kk' => 56907,
                'jumlah_penduduk' => 180432,
                'desa_berlistrik_pln' => 15,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 15,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 49394,
                'kk_berlistrik_non_pln' => 327,
                'kk_berlistrik_jumlah' => 49721,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 7186,
                'rasio_elektrifikasi' => 87.37,
            ],
            [
                'no_urut' => 'VII',
                'kabupaten_kota' => 'Penajam Paser Utara',
                'jumlah_desa' => 54,
                'jumlah_kk' => 54023,
                'jumlah_penduduk' => 172867,
                'desa_berlistrik_pln' => 54,
                'desa_berlistrik_non_pln' => 0,
                'desa_berlistrik_jumlah' => 54,
                'desa_belum_berlistrik' => 0,
                'kk_berlistrik_pln' => 41027,
                'kk_berlistrik_non_pln' => 2842,
                'kk_berlistrik_jumlah' => 43869,
                'rasio_desa_berlistrik' => 100.00,
                'jumlah_kk_belum_berlistrik' => 10154,
                'rasio_elektrifikasi' => 81.20,
            ],
            [
                'no_urut' => 'VIII',
                'kabupaten_kota' => 'Paser',
                'jumlah_desa' => 144,
                'jumlah_kk' => 85268,
                'jumlah_penduduk' => 263504,
                'desa_berlistrik_pln' => 116,
                'desa_berlistrik_non_pln' => 27,
                'desa_berlistrik_jumlah' => 143,
                'desa_belum_berlistrik' => 1,
                'kk_berlistrik_pln' => 65688,
                'kk_berlistrik_non_pln' => 5978,
                'kk_berlistrik_jumlah' => 71666,
                'rasio_desa_berlistrik' => 99.31,
                'jumlah_kk_belum_berlistrik' => 13602,
                'rasio_elektrifikasi' => 84.05,
            ],
            [
                'no_urut' => 'IX',
                'kabupaten_kota' => 'Kutai Barat',
                'jumlah_desa' => 194,
                'jumlah_kk' => 48633,
                'jumlah_penduduk' => 163351,
                'desa_berlistrik_pln' => 119,
                'desa_berlistrik_non_pln' => 72,
                'desa_berlistrik_jumlah' => 191,
                'desa_belum_berlistrik' => 3,
                'kk_berlistrik_pln' => 36228,
                'kk_berlistrik_non_pln' => 9970,
                'kk_berlistrik_jumlah' => 46198,
                'rasio_desa_berlistrik' => 98.45,
                'jumlah_kk_belum_berlistrik' => 2435,
                'rasio_elektrifikasi' => 94.99,
            ],
            [
                'no_urut' => 'X',
                'kabupaten_kota' => 'Mahakam Ulu',
                'jumlah_desa' => 50,
                'jumlah_kk' => 9771,
                'jumlah_penduduk' => 29901,
                'desa_berlistrik_pln' => 18,
                'desa_berlistrik_non_pln' => 27,
                'desa_berlistrik_jumlah' => 45,
                'desa_belum_berlistrik' => 5,
                'kk_berlistrik_pln' => 2014,
                'kk_berlistrik_non_pln' => 2689,
                'kk_berlistrik_jumlah' => 4703,
                'rasio_desa_berlistrik' => 90.00,
                'jumlah_kk_belum_berlistrik' => 5068,
                'rasio_elektrifikasi' => 48.13,
            ],
        ];

        // Insert data 2018
        foreach ($data2018 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2018], $row));
        }

        // Insert data 2019
        foreach ($data2019 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2019], $row));
        }

        // Data Tahun 2020 (dari foto)
        $data2020 = [
            ['no_urut' => 'I', 'kabupaten_kota' => 'Balikpapan', 'jumlah_desa' => 34, 'jumlah_kk' => 236252, 'jumlah_penduduk' => 672878, 'desa_berlistrik_pln' => 34, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 34, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 212645, 'kk_berlistrik_non_pln' => 416, 'kk_berlistrik_jumlah' => 213061, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 23191, 'rasio_elektrifikasi' => 90.18],
            ['no_urut' => 'II', 'kabupaten_kota' => 'Berau', 'jumlah_desa' => 110, 'jumlah_kk' => 82670, 'jumlah_penduduk' => 235756, 'desa_berlistrik_pln' => 73, 'desa_berlistrik_non_pln' => 37, 'desa_berlistrik_jumlah' => 110, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 60292, 'kk_berlistrik_non_pln' => 15567, 'kk_berlistrik_jumlah' => 75859, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 6811, 'rasio_elektrifikasi' => 91.76],
            ['no_urut' => 'III', 'kabupaten_kota' => 'Kutai Kartanegara', 'jumlah_desa' => 237, 'jumlah_kk' => 237503, 'jumlah_penduduk' => 705168, 'desa_berlistrik_pln' => 221, 'desa_berlistrik_non_pln' => 16, 'desa_berlistrik_jumlah' => 237, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 186102, 'kk_berlistrik_non_pln' => 14714, 'kk_berlistrik_jumlah' => 200816, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 36687, 'rasio_elektrifikasi' => 84.55],
            ['no_urut' => 'IV', 'kabupaten_kota' => 'Samarinda', 'jumlah_desa' => 59, 'jumlah_kk' => 265061, 'jumlah_penduduk' => 801035, 'desa_berlistrik_pln' => 59, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 59, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 266024, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 266024, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 0, 'rasio_elektrifikasi' => 100.00],
            ['no_urut' => 'V', 'kabupaten_kota' => 'Kutai Timur', 'jumlah_desa' => 141, 'jumlah_kk' => 131475, 'jumlah_penduduk' => 424170, 'desa_berlistrik_pln' => 93, 'desa_berlistrik_non_pln' => 48, 'desa_berlistrik_jumlah' => 141, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 81170, 'kk_berlistrik_non_pln' => 31193, 'kk_berlistrik_jumlah' => 112363, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 19112, 'rasio_elektrifikasi' => 85.46],
            ['no_urut' => 'VI', 'kabupaten_kota' => 'Bontang', 'jumlah_desa' => 15, 'jumlah_kk' => 58517, 'jumlah_penduduk' => 182484, 'desa_berlistrik_pln' => 15, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 15, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 50869, 'kk_berlistrik_non_pln' => 1123, 'kk_berlistrik_jumlah' => 51992, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 6525, 'rasio_elektrifikasi' => 88.85],
            ['no_urut' => 'VII', 'kabupaten_kota' => 'Penajam Paser Utara', 'jumlah_desa' => 54, 'jumlah_kk' => 57254, 'jumlah_penduduk' => 175871, 'desa_berlistrik_pln' => 54, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 54, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 43151, 'kk_berlistrik_non_pln' => 3862, 'kk_berlistrik_jumlah' => 47013, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 10241, 'rasio_elektrifikasi' => 82.11],
            ['no_urut' => 'VIII', 'kabupaten_kota' => 'Paser', 'jumlah_desa' => 144, 'jumlah_kk' => 91381, 'jumlah_penduduk' => 276276, 'desa_berlistrik_pln' => 124, 'desa_berlistrik_non_pln' => 20, 'desa_berlistrik_jumlah' => 144, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 69058, 'kk_berlistrik_non_pln' => 8431, 'kk_berlistrik_jumlah' => 77489, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 13892, 'rasio_elektrifikasi' => 84.80],
            ['no_urut' => 'IX', 'kabupaten_kota' => 'Kutai Barat', 'jumlah_desa' => 194, 'jumlah_kk' => 53886, 'jumlah_penduduk' => 165453, 'desa_berlistrik_pln' => 120, 'desa_berlistrik_non_pln' => 74, 'desa_berlistrik_jumlah' => 194, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 38757, 'kk_berlistrik_non_pln' => 11758, 'kk_berlistrik_jumlah' => 50515, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3371, 'rasio_elektrifikasi' => 93.74],
            ['no_urut' => 'X', 'kabupaten_kota' => 'Mahakam Ulu', 'jumlah_desa' => 50, 'jumlah_kk' => 11118, 'jumlah_penduduk' => 31070, 'desa_berlistrik_pln' => 21, 'desa_berlistrik_non_pln' => 29, 'desa_berlistrik_jumlah' => 50, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 3568, 'kk_berlistrik_non_pln' => 6527, 'kk_berlistrik_jumlah' => 10095, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 1023, 'rasio_elektrifikasi' => 90.80],
        ];

        // Insert data 2020
        foreach ($data2020 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2020], $row));
        }

        // Data Tahun 2021 (dari foto)
        $data2021 = [
            ['no_urut' => 'I', 'kabupaten_kota' => 'Balikpapan', 'jumlah_desa' => 34, 'jumlah_kk' => 237838, 'jumlah_penduduk' => 704110, 'desa_berlistrik_pln' => 34, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 34, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 221307, 'kk_berlistrik_non_pln' => 416, 'kk_berlistrik_jumlah' => 221723, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 16115, 'rasio_elektrifikasi' => 93.22],
            ['no_urut' => 'II', 'kabupaten_kota' => 'Berau', 'jumlah_desa' => 110, 'jumlah_kk' => 86016, 'jumlah_penduduk' => 253979, 'desa_berlistrik_pln' => 82, 'desa_berlistrik_non_pln' => 28, 'desa_berlistrik_jumlah' => 110, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 65661, 'kk_berlistrik_non_pln' => 13346, 'kk_berlistrik_jumlah' => 79007, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 7009, 'rasio_elektrifikasi' => 91.85],
            ['no_urut' => 'III', 'kabupaten_kota' => 'Kutai Kartanegara', 'jumlah_desa' => 237, 'jumlah_kk' => 240615, 'jumlah_penduduk' => 741950, 'desa_berlistrik_pln' => 221, 'desa_berlistrik_non_pln' => 16, 'desa_berlistrik_jumlah' => 237, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 194789, 'kk_berlistrik_non_pln' => 13671, 'kk_berlistrik_jumlah' => 208460, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 32155, 'rasio_elektrifikasi' => 86.64],
            ['no_urut' => 'IV', 'kabupaten_kota' => 'Samarinda', 'jumlah_desa' => 59, 'jumlah_kk' => 269651, 'jumlah_penduduk' => 825494, 'desa_berlistrik_pln' => 59, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 59, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 276574, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 276574, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 0, 'rasio_elektrifikasi' => 100.00],
            ['no_urut' => 'V', 'kabupaten_kota' => 'Kutai Timur', 'jumlah_desa' => 141, 'jumlah_kk' => 131845, 'jumlah_penduduk' => 424447, 'desa_berlistrik_pln' => 96, 'desa_berlistrik_non_pln' => 45, 'desa_berlistrik_jumlah' => 141, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 82182, 'kk_berlistrik_non_pln' => 30053, 'kk_berlistrik_jumlah' => 112235, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 19610, 'rasio_elektrifikasi' => 85.13],
            ['no_urut' => 'VI', 'kabupaten_kota' => 'Bontang', 'jumlah_desa' => 15, 'jumlah_kk' => 58785, 'jumlah_penduduk' => 185201, 'desa_berlistrik_pln' => 15, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 15, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 51875, 'kk_berlistrik_non_pln' => 1123, 'kk_berlistrik_jumlah' => 52998, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 5787, 'rasio_elektrifikasi' => 90.16],
            ['no_urut' => 'VII', 'kabupaten_kota' => 'Penajam Paser Utara', 'jumlah_desa' => 54, 'jumlah_kk' => 58941, 'jumlah_penduduk' => 185022, 'desa_berlistrik_pln' => 54, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 54, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 45762, 'kk_berlistrik_non_pln' => 2963, 'kk_berlistrik_jumlah' => 48725, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 10216, 'rasio_elektrifikasi' => 82.67],
            ['no_urut' => 'VIII', 'kabupaten_kota' => 'Paser', 'jumlah_desa' => 144, 'jumlah_kk' => 92755, 'jumlah_penduduk' => 280250, 'desa_berlistrik_pln' => 133, 'desa_berlistrik_non_pln' => 11, 'desa_berlistrik_jumlah' => 144, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 75364, 'kk_berlistrik_non_pln' => 6805, 'kk_berlistrik_jumlah' => 82169, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 10586, 'rasio_elektrifikasi' => 88.59],
            ['no_urut' => 'IX', 'kabupaten_kota' => 'Kutai Barat', 'jumlah_desa' => 194, 'jumlah_kk' => 54690, 'jumlah_penduduk' => 168348, 'desa_berlistrik_pln' => 124, 'desa_berlistrik_non_pln' => 70, 'desa_berlistrik_jumlah' => 194, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 42439, 'kk_berlistrik_non_pln' => 8414, 'kk_berlistrik_jumlah' => 50853, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3837, 'rasio_elektrifikasi' => 92.98],
            ['no_urut' => 'X', 'kabupaten_kota' => 'Mahakam Ulu', 'jumlah_desa' => 50, 'jumlah_kk' => 12112, 'jumlah_penduduk' => 35171, 'desa_berlistrik_pln' => 21, 'desa_berlistrik_non_pln' => 29, 'desa_berlistrik_jumlah' => 50, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 4293, 'kk_berlistrik_non_pln' => 6496, 'kk_berlistrik_jumlah' => 10789, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 1323, 'rasio_elektrifikasi' => 89.08],
        ];

        // Insert data 2021
        foreach ($data2021 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2021], $row));
        }

        // Data Tahun 2022 (dari foto)
        $data2022 = [
            ['no_urut' => 'I', 'kabupaten_kota' => 'Balikpapan', 'jumlah_desa' => 34, 'jumlah_kk' => 243852, 'jumlah_penduduk' => 718423, 'desa_berlistrik_pln' => 34, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 34, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 233083, 'kk_berlistrik_non_pln' => 416, 'kk_berlistrik_jumlah' => 233499, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 10353, 'rasio_elektrifikasi' => 95.75],
            ['no_urut' => 'II', 'kabupaten_kota' => 'Berau', 'jumlah_desa' => 110, 'jumlah_kk' => 90440, 'jumlah_penduduk' => 266867, 'desa_berlistrik_pln' => 86, 'desa_berlistrik_non_pln' => 24, 'desa_berlistrik_jumlah' => 110, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 70437, 'kk_berlistrik_non_pln' => 13104, 'kk_berlistrik_jumlah' => 83541, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 6899, 'rasio_elektrifikasi' => 92.37],
            ['no_urut' => 'III', 'kabupaten_kota' => 'Kutai Kartanegara', 'jumlah_desa' => 237, 'jumlah_kk' => 249667, 'jumlah_penduduk' => 765284, 'desa_berlistrik_pln' => 225, 'desa_berlistrik_non_pln' => 12, 'desa_berlistrik_jumlah' => 237, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 203342, 'kk_berlistrik_non_pln' => 13426, 'kk_berlistrik_jumlah' => 216768, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 32899, 'rasio_elektrifikasi' => 86.82],
            ['no_urut' => 'IV', 'kabupaten_kota' => 'Samarinda', 'jumlah_desa' => 59, 'jumlah_kk' => 276099, 'jumlah_penduduk' => 838935, 'desa_berlistrik_pln' => 59, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 59, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 287765, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 287765, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 0, 'rasio_elektrifikasi' => 100.00],
            ['no_urut' => 'V', 'kabupaten_kota' => 'Kutai Timur', 'jumlah_desa' => 141, 'jumlah_kk' => 140250, 'jumlah_penduduk' => 425613, 'desa_berlistrik_pln' => 97, 'desa_berlistrik_non_pln' => 44, 'desa_berlistrik_jumlah' => 141, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 88178, 'kk_berlistrik_non_pln' => 31227, 'kk_berlistrik_jumlah' => 119405, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 20845, 'rasio_elektrifikasi' => 85.14],
            ['no_urut' => 'VI', 'kabupaten_kota' => 'Bontang', 'jumlah_desa' => 15, 'jumlah_kk' => 59568, 'jumlah_penduduk' => 185928, 'desa_berlistrik_pln' => 15, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 15, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 53841, 'kk_berlistrik_non_pln' => 1123, 'kk_berlistrik_jumlah' => 54964, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 4604, 'rasio_elektrifikasi' => 92.27],
            ['no_urut' => 'VII', 'kabupaten_kota' => 'Penajam Paser Utara', 'jumlah_desa' => 54, 'jumlah_kk' => 61196, 'jumlah_penduduk' => 188923, 'desa_berlistrik_pln' => 54, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 54, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 48082, 'kk_berlistrik_non_pln' => 2963, 'kk_berlistrik_jumlah' => 51045, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 10151, 'rasio_elektrifikasi' => 83.41],
            ['no_urut' => 'VIII', 'kabupaten_kota' => 'Paser', 'jumlah_desa' => 144, 'jumlah_kk' => 96067, 'jumlah_penduduk' => 292879, 'desa_berlistrik_pln' => 134, 'desa_berlistrik_non_pln' => 10, 'desa_berlistrik_jumlah' => 144, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 80007, 'kk_berlistrik_non_pln' => 6560, 'kk_berlistrik_jumlah' => 86567, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 9500, 'rasio_elektrifikasi' => 90.11],
            ['no_urut' => 'IX', 'kabupaten_kota' => 'Kutai Barat', 'jumlah_desa' => 194, 'jumlah_kk' => 56731, 'jumlah_penduduk' => 173001, 'desa_berlistrik_pln' => 125, 'desa_berlistrik_non_pln' => 69, 'desa_berlistrik_jumlah' => 194, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 45512, 'kk_berlistrik_non_pln' => 7247, 'kk_berlistrik_jumlah' => 52759, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3972, 'rasio_elektrifikasi' => 93.00],
            ['no_urut' => 'X', 'kabupaten_kota' => 'Mahakam Ulu', 'jumlah_desa' => 50, 'jumlah_kk' => 12519, 'jumlah_penduduk' => 36153, 'desa_berlistrik_pln' => 22, 'desa_berlistrik_non_pln' => 28, 'desa_berlistrik_jumlah' => 50, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 5359, 'kk_berlistrik_non_pln' => 5803, 'kk_berlistrik_jumlah' => 11162, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 1357, 'rasio_elektrifikasi' => 89.16],
        ];

        // Insert data 2022
        foreach ($data2022 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2022], $row));
        }

        // Data Tahun 2023 (dari foto)
        $data2023 = [
            ['no_urut' => 'I', 'kabupaten_kota' => 'Balikpapan', 'jumlah_desa' => 34, 'jumlah_kk' => 249748, 'jumlah_penduduk' => 733396, 'desa_berlistrik_pln' => 34, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 34, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 246002, 'kk_berlistrik_non_pln' => 416, 'kk_berlistrik_jumlah' => 246418, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3330, 'rasio_elektrifikasi' => 98.67],
            ['no_urut' => 'II', 'kabupaten_kota' => 'Berau', 'jumlah_desa' => 110, 'jumlah_kk' => 95064, 'jumlah_penduduk' => 276241, 'desa_berlistrik_pln' => 86, 'desa_berlistrik_non_pln' => 24, 'desa_berlistrik_jumlah' => 110, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 74605, 'kk_berlistrik_non_pln' => 10966, 'kk_berlistrik_jumlah' => 85571, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 9493, 'rasio_elektrifikasi' => 90.01],
            ['no_urut' => 'III', 'kabupaten_kota' => 'Kutai Kartanegara', 'jumlah_desa' => 237, 'jumlah_kk' => 254404, 'jumlah_penduduk' => 782634, 'desa_berlistrik_pln' => 227, 'desa_berlistrik_non_pln' => 10, 'desa_berlistrik_jumlah' => 237, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 217483, 'kk_berlistrik_non_pln' => 10043, 'kk_berlistrik_jumlah' => 227526, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 26878, 'rasio_elektrifikasi' => 89.43],
            ['no_urut' => 'IV', 'kabupaten_kota' => 'Samarinda', 'jumlah_desa' => 59, 'jumlah_kk' => 282192, 'jumlah_penduduk' => 856360, 'desa_berlistrik_pln' => 59, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 59, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 298067, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 298067, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 0, 'rasio_elektrifikasi' => 100.00],
            ['no_urut' => 'V', 'kabupaten_kota' => 'Kutai Timur', 'jumlah_desa' => 141, 'jumlah_kk' => 144745, 'jumlah_penduduk' => 427492, 'desa_berlistrik_pln' => 107, 'desa_berlistrik_non_pln' => 34, 'desa_berlistrik_jumlah' => 141, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 98983, 'kk_berlistrik_non_pln' => 29307, 'kk_berlistrik_jumlah' => 128290, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 16455, 'rasio_elektrifikasi' => 88.63],
            ['no_urut' => 'VI', 'kabupaten_kota' => 'Bontang', 'jumlah_desa' => 15, 'jumlah_kk' => 59587, 'jumlah_penduduk' => 187446, 'desa_berlistrik_pln' => 15, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 15, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 54910, 'kk_berlistrik_non_pln' => 1123, 'kk_berlistrik_jumlah' => 56033, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3554, 'rasio_elektrifikasi' => 94.04],
            ['no_urut' => 'VII', 'kabupaten_kota' => 'Penajam Paser Utara', 'jumlah_desa' => 54, 'jumlah_kk' => 63226, 'jumlah_penduduk' => 193554, 'desa_berlistrik_pln' => 54, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 54, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 56319, 'kk_berlistrik_non_pln' => 1871, 'kk_berlistrik_jumlah' => 58190, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 5036, 'rasio_elektrifikasi' => 92.03],
            ['no_urut' => 'VIII', 'kabupaten_kota' => 'Paser', 'jumlah_desa' => 144, 'jumlah_kk' => 98146, 'jumlah_penduduk' => 298997, 'desa_berlistrik_pln' => 135, 'desa_berlistrik_non_pln' => 9, 'desa_berlistrik_jumlah' => 144, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 83975, 'kk_berlistrik_non_pln' => 5418, 'kk_berlistrik_jumlah' => 89393, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 8753, 'rasio_elektrifikasi' => 91.08],
            ['no_urut' => 'IX', 'kabupaten_kota' => 'Kutai Barat', 'jumlah_desa' => 194, 'jumlah_kk' => 58628, 'jumlah_penduduk' => 177007, 'desa_berlistrik_pln' => 143, 'desa_berlistrik_non_pln' => 51, 'desa_berlistrik_jumlah' => 194, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 48749, 'kk_berlistrik_non_pln' => 5312, 'kk_berlistrik_jumlah' => 54061, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 4567, 'rasio_elektrifikasi' => 92.21],
            ['no_urut' => 'X', 'kabupaten_kota' => 'Mahakam Ulu', 'jumlah_desa' => 50, 'jumlah_kk' => 12880, 'jumlah_penduduk' => 37637, 'desa_berlistrik_pln' => 26, 'desa_berlistrik_non_pln' => 24, 'desa_berlistrik_jumlah' => 50, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 6290, 'kk_berlistrik_non_pln' => 5174, 'kk_berlistrik_jumlah' => 11464, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 1416, 'rasio_elektrifikasi' => 89.01],
        ];

        // Insert data 2023
        foreach ($data2023 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2023], $row));
        }

        // Data Tahun 2024 (dari foto)
        $data2024 = [
            ['no_urut' => 'I', 'kabupaten_kota' => 'Balikpapan', 'jumlah_desa' => 34, 'jumlah_kk' => 256651, 'jumlah_penduduk' => 746804, 'desa_berlistrik_pln' => 34, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 34, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 255213, 'kk_berlistrik_non_pln' => 471, 'kk_berlistrik_jumlah' => 255684, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 967, 'rasio_elektrifikasi' => 99.62],
            ['no_urut' => 'II', 'kabupaten_kota' => 'Berau', 'jumlah_desa' => 110, 'jumlah_kk' => 100570, 'jumlah_penduduk' => 288943, 'desa_berlistrik_pln' => 91, 'desa_berlistrik_non_pln' => 19, 'desa_berlistrik_jumlah' => 110, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 79736, 'kk_berlistrik_non_pln' => 10353, 'kk_berlistrik_jumlah' => 90089, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 10481, 'rasio_elektrifikasi' => 89.58],
            ['no_urut' => 'III', 'kabupaten_kota' => 'Kutai Kartanegara', 'jumlah_desa' => 237, 'jumlah_kk' => 261201, 'jumlah_penduduk' => 793131, 'desa_berlistrik_pln' => 227, 'desa_berlistrik_non_pln' => 10, 'desa_berlistrik_jumlah' => 237, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 223658, 'kk_berlistrik_non_pln' => 9641, 'kk_berlistrik_jumlah' => 233299, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 27902, 'rasio_elektrifikasi' => 89.32],
            ['no_urut' => 'IV', 'kabupaten_kota' => 'Samarinda', 'jumlah_desa' => 59, 'jumlah_kk' => 288261, 'jumlah_penduduk' => 866499, 'desa_berlistrik_pln' => 59, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 59, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 312525, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 312525, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 0, 'rasio_elektrifikasi' => 100.00],
            ['no_urut' => 'V', 'kabupaten_kota' => 'Kutai Timur', 'jumlah_desa' => 141, 'jumlah_kk' => 149804, 'jumlah_penduduk' => 433327, 'desa_berlistrik_pln' => 115, 'desa_berlistrik_non_pln' => 26, 'desa_berlistrik_jumlah' => 141, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 107268, 'kk_berlistrik_non_pln' => 26308, 'kk_berlistrik_jumlah' => 133576, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 16228, 'rasio_elektrifikasi' => 89.17],
            ['no_urut' => 'VI', 'kabupaten_kota' => 'Bontang', 'jumlah_desa' => 15, 'jumlah_kk' => 61891, 'jumlah_penduduk' => 190621, 'desa_berlistrik_pln' => 15, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 15, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 57319, 'kk_berlistrik_non_pln' => 1123, 'kk_berlistrik_jumlah' => 58442, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3449, 'rasio_elektrifikasi' => 94.43],
            ['no_urut' => 'VII', 'kabupaten_kota' => 'Penajam Paser Utara', 'jumlah_desa' => 54, 'jumlah_kk' => 65407, 'jumlah_penduduk' => 199600, 'desa_berlistrik_pln' => 54, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 54, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 59631, 'kk_berlistrik_non_pln' => 1085, 'kk_berlistrik_jumlah' => 60716, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 4691, 'rasio_elektrifikasi' => 92.83],
            ['no_urut' => 'VIII', 'kabupaten_kota' => 'Paser', 'jumlah_desa' => 144, 'jumlah_kk' => 100027, 'jumlah_penduduk' => 307291, 'desa_berlistrik_pln' => 138, 'desa_berlistrik_non_pln' => 6, 'desa_berlistrik_jumlah' => 144, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 88203, 'kk_berlistrik_non_pln' => 4129, 'kk_berlistrik_jumlah' => 92332, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 7695, 'rasio_elektrifikasi' => 92.31],
            ['no_urut' => 'IX', 'kabupaten_kota' => 'Kutai Barat', 'jumlah_desa' => 194, 'jumlah_kk' => 61391, 'jumlah_penduduk' => 182544, 'desa_berlistrik_pln' => 164, 'desa_berlistrik_non_pln' => 30, 'desa_berlistrik_jumlah' => 194, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 53810, 'kk_berlistrik_non_pln' => 4402, 'kk_berlistrik_jumlah' => 58212, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3179, 'rasio_elektrifikasi' => 94.82],
            ['no_urut' => 'X', 'kabupaten_kota' => 'Mahakam Ulu', 'jumlah_desa' => 50, 'jumlah_kk' => 13418, 'jumlah_penduduk' => 39319, 'desa_berlistrik_pln' => 31, 'desa_berlistrik_non_pln' => 19, 'desa_berlistrik_jumlah' => 50, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 6682, 'kk_berlistrik_non_pln' => 5047, 'kk_berlistrik_jumlah' => 11729, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 1689, 'rasio_elektrifikasi' => 87.41],
        ];

        // Insert data 2024
        foreach ($data2024 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2024], $row));
        }

        $this->command->info('Rekap Elektrifikasi data seeded successfully for 2018-2024!');
    }
}
