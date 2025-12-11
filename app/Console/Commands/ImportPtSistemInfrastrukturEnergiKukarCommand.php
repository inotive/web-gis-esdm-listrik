<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PT_Sistem_Infrastruktur_Energi_Kukar;

class ImportPtSistemInfrastrukturEnergiKukarCommand extends Command
{
    protected $signature = 'pt-energi:kukar-import
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data sistem infrastruktur energi Kutai Kartanegara dari file GeoJSON ke tabel table__p_t__sistem__infrastruktur__energi__kukar';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Energi-Infrastruktur\PT_Sistem_Infrastruktur_Energi_Kukar.json"');
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

        $this->info('🚧 Memulai import data Sistem Infrastruktur Energi Kukar ...');

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
            $objectId  = $props['OBJECTID']   ?? null;
            $namobj    = $props['NAMOBJ']     ?? null;
            $orde01    = $props['ORDE01']     ?? null;
            $orde02    = $props['ORDE02']     ?? null;
            $orde03    = $props['ORDE03']     ?? null;
            $orde04    = $props['ORDE04']     ?? null;
            $jnsrsr    = $props['JNSRSR']     ?? null;
            $stsjrn    = $props['STSJRN']     ?? null;
            $wadmpr    = $props['WADMPR']     ?? null;
            $wadmkk    = $props['WADMKK']     ?? null;
            $remark    = $props['REMARK']     ?? null;
            $sbdata    = $props['SBDATA']     ?? null;

            if ($objectId !== null) {
                $model = PT_Sistem_Infrastruktur_Energi_Kukar::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new PT_Sistem_Infrastruktur_Energi_Kukar();
            }

            $model->namobj     = $namobj;
            $model->orde01     = $orde01;
            $model->orde02     = $orde02;
            $model->orde03     = $orde03;
            $model->orde04     = $orde04;
            $model->jnsrsr     = $jnsrsr;
            $model->stsjrn     = $stsjrn;
            $model->wadmpr     = $wadmpr;
            $model->wadmkk     = $wadmkk;
            $model->remark     = $remark;
            $model->sbdata     = $sbdata;
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