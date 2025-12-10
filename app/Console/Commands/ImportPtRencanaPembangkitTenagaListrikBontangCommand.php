<?php

namespace App\Console\Commands;

use App\Models\PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang;
use Illuminate\Console\Command;

class ImportPtRencanaPembangkitTenagaListrikBontangCommand extends Command
{
    protected $signature = 'pt-rencana-pembangkit-bontang:import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data PT Rencana Pembangkit Tenaga Listrik Bontang dari file GeoJSON ke tabel table__p_t__rencana__pembangkit__tenaga__listrik__bontang';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Infrastruktur/PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang.json"');
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

        $this->info('Mengosongkan tabel table__p_t__rencana__pembangkit__tenaga__listrik__bontang sebelum import ...');
        PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang::truncate();

        $this->info('Memulai import data PT Rencana Pembangkit Tenaga Listrik Bontang ...');

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

            $idExternal = $props['Id']         ?? null;
            $nama       = $props['Nama']       ?? null;
            $arahan     = $props['Arahan']     ?? null;
            $fungsiEks  = $props['fungsi_eks'] ?? null;
            $fungsiRen  = $props['fungsi_ren'] ?? null;
            $penjelasan = $props['penjelasan'] ?? null;
            $sumber     = $props['Sumber']     ?? null;

            if ($idExternal !== null) {
                $idExternal = (int) $idExternal;
            }

            $model = new PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang();
            $model->id_external = $idExternal;
            $model->nama        = $nama;
            $model->arahan      = $arahan;
            $model->fungsi_eks  = $fungsiEks;
            $model->fungsi_ren  = $fungsiRen;
            $model->penjelasan  = $penjelasan;
            $model->sumber      = $sumber;
            $model->geometry    = $geom;
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
