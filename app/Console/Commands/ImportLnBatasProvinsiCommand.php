<?php

namespace App\Console\Commands;

use App\Models\LN_Batas_Provinsi;
use Illuminate\Console\Command;

class ImportLnBatasProvinsiCommand extends Command
{
    protected $signature = 'ln-batas-provinsi:import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data LN BATAS PROVINSI dari file GeoJSON ke tabel table__l_n__batas__provinsi';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Administrasi/LN_BATAS_PROVINSI.json"');
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

        $this->info('Mengosongkan tabel table__l_n__batas__provinsi sebelum import ...');
        LN_Batas_Provinsi::truncate();

        $this->info('Memulai import data LN BATAS PROVINSI ...');

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

            $fidExport = $props['FID_Export'] ?? null;
            $wadmpr    = $props['WADMPR']     ?? null;
            $shapeLeng = $props['Shape_Leng'] ?? null;

            if ($fidExport !== null) {
                $fidExport = (int) $fidExport;
            }
            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }

            $model = new LN_Batas_Provinsi();
            $model->fid_export = $fidExport;
            $model->wadmpr     = $wadmpr;
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
