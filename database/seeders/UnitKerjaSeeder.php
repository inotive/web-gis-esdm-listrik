<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('unit_kerja')->insert([
            [
                'kode_unit' => 'DISKOMINFO',
                'nama_unit' => 'Dinas Komunikasi dan Informatika',
                'deskripsi' => 'Bertanggung jawab atas pengelolaan teknologi informasi dan komunikasi daerah.'
            ],
            [
                'kode_unit' => 'BPKAD',
                'nama_unit' => 'Badan Pengelola Keuangan dan Aset Daerah',
                'deskripsi' => 'Mengelola keuangan serta inventarisasi aset milik pemerintah daerah.'
            ],
            [
                'kode_unit' => 'DPUPR',
                'nama_unit' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'deskripsi' => 'Menangani bidang infrastruktur dan penataan ruang wilayah.'
            ],
            [
                'kode_unit' => 'DISPERINDAG',
                'nama_unit' => 'Dinas Perindustrian dan Perdagangan',
                'deskripsi' => 'Mengawasi dan mengembangkan kegiatan industri serta perdagangan daerah.'
            ],
            [
                'kode_unit' => 'DLH',
                'nama_unit' => 'Dinas Lingkungan Hidup',
                'deskripsi' => 'Mengelola dan mengawasi kebijakan lingkungan hidup daerah.'
            ],
        ]);
    }
}
