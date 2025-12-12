<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PT_Trafo_Berau;

class ImportPtTrafoBerauCommand extends Command
{
    protected $signature = 'pt-trafo:berau-import
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data trafo Berau dari file GeoJSON ke tabel pt_trafo_berau';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public\assets\Trafo\PT_Trafo_Berau.json"');
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

        $this->info('🚧 Memulai import data Trafo Berau ...');

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
            $objectId      = $props['OBJECTID']     ?? null;
            $globalid      = $props['globalid']     ?? null;
            $assetgroup    = $props['assetgroup']   ?? null;
            $assettype     = $props['assettype']    ?? null;
            $tglgambar     = $props['tglgambar']    ?? null;
            $usergambar    = $props['usergambar']   ?? null;
            $tglupdate     = $props['tglupdate']    ?? null;
            $userupdate    = $props['userupdate']   ?? null;
            $assetnum      = $props['assetnum']     ?? null;
            $classifica    = $props['classifica']   ?? null;
            $descriptio    = $props['descriptio']   ?? null;
            $installdat    = $props['installdat']   ?? null;
            $location      = $props['location']     ?? null;
            $manufactur    = $props['manufactur']   ?? null;
            $serialnum     = $props['serialnum']    ?? null;
            $status        = $props['status']       ?? null;
            $tujdnumber    = $props['tujdnumber']   ?? null;
            $vendor1       = $props['vendor1']      ?? null;
            $fasa_trafo    = $props['fasa_trafo']   ?? null;
            $jenis_traf    = $props['jenis_traf']   ?? null;
            $kapasitas     = $props['kapasitas']    ?? null;
            $kode_peral    = $props['kode_peral']   ?? null;
            $no_trafo      = $props['no_trafo']     ?? null;
            $owner_peme    = $props['owner_peme']   ?? null;
            $peruntukan    = $props['peruntukan']   ?? null;
            $posisi_fas    = $props['posisi_fas']   ?? null;
            $rujukan_ko    = $props['rujukan_ko']   ?? null;
            $status_kep     = $props['status_kep']    ?? null;
            $tap_change    = $props['tap_change']   ?? null;
            $tegangan_t    = $props['tegangan_t']   ?? null;
            $th_buat       = $props['th_buat']      ?? null;
            $prioritas     = $props['prioritas']    ?? null;
            $enabled       = $props['enabled']      ?? null;
            $globalid_1    = $props['globalid_1']   ?? null;
            $created_us    = $props['created_us']   ?? null;
            $created_da    = $props['created_da']   ?? null;
            $last_edite    = $props['last_edite']   ?? null;
            $last_edi_1    = $props['last_edi_1']   ?? null;
            $relationsh    = $props['relationsh']   ?? null;
            $kode_hanta    = $props['kode_hanta']   ?? null;
            $operatingd    = $props['operatingd']   ?? null;
            $ownersysid    = $props['ownersysid']   ?? null;
            $sourcestar    = $props['sourcestar']   ?? null;
            $sourceendm    = $props['sourceendm']   ?? null;
            $no_slo        = $props['no_slo']       ?? null;
            $sloactived    = $props['sloactived']   ?? null;
            $penyulang     = $props['penyulang']    ?? null;

            if ($objectId !== null) {
                $model = PT_Trafo_Berau::firstOrNew(['objectid' => $objectId]);
            } else {
                $model = new PT_Trafo_Berau();
            }

            $model->globalid      = $globalid;
            $model->assetgroup    = $assetgroup;
            $model->assettype     = $assettype;
            $model->tglgambar     = $tglgambar;
            $model->usergambar    = $usergambar;
            $model->tglupdate     = $tglupdate;
            $model->userupdate    = $userupdate;
            $model->assetnum      = $assetnum;
            $model->classifica    = $classifica;
            $model->descriptio    = $descriptio;
            $model->installdat    = $installdat;
            $model->location      = $location;
            $model->manufactur    = $manufactur;
            $model->serialnum     = $serialnum;
            $model->status        = $status;
            $model->tujdnumber    = $tujdnumber;
            $model->vendor1       = $vendor1;
            $model->fasa_trafo    = $fasa_trafo;
            $model->jenis_traf    = $jenis_traf;
            $model->kapasitas     = $kapasitas;
            $model->kode_peral    = $kode_peral;
            $model->no_trafo      = $no_trafo;
            $model->owner_peme    = $owner_peme;
            $model->peruntukan    = $peruntukan;
            $model->posisi_fas    = $posisi_fas;
            $model->rujukan_ko    = $rujukan_ko;
            $model->status_kep     = $status_kep;
            $model->tap_change    = $tap_change;
            $model->tegangan_t    = $tegangan_t;
            $model->th_buat       = $th_buat;
            $model->prioritas     = $prioritas;
            $model->enabled       = $enabled;
            $model->globalid_1    = $globalid_1;
            $model->created_us    = $created_us;
            $model->created_da    = $created_da;
            $model->last_edite    = $last_edite;
            $model->last_edi_1    = $last_edi_1;
            $model->relationsh    = $relationsh;
            $model->kode_hanta    = $kode_hanta;
            $model->operatingd    = $operatingd;
            $model->ownersysid    = $ownersysid;
            $model->sourcestar    = $sourcestar;
            $model->sourceendm    = $sourceendm;
            $model->no_slo        = $no_slo;
            $model->sloactived    = $sloactived;
            $model->penyulang     = $penyulang;
            $model->geometry      = $geom;

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