<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            $roles = ['superadmin', 'admin', 'ppk', 'konsultan'];
            foreach ($roles as $value) {
                Role::create([ 'name' => $value]);
            }
            $this->command->info('Seeding Roles has been completed!');
        });
    }
}
