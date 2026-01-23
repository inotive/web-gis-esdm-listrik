<?php

namespace Database\Seeders;

use App\Models\RekapElektrifikasi;
use Illuminate\Database\Seeder;

class RekapElektrifikasi2024UpdatedSeeder extends Seeder
{
    /**
     * Seed data tahun 2024 yang sudah disesuaikan dengan DataBerlistrikSeeder
     */
    public function run(): void
    {
        // Data 2024 yang disesuaikan dengan distribusi baru
        $data2024 = [
            ['no_urut' => 'I', 'kabupaten_kota' => 'Balikpapan', 'jumlah_desa' => 34, 'jumlah_kk' => 256651, 'jumlah_penduduk' => 746804, 'desa_berlistrik_pln' => 34, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 34, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 255213, 'kk_berlistrik_non_pln' => 471, 'kk_berlistrik_jumlah' => 255684, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 967, 'rasio_elektrifikasi' => 99.62],
            
            ['no_urut' => 'II', 'kabupaten_kota' => 'Berau', 'jumlah_desa' => 113, 'jumlah_kk' => 100570, 'jumlah_penduduk' => 288943, 'desa_berlistrik_pln' => 89, 'desa_berlistrik_non_pln' => 19, 'desa_berlistrik_jumlah' => 108, 'desa_belum_berlistrik' => 5, 'kk_berlistrik_pln' => 79736, 'kk_berlistrik_non_pln' => 10353, 'kk_berlistrik_jumlah' => 90089, 'rasio_desa_berlistrik' => 95.58, 'jumlah_kk_belum_berlistrik' => 10481, 'rasio_elektrifikasi' => 89.58],
            
            ['no_urut' => 'III', 'kabupaten_kota' => 'Kutai Kartanegara', 'jumlah_desa' => 240, 'jumlah_kk' => 261201, 'jumlah_penduduk' => 793131, 'desa_berlistrik_pln' => 225, 'desa_berlistrik_non_pln' => 10, 'desa_berlistrik_jumlah' => 235, 'desa_belum_berlistrik' => 5, 'kk_berlistrik_pln' => 223658, 'kk_berlistrik_non_pln' => 9641, 'kk_berlistrik_jumlah' => 233299, 'rasio_desa_berlistrik' => 97.92, 'jumlah_kk_belum_berlistrik' => 27902, 'rasio_elektrifikasi' => 89.32],
            
            ['no_urut' => 'IV', 'kabupaten_kota' => 'Samarinda', 'jumlah_desa' => 59, 'jumlah_kk' => 288261, 'jumlah_penduduk' => 866499, 'desa_berlistrik_pln' => 59, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 59, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 312525, 'kk_berlistrik_non_pln' => 0, 'kk_berlistrik_jumlah' => 312525, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 0, 'rasio_elektrifikasi' => 100.00],
            
            ['no_urut' => 'V', 'kabupaten_kota' => 'Kutai Timur', 'jumlah_desa' => 148, 'jumlah_kk' => 149804, 'jumlah_penduduk' => 433327, 'desa_berlistrik_pln' => 113, 'desa_berlistrik_non_pln' => 26, 'desa_berlistrik_jumlah' => 139, 'desa_belum_berlistrik' => 9, 'kk_berlistrik_pln' => 107268, 'kk_berlistrik_non_pln' => 26308, 'kk_berlistrik_jumlah' => 133576, 'rasio_desa_berlistrik' => 93.92, 'jumlah_kk_belum_berlistrik' => 16228, 'rasio_elektrifikasi' => 89.17],
            
            ['no_urut' => 'VI', 'kabupaten_kota' => 'Bontang', 'jumlah_desa' => 15, 'jumlah_kk' => 61891, 'jumlah_penduduk' => 190621, 'desa_berlistrik_pln' => 15, 'desa_berlistrik_non_pln' => 0, 'desa_berlistrik_jumlah' => 15, 'desa_belum_berlistrik' => 0, 'kk_berlistrik_pln' => 57319, 'kk_berlistrik_non_pln' => 1123, 'kk_berlistrik_jumlah' => 58442, 'rasio_desa_berlistrik' => 100.00, 'jumlah_kk_belum_berlistrik' => 3449, 'rasio_elektrifikasi' => 94.43],
            
            ['no_urut' => 'VII', 'kabupaten_kota' => 'Penajam Paser Utara', 'jumlah_desa' => 57, 'jumlah_kk' => 65407, 'jumlah_penduduk' => 199600, 'desa_berlistrik_pln' => 52, 'desa_berlistrik_non_pln' => 2, 'desa_berlistrik_jumlah' => 54, 'desa_belum_berlistrik' => 3, 'kk_berlistrik_pln' => 59631, 'kk_berlistrik_non_pln' => 1085, 'kk_berlistrik_jumlah' => 60716, 'rasio_desa_berlistrik' => 94.74, 'jumlah_kk_belum_berlistrik' => 4691, 'rasio_elektrifikasi' => 92.83],
            
            ['no_urut' => 'VIII', 'kabupaten_kota' => 'Paser', 'jumlah_desa' => 150, 'jumlah_kk' => 100027, 'jumlah_penduduk' => 307291, 'desa_berlistrik_pln' => 136, 'desa_berlistrik_non_pln' => 8, 'desa_berlistrik_jumlah' => 144, 'desa_belum_berlistrik' => 6, 'kk_berlistrik_pln' => 88203, 'kk_berlistrik_non_pln' => 4129, 'kk_berlistrik_jumlah' => 92332, 'rasio_desa_berlistrik' => 96.00, 'jumlah_kk_belum_berlistrik' => 7695, 'rasio_elektrifikasi' => 92.31],
            
            ['no_urut' => 'IX', 'kabupaten_kota' => 'Kutai Barat', 'jumlah_desa' => 204, 'jumlah_kk' => 61391, 'jumlah_penduduk' => 182544, 'desa_berlistrik_pln' => 160, 'desa_berlistrik_non_pln' => 32, 'desa_berlistrik_jumlah' => 192, 'desa_belum_berlistrik' => 12, 'kk_berlistrik_pln' => 53810, 'kk_berlistrik_non_pln' => 4402, 'kk_berlistrik_jumlah' => 58212, 'rasio_desa_berlistrik' => 94.12, 'jumlah_kk_belum_berlistrik' => 3179, 'rasio_elektrifikasi' => 94.82],
            
            ['no_urut' => 'X', 'kabupaten_kota' => 'Mahakam Ulu', 'jumlah_desa' => 60, 'jumlah_kk' => 13418, 'jumlah_penduduk' => 39319, 'desa_berlistrik_pln' => 28, 'desa_berlistrik_non_pln' => 20, 'desa_berlistrik_jumlah' => 48, 'desa_belum_berlistrik' => 12, 'kk_berlistrik_pln' => 6682, 'kk_berlistrik_non_pln' => 5047, 'kk_berlistrik_jumlah' => 11729, 'rasio_desa_berlistrik' => 80.00, 'jumlah_kk_belum_berlistrik' => 1689, 'rasio_elektrifikasi' => 87.41],
        ];

        // Insert data 2024
        foreach ($data2024 as $row) {
            RekapElektrifikasi::create(array_merge(['tahun' => 2024], $row));
        }

        $this->command->info('Data Rekap Elektrifikasi 2024 (updated) berhasil di-seed!');
    }
}
