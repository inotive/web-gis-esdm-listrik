<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class importJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:json-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all JSON data files (Desa Berlistrik, Jalan, Jaringan Listrik, Administrasi, Infrastruktur)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║     MULAI IMPORT SEMUA DATA JSON                         ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $commands = $this->getImportCommands();
        $total = count($commands);
        $success = 0;
        $failed = 0;
        $failedCommands = [];

        foreach ($commands as $index => $command) {
            $current = $index + 1;
            $this->info("[$current/$total] Menjalankan: {$command['name']}");

            try {
                $exitCode = Artisan::call($command['signature'], $command['options']);

                if ($exitCode === 0) {
                    $this->info("  ✅ Berhasil: {$command['name']}");
                    $success++;
                } else {
                    $this->error("  ❌ Gagal: {$command['name']} (Exit code: {$exitCode})");
                    $failed++;
                    $failedCommands[] = $command['name'];
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Error: {$command['name']}");
                $this->error("     Message: " . $e->getMessage());
                $failed++;
                $failedCommands[] = $command['name'];
            }

            $this->newLine();
        }

        // Summary
        $this->newLine();
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                    RINGKASAN IMPORT                       ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info("Total perintah: {$total}");
        $this->info("✅ Berhasil: {$success}");
        $this->info("❌ Gagal: {$failed}");

        if (!empty($failedCommands)) {
            $this->newLine();
            $this->warn('Perintah yang gagal:');
            foreach ($failedCommands as $cmd) {
                $this->warn("  • {$cmd}");
            }
        }

        $this->newLine();

        return $failed > 0 ? 1 : 0;
    }

    /**
     * Get all import commands with their options
     *
     * @return array
     */
    private function getImportCommands(): array
    {
        return [
            // Desa Berlistrik PLN
            [
                'name' => 'Desa Berlistrik PLN',
                'signature' => 'pln:import-desa-berlistrik',
                'options' => ['--file' => 'assets/data_berlistrik.json']
            ],

            // Jalan
            [
                'name' => 'Jalan Nasional',
                'signature' => 'datajalan:import',
                'options' => ['--file' => 'assets/Jalan/Jalan_Nasional.json']
            ],
            [
                'name' => 'Jalan Provinsi',
                'signature' => 'datajalanprov:import',
                'options' => ['--file' => 'assets/Jalan/Provinsi.json']
            ],
            [
                'name' => 'Jalan Balikpapan',
                'signature' => 'import:jalan-balikpapan',
                'options' => []
            ],
            [
                'name' => 'Jalan Berau',
                'signature' => 'import:jalan-berau',
                'options' => []
            ],
            [
                'name' => 'Jalan Bontang',
                'signature' => 'import:jalan-bontang',
                'options' => []
            ],
            [
                'name' => 'Jalan Kubar',
                'signature' => 'import:jalan-kubar',
                'options' => []
            ],
            [
                'name' => 'Jalan Kutai Kartanegara',
                'signature' => 'import:jalan-kutai-kartanegara',
                'options' => []
            ],
            [
                'name' => 'Jalan Kutim',
                'signature' => 'import:jalan-kutim',
                'options' => []
            ],
            [
                'name' => 'Jalan Paser',
                'signature' => 'import:jalan-paser',
                'options' => []
            ],
            [
                'name' => 'Jalan PPU',
                'signature' => 'import:jalan-ppu',
                'options' => []
            ],
            [
                'name' => 'Jalan Samarinda',
                'signature' => 'import:jalan-samarinda',
                'options' => []
            ],

            // Jaringan Listrik
            [
                'name' => 'Jaringan Listrik Balikpapan',
                'signature' => 'jaringan:bpn-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_Jaringan_Listrik_Balikpapan.json']
            ],
            [
                'name' => 'Rencana Jaringan Listrik Bontang',
                'signature' => 'jaringan:bontang-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_Rencana_Jaringan_Listrik_Bontang.json']
            ],
            [
                'name' => 'Kukar (SUTT)',
                'signature' => 'sistem-energi:kukar-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_Sistem_Jaringan_Energi_Kukar.json']
            ],
            [
                'name' => 'Mahulu (SUTR)',
                'signature' => 'sistem-energi:mahulu-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_Sistem_Jaringan_Energi_Mahulu.json']
            ],
            [
                'name' => 'Kubar (SUTM)',
                'signature' => 'sistem-energi:kubar-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_SUTM_Kubar.json']
            ],
            [
                'name' => 'Kubar UP2KB (SUTM)',
                'signature' => 'sistem-energi:kubar-up2kb-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_SUTM_Kubar_UP2KB.json']
            ],
            [
                'name' => 'Kutim (SUTM)',
                'signature' => 'sistem-energi:kutim-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_SUTM_Kutim.json']
            ],
            [
                'name' => 'Paser (SUTM)',
                'signature' => 'sistem-energi:paser-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_SUTM_Paser.json']
            ],
            [
                'name' => 'SUTM PPU',
                'signature' => 'sutm:ppu-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_SUTM_PPU.json']
            ],
            [
                'name' => 'SUTR Kutim',
                'signature' => 'sutr:kutim-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_SUTR_Kutim.json']
            ],
            [
                'name' => 'SUTM Berau',
                'signature' => 'sutm:berau-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_SUTM_Berau.json']
            ],
            [
                'name' => 'Transmisi',
                'signature' => 'transmisi:import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN_Transmisi.json']
            ],
            [
                'name' => 'LN2 SUTM Paser',
                'signature' => 'sutm2:paser-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN2_SUTM_Paser.json']
            ],
            [
                'name' => 'LN2 SUTM PPU',
                'signature' => 'sutm2:ppu-import',
                'options' => ['--file' => 'assets/Jaringan-Listrik/LN2_SUTM_PPU.json']
            ],

            // Administrasi
            [
                'name' => 'AR Batas Kaltim Full',
                'signature' => 'batas-kaltim:import',
                'options' => ['--file' => 'assets/Administrasi/AR_BATAS_KALTIM_FULL_KK_KC_KD.json']
            ],
            [
                'name' => 'AR Batas Kaltim Kabupaten/Kota',
                'signature' => 'batas-kaltim:kabkota-import',
                'options' => ['--file' => 'assets/Administrasi/AR_BATAS_KALTIM_KABUPATEN_KOTA.json']
            ],
            [
                'name' => 'AR Batas Kaltim Kecamatan',
                'signature' => 'batas-kaltim:kecamatan-import',
                'options' => ['--file' => 'assets/Administrasi/AR_BATAS_KALTIM_KK_KECAMATAN.json']
            ],
            [
                'name' => 'LN Batas Desa',
                'signature' => 'ln-batas-desa:import',
                'options' => ['--file' => 'assets/Administrasi/LN_BATAS_DESA.json']
            ],
            [
                'name' => 'LN Batas Kabupaten/Kota',
                'signature' => 'ln-batas-kabkota:import',
                'options' => ['--file' => 'assets/Administrasi/LN_BATAS_KABUPATENKOTA.json']
            ],
            [
                'name' => 'LN Batas Kecamatan',
                'signature' => 'ln-batas-kecamatan:import',
                'options' => ['--file' => 'assets/Administrasi/LN_BATAS_KECAMATAN.json']
            ],
            [
                'name' => 'LN Batas Negara',
                'signature' => 'ln-batas-negara:import',
                'options' => ['--file' => 'assets/Administrasi/LN_BATAS_NEGARA.json']
            ],
            [
                'name' => 'LN Batas Provinsi',
                'signature' => 'ln-batas-provinsi:import',
                'options' => ['--file' => 'assets/Administrasi/LN_BATAS_PROVINSI.json']
            ],

            // Infrastruktur
            [
                'name' => 'PT Gardu Berau',
                'signature' => 'pt-gardu-berau:import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Gardu_Berau.json']
            ],
            [
                'name' => 'PT Gardu Distribusi Kutim',
                'signature' => 'pt-gardu-distribusi-kutim:import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Gardu_Distribusi_Kutim.json']
            ],
            [
                'name' => 'PT Gardu Hubung Kutim',
                'signature' => 'pt-gardu-hubung-kutim:import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Gardu_Hubung_Kutim.json']
            ],
            [
                'name' => 'PT Gardu Induk Kutim',
                'signature' => 'pt-gardu-induk-kutim:import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Gardu_Induk_Kutim.json']
            ],
            [
                'name' => 'PT Pembangkit Eksisting',
                'signature' => 'pt-pembangkit-eksisting:import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Pembangkit_Eksisting.json']
            ],
            [
                'name' => 'PT Rencana Pembangkit Bontang',
                'signature' => 'pt-rencana-pembangkit-bontang:import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang.json']
            ],
            [
                'name' => 'PT Sistem Energi Balikpapan',
                'signature' => 'pt-sistem-energi-balikpapan:import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Sistem_Infrastruktur_Energi_Balikpapan.json']
            ],
            [
                'name' => 'PT Energi Kukar',
                'signature' => 'pt-energi:kukar-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Sistem_Infrastruktur_Energi_Kukar.json']
            ],
            [
                'name' => 'PT Energi Mahulu',
                'signature' => 'pt-energi:mahulu-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Sistem_Infrastruktur_Energi_Mahulu.json']
            ],
            [
                'name' => 'PT Energi Samarinda',
                'signature' => 'pt-energi:samarinda-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Sistem_Infrastruktur_Energi_Samarinda.json']
            ],
            [
                'name' => 'PT Trafo Berau',
                'signature' => 'pt-trafo:berau-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Trafo_Berau.json']
            ],
            [
                'name' => 'PT Trafo Gardu Distribusi PPU',
                'signature' => 'pt-trafo:gardu-distribusi-ppu-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Trafo_Gardu_Distribusi_PPU.json']
            ],
            [
                'name' => 'PT Trafo Gardu Kubar',
                'signature' => 'pt-trafo:gardu-kubar-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT_Trafo_Gardu_Kubar.json']
            ],
            [
                'name' => 'PT1 Trafo Gardu Paser',
                'signature' => 'pt1-trafo:gardu-paser-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT1_Trafo_Gardu_Paser.json']
            ],
            [
                'name' => 'PT2 Trafo Gardu Paser',
                'signature' => 'pt2-trafo:gardu-paser-import',
                'options' => ['--file' => 'assets/Infrastruktur/PT2_Trafo_Gardu_Paser.json']
            ],
        ];
    }
}
