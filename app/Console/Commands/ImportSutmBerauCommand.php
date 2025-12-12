<?php

namespace App\Console\Commands;

use App\Models\LN_SUTM_Berau;
use Illuminate\Console\Command;

class ImportSutmBerauCommand extends Command
{
    protected $signature = 'sutm:berau-import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data LN SUTM Berau dari file GeoJSON ke tabel table__l_n__sutm__berau';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Jaringan-Listrik/LN_SUTM_Berau.json"');
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

        $this->info('Memulai import data LN SUTM Berau ...');

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
            $globalid   = $props['globalid']   ?? null;
            $assetgroup = $props['assetgroup'] ?? null;
            $assettype  = $props['assettype']  ?? null;
            $tglgambar  = $props['tglgambar']  ?? null;
            $usergambar = $props['usergambar'] ?? null;
            $tglupdate  = $props['tglupdate']  ?? null;
            $userupdate = $props['userupdate'] ?? null;
            $assetnum   = $props['assetnum']   ?? null;
            $classifica = $props['classifica'] ?? null;
            $descriptio = $props['descriptio'] ?? null;
            $installdat = $props['installdat'] ?? null;
            $location   = $props['location']   ?? null;
            $manufactur = $props['manufactur'] ?? null;
            $prioritas  = $props['prioritas']  ?? null;
            $vendor1    = $props['vendor1']    ?? null;
            $bahanKawa  = $props['bahan_kawa'] ?? null;
            $fasaJarin  = $props['fasa_jarin'] ?? null;
            $hantaranN  = $props['hantaran_n'] ?? null;
            $jenisKabe  = $props['jenis_kabe'] ?? null;
            $jenisKond  = $props['jenis_kond'] ?? null;
            $kodePeral  = $props['kode_peral'] ?? null;
            $mainline   = $props['mainline']   ?? null;
            $panjangHa  = $props['panjang_ha'] ?? null;
            $posisiFas  = $props['posisi_fas'] ?? null;
            $sirkuit    = $props['sirkuit']    ?? null;
            $statusKep  = $props['status_kep'] ?? null;
            $teganganJ  = $props['tegangan_j'] ?? null;
            $tingkatIs  = $props['tingkat_is'] ?? null;
            $ukuranKaw  = $props['ukuran_kaw'] ?? null;
            $status     = $props['status']     ?? null;
            $tujdnumber = $props['tujdnumber'] ?? null;
            $serialnum  = $props['serialnum']  ?? null;
            $enabled    = $props['enabled']    ?? null;
            $globalid1  = $props['globalid_1'] ?? null;
            $createdUs  = $props['created_us'] ?? null;
            $createdDa  = $props['created_da'] ?? null;
            $lastEdite  = $props['last_edite'] ?? null;
            $lastEdi1   = $props['last_edi_1'] ?? null;
            $penyulang  = $props['penyulang']  ?? null;
            $relationsh = $props['relationsh'] ?? null;
            $lrm        = $props['lrm']        ?? null;
            $kodeHanta  = $props['kode_hanta'] ?? null;
            $operatingd = $props['operatingd'] ?? null;
            $ownerPeme  = $props['owner_peme'] ?? null;
            $ownersysid = $props['ownersysid'] ?? null;
            $startmeasu = $props['startmeasu'] ?? null;
            $endmeasure = $props['endmeasure'] ?? null;
            $shapeLeng  = $props['Shape_Leng'] ?? null;

            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }
            if ($panjangHa !== null) {
                $panjangHa = (float) $panjangHa;
            }
            if ($startmeasu !== null) {
                $startmeasu = (float) $startmeasu;
            }
            if ($endmeasure !== null) {
                $endmeasure = (float) $endmeasure;
            }
            if ($assetgroup !== null) {
                $assetgroup = (int) $assetgroup;
            }
            if ($assettype !== null) {
                $assettype = (int) $assettype;
            }
            if ($enabled !== null) {
                $enabled = (int) $enabled;
            }

            if ($objectId !== null) {
                $model = LN_SUTM_Berau::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new LN_SUTM_Berau();
            }

            $model->globalid   = $globalid;
            $model->assetgroup = $assetgroup;
            $model->assettype  = $assettype;
            $model->tglgambar  = $tglgambar;
            $model->usergambar = $usergambar;
            $model->tglupdate  = $tglupdate;
            $model->userupdate = $userupdate;
            $model->assetnum   = $assetnum;
            $model->classifica = $classifica;
            $model->descriptio = $descriptio;
            $model->installdat = $installdat;
            $model->location   = $location;
            $model->manufactur = $manufactur;
            $model->prioritas  = $prioritas;
            $model->vendor1    = $vendor1;
            $model->bahan_kawa = $bahanKawa;
            $model->fasa_jarin = $fasaJarin;
            $model->hantaran_n = $hantaranN;
            $model->jenis_kabe = $jenisKabe;
            $model->jenis_kond = $jenisKond;
            $model->kode_peral = $kodePeral;
            $model->mainline   = $mainline;
            $model->panjang_ha = $panjangHa;
            $model->posisi_fas = $posisiFas;
            $model->sirkuit    = $sirkuit;
            $model->status_kep = $statusKep;
            $model->tegangan_j = $teganganJ;
            $model->tingkat_is = $tingkatIs;
            $model->ukuran_kaw = $ukuranKaw;
            $model->status     = $status;
            $model->tujdnumber = $tujdnumber;
            $model->serialnum  = $serialnum;
            $model->enabled    = $enabled;
            $model->globalid_1 = $globalid1;
            $model->created_us = $createdUs;
            $model->created_da = $createdDa;
            $model->last_edite = $lastEdite;
            $model->last_edi_1 = $lastEdi1;
            $model->penyulang  = $penyulang;
            $model->relationsh = $relationsh;
            $model->lrm        = $lrm;
            $model->kode_hanta = $kodeHanta;
            $model->operatingd = $operatingd;
            $model->owner_peme = $ownerPeme;
            $model->ownersysid = $ownersysid;
            $model->startmeasu = $startmeasu;
            $model->endmeasure = $endmeasure;
            $model->shape_leng = $shapeLeng;
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
