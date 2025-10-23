<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegRegenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the reg_regencies table with initial data.
     */
    public function run()
    {
        DB::table('reg_regencies')->insert([
            [6401, 64, 'KAB. PASER'],
            [6402, 64, 'KAB. KUTAI KARTANEGARA'],
            [6403, 64, 'KAB. BERAU'],
            [6407, 64, 'KAB. KUTAI BARAT'],
            [6408, 64, 'KAB. KUTAI TIMUR'],
            [6409, 64, 'KAB. PENAJAM PASER UTARA'],
            [6411, 64, 'KAB. MAHAKAM ULU'],
            [6471, 64, 'KOTA BALIKPAPAN'],
            [6472, 64, 'KOTA SAMARINDA'],
            [6474, 64, 'KOTA BONTANG'],
            [6501, 65, 'KAB. BULUNGAN'],
            [6502, 65, 'KAB. MALINAU'],
            [6503, 65, 'KAB. NUNUKAN'],
            [6504, 65, 'KAB. TANA TIDUNG'],
            [6571, 65, 'KOTA TARAKAN']
        ]);
    }
}