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
                        'pertanyaan' => 'Nama Desa tempat belum adanya layanan listrik',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 2,
                        'pertanyaan' => 'Nama Kecamatan tempat belum adanya layanan listrik',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 3,
                        'pertanyaan' => 'Nama Kabupaten tempat belum adanya layanan listrik',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 4,
                        'pertanyaan' => 'Jumlah Kepala Keluarga (KK) yang diajukan permohonan',
                        'tipe' => 'number',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Upload Fotokopi KTP dan KK warga yang diusulkan',
                        'tipe' => 'file_multiple',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Upload Surat rekomendasi dari Kepala Desa bahwa warga yang diusulkan memang layak untuk mendapatkan bantuan pemasangan dan penyambungan listrik',
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
                        'pertanyaan' => 'Nama Desa tempat belum adanya layanan listrik',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 2,
                        'pertanyaan' => 'Nama Kecamatan tempat belum adanya layanan listrik',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 3,
                        'pertanyaan' => 'Nama Kabupaten tempat belum adanya layanan listrik',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 4,
                        'pertanyaan' => 'Jumlah Kepala Keluarga (KK) yang diajukan permohonan',
                        'tipe' => 'number',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Upload Fotokopi KTP dan KK warga yang diusulkan',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Upload Surat rekomendasi dari Kepala Desa bahwa warga yang diusulkan memang layak untuk mendapatkan bantuan pemasangan dan penyambungan listrik',
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
