<?php

namespace App\Jobs;

use App\Mail\NewPermohonanNotification;
use App\Models\Notifikasi;
use App\Models\PermohonanUser;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessNewPermohonanNotification implements ShouldQueue
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
            Log::warning("ProcessNewPermohonanNotification: PermohonanUser with ID {$this->permohonanUserId} not found.");
            return;
        }

        // Get all users with role 'admin' or 'superadmin'
        // Assuming roles are seeded as 'admin' and 'superadmin' based on previous checks
        $recipients = User::role(['admin', 'superadmin'])->get();

        Log::info("ProcessNewPermohonanNotification: Found " . $recipients->count() . " recipients.");

        foreach ($recipients as $recipient) {
            try {

                // 1. Create Database Notification
                Notifikasi::create([
                    'user_id' => $recipient->id,
                    'title' => 'Permohonan Baru Masuk',
                    'message' => 'Permohonan baru "' . ($permohonanUser->permohonan->nama ?? 'Unknown') . '" dari ' . ($permohonanUser->user->name ?? 'Unknown'),
                    'type' => 'info',
                    'reference_id' => $permohonanUser->id,
                    'reference_type' => PermohonanUser::class,
                    'action_url' => route('admin.permohonan-user.show', [$permohonanUser->permohonan_id, $permohonanUser->id]),
                    'is_read' => false,
                ]);

                // 2. Send Email
                Mail::to($recipient->email)->send(new NewPermohonanNotification($permohonanUser));
            } catch (\Exception $e) {
                Log::error("ProcessNewPermohonanNotification: Error processing for user {$recipient->id}: " . $e->getMessage());
            }
        }
    }
}
