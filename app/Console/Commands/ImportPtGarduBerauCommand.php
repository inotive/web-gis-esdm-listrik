<?php

namespace App\Console\Commands;

use App\Models\PT_Gardu_Berau;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportPtGarduBerauCommand extends Command
{
    protected $signature = 'pt-gardu-berau:import 
                            {--file= : Path relatif dari folder public ke file GeoJSON}';

    protected $description = 'Import data PT Gardu Berau dari file GeoJSON ke tabel table__p_t__gardu__berau';

    public function handle(): int
    {
        $fileOpt = $this->option('file') ?? '';

        if ($fileOpt === '') {
            $this->error('Option --file wajib diisi, contoh: --file="public/assets/Infrastruktur/PT_Gardu_Berau.json"');
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

        $this->info('Mengosongkan tabel table__p_t__gardu__berau sebelum import ...');
        PT_Gardu_Berau::truncate();

        $this->info('Memulai import data PT Gardu Berau ...');

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

            $shapeLeng = $props['Shape_Leng'] ?? null;
            $shapeArea = $props['Shape_Area'] ?? null;
            if ($shapeLeng !== null) {
                $shapeLeng = (float) $shapeLeng;
            }
            if ($shapeArea !== null) {
                $shapeArea = (float) $shapeArea;
            }

            $model = new PT_Gardu_Berau();
            $model->globalid    = $props['globalid']    ?? null;
            $model->assetgroup  = $props['assetgroup']  ?? null;
            $model->assettype   = $props['assettype']   ?? null;
            $model->tglgambar   = $this->parseDate($props['tglgambar'] ?? null);
            $model->usergambar  = $props['usergambar']  ?? null;
            $model->tglupdate   = $this->parseDate($props['tglupdate'] ?? null);
            $model->userupdate  = $props['userupdate']  ?? null;
            $model->assetnum    = $props['assetnum']    ?? null;
            $model->classifica  = $props['classifica']  ?? null;
            $model->descriptio  = $props['descriptio']  ?? null;
            $model->installdat  = $this->parseDate($props['installdat'] ?? null);
            $model->location    = $props['location']    ?? null;
            $model->prioritas   = $props['prioritas']   ?? null;
            $model->vendor1     = $props['vendor1']     ?? null;
            $model->jenis_pela  = $props['jenis_pela']  ?? null;
            $model->kode_peral  = $props['kode_peral']  ?? null;
            $model->status_kep  = $props['status_kep']  ?? null;
            $model->status_rc   = $props['status_rc']   ?? null;
            $model->type_gardu  = $props['type_gardu']  ?? null;
            $model->status      = $props['status']      ?? null;
            $model->tujdnumber  = $props['tujdnumber']  ?? null;
            $model->globalid_1  = $props['globalid_1']  ?? null;
            $model->created_us  = $props['created_us']  ?? null;
            $model->created_da  = $this->parseDate($props['created_da'] ?? null);
            $model->last_edite  = $props['last_edite']  ?? null;
            $model->last_edi_1  = $this->parseDate($props['last_edi_1'] ?? null);
            $model->parent_loc  = $props['parent_loc']  ?? null;
            $model->operatingd  = $this->parseDate($props['operatingd'] ?? null);
            $model->formatteda  = $props['formatteda']  ?? null;
            $model->streetaddr  = $props['streetaddr']  ?? null;
            $model->city        = $props['city']        ?? null;
            $model->kode_konst  = $props['kode_konst']  ?? null;
            $model->owner_peme  = $props['owner_peme']  ?? null;
            $model->ownersysid  = $props['ownersysid']  ?? null;
            $model->penyulang   = $props['penyulang']   ?? null;
            $model->no_slo      = $props['no_slo']      ?? null;
            $model->sloactived  = $this->parseDate($props['sloactived'] ?? null);
            $model->longitudex  = $props['longitudex']  ?? null;
            $model->latitudey   = $props['latitudey']   ?? null;
            $model->shape_leng  = $shapeLeng;
            $model->shape_area  = $shapeArea;
            $model->orig_fid    = $props['ORIG_FID']    ?? null;
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

    private function parseDate($value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        try {
            $dt = Carbon::parse($value);

            // Hindari tanggal yang tidak valid untuk kolom TIMESTAMP MySQL (< 1970)
            return $dt->year < 1970 ? null : $dt;
        } catch (\Throwable) {
            return null;
        }
    }
}
