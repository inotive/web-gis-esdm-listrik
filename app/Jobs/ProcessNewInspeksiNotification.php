<?php

namespace App\Jobs;

use App\Models\Inspeksi;
use App\Models\Notifikasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessNewInspeksiNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $inspeksiId;

    /**
     * Create a new job instance.
     */
    public function __construct($inspeksiId)
    {
        $this->inspeksiId = $inspeksiId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $inspeksi = Inspeksi::with('perusahaan.users')->find($this->inspeksiId);

        if (!$inspeksi) {
            Log::warning("ProcessNewInspeksiNotification: Inspeksi with ID {$this->inspeksiId} not found.");
            return;
        }

        if (!$inspeksi->perusahaan) {
            Log::info("ProcessNewInspeksiNotification: Inspeksi {$this->inspeksiId} has no associated company.");
            return;
        }

        $recipients = $inspeksi->perusahaan->users;

        Log::info("ProcessNewInspeksiNotification: Found " . $recipients->count() . " recipients for Inspeksi {$this->inspeksiId}.");

        foreach ($recipients as $recipient) {
            try {
                Notifikasi::create([
                    'user_id' => $recipient->id,
                    'title' => 'Inspeksi Baru',
                    'message' => 'Admin telah membuat jadwal inspeksi baru dengan referensi: ' . $inspeksi->referensi_izin,
                    'type' => 'info',
                    'reference_id' => $inspeksi->id,
                    'reference_type' => 'inspeksi',
                    'action_url' => route('admin.inspeksi.show', $inspeksi->id),
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                Log::error("ProcessNewInspeksiNotification: Error processing for user {$recipient->id}: " . $e->getMessage());
            }
        }
    }
}
