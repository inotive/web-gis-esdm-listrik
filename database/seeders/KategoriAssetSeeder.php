<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriAssetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori_asset')->insert([
            [
                'kode' => 'TAN001',
                'name' => 'Tanah',
                'deskripsi' => 'Aset berupa bidang tanah milik pemerintah daerah atau instansi terkait.'
            ],
            [
                'kode' => 'BANG001',
                'name' => 'Bangunan',
                'deskripsi' => 'Aset berupa gedung, kantor, atau bangunan lain yang digunakan untuk kegiatan operasional.'
            ],
            [
                'kode' => 'KND001',
                'name' => 'Kendaraan',
                'deskripsi' => 'Aset berupa kendaraan dinas seperti mobil, motor, atau alat transportasi operasional.'
            ],
            [
                'kode' => 'INF001',
                'name' => 'Infrastruktur',
                'deskripsi' => 'Aset berupa jalan, jembatan, jaringan, atau fasilitas publik lainnya.'
            ],
            [
                'kode' => 'PER001',
                'name' => 'Peralatan dan Mesin',
                'deskripsi' => 'Aset berupa peralatan, mesin, atau perlengkapan kerja lainnya.'
            ],
        ]);
    }
}
