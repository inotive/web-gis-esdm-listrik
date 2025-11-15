<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['tipe_pelanggan' => 'Rumah Tangga', 'jumlah' => 1245, 'daya_tersambung' => '450 VA', 'keterangan' => 'Pelanggan rumah tangga dengan daya 450 VA'],
            ['tipe_pelanggan' => 'Rumah Tangga', 'jumlah' => 980, 'daya_tersambung' => '900 VA', 'keterangan' => 'Pelanggan rumah tangga dengan daya 900 VA'],
            ['tipe_pelanggan' => 'Bisnis Kecil', 'jumlah' => 210, 'daya_tersambung' => '2.200 VA', 'keterangan' => 'Usaha mikro dan kecil'],
            ['tipe_pelanggan' => 'Industri', 'jumlah' => 32, 'daya_tersambung' => '1.000 kVA', 'keterangan' => 'Industri menengah'],
            ['tipe_pelanggan' => 'Pemerintah', 'jumlah' => 58, 'daya_tersambung' => '82 kVA', 'keterangan' => 'Instansi pemerintah'],
            ['tipe_pelanggan' => 'Sosial', 'jumlah' => 143, 'daya_tersambung' => '6.600 VA', 'keterangan' => 'Layanan sosial dan tempat ibadah'],
            ['tipe_pelanggan' => 'Bisnis Menengah', 'jumlah' => 76, 'daya_tersambung' => '197 kVA', 'keterangan' => 'Usaha menengah'],
            ['tipe_pelanggan' => 'Industri Besar', 'jumlah' => 12, 'daya_tersambung' => '5 MVA', 'keterangan' => 'Industri besar'],
            ['tipe_pelanggan' => 'Pertanian', 'jumlah' => 64, 'daya_tersambung' => '23 kVA', 'keterangan' => 'Sektor pertanian dan perikanan'],
            ['tipe_pelanggan' => 'Lainnya', 'jumlah' => 25, 'daya_tersambung' => '3.500 VA', 'keterangan' => 'Kategori lainnya'],
        ];

        foreach ($data as $item) {
            Pelanggan::create($item);
        }
    }
}
