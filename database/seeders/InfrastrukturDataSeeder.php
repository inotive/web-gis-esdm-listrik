<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Gardu;
use App\Models\PembangkitLokal;
use App\Models\InfrastrukturJaringan;
use App\Models\Perusahaan;

class InfrastrukturDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting infrastructure data migration...');

        // 1. MIGRATE GARDU DATA
        $this->migrateGarduData();

        // 2. MIGRATE PEMBANGKIT DATA
        $this->migratePembangkitData();

        // 3. MIGRATE JARINGAN DATA
        $this->migrateJaringanData();

        // 4. CREATE PERUSAHAAN DATA
        $this->createPerusahaanData();

        $this->command->info('Infrastructure data migration completed!');
    }

    /**
     * Migrate data gardu dari table__p_t__gardu__berau ke gardus
     */
    private function migrateGarduData()
    {
        $this->command->info('Migrating Gardu data...');

        // Truncate existing data
        Gardu::truncate();

        // Get data from source table
        $sourceData = DB::table('table__p_t__gardu__berau')->get();

        $count = 0;
        foreach ($sourceData as $item) {
            Gardu::create([
                'nama' => $item->descriptio ?? 'Gardu ' . ($count + 1),
                'lokasi' => $item->streetaddr ?? $item->formatteda ?? $item->city ?? '-',
                'jenis_gardu_distribusi' => $item->type_gardu ?? 'Tidak Diketahui',
                'wilayah_id' => null, // Will be filled later when wilayah data is available
            ]);
            $count++;
        }

        $this->command->info("✓ Migrated {$count} Gardu records");
    }

    /**
     * Migrate data pembangkit dari table__p_t__pembangkit__eksisting ke pembangkit_lokals
     */
    private function migratePembangkitData()
    {
        $this->command->info('Migrating Pembangkit Lokal data...');

        // Truncate existing data
        PembangkitLokal::truncate();

        // Get data from source table
        $sourceData = DB::table('table__p_t__pembangkit__eksisting')->get();

        $count = 0;
        foreach ($sourceData as $item) {
            // Extract kapasitas dari remark jika ada
            $kapasitas = null;
            if ($item->remark) {
                // Try to extract number from remark (e.g., "PLTG 100 MW")
                preg_match('/(\d+(?:\.\d+)?)\s*(?:MW|KW|kW)/i', $item->remark, $matches);
                if (!empty($matches[1])) {
                    $kapasitas = $matches[1];
                }
            }

            PembangkitLokal::create([
                'wilayah_id' => null, // Will be filled later when wilayah data is available
                'kapasitas_gardu' => $kapasitas ?? rand(10, 500), // Random kapasitas if not found
            ]);
            $count++;
        }

        $this->command->info("✓ Migrated {$count} Pembangkit Lokal records");
    }

    /**
     * Migrate data jaringan dari berbagai tabel ke infrastruktur_jaringan
     */
    private function migrateJaringanData()
    {
        $this->command->info('Migrating Jaringan data...');

        // Truncate existing data
        InfrastrukturJaringan::truncate();

        $totalCount = 0;

        // Jaringan dari table__l_n__sutm__berau
        $this->command->info('  - Processing SUTM Berau...');
        $sutmBerau = DB::table('table__l_n__sutm__berau')->get();
        foreach ($sutmBerau as $item) {
            InfrastrukturJaringan::create([
                'jaringan' => 'distribusi',
                'jenis' => 'SUTM - Berau',
                'panjang_jaringan' => $item->panjang_ha ?? ($item->shape_leng ? round($item->shape_leng / 1000, 2) : 0),
            ]);
            $totalCount++;
        }

        // Jaringan dari table__l_n__sutm__ppu
        $this->command->info('  - Processing SUTM PPU...');
        $sutmPpu = DB::table('table__l_n__sutm__ppu')->get();
        foreach ($sutmPpu as $item) {
            InfrastrukturJaringan::create([
                'jaringan' => 'distribusi',
                'jenis' => 'SUTM - PPU',
                'panjang_jaringan' => $item->panjang ?? ($item->shape_leng ? round($item->shape_leng / 1000, 2) : 0),
            ]);
            $totalCount++;
        }

        // Jaringan dari table__l_n__sistem__jaringan__energi__kubar
        $this->command->info('  - Processing Jaringan Kubar...');
        $jaringanKubar = DB::table('table__l_n__sistem__jaringan__energi__kubar')->get();
        foreach ($jaringanKubar as $item) {
            InfrastrukturJaringan::create([
                'jaringan' => 'distribusi',
                'jenis' => 'Jaringan Energi - Kubar',
                'panjang_jaringan' => $item->shape_leng ? round($item->shape_leng / 1000, 2) : rand(1, 50),
            ]);
            $totalCount++;
        }

        // Jaringan dari table__l_n__sistem__jaringan__energi__paser (sample saja karena banyak)
        $this->command->info('  - Processing Jaringan Paser (sampling 500 records)...');
        $jaringanPaser = DB::table('table__l_n__sistem__jaringan__energi__paser')->limit(500)->get();
        foreach ($jaringanPaser as $item) {
            InfrastrukturJaringan::create([
                'jaringan' => 'distribusi',
                'jenis' => 'Jaringan Energi - Paser',
                'panjang_jaringan' => $item->shape_leng ? round($item->shape_leng / 1000, 2) : rand(1, 50),
            ]);
            $totalCount++;
        }

        $this->command->info("✓ Migrated {$totalCount} Jaringan records");
    }

    /**
     * Create sample perusahaan data
     */
    private function createPerusahaanData()
    {
        $this->command->info('Creating Perusahaan data...');

        // Delete existing data (use delete instead of truncate due to FK constraints)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Perusahaan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $perusahaanData = [
            [
                'nama' => 'PT PLN (Persero) ULP Balikpapan',
                'alamat' => 'Jl. Jenderal Sudirman No. 1, Balikpapan',
                'kontak' => '0542-123456',
                'jenis_usaha' => 'Penyedia Listrik',
                'kabupaten_kota' => 'Balikpapan',
            ],
            [
                'nama' => 'PT PLN (Persero) ULP Samarinda',
                'alamat' => 'Jl. Pahlawan No. 45, Samarinda',
                'kontak' => '0541-654321',
                'jenis_usaha' => 'Penyedia Listrik',
                'kabupaten_kota' => 'Samarinda',
            ],
            [
                'nama' => 'PT PLN (Persero) ULP Berau',
                'alamat' => 'Jl. Garuda, Tanjung Redeb',
                'kontak' => '0554-111222',
                'jenis_usaha' => 'Penyedia Listrik',
                'kabupaten_kota' => 'Berau',
            ],
            [
                'nama' => 'PT PLN (Persero) ULP Kutai Kartanegara',
                'alamat' => 'Jl. DI Panjaitan, Tenggarong',
                'kontak' => '0541-998877',
                'jenis_usaha' => 'Penyedia Listrik',
                'kabupaten_kota' => 'Kutai Kartanegara',
            ],
            [
                'nama' => 'PT PLN (Persero) ULP Paser',
                'alamat' => 'Jl. Ahmad Yani, Tanah Grogot',
                'kontak' => '0543-445566',
                'jenis_usaha' => 'Penyedia Listrik',
                'kabupaten_kota' => 'Paser',
            ],
            [
                'nama' => 'PT PLN (Persero) ULP Penajam Paser Utara',
                'alamat' => 'Jl. Penajam - Balikpapan',
                'kontak' => '0542-776655',
                'jenis_usaha' => 'Penyedia Listrik',
                'kabupaten_kota' => 'Penajam Paser Utara',
            ],
            [
                'nama' => 'PT Energi Kaltim Prima',
                'alamat' => 'Jl. Industri No. 88, Bontang',
                'kontak' => '0548-334455',
                'jenis_usaha' => 'Pembangkit Listrik Swasta',
                'kabupaten_kota' => 'Bontang',
            ],
            [
                'nama' => 'PT Kutai Energi',
                'alamat' => 'Jl. Pembangunan, Kutai Barat',
                'kontak' => '0545-223344',
                'jenis_usaha' => 'Pembangkit Listrik Swasta',
                'kabupaten_kota' => 'Kutai Barat',
            ],
        ];

        foreach ($perusahaanData as $data) {
            Perusahaan::create($data);
        }

        $this->command->info("✓ Created " . count($perusahaanData) . " Perusahaan records");
    }
}
