<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataJalanProvinsi;

class ImportDataJalanProvinsiCommand extends Command
{
    protected $signature = 'datajalanprov:import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data jalan provinsi dari file GeoJSON ke tabel table_data_jalan_provinsi';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="assets/Jalan/Provinsi.json"');
            return 1;
        }

        // Normalisasi path
        $fileOpt = str_replace(['\\'], '/', $fileOpt);

        // Hilangkan prefix "public/" kalau ada
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

        $this->info('🚧 Memulai import data jalan provinsi ...');
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

            // Ambil semua field yang ada di contoh kamu
            $objectId   = $props['OBJECTID']   ?? null;
            $klDatDas   = $props['Kl_Dat_Das'] ?? null;
            $nmRuas     = $props['Nm_Ruas']    ?? null;
            $thnData    = $props['Thn_Data']   ?? null;
            $status     = $props['Status']     ?? null;
            $fungsi     = $props['Fungsi']     ?? null;
            $mendukung  = $props['Mendukung']  ?? null;
            $uraDukung  = $props['Ura_Dukung'] ?? null;
            $kdBdPu     = $props['Kd_Bd_PU']   ?? null;
            $kdJnsInf   = $props['Kd_Jns_Inf'] ?? null;
            $kdInf      = $props['Kd_Inf']     ?? null;
            $propinsi   = $props['Propinsi']   ?? null;
            $kabKot     = $props['Kab_Kot']    ?? null;
            $kecamatan  = $props['Kecamatan']  ?? null;
            $desaKel    = $props['Desa_Kel']   ?? null;
            $tkRuasAw   = $props['Tk_Ruas_Aw'] ?? null;
            $tkRuasAk   = $props['Tk_Ruas_Ak'] ?? null;
            $kdPatok    = $props['Kd_Patok']   ?? null;
            $kmAwal     = $props['Km_Awal']    ?? null;
            $kmAkhir    = $props['Km_Akhir']   ?? null;
            $nmLintas   = $props['Nm_Lintas']  ?? null;
            $konBaik    = $props['Kon_Baik']   ?? null;
            $konSdg     = $props['Kon_Sdg']    ?? null;
            $konRgn     = $props['Kon_Rgn']    ?? null;
            $konRusak   = $props['Kon_Rusak']  ?? null;
            $konMntp    = $props['Kon_Mntp']   ?? null;
            $konTMntp   = $props['Kon_T_Mntp'] ?? null;
            $panjang    = $props['Panjang']    ?? null;
            $lbrKeras   = $props['Lbr_Keras']  ?? null;
            $lhrt       = $props['LHRT']       ?? null;
            $vcr        = $props['VCR']        ?? null;
            $tipeJln    = $props['Tipe_Jln']   ?? null;
            $mst        = $props['MST']        ?? null;
            $tanahKri   = $props['Tanah_Kri']  ?? null;
            $macadam    = $props['Macadam']    ?? null;
            $aspal      = $props['Aspal']      ?? null;
            $rigid      = $props['Rigid']      ?? null;
            $thnPenAk   = $props['Thn_Pen_Ak'] ?? null;
            $jnsPen     = $props['Jns_Pen']    ?? null;
            $koordXAw   = $props['Koord_X_Aw'] ?? null;
            $koordYAw   = $props['Koord_Y_Aw'] ?? null;
            $koordXAk   = $props['Koord_X_Ak'] ?? null;
            $koordYAk   = $props['Koord_Y_Ak'] ?? null;
            $shapeLeng  = $props['Shape_Leng'] ?? null;
            $statusJ1   = $props['Status_J_1'] ?? null;
            $keterangan = $props['Keterangan'] ?? null;
            $masuk      = $props['Masuk']      ?? null;
            $panjangjal = $props['panjangjal'] ?? null;

            // Casting angka yang perlu
            $kmAwal     = $kmAwal     !== null ? (float)$kmAwal     : null;
            $kmAkhir    = $kmAkhir    !== null ? (float)$kmAkhir    : null;
            $konBaik    = $konBaik    !== null ? (float)$konBaik    : null;
            $konSdg     = $konSdg     !== null ? (float)$konSdg     : null;
            $konRgn     = $konRgn     !== null ? (float)$konRgn     : null;
            $konRusak   = $konRusak   !== null ? (float)$konRusak   : null;
            $konMntp    = $konMntp    !== null ? (float)$konMntp    : null;
            $konTMntp   = $konTMntp   !== null ? (float)$konTMntp   : null;
            $panjang    = $panjang    !== null ? (float)$panjang    : null;
            $lbrKeras   = $lbrKeras   !== null ? (float)$lbrKeras   : null;
            $lhrt       = $lhrt       !== null ? (float)$lhrt       : null;
            $vcr        = $vcr        !== null ? (float)$vcr        : null;
            $tipeJln    = $tipeJln    !== null ? (int)$tipeJln      : null;
            $mst        = $mst        !== null ? (int)$mst          : null;
            $tanahKri   = $tanahKri   !== null ? (float)$tanahKri   : null;
            $macadam    = $macadam    !== null ? (float)$macadam    : null;
            $aspal      = $aspal      !== null ? (float)$aspal      : null;
            $rigid      = $rigid      !== null ? (float)$rigid      : null;
            $thnPenAk   = $thnPenAk   !== null ? (int)$thnPenAk     : null;
            $koordXAw   = $koordXAw   !== null ? (float)$koordXAw   : null;
            $koordYAw   = $koordYAw   !== null ? (float)$koordYAw   : null;
            $koordXAk   = $koordXAk   !== null ? (float)$koordXAk   : null;
            $koordYAk   = $koordYAk   !== null ? (float)$koordYAk   : null;
            $shapeLeng  = $shapeLeng  !== null ? (float)$shapeLeng  : null;
            $panjangjal = $panjangjal !== null ? (float)$panjangjal : null;

            // Jika ada OBJECTID, pakai sebagai key unik
            if ($objectId !== null) {
                $model = DataJalanProvinsi::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new DataJalanProvinsi();
            }

            $model->kl_dat_das  = $klDatDas;
            $model->nm_ruas     = $nmRuas;
            $model->thn_data    = $thnData;
            $model->status      = $status;
            $model->fungsi      = $fungsi;
            $model->mendukung   = $mendukung;
            $model->ura_dukung  = $uraDukung;
            $model->kd_bd_pu    = $kdBdPu;
            $model->kd_jns_inf  = $kdJnsInf;
            $model->kd_inf      = $kdInf;
            $model->propinsi    = $propinsi;
            $model->kab_kot     = $kabKot;
            $model->kecamatan   = $kecamatan;
            $model->desa_kel    = $desaKel;
            $model->tk_ruas_aw  = $tkRuasAw;
            $model->tk_ruas_ak  = $tkRuasAk;
            $model->kd_patok    = $kdPatok;
            $model->km_awal     = $kmAwal;
            $model->km_akhir    = $kmAkhir;
            $model->nm_lintas   = $nmLintas;
            $model->kon_baik    = $konBaik;
            $model->kon_sdg     = $konSdg;
            $model->kon_rgn     = $konRgn;
            $model->kon_rusak   = $konRusak;
            $model->kon_mntp    = $konMntp;
            $model->kon_t_mntp  = $konTMntp;
            $model->panjang     = $panjang;
            $model->lbr_keras   = $lbrKeras;
            $model->lhrt        = $lhrt;
            $model->vcr         = $vcr;
            $model->tipe_jln    = $tipeJln;
            $model->mst         = $mst;
            $model->tanah_kri   = $tanahKri;
            $model->macadam     = $macadam;
            $model->aspal       = $aspal;
            $model->rigid       = $rigid;
            $model->thn_pen_ak  = $thnPenAk;
            $model->jns_pen     = $jnsPen;
            $model->koord_x_aw  = $koordXAw;
            $model->koord_y_aw  = $koordYAw;
            $model->koord_x_ak  = $koordXAk;
            $model->koord_y_ak  = $koordYAk;
            $model->shape_leng  = $shapeLeng;
            $model->status_j_1  = $statusJ1;
            $model->keterangan  = $keterangan;
            $model->masuk       = $masuk;
            $model->panjangjal  = $panjangjal;
            $model->geometry    = $geom;

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
