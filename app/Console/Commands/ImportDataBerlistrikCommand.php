<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use App\Models\DataBerlistrik;

class ImportDataBerlistrikCommand extends Command
{
    protected $signature = 'pln:import-desa-berlistrik
                            {--update : Update data desa jika sudah ada}
                            {--force : Paksa import walau duplikat}
                            {--debug : Tampilkan log detail}';

    protected $description = 'Import data Desa Berlistrik PLN dari file GeoJSON ke table_data_berlistrik';

    private $debugMode = false;

    public function handle()
    {
        // Ubah path ini sesuai lokasi file GeoJSON kamu
        $path = public_path('assets/data_berlistrik.json');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan di: {$path}");
            return 1;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!isset($data['features'])) {
            $this->error("File GeoJSON tidak valid (tidak ada key 'features').");
            return 1;
        }

        $updateMode      = $this->option('update');
        $forceMode       = $this->option('force');
        $this->debugMode = $this->option('debug');

        // Info mode
        if ($updateMode) {
            $this->info("🔄 Mode: UPDATE (data desa yang sudah ada akan di-update)");
        } elseif ($forceMode) {
            $this->info("⚡ Mode: FORCE (duplikat akan dibuat sebagai baris baru)");
        } else {
            $this->info("⏭️  Mode: SKIP (data yang terdeteksi duplikat akan dilewati)");
        }

        if ($this->debugMode) {
            $this->warn("🐛 Debug mode: ON");
        }

        $this->newLine();
        $this->info("Memulai import " . count($data['features']) . " desa berlistrik PLN...");
        $this->newLine();

        $stats = $this->initializeStats();

        foreach ($data['features'] as $index => $feature) {
            try {
                $this->processFeature($feature, $index, $stats, $updateMode, $forceMode);
            } catch (\Exception $e) {
                $this->handleImportError($e, $feature, $index, $stats);
            }
        }

        $this->displaySummary($data, $stats, $updateMode);

        return 0;
    }

    private function initializeStats()
    {
        return [
            'imported'  => 0,
            'updated'   => 0,
            'skipped'   => 0,
            'duplicate' => 0,
        ];
    }

    private function processFeature($feature, $index, &$stats, $updateMode, $forceMode)
    {
        $props = $feature['properties'] ?? [];

        $namaDesa = $props['NAMOBJ'] ?? null;

        if (empty($namaDesa)) {
            $this->warn("  ⚠️  [{$index}] NAMOBJ (nama desa) kosong, dilewati.");
            $stats['skipped']++;
            return;
        }

        // Siapkan data sesuai model DataBerlistrik
        $desaData = $this->prepareDesaData($props);

        $this->saveDesa($desaData, $stats, $updateMode, $forceMode, $props, $index);
    }

    /**
     * Mapping field dari GeoJSON ke kolom table_data_berlistrik
     */
    private function prepareDesaData(array $props): array
    {
        return [
            'NAMOBJ'   => $props['NAMOBJ']   ?? null,
            'LUASWH'   => $props['LUASWH']   ?? null,
            'TIPADM'   => $props['TIPADM']   ?? null,
            'WADMKC'   => $props['WADMKC']   ?? null,
            'WADMKD'   => $props['WADMKD']   ?? null,
            'WADMKK'   => $props['WADMKK']   ?? null,
            'WADMPR'   => $props['WADMPR']   ?? null,
            'H_Survei' => $props['H_Survei'] ?? null,
        ];
    }

    /**
     * Simpan / update ke tabel table_data_berlistrik menggunakan model DataBerlistrik
     */
    private function saveDesa(array $desaData, array &$stats, bool $updateMode, bool $forceMode, array $props, int $index)
    {
        // Kriteria unik: NAMOBJ + WADMKC + WADMKK
        $existing = DataBerlistrik::where('NAMOBJ', $desaData['NAMOBJ'])
            ->where('WADMKC', $desaData['WADMKC'])
            ->where('WADMKK', $desaData['WADMKK'])
            ->first();

        if ($existing) {
            if ($updateMode) {
                $existing->update($desaData);
                $stats['updated']++;

                if ($this->debugMode) {
                    $this->line("  🔄 [{$index}] Update desa: {$desaData['NAMOBJ']}");
                }
            } elseif ($forceMode) {
                DataBerlistrik::create($desaData);
                $stats['imported']++;

                if ($this->debugMode) {
                    $this->line("  ➕ [{$index}] Duplikat dibuat: {$desaData['NAMOBJ']}");
                }
            } else {
                $stats['duplicate']++;
                if ($stats['duplicate'] % 50 == 0 && !$this->debugMode) {
                    $this->line("  ⏭️  Skipped duplicates: {$stats['duplicate']}");
                }
            }
        } else {
            DataBerlistrik::create($desaData);
            $stats['imported']++;

            if ($stats['imported'] % 100 == 0 && !$this->debugMode) {
                $this->line("  📊 Progress: {$stats['imported']} desa diimport");
            }

            if ($this->debugMode) {
                $this->line("  ✅ [{$index}] Import desa: {$desaData['NAMOBJ']}");
            }
        }
    }

    private function handleImportError(\Exception $e, $feature, $index, array &$stats)
    {
        $props = $feature['properties'] ?? [];
        $nama  = $props['NAMOBJ'] ?? 'Unknown';

        if ($e instanceof QueryException) {
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $stats['duplicate']++;
                if ($this->debugMode) {
                    $this->warn("  ⚠️  [{$index}] Duplicate DB: {$nama}");
                }
            } else {
                $this->error("  ❌ [{$index}] DB Error: {$nama}");
                if ($this->debugMode) {
                    $this->error("     Message: " . $e->getMessage());
                }
                $stats['skipped']++;
            }
        } else {
            $this->error("  ❌ [{$index}] Error: {$nama}");
            if ($this->debugMode) {
                $this->error("     Message: " . $e->getMessage());
            }
            $stats['skipped']++;
        }
    }

    private function displaySummary(array $data, array $stats, bool $updateMode)
    {
        $this->newLine();
        $this->info("╔════════════════════════════════════════════╗");
        $this->info("║        IMPORT DESA BERLISTRIK SELESAI      ║");
        $this->info("╚════════════════════════════════════════════╝");

        $this->info("📊 Total data dalam GeoJSON: " . count($data['features']));
        $this->info("✅ Berhasil diimport: {$stats['imported']}");

        if ($updateMode) {
            $this->info("🔄 Berhasil diupdate: {$stats['updated']}");
        }

        if ($stats['duplicate'] > 0) {
            $this->warn("⚠️  Duplicate (dilewati / dibuat tergantung mode): {$stats['duplicate']}");
        }

        $this->info("❌ Error/Skipped: {$stats['skipped']}");

        $this->newLine();

        if ($stats['duplicate'] > 0 && !$updateMode) {
            $this->newLine();
            $this->comment("💡 Tips:");
            $this->line("   • Update data existing: php artisan pln:import-desa-berlistrik --update");
            $this->line("   • Buat duplikat:        php artisan pln:import-desa-berlistrik --force");
            $this->line("   • Debug detail:         php artisan pln:import-desa-berlistrik --debug");
        }
    }
}
