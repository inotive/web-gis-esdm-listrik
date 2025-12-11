<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PT_Trafo_Gardu_Kubar;

class ImportPtTrafoGarduKubarCommand extends Command
{
    protected $signature = 'pt-trafo:gardu-kubar-import
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data gardu arrester Kubar dari file GeoJSON ke tabel pt_trafo_gardu_kubar';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Gardu\PT_Trafo_Gardu_Kubar.json"');
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

        $this->info('🚧 Memulai import data Trafo Gardu Kubar ...');

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
            $id_prop     = $props['id']         ?? null;
            $name        = $props['Name']       ?? null;
            $descriptio  = $props['descriptio'] ?? null;
            $timestamp   = $props['timestamp']  ?? null;
            $begin       = $props['begin']      ?? null;
            $end         = $props['end']        ?? null;
            $altitudemo  = $props['altitudeMo'] ?? null;
            $tessellate  = $props['tessellate'] ?? null;
            $extrude     = $props['extrude']    ?? null;
            $visibility  = $props['visibility'] ?? null;
            $draworder   = $props['drawOrder']  ?? null;
            $icon        = $props['icon']       ?? null;
            $kapasitas   = $props['KAPASITAS']  ?? null;
            $feeder      = $props['FEEDER']     ?? null;
            $zona        = $props['ZONA']       ?? null;
            $nilai_pent  = $props['NILAI_PENT'] ?? null;
            $latitude    = $props['LATITUDE']   ?? null;
            $longitude   = $props['LONGITUDE']  ?? null;
            $layer       = $props['layer']      ?? null;
            $path        = $props['path']       ?? null;

            if ($id_prop !== null && $id_prop !== '') {
                $model = PT_Trafo_Gardu_Kubar::firstOrNew(['id_prop' => $id_prop]);
            } else {
                $model = new PT_Trafo_Gardu_Kubar();
            }

            $model->id_prop     = $id_prop;
            $model->name        = $name;
            $model->descriptio  = $descriptio;
            $model->timestamp   = $timestamp;
            $model->begin       = $begin;
            $model->end         = $end;
            $model->altitudemo  = $altitudemo;
            $model->tessellate  = $tessellate;
            $model->extrude     = $extrude;
            $model->visibility  = $visibility;
            $model->draworder   = $draworder;
            $model->icon        = $icon;
            $model->kapasitas   = $kapasitas;
            $model->feeder      = $feeder;
            $model->zona        = $zona;
            $model->nilai_pent  = $nilai_pent;
            $model->latitude    = $latitude;
            $model->longitude   = $longitude;
            $model->layer       = $layer;
            $model->path        = $path;
            $model->geometry    = $geom;

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