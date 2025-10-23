<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $sqlPath = database_path('seeders/sql/wilayah_indonesia.sql');

            if (!File::exists($sqlPath)) {
                $this->command->error("File tidak ditemukan");
                return;
                
            }

            $this->command->info('Membaca file SQL...');
            $sql = File::get($sqlPath);
            

            // Normalize line endings
            $sql = str_replace(["\r\n", "\r"], "\n", $sql);

            // Drop dan Create Tables
            $this->createTables($sql);

            // Seed data untuk setiap tabel
            $this->seedProvinces($sql);
            $this->seedRegencies($sql);
            $this->seedDistricts($sql);
            $this->seedVillages($sql);

            // Show results
            $this->showResults();

        } catch (\Exception $e) {
            $this->command->error('Error: ' . $e->getMessage());
            $this->command->error('Trace: ' . $e->getTraceAsString());
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function createTables(string $sql): void
    {
        $this->command->info('Creating tables...');

        $tables = ['reg_provinces', 'reg_regencies', 'reg_districts', 'reg_villages'];

        foreach ($tables as $table) {
            // Drop table
            DB::unprepared("DROP TABLE IF EXISTS `{$table}`");

            // Extract CREATE TABLE statement
            if (preg_match("/CREATE TABLE `{$table}`\s*\((.*?)\)\s*ENGINE=InnoDB[^;]*;/s", $sql, $match)) {
                DB::unprepared($match[0]);
                $this->command->info("✓ Table {$table} created");
            }
        }

        $this->command->newLine();
    }

    private function seedProvinces(string $sql): void
    {
        $this->command->info('Seeding reg_provinces...');

        // Extract INSERT statement untuk provinces
        if (preg_match("/INSERT INTO `reg_provinces` VALUES\s*(.*?)(?=\n*SET NAMES|DROP TABLE|CREATE TABLE)/s", $sql, $match)) {
            $values = trim($match[1]);
            
            // Parse rows
            preg_match_all("/\('([^']+)',\s*'([^']+)'\)/", $values, $rows, PREG_SET_ORDER);

            $data = [];
            foreach ($rows as $row) {
                $data[] = [
                    'id' => $row[1],
                    'name' => $row[2]
                ];
            }

            if (!empty($data)) {
                DB::table('reg_provinces')->insert($data);
                $this->command->info("✓ " . count($data) . " provinces inserted");
            }
        }

        $this->command->newLine();
    }

    private function seedRegencies(string $sql): void
    {
        $this->command->info('Seeding reg_regencies...');

        // Extract section untuk regencies - cari dari DROP TABLE sampai INSERT VALUES
        if (preg_match("/DROP TABLE IF EXISTS `reg_regencies`;.*?INSERT INTO `reg_regencies` VALUES(.*?)(?=SET NAMES|DROP TABLE IF EXISTS `reg_districts`)/s", $sql, $section)) {
            $valuesSection = $section[1];
            
            // Parse semua baris data
            preg_match_all("/\('(\d+)',\s*'(\d+)',\s*'([^']+)'\)/", $valuesSection, $rows, PREG_SET_ORDER);

            $data = [];
            $bar = $this->command->getOutput()->createProgressBar(count($rows));
            $bar->start();

            foreach ($rows as $row) {
                $data[] = [
                    'id' => $row[1],
                    'province_id' => $row[2],
                    'name' => $row[3]
                ];

                // Insert per 500 rows untuk menghindari memory issue
                if (count($data) >= 500) {
                    DB::table('reg_regencies')->insert($data);
                    $data = [];
                }
                
                $bar->advance();
            }

            // Insert sisa data
            if (!empty($data)) {
                DB::table('reg_regencies')->insert($data);
            }

            $bar->finish();
            $this->command->newLine();
            $this->command->info("✓ Regencies inserted");
        } else {
            $this->command->warn("Regencies data not found in SQL");
        }

        $this->command->newLine();
    }

    private function seedDistricts(string $sql): void
    {
        $this->command->info('Seeding reg_districts...');

        // Extract section untuk districts
        if (preg_match("/DROP TABLE IF EXISTS `reg_districts`;.*?INSERT INTO `reg_districts` VALUES(.*?)(?=SET NAMES|DROP TABLE IF EXISTS `reg_villages`)/s", $sql, $section)) {
            $valuesSection = $section[1];
            
            // Parse semua baris data
            preg_match_all("/\('(\d+)',\s*'(\d+)',\s*'([^']+)'\)/", $valuesSection, $rows, PREG_SET_ORDER);

            $data = [];
            $bar = $this->command->getOutput()->createProgressBar(count($rows));
            $bar->start();

            foreach ($rows as $row) {
                $data[] = [
                    'id' => $row[1],
                    'regency_id' => $row[2],
                    'name' => $row[3]
                ];

                if (count($data) >= 1000) {
                    DB::table('reg_districts')->insert($data);
                    $data = [];
                }
                
                $bar->advance();
            }

            if (!empty($data)) {
                DB::table('reg_districts')->insert($data);
            }

            $bar->finish();
            $this->command->newLine();
            $this->command->info("✓ Districts inserted");
        } else {
            $this->command->warn("Districts data not found in SQL");
        }

        $this->command->newLine();
    }

    private function seedVillages(string $sql): void
    {
        $this->command->info('Seeding reg_villages...');

        // Extract section untuk villages - sampai akhir file
        if (preg_match("/INSERT INTO `reg_villages` VALUES(.*)/s", $sql, $section)) {
            $valuesSection = $section[1];
            
            // Parse semua baris data
            preg_match_all("/\('(\d+)',\s*'(\d+)',\s*'([^']+)'\)/", $valuesSection, $rows, PREG_SET_ORDER);

            $data = [];
            $bar = $this->command->getOutput()->createProgressBar(count($rows));
            $bar->start();

            foreach ($rows as $row) {
                $data[] = [
                    'id' => $row[1],
                    'district_id' => $row[2],
                    'name' => $row[3]
                ];

                if (count($data) >= 1000) {
                    DB::table('reg_villages')->insert($data);
                    $data = [];
                }
                
                $bar->advance();
            }

            if (!empty($data)) {
                DB::table('reg_villages')->insert($data);
            }

            $bar->finish();
            $this->command->newLine();
            $this->command->info("✓ Villages inserted");
        } else {
            $this->command->warn("Villages data not found in SQL");
        }

        $this->command->newLine();
    }

    private function showResults(): void
    {
        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('           SEEDING COMPLETED           ');
        $this->command->info('═══════════════════════════════════════');
        $this->command->newLine();

        $data = [
            ['Provinsi (reg_provinces)', DB::table('reg_provinces')->count()],
            ['Kabupaten/Kota (reg_regencies)', DB::table('reg_regencies')->count()],
            ['Kecamatan (reg_districts)', DB::table('reg_districts')->count()],
            ['Desa/Kelurahan (reg_villages)', DB::table('reg_villages')->count()],
        ];

        $this->command->table(['Tabel', 'Jumlah Data'], $data);

        // Validasi
        $allGood = true;
        foreach ($data as $row) {
            if ($row[1] == 0) {
                $this->command->error("⚠ {$row[0]} tidak memiliki data!");
                $allGood = false;
            }
        }

        if ($allGood) {
            $this->command->info('✓ Semua data berhasil di-seed dengan lengkap!');
        }
    }
}