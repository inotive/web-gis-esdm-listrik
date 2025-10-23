<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the reg_provinces table with initial data.
     */
    public function run()
    {
        DB::table('reg_provinces')->insert([
            [64, 'KALIMANTAN TIMUR']
        ]);
    }
}