<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $permission = [
                [
                    'name' => 'create-users',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pengguna',
                    'display_name' => 'Tambah Data',
                ],
                [
                    'name' => 'read-users',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pengguna',
                    'display_name' => 'Lihat Data',
                ],
                [
                    'name' => 'edit-users',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pengguna',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'delete-users',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pengguna',
                    'display_name' => 'Hapus Data',
                ],
                [
                    'name' => 'create-company',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Perusahaan',
                    'display_name' => 'Tambah Data',
                ],
                [
                    'name' => 'read-company',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Perusahaan',
                    'display_name' => 'Lihat Data',
                ],
                [
                    'name' => 'edit-company',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Perusahaan',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'delete-company',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Perusahaan',
                    'display_name' => 'Hapus Data',
                ],
                [
                    'name' => 'blacklist-company',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Perusahaan',
                    'display_name' => 'Blacklist Perusahaan',
                ],
                [
                    'name' => 'create-role',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Jabatan',
                    'display_name' => 'Tambah Data',
                ],
                [
                    'name' => 'read-role',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Jabatan',
                    'display_name' => 'Lihat Data',
                ],
                [
                    'name' => 'edit-role',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Jabatan',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'delete-role',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Jabatan',
                    'display_name' => 'Hapus Data',
                ],
                [
                    'name' => 'setting-role',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Jabatan',
                    'display_name' => 'Pengaturan Role & User',
                ],
                [
                    'name' => 'create-permission',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Hak Akses',
                    'display_name' => 'Tambah Data',
                ],
                [
                    'name' => 'read-permission',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Hak Akses',
                    'display_name' => 'Lihat dData',
                ],
                [
                    'name' => 'edit-permission',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Hak Akses',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'delete-permission',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Hak Akses',
                    'display_name' => 'Hapus Data',
                ],
                [
                    'name' => 'create-indicator',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Indikator Penilaian',
                    'display_name' => 'Tambah Data',
                ],

                [
                    'name' => 'read-indicator',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Indikator Penilaian',
                    'display_name' => 'Lihat Data',
                ],
                [
                    'name' => 'edit-indicator',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Indikator Penilaian',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'delete-indicator',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Indikator Penilaian',
                    'display_name' => 'Delete Data',
                ],
                [
                    'name' => 'create-aspek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Aspek Penilaian',
                    'display_name' => 'Tambah Data',
                ],
                [
                    'name' => 'read-aspek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Aspek Penilaian',
                    'display_name' => 'Lihat Data',
                ],
                [
                    'name' => 'edit-aspek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Aspek Penilaian',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'delete-aspek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Aspek Penilaian',
                    'display_name' => 'Delete Data',
                ],
                [
                    'name' => 'setting-indikator-aspek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Aspek Penilaian',
                    'display_name' => 'Setting Aspek Penilaian',
                ],
                [
                    'name' => 'create-template',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Template',
                    'display_name' => 'Tambah Data',
                ],
                [
                    'name' => 'read-template',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Template',
                    'display_name' => 'Lihat Data',
                ],
                [
                    'name' => 'edit-template',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Template',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'delete-template',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Template',
                    'display_name' => 'Hapus Data',
                ],
                [
                    'name' => 'template-template',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Template',
                    'display_name' => 'Setting Template Penilaian',
                ],
                [
                    'name' => 'read-ranking',
                    'guard_name' => 'web',
                    'group' => 'Ranking Perusahaan',
                    'display_name' => 'Lihat Ranking Perusahaan',
                ],
                [
                    'name' => 'read-blacklist',
                    'guard_name' => 'web',
                    'group' => 'Blacklist Perusahaan',
                    'display_name' => 'Lihat Blacklist Perusahaan',
                ],
                [
                    'name' => 'un-blacklist',
                    'guard_name' => 'web',
                    'group' => 'Blacklist Perusahaan',
                    'display_name' => 'Aktifkan Perusahaan',
                ],
                [
                    'name' => 'read-dashboard',
                    'guard_name' => 'web',
                    'group' => 'Dashboard',
                    'display_name' => 'Lihat Dashboard',
                ],
                [
                    'name' => 'create-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Tambah Data',
                ],
                [
                    'name' => 'read-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Lihat Data',
                ],
                [
                    'name' => 'edit-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Edit Data',
                ],
                [
                    'name' => 'edit-show-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Edit Show Data',
                ],
                [
                    'name' => 'delete-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Hapus Data',
                ],
                [
                    'name' => 'export-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Export Excel',
                ],
                [
                    'name' => 'update-status-waiting-score-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Update Status Proyek - Menunggu Penilaian',
                ],
                [
                    'name' => 'update-status-finish-proyek',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Pekerjaan',
                    'display_name' => 'Update Status Proyek - Selesai',
                ],
                [
                    'name' => 'export-nilai',
                    'guard_name' => 'web',
                    'group' => 'Manajemen Penilaian',
                    'display_name' => 'Export Excel',
                ]
            ];

            foreach ($permission as $value) {
                $data = Permission::create([
                    'name' => $value['name'],
                    'guard_name' => $value['guard_name'],
                    'group' => $value['group'],
                    'display_name' => $value['display_name'],
                ]);

                $role = Role::where('id', 1)->first();

                $role->givePermissionTo($data->id);
            }
            $this->command->info('Seeding Permissions has been completed!');
        });
    }
}
