<?php

namespace App\Console\Commands;

use App\Models\LN_Batas_Kecamatan;
use Illuminate\Console\Command;

class ImportLnBatasKecamatanCommand extends Command
{
    protected $signature = 'ln-batas-kecamatan:import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data LN BATAS KECAMATAN dari file GeoJSON ke tabel table__l_n__batas__kecamatan';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Administrasi/LN_BATAS_KECAMATAN.json"');
            return 1;
        }

        $fileOpt = str_replace(['\\'], '/', $fileOpt);

        if (str_starts_with($fileOpt, 'public/')) {
            $fileOpt = substr($fileOpt, strlen('public/'));
        }

        $path = public_path($fileOpt);

        if (!file_exists($path)) {
            $this->error("File GeoJSON tidak ditemukan di: {$path}");
            return 1;
        }

        $this->info("Membaca file: {$path}");

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data) || !isset($data['features']) || !is_array($data['features'])) {
            $this->error('File GeoJSON tidak valid (tidak ada key "features").');
            return 1;
        }

        $this->info('Mengosongkan tabel table__l_n__batas__kecamatan sebelum import ...');
        LN_Batas_Kecamatan::truncate();

        $this->info('Memulai import data LN BATAS KECAMATAN ...');

        $imported = 0;
        $skipped  = 0;

        foreach ($data['features'] as $index => $feature) {
            $props = $feature['properties'] ?? [];
            $geom  = $feature['geometry']   ?? null;

            if (!$geom || !isset($geom['type'])) {
                $this->warn("  [{$index}] Melewati feature tanpa geometry yang valid.");
                $skipped++;
                continue;
            }

            $fidArBat  = $props['FID_AR_BAT'] ?? null;
            $wadmpr    = $props['WADMPR']     ?? null;
            $wadmkk    = $props['WADMKK']     ?? null;
            $wadmkc    = $props['WADMKC']     ?? null;
            $shapeLeng = $props['Shape_Leng'] ?? null;

            if ($fidArBat !== null) {
                $fidArBat = (int) $fidArBat;
            }
            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }

            $model = new LN_Batas_Kecamatan();
            $model->fid_ar_bat = $fidArBat;
            $model->wadmpr     = $wadmpr;
            $model->wadmkk     = $wadmkk;
            $model->wadmkc     = $wadmkc;
            $model->shape_leng = $shapeLeng;
            $model->geometry   = $geom;
            $model->save();

            $imported++;

            if ($imported % 200 === 0) {
                $this->line("  Progress: {$imported} baris diinsert");
            }
        }

        $this->newLine();
        $this->info('Import selesai');
        $this->info("   Baru diinsert : {$imported}");
        $this->info("   Diskip        : {$skipped}");

        return 0;
    }
}
