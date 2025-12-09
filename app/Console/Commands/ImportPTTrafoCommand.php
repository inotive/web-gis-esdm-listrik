<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PtTrafo;
use Carbon\Carbon;

class ImportPTTrafoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Contoh pemakaian:
     *  php artisan pttrafo:import
     *  php artisan pttrafo:import --file="assets/PT_Trafo_Berau.json"
     *  php artisan pttrafo:import --update
     */
    protected $signature = 'pttrafo:import
                            {--file=assets\Infrastruktur\PT_Trafo_Berau.json : Path file GeoJSON relatif dari public/}
                            {--update : Update data yang sudah ada (berdasarkan globalid)}';

    /**
     * The console command description.
     */
    protected $description = 'Import data PT_Trafo_Berau GeoJSON ke tabel pt_trafos';

    public function handle(): int
    {
        $relativePath = $this->option('file');
        $path = public_path($relativePath);

        if (!file_exists($path)) {
            $this->error("File GeoJSON tidak ditemukan di: {$path}");
            return 1;
        }

        $this->info("📂 Membaca file: {$path}");

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!is_array($data) || !isset($data['features']) || !is_array($data['features'])) {
            $this->error('File GeoJSON tidak valid: tidak ada key "features".');
            return 1;
        }

        $updateMode = $this->option('update');
        $totalFeatures = count($data['features']);

        $this->info("🔧 Mode: " . ($updateMode ? 'UPDATE (by globalid)' : 'SKIP DUPLIKAT'));
        $this->info("🔢 Total fitur yang akan diproses: {$totalFeatures}");
        $this->newLine();

        $imported = 0;
        $updated  = 0;
        $skipped  = 0;

        foreach ($data['features'] as $index => $feature) {
            $idx = $index + 1;

            $props = $feature['properties'] ?? [];
            $geom  = $feature['geometry']  ?? null;

            if (!$geom || !isset($geom['type'])) {
                $this->warn("  [{$idx}] ⚠️  Geometry kosong / tidak valid → dilewati");
                $skipped++;
                continue;
            }

            $globalid = $props['globalid'] ?? null;
            if (!$globalid || trim($globalid) === '') {
                $this->warn("  [{$idx}] ⚠️  globalid kosong → dilewati");
                $skipped++;
                continue;
            }

            // Konversi koordinat WebMercator -> lat/long WGS84 (jika geometry Point)
            $longitudex = null;
            $latitudey  = null;

            if (
                isset($geom['type']) &&
                $geom['type'] === 'Point' &&
                isset($geom['coordinates'][0]) &&
                isset($geom['coordinates'][1])
            ) {
                $x = (float) $geom['coordinates'][0];
                $y = (float) $geom['coordinates'][1];

                [$lon, $lat] = $this->webMercatorToLonLat($x, $y);

                $longitudex = $lon;
                $latitudey  = $lat;
            }

            // Siapkan array data untuk disimpan
            $dataInsert = [
                'globalid'      => $globalid,
                'objectid'      => $props['OBJECTID']   ?? null,
                'assetgroup'    => $props['assetgroup'] ?? null,
                'assettype'     => $props['assettype']  ?? null,
                'tglgambar'     => $this->parseDate($props['tglgambar'] ?? null),
                'usergambar'    => $props['usergambar'] ?? null,
                'tglupdate'     => $this->parseDate($props['tglupdate'] ?? null),
                'userupdate'    => $props['userupdate'] ?? null,
                'assetnum'      => $props['assetnum']   ?? null,
                'classifica'    => $props['classifica'] ?? null,
                'descriptio'    => $props['descriptio'] ?? null,
                'installdat'    => $this->parseDate($props['installdat'] ?? null),
                'location'      => $props['location']   ?? null,
                'manufactur'    => $props['manufactur'] ?? null,
                'serialnum'     => $props['serialnum']  ?? null,
                'status'        => $props['status']     ?? null,
                'tujdnumber'    => $props['tujdnumber'] ?? null,
                'vendor1'       => $props['vendor1']    ?? null,
                'fasa_trafo'    => $props['fasa_trafo'] ?? null,
                'jenis_traf'    => $props['jenis_traf'] ?? null,
                'kapasitas'     => isset($props['kapasitas']) ? (float) $props['kapasitas'] : null,
                'kode_peral'    => $props['kode_peral'] ?? null,
                'no_trafo'      => $props['no_trafo']   ?? null,
                'owner_peme'    => $props['owner_peme'] ?? null,
                'peruntukan'    => $props['peruntukan'] ?? null,
                'posisi_fas'    => $props['posisi_fas'] ?? null,
                'rujukan_ko'    => $props['rujukan_ko'] ?? null,
                'status_kep'    => $props['status_kep'] ?? null,
                'tap_change'    => $props['tap_change'] ?? null,
                'tegangan_t'    => $props['tegangan_t'] ?? null,
                'th_buat'       => $props['th_buat']    ?? null,
                'prioritas'     => $props['prioritas']  ?? null,
                'enabled'       => isset($props['enabled']) ? (bool) $props['enabled'] : null,
                'globalid_1'    => $props['globalid_1'] ?? null,
                'created_us'    => $props['created_us'] ?? null,
                'created_da'    => $this->parseDate($props['created_da'] ?? null),
                'last_edite'    => $props['last_edite'] ?? null,
                'last_edi_1'    => $this->parseDate($props['last_edi_1'] ?? null),
                'relationsh'    => $props['relationsh'] ?? null,
                'kode_hanta'    => $props['kode_hanta'] ?? null,
                'operatingd'    => $this->parseDate($props['operatingd'] ?? null),
                'ownersysid'    => $props['ownersysid'] ?? null,
                'sourcestar'    => isset($props['sourcestar']) ? (float) $props['sourcestar'] : null,
                'sourceendm'    => isset($props['sourceendm']) ? (float) $props['sourceendm'] : null,
                'no_slo'        => $props['no_slo']     ?? null,
                'sloactived'    => $this->parseDate($props['sloactived'] ?? null),
                'penyulang'     => $props['penyulang']  ?? null,
                // kalau di GeoJSON ada streetaddr/city, bisa tambahkan di sini
                'streetaddr'    => $props['streetaddr'] ?? null,
                'city'          => $props['city']       ?? null,
                'longitudex'    => $longitudex,
                'latitudey'     => $latitudey,
                'geometry'      => $geom, // biar Eloquent cast ke JSON
            ];

            // Cek data eksisting by globalid
            $existing = PtTrafo::where('globalid', $globalid)->first();

            if ($existing) {
                if ($updateMode) {
                    $existing->update($dataInsert);
                    $updated++;

                    if ($updated % 50 === 0) {
                        $this->line("  🔄 Updated: {$updated} data trafo...");
                    }
                } else {
                    $skipped++;
                }
                continue;
            }

            // Insert baru
            PtTrafo::create($dataInsert);
            $imported++;

            if ($imported % 50 === 0) {
                $this->line("  ✅ Imported: {$imported} data trafo...");
            }
        }

        // Summary
        $this->newLine();
        $this->info("===== IMPORT PT_TRAFO SELESAI =====");
        $this->info("Total fitur di GeoJSON : {$totalFeatures}");
        $this->info("Berhasil diimport      : {$imported}");
        if ($updateMode) {
            $this->info("Berhasil diupdate      : {$updated}");
        }
        $this->info("Dilewati (skip/duplikat): {$skipped}");

        return 0;
    }

    /**
     * Parse datetime string, treat '1899-11-30' / kosong sebagai null.
     */
    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Buang "Z" kalau ada
        $clean = str_replace('Z', '', $value);

        // Excel sentinel "1899-11-30" → anggap null
        if (str_starts_with($clean, '1899-11-30')) {
            return null;
        }

        try {
            return Carbon::parse($clean)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Konversi koordinat WebMercator (EPSG:3857) → [lon, lat] WGS84.
     *
     * @return array{float,float} [lon, lat]
     */
    private function webMercatorToLonLat(float $x, float $y): array
    {
        $R = 6378137.0;

        $lon = ($x / $R) * 180 / M_PI;

        $lat = (2 * atan(exp($y / $R)) - M_PI / 2) * 180 / M_PI;

        return [$lon, $lat];
    }
}
