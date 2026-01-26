<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $users = [
                [
                    'name' => 'Super Admin',
                    'username' => 'superadmin',
                    'email' => 'superadmin@gmail.com',
                    'role' => 'superadmin',
                    'is_verified' => true,
                ],
                [
                    'name' => 'Admin',
                    'username' => 'admin',
                    'email' => 'admin@gmail.com',
                    'role' => 'admin',
                    'is_verified' => true,
                ],
                [
                    'name' => 'Pengawas',
                    'username' => 'pengawas',
                    'email' => 'pengawas@gmail.com',
                    'role' => 'superadmin',
                    'is_verified' => true,
                ],
                [
                    'name' => 'Operator',
                    'username' => 'operator',
                    'email' => 'operator@gmail.com',
                    'role' => 'ppk',
                    'is_verified' => true,
                ],
                [
                    'name' => 'Perusahaan',
                    'username' => 'perusahaan',
                    'email' => 'perusahaan@gmail.com',
                    'role' => 'perusahaan',
                    'is_verified' => true,
                ],
                [
                    'name' => 'Desa',
                    'username' => 'desa',
                    'email' => 'desa@gmail.com',
                    'role' => 'desa',
                    'is_verified' => true,
                ],
            ];

            foreach ($users as $value) {
                $user = User::create([
                    'name' => $value['name'],
                    'username' => $value['username'],
                    'email' => $value['email'],
                    'password' => bcrypt('123123'),
                    'is_verified' => $value['is_verified'],
                ]);

                $user->syncRoles([$value['role']]);
            }

            $this->command->info('Seeding User has been completed!');
        });
    }
}
