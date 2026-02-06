<?php

namespace App\Imports;

use App\Models\PermohonanUser;
use App\Models\Permohonan;
use App\Models\User;
use App\Models\Perusahaan;
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
        // Headers are typically slugified by Excel import (e.g. 'Nama Perusahaan' -> 'nama_perusahaan')
        // Expected keys: nama_perusahaan, nama_pemohon, jenis_permohonan, bulan, tahun, kotakabupaten, kecamatan, kelurahandesa, keterangan

        // 1. Find Perusahaan (Do NOT Create)
        $perusahaan = null;
        $namaPerusahaanImport = null;

        if (!empty($row['nama_perusahaan'])) {
            $perusahaan = Perusahaan::where('nama', trim($row['nama_perusahaan']))->first();
            
            if (!$perusahaan) {
                // FALLBACK: Perusahaan not found, save name as text
                $namaPerusahaanImport = trim($row['nama_perusahaan']);
            }
        }

        // 2. Find User (Pemohon)
        $user = null;
        $namaPemohonImport = null;

        if (!empty($row['nama_pemohon'])) {
            $user = User::where('name', 'like', '%' . $row['nama_pemohon'] . '%')->first();
            
            if (!$user) {
                // FALLBACK: User not found, save name as text
                $namaPemohonImport = $row['nama_pemohon'];
            } else {
                // Connect to existing user
                // Only update linkage if user is linked to specific Perusahaan ID
                if (!$user->perusahaan_id && $perusahaan) {
                    $user->update(['perusahaan_id' => $perusahaan->id, 'company_name' => $perusahaan->nama]);
                }
            }
        }

        // 3. Find Permohonan Type
        $permohonan = null;
        $jenisPermohonanImport = null;

        if (!empty($row['jenis_permohonan'])) {
            $permohonan = Permohonan::where('nama', trim($row['jenis_permohonan']))->first();
            
            if (!$permohonan) {
                // FALLBACK: Type not found, save name as text
                $jenisPermohonanImport = $row['jenis_permohonan'];
            }
        }

        // Need at least a Permohonan Type or an Imported Type Name
        if (!$permohonan && !$jenisPermohonanImport) {
             return null; 
        }

        // 4. Map Columns to Questions (Dynamic Fields)
        // Note: Map 'keterangan' to 'Keterangan Permohonan' so it appears as Applicant Data.
        $columnsToMap = [
            'kotakabupaten' => 'Kota/Kabupaten',
            'kecamatan'     => 'Kecamatan',
            'kelurahandesa' => 'Kelurahan/Desa',
            'keterangan'    => 'Keterangan Permohonan',
        ];

        $jawaban = [];
        
        if ($permohonan) {
            // Standard behavior: Link to Questions
            foreach ($columnsToMap as $colKey => $questionText) {
                if (isset($row[$colKey])) {
                    $question = \App\Models\PermohonanQuestion::firstOrCreate(
                        [
                            'permohonan_id' => $permohonan->id,
                            'pertanyaan' => $questionText
                        ],
                        [
                            'urutan' => 99,
                            'tipe' => 'text',
                            'wajib' => false
                        ]
                    );
                    $jawaban[$question->id] = $row[$colKey];
                }
            }
        } else {
            // Fallback: Store directly with keys since no Question model exists
            foreach ($columnsToMap as $colKey => $questionText) {
                if (isset($row[$colKey])) {
                    $jawaban[$questionText] = $row[$colKey];
                }
            }
        }

        // 5. Construct Periode Question
        $bulan = $row['bulan'] ?? '-';
        $tahun = $row['tahun'] ?? '-';
        if ($bulan !== '-' || $tahun !== '-') {
            $periodeVal = trim("$bulan $tahun");
            
            if ($permohonan) {
                $qPeriode = \App\Models\PermohonanQuestion::firstOrCreate(
                    [
                        'permohonan_id' => $permohonan->id,
                        'pertanyaan' => 'Periode'
                    ],
                    [
                        'urutan' => 98,
                        'tipe' => 'text',
                        'wajib' => false
                    ]
                );
                $jawaban[$qPeriode->id] = $periodeVal;
            } else {
                $jawaban['Periode'] = $periodeVal;
            }
        }

        // 6. Create PermohonanUser (The Application Record)
        return new PermohonanUser([
            'permohonan_id' => $permohonan ? $permohonan->id : null,
            'user_id'       => $user ? $user->id : null,
            'perusahaan_id' => $perusahaan ? $perusahaan->id : null,
            'nama_pemohon_import'     => $namaPemohonImport,
            'jenis_permohonan_import' => $jenisPermohonanImport,
            'nama_perusahaan_import'  => $namaPerusahaanImport,
            'status'        => 'pending',
            'keterangan'    => null, 
            'jawaban'       => $jawaban,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
