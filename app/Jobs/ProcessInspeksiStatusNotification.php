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

class ProcessInspeksiStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $inspeksiId;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new job instance.
     */
    public function __construct($inspeksiId, $oldStatus, $newStatus)
    {
        $this->inspeksiId = $inspeksiId;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $inspeksi = Inspeksi::with('perusahaan.users')->find($this->inspeksiId);

        if (!$inspeksi) {
            Log::warning("ProcessInspeksiStatusNotification: Inspeksi with ID {$this->inspeksiId} not found.");
            return;
        }

        if (!$inspeksi->perusahaan) {
            Log::info("ProcessInspeksiStatusNotification: Inspeksi {$this->inspeksiId} has no associated company.");
            return;
        }

        $recipients = $inspeksi->perusahaan->users;

        Log::info("ProcessInspeksiStatusNotification: Found " . $recipients->count() . " recipients for Inspeksi {$this->inspeksiId}.");

        foreach ($recipients as $recipient) {
            try {
                Notifikasi::create([
                    'user_id' => $recipient->id,
                    'title' => 'Status Inspeksi Diperbarui',
                    'message' => 'Status inspeksi dengan referensi ' . $inspeksi->referensi_izin . ' telah diperbarui dari "' . $this->oldStatus . '" menjadi "' . $this->newStatus . '".',
                    'type' => 'info',
                    'reference_id' => $inspeksi->id,
                    'reference_type' => 'inspeksi',
                    'action_url' => route('admin.inspeksi.show', $inspeksi->id),
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                Log::error("ProcessInspeksiStatusNotification: Error processing for user {$recipient->id}: " . $e->getMessage());
            }
        }
    }
}
