<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            // ProvinsiSeeder::class,
            // KabupatenSeeder::class,
            // KecamatanSeeder::class,
            // KelurahanSeeder::class,
            // KategoriAssetSeeder::class,
            // StatusHukumAssetSeeder::class,
            // UnitKerjaSeeder::class,
            // WilayahSeeder::class,
            // RegProvincesSeeder::class,
            // RegRegenciesSeeder::class,
            // RegDistrictsSeeder::class,
            // RegVillagesSeeder::class,

            // Dokumen Seeders
            DokumenSeeder::class, // New general seeder - imports all from storage/app/public/dokumen/
            RekapElektrifikasiSeeder::class,
            PermohonanSeeder::class,
            DataBerlistrikSeeder::class,
            InfrastrukturPerusahaanSeeder::class,
            PerusahaanSeeder::class,
            PerizinanListrikSeeder::class,
            JalanSeeder::class,
            RencanaPengembanganBantuanSeeder::class,
            // DataJaringanSeeder::class,
            // DataJaringanSeeder::class,
            ImportJsonSeeder::class,
            ImportJsonVideoSeeder::class,
        ]);
    }
}
