<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerizinanListrik extends Model
{
    use HasFactory;

    protected $table = 'perizinan_listriks';

    protected $fillable = [
        'tahun',
        'kabupaten_kota',
        'nama_pemohon',
        'kontak',
        'jenis_usaha',
        'no_pengajuan',
        'no_surat_keluar',
        'tanggal_perizinan',
        'no_surat_izin',
        'tanggal_terbit',
        'tanggal_akhir',
        'lokasi',
        'koordinat',
        'jumlah_unit',
        'kapasitas',
        'total_kapasitas',
        'jenis',
        'sifat_penggunaan',
        'catatan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'tanggal_perizinan' => 'date',
        'tanggal_terbit' => 'date',
        'tanggal_akhir' => 'date',
        'jumlah_unit' => 'integer',
        'kapasitas' => 'decimal:2',
        'total_kapasitas' => 'decimal:2',
    ];

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk filter berdasarkan kabupaten/kota
     */
    public function scopeKabupaten($query, $kabupaten)
    {
        return $query->where('kabupaten_kota', $kabupaten);
    }

    /**
     * Scope untuk filter berdasarkan jenis
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    /**
     * Get daftar tahun yang tersedia
     */
    public static function getAvailableYears()
    {
        return self::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
    }

    /**
     * Get daftar kabupaten/kota yang tersedia
     */
    public static function getKabupatenList()
    {
        return self::select('kabupaten_kota')
            ->distinct()
            ->orderBy('kabupaten_kota')
            ->pluck('kabupaten_kota');
    }

    /**
     * Get total kapasitas per kabupaten
     */
    public static function getTotalKapasitasPerKabupaten($tahun = null)
    {
        $query = self::selectRaw('kabupaten_kota, SUM(total_kapasitas) as total')
            ->groupBy('kabupaten_kota');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        return $query->get();
    }
}
