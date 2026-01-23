<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perusahaan;
use App\Models\Gardu;
use App\Models\InfrastrukturJaringan;
use App\Models\PembangkitLokal;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;

class InfrastrukturPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Create sample companies
        $pln = Perusahaan::create([
            'nama' => 'PT PLN (Persero)',
            'alamat' => 'Jl. Trunojoyo No. 1, Balikpapan',
            'village_id' => '6401012009', // Samurangau
            'kontak' => '0542-421234',
            'jenis_usaha' => 'Penyedia Listrik Negara',
            'kabupaten_kota' => 'Balikpapan',
        ]);

        $abcEnergy = Perusahaan::create([
            'nama' => 'PT ABC Energy Indonesia',
            'alamat' => 'Jl. Industri No. 45, Samarinda',
            'village_id' => '6401012010', // Busui
            'kontak' => '0541-765432',
            'jenis_usaha' => 'Pembangkit Listrik Swasta',
            'kabupaten_kota' => 'Samarinda',
        ]);

        $xyzPower = Perusahaan::create([
            'nama' => 'PT XYZ Power Solutions',
            'alamat' => 'Jl. Energi Raya No. 88, Bontang',
            'village_id' => '6401012011', // Batu Kajan
            'kontak' => '0548-987654',
            'jenis_usaha' => 'Distribusi Jaringan Listrik',
            'kabupaten_kota' => 'Bontang',
        ]);

        // Step 2: Create Wilayah records for infrastructure location
        // We'll create some sample wilayah records for different kabupaten
        $wilayahBalikpapan = Wilayah::create([
            'regency_id' => '6472', // Balikpapan
            'district_id' => '6472010', // Sample district
            'village_id' => '6401012009', // Samurangau
        ]);

        $wilayahSamarinda = Wilayah::create([
            'regency_id' => '6472', // Placeholder
            'district_id' => '6472020',
            'village_id' => '6401012010', // Busui
        ]);

        $wilayahBontang = Wilayah::create([
            'regency_id' => '6474', // Placeholder
            'district_id' => '6474010',
            'village_id' => '6401012011', // Batu Kajan
        ]);

        // Step 3: Create Gardu (Substations) and assign to companies
        // PLN owns most gardu
        $garduData = [
            ['nama' => 'Gardu Induk Balikpapan', 'jenis' => 'Gardu Induk', 'wilayah' => $wilayahBalikpapan, 'perusahaan' => $pln],
            ['nama' => 'Gardu Distribusi Sepinggan', 'jenis' => 'Gardu Distribusi', 'wilayah' => $wilayahBalikpapan, 'perusahaan' => $pln],
            ['nama' => 'Gardu Distribusi Klandasan', 'jenis' => 'Gardu Distribusi', 'wilayah' => $wilayahBalikpapan, 'perusahaan' => $pln],
            ['nama' => 'Gardu Hubung Samarinda', 'jenis' => 'Gardu Hubung', 'wilayah' => $wilayahSamarinda, 'perusahaan' => $pln],
            ['nama' => 'Gardu Distribusi Loa Janan', 'jenis' => 'Gardu Distribusi', 'wilayah' => $wilayahSamarinda, 'perusahaan' => $pln],
            ['nama' => 'Gardu Induk Bontang', 'jenis' => 'Gardu Induk', 'wilayah' => $wilayahBontang, 'perusahaan' => $pln],
            ['nama' => 'Gardu Distribusi ABC 1', 'jenis' => 'Gardu Distribusi', 'wilayah' => $wilayahSamarinda, 'perusahaan' => $abcEnergy],
            ['nama' => 'Gardu Distribusi ABC 2', 'jenis' => 'Gardu Distribusi', 'wilayah' => $wilayahSamarinda, 'perusahaan' => $abcEnergy],
            ['nama' => 'Gardu Distribusi XYZ 1', 'jenis' => 'Gardu Distribusi', 'wilayah' => $wilayahBontang, 'perusahaan' => $xyzPower],
            ['nama' => 'Gardu Distribusi XYZ 2', 'jenis' => 'Gardu Distribusi', 'wilayah' => $wilayahBontang, 'perusahaan' => $xyzPower],
        ];

        foreach ($garduData as $data) {
            Gardu::create([
                'nama' => $data['nama'],
                'lokasi' => $data['wilayah']->village_id,
                'jenis_gardu_distribusi' => $data['jenis'],
                'wilayah_id' => $data['wilayah']->id,
                'perusahaan_id' => $data['perusahaan']->id,
            ]);
        }

        // Step 4: Create InfrastrukturJaringan (Network Infrastructure)
        $jaringanData = [
            // PLN networks
            ['jaringan' => 'distribusi', 'jenis' => 'JTM', 'panjang' => 45.5, 'perusahaan' => $pln],
            ['jaringan' => 'distribusi', 'jenis' => 'JTR', 'panjang' => 78.3, 'perusahaan' => $pln],
            ['jaringan' => 'transmisi', 'jenis' => 'SUTT 150kV', 'panjang' => 120.0, 'perusahaan' => $pln],
            ['jaringan' => 'distribusi', 'jenis' => 'JTM', 'panjang' => 32.7, 'perusahaan' => $pln],
            ['jaringan' => 'distribusi', 'jenis' => 'JTR', 'panjang' => 56.2, 'perusahaan' => $pln],
            
            // ABC Energy networks
            ['jaringan' => 'distribusi', 'jenis' => 'JTM', 'panjang' => 15.8, 'perusahaan' => $abcEnergy],
            ['jaringan' => 'distribusi', 'jenis' => 'JTR', 'panjang' => 22.4, 'perusahaan' => $abcEnergy],
            
            // XYZ Power networks
            ['jaringan' => 'distribusi', 'jenis' => 'JTM', 'panjang' => 18.6, 'perusahaan' => $xyzPower],
            ['jaringan' => 'distribusi', 'jenis' => 'JTR', 'panjang' => 25.9, 'perusahaan' => $xyzPower],
        ];

        foreach ($jaringanData as $data) {
            InfrastrukturJaringan::create([
                'jaringan' => $data['jaringan'],
                'jenis' => $data['jenis'],
                'panjang_jaringan' => $data['panjang'],
                'perusahaan_id' => $data['perusahaan']->id,
            ]);
        }

        // Step 5: Create PembangkitLokal (Local Power Plants)
        $pembangkitData = [
            // PLN power plants
            ['kapasitas' => '100 MW', 'wilayah' => $wilayahBalikpapan, 'perusahaan' => $pln],
            ['kapasitas' => '75 MW', 'wilayah' => $wilayahSamarinda, 'perusahaan' => $pln],
            ['kapasitas' => '50 MW', 'wilayah' => $wilayahBontang, 'perusahaan' => $pln],
            
            // ABC Energy power plants
            ['kapasitas' => '25 MW', 'wilayah' => $wilayahSamarinda, 'perusahaan' => $abcEnergy],
            ['kapasitas' => '30 MW', 'wilayah' => $wilayahSamarinda, 'perusahaan' => $abcEnergy],
            
            // XYZ Power plants
            ['kapasitas' => '20 MW', 'wilayah' => $wilayahBontang, 'perusahaan' => $xyzPower],
        ];

        foreach ($pembangkitData as $data) {
            PembangkitLokal::create([
                'kapasitas_gardu' => $data['kapasitas'],
                'wilayah_id' => $data['wilayah']->id,
                'perusahaan_id' => $data['perusahaan']->id,
            ]);
        }

        $this->command->info('✓ Created 3 companies');
        $this->command->info('✓ Created 10 gardu (substations)');
        $this->command->info('✓ Created 9 network infrastructure records');
        $this->command->info('✓ Created 6 local power plants');
        $this->command->info('✓ All infrastructure successfully linked to companies!');
    }
}
