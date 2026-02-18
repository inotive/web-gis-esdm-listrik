<?php

namespace App\Jobs;

use App\Models\Inspeksi;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessInspeksiFeedbackNotification implements ShouldQueue
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
        $inspeksi = Inspeksi::with('perusahaan')->find($this->inspeksiId);

        if (!$inspeksi) {
            Log::warning("ProcessInspeksiFeedbackNotification: Inspeksi with ID {$this->inspeksiId} not found.");
            return;
        }

        // Notify Admins and Superadmins
        // Note: Using 'admin' and 'superadmin' roles as per convention seen in other jobs
        $recipients = User::role(['admin', 'superadmin'])->get();

        Log::info("ProcessInspeksiFeedbackNotification: Found " . $recipients->count() . " admin recipients for Inspeksi {$this->inspeksiId}.");

        foreach ($recipients as $recipient) {
            try {
                Notifikasi::create([
                    'user_id' => $recipient->id,
                    'title' => 'Feedback Inspeksi',
                    'message' => 'Perusahaan ' . ($inspeksi->perusahaan->nama ?? '-') . ' telah memberikan feedback untuk inspeksi: ' . $inspeksi->referensi_izin,
                    'type' => 'success',
                    'reference_id' => $inspeksi->id,
                    'reference_type' => 'inspeksi',
                    'action_url' => route('admin.inspeksi.show', $inspeksi->id),
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                Log::error("ProcessInspeksiFeedbackNotification: Error processing for user {$recipient->id}: " . $e->getMessage());
            }
        }
    }
}
