<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PT2_Trafo_Gardu_Paser;

class ImportPt2TrafoGarduPaserCommand extends Command
{
    protected $signature = 'pt2-trafo:gardu-paser-import
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data gardu induk Paser dari file GeoJSON ke tabel pt2_trafo_gardu_paser';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Gardu\PT2_Trafo_Gardu_Paser.json"');
            return 1;
        }

        // Normalisasi path (hindari double "public/public")
        $fileOpt = str_replace(['\\'], '/', $fileOpt);

        if (str_starts_with($fileOpt, 'public/')) {
            $fileOpt = substr($fileOpt, strlen('public/'));
        }

        $path = public_path($fileOpt);

        if (!file_exists($path)) {
            $this->error("File GeoJSON tidak ditemukan di: {$path}");
            return 1;
        }

        $this->info("📂 Membaca file: {$path}");

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data) || !isset($data['features']) || !is_array($data['features'])) {
            $this->error('File GeoJSON tidak valid (tidak ada key "features").');
            return 1;
        }

        $this->info('🚧 Memulai import data Gardu Induk Paser ...');

        $imported = 0;
        $updated  = 0;
        $skipped  = 0;

        foreach ($data['features'] as $index => $feature) {
            $props = $feature['properties'] ?? [];
            $geom  = $feature['geometry']   ?? null;

            if (!$geom || !isset($geom['type'])) {
                $this->warn("  ⚠️ [{$index}] Melewati feature tanpa geometry yang valid.");
                $skipped++;
                continue;
            }

            // Mapping properties
            $id_prop    = $props['id']         ?? null;
            $name       = $props['Name']       ?? null;
            $descriptio = $props['descriptio'] ?? null;
            $timestamp  = $props['timestamp']  ?? null;
            $begin      = $props['begin']      ?? null;
            $end        = $props['end']        ?? null;
            $altitudemo = $props['altitudeMo'] ?? null;
            $tessellate = $props['tessellate'] ?? null;
            $extrude    = $props['extrude']    ?? null;
            $visibility = $props['visibility'] ?? null;
            $draworder  = $props['drawOrder']  ?? null;
            $icon       = $props['icon']       ?? null;
            $tes_1      = $props['TES_1']      ?? null;
            $tes_2      = $props['TES_2']      ?? null;
            $tes_4      = $props['TES_4']      ?? null;
            $tes_5      = $props['TES_5']      ?? null;
            $tes_6      = $props['TES_6']      ?? null;

            if ($id_prop !== null && $id_prop !== '') {
                $model = PT2_Trafo_Gardu_Paser::firstOrNew(['id_prop' => $id_prop]);
            } else {
                $model = new PT2_Trafo_Gardu_Paser();
            }

            $model->id_prop    = $id_prop;
            $model->name       = $name;
            $model->descriptio = $descriptio;
            $model->timestamp  = $timestamp;
            $model->begin      = $begin;
            $model->end        = $end;
            $model->altitudemo = $altitudemo;
            $model->tessellate = $tessellate;
            $model->extrude    = $extrude;
            $model->visibility = $visibility;
            $model->draworder  = $draworder;
            $model->icon       = $icon;
            $model->tes_1      = $tes_1;
            $model->tes_2      = $tes_2;
            $model->tes_4      = $tes_4;
            $model->tes_5      = $tes_5;
            $model->tes_6      = $tes_6;
            $model->geometry   = $geom;

            if ($model->exists) {
                $model->save();
                $updated++;
            } else {
                $model->save();
                $imported++;
            }

            if (($imported + $updated) % 100 === 0) {
                $this->line("  ⏳ Progress: " . ($imported + $updated) . " baris diproses");
            }
        }

        $this->newLine();
        $this->info('✅ Import selesai');
        $this->info("   • Baru diinsert : {$imported}");
        $this->info("   • Diupdate      : {$updated}");
        $this->info("   • Diskip        : {$skipped}");

        return 0;
    }
}