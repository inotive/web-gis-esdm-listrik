<?php

namespace App\Console\Commands;

use App\Models\PtGardu;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ImportPtGarduCommand extends Command
{
    protected $signature = 'pln:import-pt-gardu
                            {--path= : Path ke file GeoJSON (default: public/assets/Infrastruktur/PT_Gardu_Berau.json)}
                            {--update : Update data jika sudah ada}
                            {--force : Paksa insert walau duplikat}
                            {--debug : Tampilkan log detail}';

    protected $description = 'Import data PT Gardu dari file GeoJSON ke tabel pt_gardus';

    private bool $debugMode = false;

    public function handle(): int
    {
        $path = $this->option('path') ?: public_path('assets/Infrastruktur/PT_Gardu_Berau.json');

        if (! File::exists($path)) {
            $this->error("File tidak ditemukan di: {$path}");
            return 1;
        }

        $data = json_decode(File::get($path), true);

        if (! isset($data['features']) || ! is_array($data['features'])) {
            $this->error("File GeoJSON tidak valid (tidak ada key 'features').");
            return 1;
        }

        $updateMode      = $this->option('update');
        $forceMode       = $this->option('force');
        $this->debugMode = $this->option('debug');

        if ($updateMode) {
            $this->info('🔄 Mode: UPDATE (data existing akan di-update)');
        } elseif ($forceMode) {
            $this->info('⚡ Mode: FORCE (duplikat dibuat sebagai baris baru)');
        } else {
            $this->info('⏭️  Mode: SKIP (duplikat dilewati)');
        }

        if ($this->debugMode) {
            $this->warn('🐛 Debug mode: ON');
        }

        $this->newLine();
        $this->info('Memulai import ' . count($data['features']) . ' PT Gardu...');
        $this->newLine();

        $stats = $this->initializeStats();

        foreach ($data['features'] as $index => $feature) {
            try {
                $this->processFeature($feature, $index, $stats, $updateMode, $forceMode);
            } catch (\Exception $e) {
                $this->handleImportError($e, $feature, $index, $stats);
            }
        }

        $this->displaySummary($data, $stats, $updateMode);

        return 0;
    }

    private function initializeStats(): array
    {
        return [
            'imported'  => 0,
            'updated'   => 0,
            'skipped'   => 0,
            'duplicate' => 0,
        ];
    }

    private function processFeature(array $feature, int $index, array &$stats, bool $updateMode, bool $forceMode): void
    {
        $props = $feature['properties'] ?? [];

        $globalId = $props['globalid'] ?? null;

        if (! $globalId) {
            $this->warn("  ⚠️  [{$index}] globalid kosong, dilewati.");
            $stats['skipped']++;
            return;
        }

        $garduData = $this->prepareGarduData($feature);

        $this->saveGardu($garduData, $stats, $updateMode, $forceMode, $globalId, $index);
    }

    /**
     * Mapping field dari GeoJSON ke kolom pt_gardus.
     */
    private function prepareGarduData(array $feature): array
    {
        $props = $feature['properties'] ?? [];

        return [
            'wilayah_id'  => $this->resolveWilayahId($props),
            'globalid'    => $props['globalid'] ?? null,
            'assetgroup'  => isset($props['assetgroup']) ? (int) $props['assetgroup'] : null,
            'assettype'   => isset($props['assettype']) ? (int) $props['assettype'] : null,
            'tglgambar'   => $this->parseDate($props['tglgambar'] ?? null),
            'usergambar'  => $props['usergambar'] ?? null,
            'tglupdate'   => $this->parseDate($props['tglupdate'] ?? null),
            'userupdate'  => $props['userupdate'] ?? null,
            'assetnum'    => $props['assetnum'] ?? null,
            'classifica'  => $props['classifica'] ?? null,
            'descriptio'  => $props['descriptio'] ?? null,
            'installdat'  => $this->parseDate($props['installdat'] ?? null),
            'location'    => $props['location'] ?? null,
            'prioritas'   => $props['prioritas'] ?? null,
            'vendor1'     => $props['vendor1'] ?? null,
            'jenis_pela'  => $props['jenis_pela'] ?? null,
            'kode_peral'  => $props['kode_peral'] ?? null,
            'status_kep'  => $props['status_kep'] ?? null,
            'status_rc'   => $props['status_rc'] ?? null,
            'type_gardu'  => $props['type_gardu'] ?? null,
            'status'      => $props['status'] ?? null,
            'tujdnumber'  => $props['tujdnumber'] ?? null,
            'globalid_1'  => $props['globalid_1'] ?? null,
            'created_us'  => $props['created_us'] ?? null,
            'created_da'  => $this->parseDate($props['created_da'] ?? null),
            'last_edite'  => $props['last_edite'] ?? null,
            'last_edi_1'  => $this->parseDate($props['last_edi_1'] ?? null),
            'parent_loc'  => $props['parent_loc'] ?? null,
            'operatingd'  => $this->parseDate($props['operatingd'] ?? null),
            'formatteda'  => $props['formatteda'] ?? null,
            'streetaddr'  => $props['streetaddr'] ?? null,
            'city'        => $props['city'] ?? null,
            'kode_konst'  => $props['kode_konst'] ?? null,
            'owner_peme'  => $props['owner_peme'] ?? null,
            'ownersysid'  => $props['ownersysid'] ?? null,
            'penyulang'   => $props['penyulang'] ?? null,
            'no_slo'      => $props['no_slo'] ?? null,
            'sloactived'  => $this->parseDate($props['sloactived'] ?? null),
            'longitudex'  => isset($props['longitudex']) ? (float) $props['longitudex'] : null,
            'latitudey'   => isset($props['latitudey']) ? (float) $props['latitudey'] : null,
            'shape_leng'  => isset($props['Shape_Leng']) ? (float) $props['Shape_Leng'] : null,
            'shape_area'  => isset($props['Shape_Area']) ? (float) $props['Shape_Area'] : null,
            'orig_fid'    => isset($props['ORIG_FID']) ? (int) $props['ORIG_FID'] : null,
            'geometry'    => $feature['geometry'] ?? null,
        ];
    }

    /**
     * Simpan / update ke tabel pt_gardus menggunakan model PtGardu.
     */
    private function saveGardu(array $garduData, array &$stats, bool $updateMode, bool $forceMode, string $globalId, int $index): void
    {
        $existing = PtGardu::where('globalid', $globalId)->first();

        if ($existing) {
            if ($updateMode) {
                $existing->update($garduData);
                $stats['updated']++;

                if ($this->debugMode) {
                    $this->line("  🔄 [{$index}] Update gardu: {$globalId}");
                }
            } elseif ($forceMode) {
                PtGardu::create($garduData);
                $stats['imported']++;

                if ($this->debugMode) {
                    $this->line("  ➕ [{$index}] Duplikat dibuat: {$globalId}");
                }
            } else {
                $stats['duplicate']++;
                if ($stats['duplicate'] % 50 === 0 && ! $this->debugMode) {
                    $this->line("  ⏭️  Skipped duplicates: {$stats['duplicate']}");
                }
            }
        } else {
            PtGardu::create($garduData);
            $stats['imported']++;

            if ($stats['imported'] % 100 === 0 && ! $this->debugMode) {
                $this->line("  📊 Progress: {$stats['imported']} gardu diimport");
            }

            if ($this->debugMode) {
                $this->line("  ✅ [{$index}] Import gardu: {$globalId}");
            }
        }
    }

    private function handleImportError(\Exception $e, array $feature, int $index, array &$stats): void
    {
        $props = $feature['properties'] ?? [];
        $name  = $props['globalid'] ?? $props['descriptio'] ?? 'Unknown';

        if ($e instanceof QueryException) {
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry')) {
                $stats['duplicate']++;
                if ($this->debugMode) {
                    $this->warn("  ⚠️  [{$index}] Duplicate DB: {$name}");
                }
            } else {
                $this->error("  ❌ [{$index}] DB Error: {$name}");
                if ($this->debugMode) {
                    $this->error('     Message: ' . $e->getMessage());
                }
                $stats['skipped']++;
            }
        } else {
            $this->error("  ❌ [{$index}] Error: {$name}");
            if ($this->debugMode) {
                $this->error('     Message: ' . $e->getMessage());
            }
            $stats['skipped']++;
        }
    }

    private function displaySummary(array $data, array $stats, bool $updateMode): void
    {
        $this->newLine();
        $this->info('╔════════════════════════════════════════╗');
        $this->info('║        IMPORT PT GARDU SELESAI         ║');
        $this->info('╚════════════════════════════════════════╝');

        $this->info('📊 Total data dalam GeoJSON: ' . count($data['features']));
        $this->info("✅ Berhasil diimport: {$stats['imported']}");

        if ($updateMode) {
            $this->info("🔄 Berhasil diupdate: {$stats['updated']}");
        }

        if ($stats['duplicate'] > 0) {
            $this->warn("⚠️  Duplicate (dilewati / dibuat tergantung mode): {$stats['duplicate']}");
        }

        $this->info("❌ Error/Skipped: {$stats['skipped']}");

        if ($stats['duplicate'] > 0 && ! $updateMode) {
            $this->newLine();
            $this->comment('💡 Tips:');
            $this->line('   • Update data existing: php artisan pln:import-pt-gardu --update');
            $this->line('   • Buat duplikat:        php artisan pln:import-pt-gardu --force');
            $this->line('   • Debug detail:         php artisan pln:import-pt-gardu --debug');
        }
    }

    private function parseDate(?string $value): ?Carbon
    {
        if (! $value || $value === '0' || strtolower($value) === 'null') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function resolveWilayahId(array $props): ?int
    {
        if (! Schema::hasTable('wilayah_indonesia')) {
            return null;
        }

        $cityName = $props['city'] ?? $props['formatteda'] ?? null;

        if (! $cityName) {
            return null;
        }

        $matchingColumns = collect(['nama', 'name', 'wilayah', 'nama_wilayah'])
            ->filter(fn ($column) => Schema::hasColumn('wilayah_indonesia', $column));

        if ($matchingColumns->isEmpty()) {
            return null;
        }

        return DB::table('wilayah_indonesia')
            ->where(function ($query) use ($matchingColumns, $cityName) {
                foreach ($matchingColumns as $column) {
                    $query->orWhere($column, $cityName)
                        ->orWhere($column, 'like', "%{$cityName}%");
                }
            })
            ->value('id');
    }
}
