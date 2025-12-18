<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataJalanNasional;
use App\Models\LN_Jalan_Balikpapan;
use App\Models\LN_Jalan_Berau;
use App\Models\LN_Jalan_Bontang;
use App\Models\LN_Jalan_Kubar;
use App\Models\LN_Jalan_KutaiKartanegara;
use App\Models\LN_Jalan_Kutim;
use App\Models\LN_Jalan_Paser;
use App\Models\LN_Jalan_PPU;
use App\Models\LN_Jalan_Samarinda;
use Illuminate\Support\Facades\DB;

class SyncJalanDataCommand extends Command
{
    protected $signature = 'jalan:sync';
    protected $description = 'Sync data jalan from regional tables to main table_data_jalan';

    public function handle()
    {
        $this->info('Starting data synchronization...');

        DB::beginTransaction();
        try {
            // Clear existing data from regional sources
            DataJalanNasional::whereNotNull('kabupaten_kota')->delete();

            // Sync Balikpapan
            $this->syncBalikpapan();

            // Sync Berau
            $this->syncBerau();

            // Sync Bontang
            $this->syncBontang();

            // Sync Kubar
            $this->syncKubar();

            // Sync Kutai Kartanegara
            $this->syncKutaiKartanegara();

            // Sync Kutim
            $this->syncKutim();

            // Sync Paser
            $this->syncPaser();

            // Sync PPU
            $this->syncPPU();

            // Sync Samarinda
            $this->syncSamarinda();

            DB::commit();
            $this->info('Data synchronization completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function syncBalikpapan()
    {
        $this->info('Syncing Balikpapan...');
        $data = LN_Jalan_Balikpapan::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => 'Balikpapan',
                'kecamatan' => $item->Kecamatan,
                'nama_jln' => $item->NAMA_RUAS,
                'fungsi_jal' => $item->FUNGSI,
                'sumber' => 'LN Jalan Balikpapan',
                'shape_leng' => $item->Shape_Le_1,
            ]);
        }

        $this->info('Balikpapan synced: ' . $data->count() . ' records');
    }

    private function syncBerau()
    {
        $this->info('Syncing Berau...');
        $data = LN_Jalan_Berau::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => $item->KAB_KOTA ?? 'Berau',
                'kecamatan' => null,
                'nama_jln' => $item->NAMA_RUAS,
                'fungsi_jal' => $item->FUNGSI,
                'sumber' => 'LN Jalan Berau',
                'shape_leng' => $item->Shape_Leng,
            ]);
        }

        $this->info('Berau synced: ' . $data->count() . ' records');
    }

    private function syncBontang()
    {
        $this->info('Syncing Bontang...');
        $data = LN_Jalan_Bontang::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => $item->Kab_Kota ?? 'Bontang',
                'kecamatan' => $item->Kecamatan,
                'nama_jln' => $item->Nm_Ruas,
                'fungsi_jal' => $item->Fungsi,
                'sumber' => 'LN Jalan Bontang',
                'shape_leng' => $item->Panjang,
            ]);
        }

        $this->info('Bontang synced: ' . $data->count() . ' records');
    }

    private function syncKubar()
    {
        $this->info('Syncing Kutai Barat...');
        $data = LN_Jalan_Kubar::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => 'Kutai Barat',
                'kecamatan' => null,
                'nama_jln' => $item->Nm_Ruas,
                'fungsi_jal' => $item->Fungsi,
                'sumber' => 'LN Jalan Kutai Barat',
                'shape_leng' => $item->Panjang,
            ]);
        }

        $this->info('Kutai Barat synced: ' . $data->count() . ' records');
    }

    private function syncKutaiKartanegara()
    {
        $this->info('Syncing Kutai Kartanegara...');
        $data = LN_Jalan_KutaiKartanegara::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => 'Kutai Kartanegara',
                'kecamatan' => $item->KECAMATAN,
                'nama_jln' => $item->NAMA_BARU ?? $item->NAMA_LAMA,
                'fungsi_jal' => $item->FUNGSI,
                'sumber' => 'LN Jalan Kutai Kartanegara',
                'shape_leng' => $item->Panjang,
            ]);
        }

        $this->info('Kutai Kartanegara synced: ' . $data->count() . ' records');
    }

    private function syncKutim()
    {
        $this->info('Syncing Kutai Timur...');
        $data = LN_Jalan_Kutim::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => 'Kutai Timur',
                'kecamatan' => $item->Kecamatan,
                'nama_jln' => $item->Nm_Ruas,
                'fungsi_jal' => $item->Fungsi,
                'sumber' => 'LN Jalan Kutai Timur',
                'shape_leng' => $item->Panjang,
            ]);
        }

        $this->info('Kutai Timur synced: ' . $data->count() . ' records');
    }

    private function syncPaser()
    {
        $this->info('Syncing Paser...');
        $data = LN_Jalan_Paser::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => $item->Kab_Kot ?? 'Paser',
                'kecamatan' => $item->Kecamatan,
                'nama_jln' => $item->Nm_Ruas,
                'fungsi_jal' => $item->Fungsi,
                'sumber' => 'LN Jalan Paser',
                'shape_leng' => $item->Panjang,
            ]);
        }

        $this->info('Paser synced: ' . $data->count() . ' records');
    }

    private function syncPPU()
    {
        $this->info('Syncing Penajam Paser Utara...');
        $data = LN_Jalan_PPU::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => 'Penajam Paser Utara',
                'kecamatan' => null,
                'nama_jln' => $item->Name,
                'fungsi_jal' => null,
                'sumber' => 'LN Jalan PPU',
                'shape_leng' => $item->Shape_Leng,
            ]);
        }

        $this->info('PPU synced: ' . $data->count() . ' records');
    }

    private function syncSamarinda()
    {
        $this->info('Syncing Samarinda...');
        $data = LN_Jalan_Samarinda::all();

        foreach ($data as $item) {
            DataJalanNasional::create([
                'kabupaten_kota' => $item->Kab_Kot ?? 'Samarinda',
                'kecamatan' => $item->Kecamatan,
                'nama_jln' => $item->Nm_Ruas,
                'fungsi_jal' => $item->Fungsi,
                'sumber' => 'LN Jalan Samarinda',
                'shape_leng' => $item->Panjang,
            ]);
        }

        $this->info('Samarinda synced: ' . $data->count() . ' records');
    }
}
