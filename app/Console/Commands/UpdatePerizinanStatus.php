<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePerizinanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'perizinan:update-status';

    protected $description = 'Update status perizinan secara otomatis berdasarkan tanggal berakhir';

    public function handle()
    {
        $this->info('Memulai update status perizinan...');

        $now = now();
        $count = 0;

        // 1. Update ke "Berakhir" jika tanggal_akhir < now
        $expired = \App\Models\Perizinan::whereDate('tanggal_akhir', '<', $now)
            ->where('status_izin', '!=', 'Berakhir')
            ->update(['status_izin' => 'Berakhir']);
        
        $this->info("Updated {$expired} status to 'Berakhir'");

        // 2. Update ke "Mau Berakhir" jika tanggal_akhir > now AND <= now + 30 days
        $nearExpiry = \App\Models\Perizinan::whereDate('tanggal_akhir', '>', $now)
            ->whereDate('tanggal_akhir', '<=', $now->copy()->addDays(30))
            ->where('status_izin', '!=', 'Mau Berakhir')
            ->update(['status_izin' => 'Mau Berakhir']);

        $this->info("Updated {$nearExpiry} status to 'Mau Berakhir'");

        // 3. Update ke "Sedang Aktif" jika tanggal_akhir > now + 30 days
        // Ini optional, untuk safety jika ada yang diperpanjang tapi status belum update
        $active = \App\Models\Perizinan::whereDate('tanggal_akhir', '>', $now->copy()->addDays(30))
            ->where('status_izin', '!=', 'Sedang Aktif')
            ->update(['status_izin' => 'Sedang Aktif']);

        $this->info("Updated {$active} status to 'Sedang Aktif'");
        
        $this->info('Selesai update status perizinan.');
    }
}
