<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaPengembanganBantuan extends Model
{
    use HasFactory;

    protected $table = 'rencana_pengembangan_bantuan';

    protected $fillable = [
        'regency_id',
        'district_id',
        'village_id',
        'lokasi',
        'status_desa_berlistrik',
        'kodifikasi',
        'jumlah_penduduk',
        'jumlah_calon_pelanggan',
        'aksesibilitas',
        'skor_aksesibilitas',
        'radius_jaringan',
        'skor_radius',
        'arah_kebijakan',
        'skor_arah_kebijakan',
        'potensi_kegiatan',
        'skor_potensi_kegiatan',
        'jumlah_pelanggan',
        'skor_jumlah_pelanggan',
        'total_skor',
        'rencana_sumber_listrik',
    ];

    protected $appends = [
        'prioritas',
    ];

    protected $casts = [
        'jumlah_penduduk' => 'integer',
        'jumlah_calon_pelanggan' => 'integer',
        'skor_aksesibilitas' => 'integer',
        'skor_radius' => 'integer',
        'skor_arah_kebijakan' => 'integer',
        'skor_potensi_kegiatan' => 'integer',
        'skor_jumlah_pelanggan' => 'integer',
        'total_skor' => 'integer',
    ];

    // Relationships
    public function regency()
    {
        return $this->belongsTo(RegRegency::class, 'regency_id');
    }

    public function district()
    {
        return $this->belongsTo(RegDistrict::class, 'district_id');
    }

    public function village()
    {
        return $this->belongsTo(RegVillage::class, 'village_id');
    }

    // Dropdown options with scores
    public static function getAksesibilitasOptions()
    {
        return [
            'Terdapat Jaringan Jalan dengan Lebar Lebih dari 6 Meter dan Tersambung dengan Jalan Penghubung Lainnya' => 5,
            'Terdapat Jaringan Jalan dengan Lebar Lebih dari 4 Meter dan Tersambung dengan Jalan Penghubung Lainnya' => 4,
            'Terdapat Jaringan Jalan yang memerlukan Rekayasa Teknis' => 4,
            'Tidak Terdapat Jaringan Jalan dan Dekat dengan Jalan Penghubung Lainnya' => 3,
            'Tidak Terdapat Jaringan Jalan dan Tidak Tersambung dengan Jalan Penghubung Lain' => 1,
        ];
    }

    public static function getRadiusJaringanOptions()
    {
        return [
            '<100 Meter' => 5,
            '<250 Meter' => 4,
            '<500 Meter' => 3,
            '<1.500 Meter' => 2,
            '<5.000 Meter' => 1,
        ];
    }

    public static function getArahKebijakanOptions()
    {
        return [
            'Sesuai Dengan RTR dan Tidak Terdapat Perizinan Kawasan Hutan Lainnya' => 5,
            'Sesuai Dengan RTR dan Terdapat Perizinan Berusaha Lainnya/Tidak Terdapat Perizinan Berusaha Lainnya' => 4,
            'Tidak Sesuai Dengan RTR dan Terdapat Perizinan Berusaha Lainnya' => 3,
            'Tidak Sesuai Dengan RTR dan Tidak Terdapat Perizinan Berusaha Lainnya' => 2,
            'Tidak Sesuai Dengan RTR dan Terdapat Perizinan Kawasan Hutan Lainnya' => 1,
        ];
    }

    public static function getPotensiKegiatanOptions()
    {
        return [
            'Terdapat Pusat Kegiatan Eksisting  dan Mengalami Pertumbuhan' => 5,
            'Terdapat Pusat Kegiatan Eksisting' => 4,
            'Terdapat Potensi Perkembangan Kegiatan' => 3,
            'Terdapat Kegiatan Non Pendukung (Pertanian, Perkebunan)' => 2,
            'Tidak Terdapat Kegiatan Pendukung' => 1,
        ];
    }

    public static function getJumlahPelangganOptions()
    {
        return [
            '100' => 5,
            '50' => 4,
            '35' => 3,
            '20' => 2,
            '10' => 1,
        ];
    }

    // Calculate total score
    public function calculateTotalSkor()
    {
        $this->total_skor =
            ($this->skor_aksesibilitas ?? 0) +
            ($this->skor_radius ?? 0) +
            ($this->skor_arah_kebijakan ?? 0) +
            ($this->skor_potensi_kegiatan ?? 0) +
            ($this->skor_jumlah_pelanggan ?? 0);

        return $this->total_skor;
    }

    // Accessor for prioritas
    public function getPrioritasAttribute()
    {
        $skor = $this->total_skor;
        
        if ($skor >= 19 && $skor <= 25) {
            return 1;
        } elseif ($skor >= 15 && $skor <= 18) {
            return 2;
        } elseif ($skor >= 5 && $skor <= 14) {
            return 3;
        }
        
        return null;
    }
}
