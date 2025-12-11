<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PT1_Trafo_Gardu_Paser;

class ImportPt1TrafoGarduPaserCommand extends Command
{
    protected $signature = 'pt1-trafo:gardu-paser-import
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data gardu dan trafo Paser dari file GeoJSON ke tabel pt1_trafo_gardu_paser';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Gardu\PT1_Trafo_Gardu_Paser.json"');
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

        $this->info('🚧 Memulai import data Gardu dan Trafo Paser ...');

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
            $id_prop    = $props['Id']         ?? null;
            $name       = $props['Name']       ?? null;
            $descript   = $props['Descript']   ?? null;
            $type       = $props['Type']       ?? null;
            $comment    = $props['Comment']    ?? null;
            $symbol     = $props['Symbol']     ?? null;
            $dateTimeS  = $props['DateTimeS']  ?? null;
            $elevation  = $props['Elevation']  ?? null;
            $nama       = $props['Nama']       ?? null;
            $data       = $props['Data']       ?? null;

            if ($id_prop !== null && $id_prop !== 0) {
                $model = PT1_Trafo_Gardu_Paser::firstOrNew(['id_prop' => $id_prop]);
            } else {
                $model = new PT1_Trafo_Gardu_Paser();
            }

            $model->id_prop   = $id_prop;
            $model->name      = $name;
            $model->descript  = $descript;
            $model->type      = $type;
            $model->comment   = $comment;
            $model->symbol    = $symbol;
            $model->datetimes = $dateTimeS;
            $model->elevation = $elevation;
            $model->nama      = $nama;
            $model->data      = $data;
            $model->geometry  = $geom;

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