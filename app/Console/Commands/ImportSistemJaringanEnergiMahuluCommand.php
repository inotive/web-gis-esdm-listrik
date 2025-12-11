<?php

namespace App\Console\Commands;

use App\Models\LN_Sistem_Jaringan_Energi_Mahulu;
use Illuminate\Console\Command;

class ImportSistemJaringanEnergiMahuluCommand extends Command
{
    protected $signature = 'sistem-energi:mahulu-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data sistem jaringan energi Mahakam Ulu dari file GeoJSON ke tabel table__l_n__sistem__jaringan__energi__mahulu';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Jaringan-Listrik/LN_Sistem_Jaringan_Energi_Mahulu.json"');
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

        $this->info("Membaca file: {$path}");

        $data = json_decode(file_get_contents($path), true);

        if (!is_array($data) || !isset($data['features']) || !is_array($data['features'])) {
            $this->error('File GeoJSON tidak valid (tidak ada key "features").');
            return 1;
        }

        $this->info('Memulai import data Sistem Jaringan Energi Mahulu ...');

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
            $shapeLeng = $props['SHAPE_Leng'] ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }

            if ($objectId !== null) {
                $model = LN_Sistem_Jaringan_Energi_Mahulu::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new LN_Sistem_Jaringan_Energi_Mahulu();
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
            $model->shape_leng = $shapeLeng;
            $model->geometry   = $geom;

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
