<?php

namespace App\Console\Commands;

use App\Models\PT_Gardu_Distribusi_Kutim;
use Illuminate\Console\Command;

class ImportPtGarduDistribusiKutimCommand extends Command
{
    protected $signature = 'pt-gardu-distribusi-kutim:import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data PT Gardu Distribusi Kutim dari file GeoJSON ke tabel table__p_t__gardu__distribusi__kutim';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Infrastruktur/PT_Gardu_Distribusi_Kutim.json"');
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

        $this->info('Mengosongkan tabel table__p_t__gardu__distribusi__kutim sebelum import ...');
        PT_Gardu_Distribusi_Kutim::truncate();

        $this->info('Memulai import data PT Gardu Distribusi Kutim ...');

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

            $classifica = $props['classifica'] ?? null;
            $globalid   = $props['globalid']   ?? null;
            $origFid    = $props['ORIG_FID']   ?? null;

            if ($origFid !== null) {
                $origFid = (int) $origFid;
            }

            $model = new PT_Gardu_Distribusi_Kutim();
            $model->classifica = $classifica;
            $model->globalid   = $globalid;
            $model->orig_fid   = $origFid;
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
