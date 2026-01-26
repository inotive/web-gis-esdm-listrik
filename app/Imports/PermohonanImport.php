<?php

namespace App\Imports;

use App\Models\PermohonanUser;
use App\Models\Permohonan;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class PermohonanImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. Cari User berdasarkan Nama Pemohon
        $user = null;
        if (isset($row['nama_pemohon'])) {
            $user = User::where('name', 'like', '%' . $row['nama_pemohon'] . '%')->first();
        }

        // Jika user tidak ditemukan, create dummy user (atau skip, tergantung kebutuhan)
        // Disini kita create user simple jika email ada, atau skip.
        // Asumsi: jika nama pemohon ada tapi user tidak ada, kita skip atau return null 
        // untuk kehati-hatian, tapi user minta "import", biasanya ingin datanya masuk.
        // Kita coba cari user pertama jika null, atau create user baru.
        if (!$user && !empty($row['nama_pemohon'])) {
             // Create dummy user for import
             $user = User::create([
                 'name' => $row['nama_pemohon'],
                 'username' => Str::slug($row['nama_pemohon']) . rand(100, 999), // Generate username
                 'email' => Str::slug($row['nama_pemohon']) . '_' . rand(100,999) . '@import.com',
                 'password' => bcrypt('password'), // default password
                 'role' => 'user' // Asumsi ada field role atau menggunakan spatie
             ]);
        }
        
        if (!$user) return null;

        // 2. Cari Jenis Permohonan
        $permohonan = null;
        if (isset($row['jenis_permohonan'])) {
            $permohonan = Permohonan::where('nama', 'like', '%' . $row['jenis_permohonan'] . '%')->first();
        }

        // Jika jenis permohonan tidak ditemukan, cari default atau create baru
        if (!$permohonan && !empty($row['jenis_permohonan'])) {
            $permohonan = Permohonan::create([
                'nama' => $row['jenis_permohonan'],
                'jenis_permohonan' => 'Imported', // Default type
                'keterangan' => 'Otomatis dibuat dari import'
            ]);
        } elseif (!$permohonan) {
             // Fallback terakhir jika row['jenis_permohonan'] kosong
             $permohonan = Permohonan::first(); 
        }

        if (!$permohonan) return null;

        // 3. Map Columns to Questions (Auto-create questions if needed)
        // Daftar kolom yang ingin dijadikan "Pertanyaan" / Field dinamis
        $columnsToMap = [
            'status_kelistrikan' => 'Status Kelistrikan',
            'titik_koordinat' => 'Titik Koordinat',
            'jumlah_kapasitas' => 'Jumlah Kapasitas',
            'total_kapasitas_kva' => 'Total Kapasitas (kVA)',
            'jenis_penggunaan' => 'Jenis Penggunaan',
            'sifat_penggunaan' => 'Sifat Penggunaan',
            'catatan' => 'Catatan',
            'lokasi' => 'Lokasi'
        ];

        $jawaban = [];

        foreach ($columnsToMap as $colKey => $questionText) {
            if (isset($row[$colKey])) {
                // Find or create question for this Permohonan
                $question = \App\Models\PermohonanQuestion::firstOrCreate(
                    [
                        'permohonan_id' => $permohonan->id,
                        'pertanyaan' => $questionText
                    ],
                    [
                        'urutan' => 99, // Urutan default di akhir
                        'tipe' => 'text',
                        'wajib' => false
                    ]
                );

                // Map answer to question ID
                $jawaban[$question->id] = $row[$colKey];
            }
        }

        return new PermohonanUser([
            'permohonan_id' => $permohonan->id,
            'user_id'       => $user->id,
            'status'        => $this->mapStatus($row['status'] ?? 'pending'),
            'keterangan'    => $row['keterangan'] ?? null,
            'jawaban'       => $jawaban, // Simpan jawaban yang sudah dimap
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    private function mapStatus($status)
    {
        $status = strtolower($status ?? '');
        if (str_contains($status, 'selesai') || str_contains($status, 'aktif')) return 'selesai';
        if (str_contains($status, 'proses')) return 'diproses';
        if (str_contains($status, 'tolak')) return 'ditolak';
        return 'pending';
    }
}
