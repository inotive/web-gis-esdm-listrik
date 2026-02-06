<?php

namespace App\Jobs;

use App\Mail\PermohonanStatusNotification;
use App\Models\Notifikasi;
use App\Models\PermohonanUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessPermohonanStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $permohonanUserId;

    /**
     * Create a new job instance.
     */
    public function __construct($permohonanUserId)
    {
        $this->permohonanUserId = $permohonanUserId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $permohonanUser = PermohonanUser::with(['user', 'permohonan'])->find($this->permohonanUserId);

        if (!$permohonanUser) {
            Log::warning("ProcessPermohonanStatusNotification: PermohonanUser with ID {$this->permohonanUserId} not found.");
            return;
        }

        $recipient = $permohonanUser->user;

        if (!$recipient) {
            Log::warning("ProcessPermohonanStatusNotification: User for PermohonanUser ID {$this->permohonanUserId} not found.");
            return;
        }

        $statusLabel = $permohonanUser->status === 'selesai' ? 'Disetujui' : 'Ditolak';
        $type = $permohonanUser->status === 'selesai' ? 'success' : 'danger';

        try {
            // 1. Create Database Notification
            Notifikasi::create([
                'user_id' => $recipient->id,
                'title' => "Permohonan {$statusLabel}",
                'message' => "Permohonan \"" . ($permohonanUser->permohonan->nama ?? 'Unknown') . "\" Anda telah {$statusLabel}.",
                'type' => $type,
                'reference_id' => $permohonanUser->id,
                'reference_type' => PermohonanUser::class,
                'action_url' => route('admin.pengajuan-permohonan.show', $permohonanUser->id),
                'is_read' => false,
            ]);

            // 2. Send Email
            Mail::to($recipient->email)->send(new PermohonanStatusNotification($permohonanUser, $permohonanUser->status));
        } catch (\Exception $e) {
            Log::error("ProcessPermohonanStatusNotification: Error processing for user {$recipient->id}: " . $e->getMessage());
        }
    }
}
