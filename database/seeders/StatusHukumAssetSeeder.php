<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusHukumAssetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('status_hukum_asset')->insert([
            [
                'name' => 'Bersertifikat',
                'deskripsi' => 'Aset telah memiliki sertifikat resmi atas nama instansi pemerintah.'
            ],
            [
                'name' => 'Belum Bersertifikat',
                'deskripsi' => 'Aset belum memiliki dokumen sertifikat kepemilikan yang sah.'
            ],
            [
                'name' => 'Dalam Sengketa',
                'deskripsi' => 'Aset sedang dalam proses sengketa hukum atau klaim kepemilikan.'
            ],
            [
                'name' => 'Sewa',
                'deskripsi' => 'Aset bukan milik instansi, namun digunakan dengan perjanjian sewa menyewa.'
            ],
            [
                'name' => 'Hibah',
                'deskripsi' => 'Aset yang diterima dari pihak lain sebagai hibah atau donasi resmi.'
            ],
        ]);
    }
}
