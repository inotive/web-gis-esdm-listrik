<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PT_Trafo_Gardu_Distribusi_PPU;

class ImportPtTrafoGarduDistribusiPpuCommand extends Command
{
    protected $signature = 'pt-trafo:gardu-distribusi-ppu-import
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data trafo gardu distribusi PPU dari file GeoJSON ke tabel pt_trafo_gardu_distribusi_ppu';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Trafo\PT_Trafo_Gardu_Distribusi_PPU.json"');
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

        $this->info('🚧 Memulai import data Trafo Gardu Distribusi PPU ...');

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
            $oid_       = $props['OID_']       ?? null;
            $name       = $props['Name']       ?? null;
            $folderPath = $props['FolderPath'] ?? null;
            $symbolId   = $props['SymbolID']   ?? null;
            $altMode    = $props['AltMode']    ?? null;
            $base       = $props['Base']       ?? null;
            $timeSpan   = $props['TimeSpan']   ?? null;
            $timeStamp  = $props['TimeStamp']  ?? null;
            $beginTime  = $props['BeginTime']  ?? null;
            $endTime    = $props['EndTime']    ?? null;
            $snippet    = $props['Snippet']    ?? null;
            $popupInfo  = $props['PopupInfo']  ?? null;
            $hasLabel   = $props['HasLabel']   ?? null;
            $labelId    = $props['LabelID']    ?? null;
            $nama       = $props['Nama']       ?? null;

            if ($oid_ !== null) {
                $model = PT_Trafo_Gardu_Distribusi_PPU::firstOrNew(['oid_' => $oid_]);
            } else {
                $model = new PT_Trafo_Gardu_Distribusi_PPU();
            }

            $model->oid_       = $oid_;
            $model->name       = $name;
            $model->folderpath = $folderPath;
            $model->symbolid   = $symbolId;
            $model->altmode    = $altMode;
            $model->base       = $base;
            $model->timespan   = $timeSpan;
            $model->timestamp  = $timeStamp;
            $model->begintime  = $beginTime;
            $model->endtime    = $endTime;
            $model->snippet    = $snippet;
            $model->popupinfo  = $popupInfo;
            $model->haslabel   = $hasLabel;
            $model->labelid    = $labelId;
            $model->nama       = $nama;
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