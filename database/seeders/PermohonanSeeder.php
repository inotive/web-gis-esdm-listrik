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
            // ========================================
            // PERMOHONAN DESA
            // ========================================
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

            // ========================================
            // PERMOHONAN PERUSAHAAN
            // ========================================
            [
                'nama' => 'Permohonan SKTP',
                'keterangan' => 'Surat Keterangan Terpasang (SKTP) untuk instalasi tenaga listrik',
                'jenis_permohonan' => 'perusahaan',
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
                        'pertanyaan' => 'Jenis Usaha / Bidang Usaha',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Upload Fotokopi Akte Pendirian Perusahaan',
                        'tipe' => 'file',
                        'wajib' => false,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Upload Fotokopi Izin Usaha (SIUP / TDP / Izin lainnya)',
                        'tipe' => 'file',
                        'wajib' => false,
                    ],
                    [
                        'urutan' => 7,
                        'pertanyaan' => 'Upload NPWP Perusahaan',
                        'tipe' => 'file',
                        'wajib' => false,
                    ],
                    [
                        'urutan' => 8,
                        'pertanyaan' => 'Jumlah Karyawan',
                        'tipe' => 'number',
                        'wajib' => false,
                    ],
                    [
                        'urutan' => 9,
                        'pertanyaan' => 'Status Perusahaan',
                        'tipe' => 'radio',
                        'wajib' => false,
                    ],
                    [
                        'urutan' => 10,
                        'pertanyaan' => 'Tujuan Pengajuan SKTP',
                        'tipe' => 'textarea',
                        'wajib' => true,
                    ],
                ],
            ],
            [
                'nama' => 'Permohonan IUJPTL',
                'keterangan' => 'Izin Usaha Jasa Penunjang Tenaga Listrik',
                'jenis_permohonan' => 'perusahaan',
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
                        'pertanyaan' => 'Jenis Usaha Jasa Penunjang',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Upload Proposal',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Contact Person',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                ],
            ],
            [
                'nama' => 'Permohonan IUPTLS',
                'keterangan' => 'Izin Usaha Penyediaan Tenaga Listrik untuk Kepentingan Sendiri',
                'jenis_permohonan' => 'perusahaan',
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
                        'pertanyaan' => 'Kapasitas Pembangkit (kVA/MW)',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Jenis Pembangkit',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Lokasi Pembangkit',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 7,
                        'pertanyaan' => 'Upload Dokumen Teknis',
                        'tipe' => 'file',
                        'wajib' => true,
                    ],
                ],
            ],
            [
                'nama' => 'Permohonan Rekomendasi CSR',
                'keterangan' => 'Rekomendasi untuk program Corporate Social Responsibility di bidang ketenagalistrikan',
                'jenis_permohonan' => 'perusahaan',
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
                        'pertanyaan' => 'Nama Desa',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 5,
                        'pertanyaan' => 'Bentuk CSR',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 6,
                        'pertanyaan' => 'Tujuan',
                        'tipe' => 'text',
                        'wajib' => true,
                    ],
                    [
                        'urutan' => 7,
                        'pertanyaan' => 'Upload Proposal',
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
