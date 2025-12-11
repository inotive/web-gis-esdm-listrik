<?php

namespace App\Console\Commands;

use App\Models\AR_Batas_Kaltim_KK_Kecamatan;
use Illuminate\Console\Command;

class ImportArBatasKaltimKecamatanCommand extends Command
{
    protected $signature = 'batas-kaltim:kecamatan-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data AR_BATAS_KALTIM_KK_KECAMATAN dari file GeoJSON ke tabel table__a_r__batas__kaltim__k_k__kecamatan';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Batas/AR_BATAS_KALTIM_KK_KECAMATAN.json"');
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

        $this->info('Memulai import data AR_BATAS_KALTIM_KK_KECAMATAN ...');

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

            $objectId  = $props['OBJECTID']   ?? null;
            $objectId  = $props['OBJECTID']   ?? null;
            $fidArBat  = $props['FID_AR_BAT'] ?? null;
            $wadmpr    = $props['WADMPR']     ?? null;
            $wadmkk    = $props['WADMKK']     ?? null;
            $wadmkc    = $props['WADMKC']     ?? null;
            $wadmkd    = $props['WADMKD']     ?? null;
            $namobj    = $props['NAMOBJ']     ?? null;
            $tipadm    = $props['TIPADM']     ?? null;
            $remark    = $props['REMARK']     ?? null;
            $uupp      = $props['UUPP']       ?? null;
            $luaswh    = $props['LUASWH']     ?? null;
            $luas      = $props['Luas']       ?? null;
            $shapeLeng = $props['Shape_Leng'] ?? null;
            $shapeArea = $props['Shape_Area'] ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }
            if ($shapeArea !== null) {
                $shapeArea = (float) $shapeArea;
            }
            if ($luaswh !== null) {
                $luaswh = (float) $luaswh;
            }
            if ($luas !== null) {
                $luas = (float) $luas;
            }
            if ($tipadm !== null) {
                $tipadm = (int) $tipadm;
            }
            if ($fidArBat !== null) {
                $fidArBat = (int) $fidArBat;
            }

            if ($objectId !== null) {
                $model = AR_Batas_Kaltim_KK_Kecamatan::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new AR_Batas_Kaltim_KK_Kecamatan();
            }

            $model->fid_ar_bat = $fidArBat;
            $model->wadmpr     = $wadmpr;
            $model->wadmkk     = $wadmkk;
            $model->wadmkc     = $wadmkc;
            $model->wadmkd     = $wadmkd;
            $model->namobj     = $namobj;
            $model->tipadm     = $tipadm;
            $model->remark     = $remark;
            $model->uupp       = $uupp;
            $model->luaswh     = $luaswh;
            $model->luas       = $luas;
            $model->shape_leng = $shapeLeng;
            $model->shape_area = $shapeArea;
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
