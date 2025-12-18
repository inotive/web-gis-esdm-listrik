<?php

namespace Database\Seeders;

use App\Models\Permohonan;
use App\Models\PermohonanQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermohonanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permohonans = [
            [
                'nama' => 'Permohonan Sambungan Listrik Rumah Tangga',
                'keterangan' => 'Permohonan untuk mengajukan sambungan listrik rumah tangga',
                'jenis_permohonan' => 'desa',
                'questions' => [
                    [
                        'urutan' => 1,
                        'pertanyaan' => 'Nomor Surat Permohonan',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 2,
                        'pertanyaan' => 'Tanggal Surat Permohonan',
                        'tipe' => 'date',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 3,
                        'pertanyaan' => 'Unggah File Surat Permohonan',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 4,
                        'pertanyaan' => 'Jumlah Kepala Keluarga (KK) yang Mengajukan',
                        'tipe' => 'number',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Unggah KTP Salah Satu Anggota Keluarga untuk Setiap KK yang Diusulkan',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Unggah Kartu Keluarga untuk Setiap KK yang Diusulkan',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 7,
                        'pertanyaan' => 'Unggah Surat Rekomendasi dari Pemerintah Desa / Kelurahan',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                ],
            ],
            [
                'nama' => 'Permohonan Pemasangan Jaringan Distribusi Tenaga Listrik',
                'keterangan' => 'Permohonan untuk mengajukan pemasangan jaringan distribusi tenaga listrik',
                'jenis_permohonan' => 'desa',
                'questions' => [
                    [
                        'urutan' => 1,
                        'pertanyaan' => 'Nomor Surat Permohonan',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 2,
                        'pertanyaan' => 'Tanggal Surat Permohonan',
                        'tipe' => 'date',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 3,
                        'pertanyaan' => 'Unggah File Surat Permohonan',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 4,
                        'pertanyaan' => 'Panjang Jaringan Listrik yang Akan Dipasang',
                        'tipe' => 'number',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Jarak Lokadi dengan Jaringan Listrik PLN Terdekat',
                        'tipe' => 'number',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Unggah Surat Pernyataan',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                ],
            ],
        ];

        foreach ($permohonans as $permohonanData) {
            $questions = $permohonanData['questions'] ?? [];
            unset($permohonanData['questions']);

            $permohonan = Permohonan::updateOrCreate(
                ['nama' => $permohonanData['nama']],
                $permohonanData
            );

            // Create questions for this permohonan
            foreach ($questions as $questionData) {
                PermohonanQuestion::updateOrCreate(
                    [
                        'permohonan_id' => $permohonan->id,
                        'urutan' => $questionData['urutan'],
                    ],
                    [
                        'pertanyaan' => $questionData['pertanyaan'],
                        'tipe' => $questionData['tipe'],
                        'wajib' => $questionData['wajib'],
                    ]
                );
            }
        }

        $this->command->info('Seeding Permohonan dan PermohonanQuestion telah selesai!');
    }
}
