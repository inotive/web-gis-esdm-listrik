<?php

namespace Tests\Feature;

use App\Jobs\ProcessPermohonanStatusNotification;
use App\Models\Permohonan;
use App\Models\PermohonanUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermohonanNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'desa']);
    }

    public function test_approve_dispatches_notification_job()
    {
        Queue::fake();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();
        $user->assignRole('desa');

        $permohonan = Permohonan::create([
            'nama' => 'Test Permohonan',
            'jenis_permohonan' => 'desa',
        ]);

        $permohonanUser = PermohonanUser::create([
            'permohonan_id' => $permohonan->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.permohonan-user.approve', [$permohonan->id, $permohonanUser->id]), [
                'keterangan' => 'Approved in test',
            ]);

        $response->assertStatus(302);
        Queue::assertPushed(ProcessPermohonanStatusNotification::class, function ($job) use ($permohonanUser) {
            return $job->permohonanUserId === $permohonanUser->id;
        });
    }

    public function test_reject_dispatches_notification_job()
    {
        Queue::fake();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user = User::factory()->create();
        $user->assignRole('desa');

        $permohonan = Permohonan::create([
            'nama' => 'Test Permohonan',
            'jenis_permohonan' => 'desa',
        ]);

        $permohonanUser = PermohonanUser::create([
            'permohonan_id' => $permohonan->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.permohonan-user.reject', [$permohonan->id, $permohonanUser->id]), [
                'keterangan' => 'Rejected in test',
            ]);

        $response->assertStatus(302);
        Queue::assertPushed(ProcessPermohonanStatusNotification::class, function ($job) use ($permohonanUser) {
            return $job->permohonanUserId === $permohonanUser->id;
        });
    }
}
