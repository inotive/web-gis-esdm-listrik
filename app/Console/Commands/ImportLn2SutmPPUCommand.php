<?php

namespace App\Console\Commands;

use App\Models\LN2_SUTM_PPU;
use Illuminate\Console\Command;

class ImportLn2SutmPPUCommand extends Command
{
    protected $signature = 'sutm2:ppu-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data LN2 SUTM PPU dari file GeoJSON ke tabel table__l_n2__sutm__ppu';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Jaringan-Listrik/LN2_SUTM_PPU.json"');
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

        $this->info('Memulai import data LN2 SUTM PPU ...');

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

            $objectId   = $props['OBJECTID']    ?? $props['id'] ?? null;
            $fidJalan   = $props['FID_Jalan']   ?? null;
            $namobj     = $props['NAMOBJ']      ?? null;
            $fcode      = $props['FCODE']       ?? null;
            $remark     = $props['REMARK']      ?? null;
            $metadata   = $props['METADATA']    ?? null;
            $srsId      = $props['SRS_ID']      ?? null;
            $arhrjl     = $props['ARHRJL']      ?? null;
            $autrjl     = $props['AUTRJL']      ?? null;
            $fgsrjl     = $props['FGSRJL']      ?? null;
            $jarrjl     = $props['JARRJL']      ?? null;
            $jparjl     = $props['JPARJL']      ?? null;
            $kllrjl     = $props['KLLRJL']      ?? null;
            $konrjl     = $props['KONRJL']      ?? null;
            $kpmstr     = $props['KPMSTR']      ?? null;
            $lkonof     = $props['LKONOF']      ?? null;
            $lksbsp     = $props['LKSBSP']      ?? null;
            $lksrta     = $props['LKSRTA']      ?? null;
            $llhrrt     = $props['LLHRRT']      ?? null;
            $locrjl     = $props['LOCRJL']      ?? null;
            $lbrbhj     = $props['LBRBHJ']      ?? null;
            $lbrjln     = $props['LBRJLN']      ?? null;
            $matrjl     = $props['MATRJL']      ?? null;
            $medrjl     = $props['MEDRJL']      ?? null;
            $spcrjl     = $props['SPCRJL']      ?? null;
            $starjl     = $props['STARJL']      ?? null;
            $tolrjl     = $props['TOLRJL']      ?? null;
            $utkrjl     = $props['UTKRJL']      ?? null;
            $vlcprt     = $props['VLCPRT']      ?? null;
            $wlyrjl     = $props['WLYRJL']      ?? null;
            $tglSk      = $props['TGL_SK']      ?? null;
            $jlnlyg     = $props['JLNLYG']      ?? null;
            $klsrjl     = $props['KLSRJL']      ?? null;
            $jalanlistr = $props['JalanListr']  ?? null;
            $fidBatasp  = $props['FID_BatasP']  ?? null;
            $wadmkc     = $props['WADMKC']      ?? null;
            $wadmkd     = $props['WADMKD']      ?? null;
            $wadmkk     = $props['WADMKK']      ?? null;
            $wadmpr     = $props['WADMPR']      ?? null;
            $shapeLeng  = $props['SHAPE_Leng']  ?? null;
            $panjang    = $props['Panjang']     ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }
            if ($panjang !== null) {
                $panjang = (float) $panjang;
            }

            foreach (['fidJalan','arhrjl','autrjl','fgsrjl','jarrjl','jparjl','konrjl','kpmstr','llhrrt','locrjl','lbrbhj','lbrjln','matrjl','medrjl','spcrjl','starjl','tolrjl','utkrjl','vlcprt','wlyrjl','jlnlyg','klsrjl','fidBatasp'] as $intField) {
                if (isset($$intField) && $$intField !== null) {
                    $$intField = (int) $$intField;
                }
            }

            if ($objectId !== null) {
                $model = LN2_SUTM_PPU::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new LN2_SUTM_PPU();
            }

            $model->fid_jalan  = $fidJalan;
            $model->namobj     = $namobj;
            $model->fcode      = $fcode;
            $model->remark     = $remark;
            $model->metadata   = $metadata;
            $model->srs_id     = $srsId;
            $model->arhrjl     = $arhrjl;
            $model->autrjl     = $autrjl;
            $model->fgsrjl     = $fgsrjl;
            $model->jarrjl     = $jarrjl;
            $model->jparjl     = $jparjl;
            $model->kllrjl     = $kllrjl;
            $model->konrjl     = $konrjl;
            $model->kpmstr     = $kpmstr;
            $model->lkonof     = $lkonof;
            $model->lksbsp     = $lksbsp;
            $model->lksrta     = $lksrta;
            $model->llhrrt     = $llhrrt;
            $model->locrjl     = $locrjl;
            $model->lbrbhj     = $lbrbhj;
            $model->lbrjln     = $lbrjln;
            $model->matrjl     = $matrjl;
            $model->medrjl     = $medrjl;
            $model->spcrjl     = $spcrjl;
            $model->starjl     = $starjl;
            $model->tolrjl     = $tolrjl;
            $model->utkrjl     = $utkrjl;
            $model->vlcprt     = $vlcprt;
            $model->wlyrjl     = $wlyrjl;
            $model->tgl_sk     = $tglSk;
            $model->jlnlyg     = $jlnlyg;
            $model->klsrjl     = $klsrjl;
            $model->jalanlistr = $jalanlistr;
            $model->fid_batasp = $fidBatasp;
            $model->wadmkc     = $wadmkc;
            $model->wadmkd     = $wadmkd;
            $model->wadmkk     = $wadmkk;
            $model->wadmpr     = $wadmpr;
            $model->shape_leng = $shapeLeng;
            $model->panjang    = $panjang;
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
