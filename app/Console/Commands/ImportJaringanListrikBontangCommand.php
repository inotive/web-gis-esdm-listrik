<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Rencana_Jaringan_Listrik_Bontang;

class ImportJaringanListrikBontangCommand extends Command
{
    protected $signature = 'jaringan:bontang-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data rencana jaringan listrik Bontang dari file GeoJSON ke tabel table__l_n__rencana__jaringan__listrik__bontang';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Jaringan-Listrik\LN_Rencana_Jaringan_Listrik_Bontang.json"');
            return 1;
        }

        // Normalisasi path: ubah backslash ke slash
        $fileOpt = str_replace(['\\'], '/', $fileOpt);

        // Hilangkan "public/" di depan kalau ada, biar tidak jadi public/public
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

        $this->info('🚧 Memulai import data rencana jaringan listrik Bontang ...');

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
            $rawId      = $props['Id']          ?? null;
            $rencana    = $props['Rencana']     ?? null;
            $fungsiEks  = $props['fungsi_eks']  ?? null;
            $fungsiRen  = $props['fungsi_ren']  ?? null;
            $keterangan = $props['Keterangan']  ?? null;
            $sumber     = $props['Sumber']      ?? null;

            $sourceId = $rawId !== null ? (int) $rawId : null;

            if ($sourceId !== null) {
                $model = LN_Rencana_Jaringan_Listrik_Bontang::firstOrNew(['source_id' => $sourceId]);
            } else {
                $model = new LN_Rencana_Jaringan_Listrik_Bontang();
            }

            $model->rencana    = $rencana;
            $model->fungsi_eks = $fungsiEks;
            $model->fungsi_ren = $fungsiRen;
            $model->keterangan = $keterangan;
            $model->sumber     = $sumber;
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
