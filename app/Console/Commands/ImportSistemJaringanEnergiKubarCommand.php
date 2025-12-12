<?php

namespace App\Console\Commands;

use App\Models\LN_Sistem_Jaringan_Energi_Kubar;
use Illuminate\Console\Command;

class ImportSistemJaringanEnergiKubarCommand extends Command
{
    protected $signature = 'sistem-energi:kubar-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data sistem jaringan energi Kubar dari file GeoJSON ke tabel table__l_n__sistem__jaringan__energi__kubar';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Jaringan-Energi/LN_Sistem_Jaringan_Energi_Kubar.json"');
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

        $this->info('Memulai import data Sistem Jaringan Energi Kubar ...');

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
            $name       = $props['Name']       ?? null;
            $descriptio = $props['descriptio'] ?? null;
            $timestamp  = $props['timestamp']  ?? null;
            $begin      = $props['begin']      ?? null;
            $end        = $props['end']        ?? null;
            $altitudeMo = $props['altitudeMo'] ?? null;
            $tessellate = $props['tessellate'] ?? null;
            $extrude    = $props['extrude']    ?? null;
            $visibility = $props['visibility'] ?? null;
            $drawOrder  = $props['drawOrder']  ?? null;
            $icon       = $props['icon']       ?? null;
            $layer      = $props['layer']      ?? null;
            $path       = $props['path']       ?? null;
            $shapeLeng  = $props['Shape_Leng'] ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }
            if ($tessellate !== null) {
                $tessellate = (int) $tessellate;
            }
            if ($extrude !== null) {
                $extrude = (int) $extrude;
            }
            if ($visibility !== null) {
                $visibility = (int) $visibility;
            }
            if ($drawOrder !== null) {
                $drawOrder = (int) $drawOrder;
            }

            if ($objectId !== null) {
                $model = LN_Sistem_Jaringan_Energi_Kubar::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new LN_Sistem_Jaringan_Energi_Kubar();
            }

            $model->name       = $name;
            $model->descriptio = $descriptio;
            $model->timestamp  = $timestamp;
            $model->begin      = $begin;
            $model->end        = $end;
            $model->altitudemo = $altitudeMo;
            $model->tessellate = $tessellate;
            $model->extrude    = $extrude;
            $model->visibility = $visibility;
            $model->draworder  = $drawOrder;
            $model->icon       = $icon;
            $model->layer      = $layer;
            $model->path       = $path;
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
