<?php

namespace Tests\Unit;

use App\Jobs\ProcessPermohonanStatusNotification;
use App\Models\Notifikasi;
use App\Models\PermohonanUser;
use App\Models\User;
use App\Models\Permohonan;
use App\Mail\PermohonanStatusNotification as MailableNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProcessPermohonanStatusNotificationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_handle_sends_notification_successfully()
    {
        Mail::fake();

        // Mock PermohonanUser
        $permohonanUser = \Mockery::mock(PermohonanUser::class)->makePartial();
        $permohonanUser->id = 1;
        $permohonanUser->status = 'selesai';
        $permohonanUser->permohonan_id = 10;

        // Mock User (Recipient)
        $recipient = \Mockery::mock(User::class)->makePartial();
        $recipient->id = 100;
        $recipient->name = 'Test User';
        $recipient->email = 'test@example.com';
        $permohonanUser->user = $recipient;

        // Mock Permohonan
        $permohonan = \Mockery::mock(Permohonan::class)->makePartial();
        $permohonan->nama = 'Test Permohonan';
        $permohonanUser->permohonan = $permohonan;

        // Mock find method
        PermohonanUser::shouldReceive('with')->once()->with(['user', 'permohonan'])->andReturnSelf();
        PermohonanUser::shouldReceive('find')->once()->with(1)->andReturn($permohonanUser);

        // Mock Notifikasi::create
        Notifikasi::shouldReceive('create')->once()->with(\Mockery::on(function ($data) use ($recipient, $permohonanUser) {
            return $data['user_id'] === $recipient->id &&
                $data['title'] === 'Permohonan Disetujui' &&
                $data['type'] === 'success' &&
                $data['reference_id'] === $permohonanUser->id;
        }));

        $job = new ProcessPermohonanStatusNotification(1);
        $job->handle();

        Mail::assertSent(MailableNotification::class, function ($mail) use ($recipient) {
            return $mail->hasTo($recipient->email);
        });
    }

    public function test_handle_logs_warning_if_permohonan_not_found()
    {
        Log::shouldReceive('warning')->once()->with(\Mockery::pattern('/not found/'));

        PermohonanUser::shouldReceive('with')->once()->andReturnSelf();
        PermohonanUser::shouldReceive('find')->once()->with(1)->andReturn(null);

        $job = new ProcessPermohonanStatusNotification(1);
        $job->handle();
    }
}
