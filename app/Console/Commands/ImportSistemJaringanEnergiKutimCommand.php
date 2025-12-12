<?php

namespace App\Console\Commands;

use App\Models\LN_Sistem_Jaringan_Energi_Kutim;
use Illuminate\Console\Command;

class ImportSistemJaringanEnergiKutimCommand extends Command
{
    protected $signature = 'sistem-energi:kutim-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data sistem jaringan energi Kutim dari file GeoJSON ke tabel table__l_n__sistem__jaringan__energi__kutim';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Jaringan-Energi/LN_Sistem_Jaringan_Energi_Kutim.json"');
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

        $this->info('Memulai import data Sistem Jaringan Energi Kutim ...');

        $imported = 0;
        $updated  = 0;
        $skipped  = 0;

        foreach ($data['features'] as $index => $feature) {
            $props = $feature['properties'] ?? [];
            $geom  = $feature['geometry']   ?? null;

            if (!$geom || !isset($geom['type'])) {
                $this->warn("  [{$index}] Melewati feature tanpa geometry yang valid.");
                $skipped++;
                continue;
            }

            $objectId   = $props['OBJECTID']   ?? null;
            $classifica = $props['classifica'] ?? null;
            $globalId   = $props['GlobalID']   ?? null;
            $shapeLeng  = $props['Shape_Leng'] ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }

            if ($objectId !== null) {
                $model = LN_Sistem_Jaringan_Energi_Kutim::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new LN_Sistem_Jaringan_Energi_Kutim();
            }

            $model->classifica = $classifica;
            $model->globalid   = $globalId;
            $model->shape_leng = $shapeLeng;
            $model->geometry   = $geom;
            $model->objectid   = $objectId;

            if ($model->exists) {
                $model->save();
                $updated++;
            } else {
                $model->save();
                $imported++;
            }

            if (($imported + $updated) % 100 === 0) {
                $this->line("  Progress: " . ($imported + $updated) . " baris diproses");
            }
        }

        $this->newLine();
        $this->info('Import selesai');
        $this->info("   Baru diinsert : {$imported}");
        $this->info("   Diupdate      : {$updated}");
        $this->info("   Diskip        : {$skipped}");

        return 0;
    }
}
