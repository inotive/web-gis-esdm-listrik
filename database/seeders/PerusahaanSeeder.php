<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $perusahaans = \App\Models\Perusahaan::all();

        foreach ($perusahaans as $perusahaan) {
            // Cek apakah perusahaan sudah memiliki user
            $user = \App\Models\User::where('perusahaan_id', $perusahaan->id)->first();

            if (!$user) {
                // Buat user baru jika belum ada
                $baseUsername = \Illuminate\Support\Str::slug($perusahaan->nama);
                $username = $baseUsername;
                $email = $username . '@gmail.com';
                $counter = 1;

                while (\App\Models\User::where('email', $email)->exists() || \App\Models\User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $email = $username . '@gmail.com';
                    $counter++;
                }

                $newUser = \App\Models\User::create([
                    'name' => $perusahaan->nama,
                    'username' => $username,
                    'email' => $email,
                    'password' => bcrypt('123123'),

                    'perusahaan_id' => $perusahaan->id,
                    'is_verified' => true,
                    'email_verified_at' => now(),
                ]);

                $newUser->assignRole('perusahaan');
            }
        }
    }
}
