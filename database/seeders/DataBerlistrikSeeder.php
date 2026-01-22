<?php

namespace Database\Seeders;

use App\Models\DataBerlistrik;
use Illuminate\Database\Seeder;

class DataBerlistrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Data dummy untuk 1,070 desa di Kalimantan Timur
     * Distribusi disesuaikan untuk testing visualisasi (termasuk desa belum berlistrik)
     */
    public function run(): void
    {
        // Data distribusi per kabupaten (disesuaikan untuk testing)
        // Menambahkan beberapa desa belum berlistrik untuk visualisasi
        $distribusi = [
            [
                'kabupaten' => 'Balikpapan',
                'kecamatan' => ['Balikpapan Selatan', 'Balikpapan Timur', 'Balikpapan Utara', 'Balikpapan Tengah', 'Balikpapan Barat', 'Balikpapan Kota'],
                'total_desa' => 34,
                'pln' => 34,
                'non_pln' => 0,
                'belum' => 0, // Kota besar, 100% terlayani
            ],
            [
                'kabupaten' => 'Berau',
                'kecamatan' => ['Tanjung Redeb', 'Gunung Tabur', 'Sambaliung', 'Segah', 'Kelay', 'Talisayan', 'Biduk-Biduk', 'Teluk Bayur', 'Maratua', 'Derawan', 'Pulau Derawan', 'Batu Putih', 'Biatan'],
                'total_desa' => 113,
                'pln' => 89,
                'non_pln' => 19,
                'belum' => 5, // Daerah kepulauan, ada yang belum terlayani
            ],
            [
                'kabupaten' => 'Kutai Kartanegara',
                'kecamatan' => ['Samboja', 'Muara Jawa', 'Loa Janan', 'Loa Kulu', 'Tenggarong', 'Sebulu', 'Tenggarong Seberang', 'Anggana', 'Muara Badak', 'Marang Kayu', 'Sanga-Sanga', 'Kota Bangun', 'Kenohan', 'Kembang Janggut', 'Muara Muntai', 'Muara Wis', 'Tabang', 'Kota Bangun'],
                'total_desa' => 240,
                'pln' => 225,
                'non_pln' => 10,
                'belum' => 5, // Daerah terpencil di pedalaman
            ],
            [
                'kabupaten' => 'Samarinda',
                'kecamatan' => ['Samarinda Ilir', 'Samarinda Ulu', 'Samarinda Utara', 'Samarinda Seberang', 'Sungai Kunjang', 'Samarinda Kota', 'Loa Janan Ilir', 'Sungai Pinang', 'Palaran', 'Sambutan'],
                'total_desa' => 59,
                'pln' => 59,
                'non_pln' => 0,
                'belum' => 0, // Kota besar, 100% terlayani
            ],
            [
                'kabupaten' => 'Kutai Timur',
                'kecamatan' => ['Sangatta Utara', 'Sangatta Selatan', 'Bengalon', 'Teluk Pandan', 'Sandaran', 'Telen', 'Sangkulirang', 'Busang', 'Long Mesangat', 'Muara Wahau', 'Muara Ancalong', 'Rantau Pulung', 'Karangan', 'Kaliorang', 'Kaubun'],
                'total_desa' => 148,
                'pln' => 113,
                'non_pln' => 26,
                'belum' => 9, // Daerah tambang, ada pedalaman belum terlayani
            ],
            [
                'kabupaten' => 'Bontang',
                'kecamatan' => ['Bontang Utara', 'Bontang Selatan', 'Bontang Barat'],
                'total_desa' => 15,
                'pln' => 15,
                'non_pln' => 0,
                'belum' => 0, // Kota kecil, 100% terlayani
            ],
            [
                'kabupaten' => 'Penajam Paser Utara',
                'kecamatan' => ['Penajam', 'Waru', 'Babulu', 'Sepaku', 'Petung'],
                'total_desa' => 57,
                'pln' => 52,
                'non_pln' => 2,
                'belum' => 3, // IKN baru, masih ada yang belum terlayani
            ],
            [
                'kabupaten' => 'Paser',
                'kecamatan' => ['Tanah Grogot', 'Pasir Belengkong', 'Kuaro', 'Long Ikis', 'Muara Komam', 'Long Kali', 'Batu Sopang', 'Tanjung Harapan', 'Muara Samu', 'Batu Engau'],
                'total_desa' => 150,
                'pln' => 136,
                'non_pln' => 8,
                'belum' => 6, // Daerah pegunungan, ada yang terisolir
            ],
            [
                'kabupaten' => 'Kutai Barat',
                'kecamatan' => ['Melak', 'Barong Tongkok', 'Damai', 'Muara Lawa', 'Muara Pahu', 'Jempang', 'Bongan', 'Penyinggahan', 'Bentian Besar', 'Linggang Bigung', 'Nyuatan', 'Siluq Ngurai', 'Mook Manaar Bulatn', 'Tering', 'Sekolaq Darat', 'Long Iram', 'Long Hubung', 'Laham', 'Long Bagun', 'Long Pahangai'],
                'total_desa' => 204,
                'pln' => 160,
                'non_pln' => 32,
                'belum' => 12, // Pedalaman Kalimantan, banyak desa terpencil
            ],
            [
                'kabupaten' => 'Mahakam Ulu',
                'kecamatan' => ['Long Bagun', 'Long Hubung', 'Laham', 'Long Apari', 'Long Pahangai'],
                'total_desa' => 60,
                'pln' => 28,
                'non_pln' => 20,
                'belum' => 12, // Kabupaten paling terpencil, banyak belum terlayani
            ],
        ];

        $namaDesaTemplate = [
            'Sungai Nangka', 'Teritip', 'Kariangau', 'Manggar', 'Lamaru', 'Baru Ilir', 'Baru Tengah', 'Baru Ulu',
            'Gunung Samarinda', 'Karang Joang', 'Karang Rejo', 'Karang Bugis', 'Sepinggan', 'Sepinggan Raya',
            'Prapatan', 'Damai', 'Damai Bahagia', 'Mekar Sari', 'Sumber Rejo', 'Sumber Harapan', 'Teluk Dalam',
            'Teluk Seribu', 'Kampung Baru', 'Kampung Dalam', 'Gunung Tabur', 'Gunung Sari', 'Tanjung Batu',
            'Tanjung Redeb', 'Sambaliung', 'Segah', 'Biduk-Biduk', 'Maratua', 'Derawan', 'Pulau Derawan',
            'Samboja', 'Muara Jawa', 'Loa Janan', 'Loa Kulu', 'Tenggarong', 'Sebulu', 'Anggana', 'Muara Badak',
            'Marang Kayu', 'Sanga-Sanga', 'Kota Bangun', 'Kenohan', 'Muara Muntai', 'Muara Wis', 'Tabang',
            'Samarinda Ilir', 'Samarinda Ulu', 'Samarinda Utara', 'Samarinda Seberang', 'Sungai Kunjang',
            'Loa Janan Ilir', 'Sungai Pinang', 'Palaran', 'Sambutan', 'Air Hitam', 'Air Putih', 'Rapak Dalam',
            'Sangatta', 'Bengalon', 'Teluk Pandan', 'Sandaran', 'Telen', 'Sangkulirang', 'Busang', 'Long Mesangat',
            'Muara Wahau', 'Muara Ancalong', 'Rantau Pulung', 'Karangan', 'Kaliorang', 'Kaubun', 'Bontang Lestari',
            'Bontang Baru', 'Bontang Kuala', 'Kanaan', 'Tanjung Laut', 'Tanjung Laut Indah', 'Gunung Elai',
            'Penajam', 'Waru', 'Babulu', 'Sepaku', 'Petung', 'Nipah-Nipah', 'Rintik', 'Tanah Grogot', 'Pasir Belengkong',
            'Kuaro', 'Long Ikis', 'Muara Komam', 'Long Kali', 'Batu Sopang', 'Tanjung Harapan', 'Muara Samu',
            'Melak', 'Barong Tongkok', 'Muara Lawa', 'Muara Pahu', 'Jempang', 'Bongan', 'Penyinggahan',
            'Bentian Besar', 'Linggang Bigung', 'Nyuatan', 'Siluq Ngurai', 'Tering', 'Sekolaq Darat',
            'Long Iram', 'Long Hubung', 'Laham', 'Long Bagun', 'Long Pahangai', 'Long Apari', 'Ujoh Bilang',
        ];

        $counter = 0;
        
        foreach ($distribusi as $kab) {
            $kabupaten = $kab['kabupaten'];
            $kecamatanList = $kab['kecamatan'];
            $totalDesa = $kab['total_desa'];
            $jumlahPln = $kab['pln'];
            $jumlahNonPln = $kab['non_pln'];
            $jumlahBelum = $kab['belum'];
            
            // Hitung desa per kecamatan (distribusi merata)
            $desaPerKecamatan = (int) ceil($totalDesa / count($kecamatanList));
            
            $desaDibuat = 0;
            $plnDibuat = 0;
            $nonPlnDibuat = 0;
            $belumDibuat = 0;
            
            foreach ($kecamatanList as $kecIndex => $kecamatan) {
                $sisaDesa = $totalDesa - $desaDibuat;
                $sisaKecamatan = count($kecamatanList) - $kecIndex;
                
                // Hitung berapa desa untuk kecamatan ini
                $jumlahDesaKec = min($desaPerKecamatan, $sisaDesa);
                
                if ($sisaKecamatan == 1) {
                    // Kecamatan terakhir, ambil semua sisa
                    $jumlahDesaKec = $sisaDesa;
                }
                
                for ($i = 0; $i < $jumlahDesaKec; $i++) {
                    $counter++;
                    
                    // Tentukan nama desa
                    $namaIndex = ($counter - 1) % count($namaDesaTemplate);
                    $namaDesa = $namaDesaTemplate[$namaIndex];
                    
                    // Tambahkan suffix jika nama sama
                    if ($i > 0 && $i % count($namaDesaTemplate) == 0) {
                        $namaDesa .= ' ' . ($i + 1);
                    }
                    
                    // Tentukan sumber listrik berdasarkan distribusi
                    $sumberListrik = null;
                    $hSurvei = 'Terlayani Listrik';
                    
                    if ($plnDibuat < $jumlahPln) {
                        $sumberListrik = 'PLN';
                        $plnDibuat++;
                    } elseif ($nonPlnDibuat < $jumlahNonPln) {
                        $sumberListrik = 'Non-PLN';
                        $nonPlnDibuat++;
                    } elseif ($belumDibuat < $jumlahBelum) {
                        $hSurvei = 'Belum Terlayani Listrik';
                        $sumberListrik = null;
                        $belumDibuat++;
                    }
                    
                    // Generate luas wilayah random (50-500 ha)
                    $luasWilayah = rand(50, 500) + (rand(0, 99) / 100);
                    
                    DataBerlistrik::create([
                        'NAMOBJ' => $namaDesa,
                        'LUASWH' => $luasWilayah,
                        'TIPADM' => 'Desa',
                        'WADMKC' => $kecamatan,
                        'WADMKD' => $namaDesa,
                        'WADMKK' => $kabupaten,
                        'WADMPR' => 'Kalimantan Timur',
                        'H_Survei' => $hSurvei,
                        'sumber_listrik' => $sumberListrik,
                    ]);
                    
                    $desaDibuat++;
                }
            }
            
            $this->command->info("✓ {$kabupaten}: {$desaDibuat} desa (PLN: {$plnDibuat}, Non-PLN: {$nonPlnDibuat}, Belum: {$belumDibuat})");
        }
        
        $this->command->info("========================================");
        $this->command->info("Total: {$counter} desa berhasil di-seed!");
        $this->command->info("========================================");
    }
}
