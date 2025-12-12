<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataJalanNasional;

class ImportDataJalanNasionalCommand extends Command
{
    protected $signature = 'datajalan:import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data jalan nasional dari file GeoJSON ke tabel table_data_jalan';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Jalan\Jalan_Nasional.json"');
            return 1;
        }

        // Normalisasi path
        $fileOpt = str_replace(['\\'], '/', $fileOpt);

        // Jika diawali "public/" hilangkan biar nggak dobel
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

        $this->info('🚧 Memulai import data jalan nasional ...');
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

            $objectId  = $props['OBJECTID']  ?? null;
            $fungsiJal = $props['Fungsi_Jal'] ?? null;
            $namaJln   = $props['Nama_Jln']   ?? null;
            $sumber    = $props['Sumber']     ?? null;
            $shapeLeng = $props['Shape_Leng'] ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }

            // Jika ada OBJECTID, kita pakai sebagai key unik
            if ($objectId !== null) {
                $model = DataJalanNasional::firstOrNew(['objectid' => $objectId]);
            } else {
                // Kalau tidak ada OBJECTID, kita selalu create baru
                $model = new DataJalanNasional();
            }

            $model->fungsi_jal = $fungsiJal;
            $model->nama_jln   = $namaJln;
            $model->sumber     = $sumber;
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
