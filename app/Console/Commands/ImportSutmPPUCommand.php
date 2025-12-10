<?php

namespace App\Console\Commands;

use App\Models\LN_SUTM_PPU;
use Illuminate\Console\Command;

class ImportSutmPPUCommand extends Command
{
    protected $signature = 'sutm:ppu-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data LN SUTM PPU dari file GeoJSON ke tabel table__l_n__sutm__ppu';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Jaringan-Listrik/LN_SUTM_PPU.json"');
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

        $this->info('Memulai import data LN SUTM PPU ...');

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

            $objectId   = $props['OBJECTID']   ?? null;
            $noRuas     = $props['No_Ruas']    ?? null;
            $kodeKelas  = $props['Kode_Kelas'] ?? null;
            $namaJalan  = $props['Nama_Jalan'] ?? null;
            $namaPangk  = $props['Nama_Pangk'] ?? null;
            $namaUjung  = $props['Nama_Ujung'] ?? null;
            $titkPenge  = $props['Titk_Penge'] ?? null;
            $titikPeng  = $props['Titik_Peng'] ?? null;
            $panjang    = $props['Panjang']    ?? null;
            $lebar      = $props['Lebar']      ?? null;
            $aspal      = $props['Aspal']      ?? null;
            $rijit      = $props['Rijit']      ?? null;
            $perkerasan = $props['Perkerasan'] ?? null;
            $tanah      = $props['Tanah']      ?? null;
            $kondisi    = $props['Kondisi']    ?? null;
            $thPekerja  = $props['Th_Pekerja'] ?? null;
            $ket        = $props['Ket']        ?? null;
            $shapeLeng  = $props['SHAPE_Leng'] ?? null;
            $legacyId   = $props['ID']         ?? null;
            $foto       = $props['FOTO']       ?? null;
            $statusJal  = $props['Status_Jal'] ?? null;
            $fungsiJal  = $props['Fungsi_Jal'] ?? null;
            $sistemJal  = $props['Sistem_Jal'] ?? null;
            $nama       = $props['Nama']       ?? null;
            $dana       = $props['Dana']       ?? null;
            $namaJal1   = $props['Nama_Jal_1'] ?? null;
            $kodeRTRW   = $props['KODE_RTRW']  ?? null;
            $statusRTRW = $props['statusRTRW'] ?? null;
            $shapeLe1   = $props['Shape_Le_1'] ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }
            if ($shapeLe1 !== null) {
                $shapeLe1 = (float) $shapeLe1;
            }
            if ($panjang !== null) {
                $panjang = (float) $panjang;
            }
            if ($lebar !== null) {
                $lebar = (float) $lebar;
            }
            if ($aspal !== null) {
                $aspal = (float) $aspal;
            }
            if ($rijit !== null) {
                $rijit = (float) $rijit;
            }
            if ($perkerasan !== null) {
                $perkerasan = (float) $perkerasan;
            }
            if ($tanah !== null) {
                $tanah = (float) $tanah;
            }
            if ($noRuas !== null) {
                $noRuas = (int) $noRuas;
            }
            if ($kodeKelas !== null) {
                $kodeKelas = (int) $kodeKelas;
            }
            if ($legacyId !== null) {
                $legacyId = (int) $legacyId;
            }

            if ($objectId !== null) {
                $model = LN_SUTM_PPU::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new LN_SUTM_PPU();
            }

            $model->no_ruas     = $noRuas;
            $model->kode_kelas  = $kodeKelas;
            $model->nama_jalan  = $namaJalan;
            $model->nama_pangk  = $namaPangk;
            $model->nama_ujung  = $namaUjung;
            $model->titk_penge  = $titkPenge;
            $model->titik_peng  = $titikPeng;
            $model->panjang     = $panjang;
            $model->lebar       = $lebar;
            $model->aspal       = $aspal;
            $model->rijit       = $rijit;
            $model->perkerasan  = $perkerasan;
            $model->tanah       = $tanah;
            $model->kondisi     = $kondisi;
            $model->th_pekerja  = $thPekerja;
            $model->ket         = $ket;
            $model->shape_leng  = $shapeLeng;
            $model->legacy_id   = $legacyId;
            $model->foto        = $foto;
            $model->status_jal  = $statusJal;
            $model->fungsi_jal  = $fungsiJal;
            $model->sistem_jal  = $sistemJal;
            $model->nama        = $nama;
            $model->dana        = $dana;
            $model->nama_jal_1  = $namaJal1;
            $model->kode_rtrw   = $kodeRTRW;
            $model->statusrtrw  = $statusRTRW;
            $model->shape_le_1  = $shapeLe1;
            $model->geometry    = $geom;
            $model->objectid    = $objectId;

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
