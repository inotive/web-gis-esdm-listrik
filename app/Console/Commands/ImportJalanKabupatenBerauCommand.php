<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LN_Jalan_Kabupaten_Berau;

class ImportJalanKabupatenBerauCommand extends Command
{
    protected $signature = 'jalan-berau:import
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data jalan kabupaten Berau dari file GeoJSON ke tabel table__l_n__jalan__kabupaten__berau';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="assets/Jalan/jalan-kabupaten/Berau.json"');
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

        $this->info('🚧 Memulai import data Jalan Kabupaten Berau ...');

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
            $noRuas    = $props['NO_RUAS']    ?? null;
            $namaRuas  = $props['NAMA_RUAS']  ?? null;
            $kabKota   = $props['KAB_KOTA']   ?? null;
            $ttkPngkal = $props['TTK_PNGKAL'] ?? null;
            $ttkAkhir  = $props['TTK_AKHIR']  ?? null;
            $panjang   = $props['PANJANG']    ?? null;
            $jkp2      = $props['JKP_2']      ?? null;
            $jkp3      = $props['JKP_3']      ?? null;
            $jkp4      = $props['JKP_4']      ?? null;
            $jlp       = $props['JLP']        ?? null;
            $jlingP    = $props['Jling_P']    ?? null;
            $jas       = $props['JAS']        ?? null;
            $jks       = $props['JKS']        ?? null;
            $jls       = $props['JLS']        ?? null;
            $jlingS    = $props['Jling_S']    ?? null;
            $fungsi    = $props['FUNGSI']     ?? null;
            $status    = $props['STATUS']     ?? null;
            $shapeLeng = $props['Shape_Leng'] ?? null;

            if ($panjang !== null) {
                $panjang = (float) $panjang;
            }
            if ($jlingP !== null) {
                $jlingP = (float) $jlingP;
            }
            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }
            if ($jlingS !== null) {
                $jlingS = (float) $jlingS;
            }

            if ($objectId !== null) {
                $model = LN_Jalan_Kabupaten_Berau::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new LN_Jalan_Kabupaten_Berau();
            }

            $model->no_ruas    = $noRuas;
            $model->nama_ruas  = $namaRuas;
            $model->kab_kota   = $kabKota;
            $model->ttk_pngkal = $ttkPngkal;
            $model->ttk_akhir  = $ttkAkhir;
            $model->panjang    = $panjang;
            $model->jkp_2      = $jkp2;
            $model->jkp_3      = $jkp3;
            $model->jkp_4      = $jkp4;
            $model->jlp        = $jlp;
            $model->jling_p    = $jlingP;
            $model->jas        = $jas;
            $model->jks        = $jks;
            $model->jls        = $jls;
            $model->jling_s    = $jlingS;
            $model->fungsi     = $fungsi;
            $model->status     = $status;
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