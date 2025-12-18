<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapElektrifikasi extends Model
{
    use HasFactory;

    protected $table = 'rekap_elektrifikasis';

    protected $fillable = [
        'tahun',
        'no_urut',
        'kabupaten_kota',
        'jumlah_desa',
        'jumlah_kk',
        'jumlah_penduduk',
        'desa_berlistrik_pln',
        'desa_berlistrik_non_pln',
        'desa_berlistrik_jumlah',
        'desa_belum_berlistrik',
        'kk_berlistrik_pln',
        'kk_berlistrik_non_pln',
        'kk_berlistrik_jumlah',
        'rasio_desa_berlistrik',
        'jumlah_kk_belum_berlistrik',
        'rasio_elektrifikasi',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah_desa' => 'integer',
        'jumlah_kk' => 'integer',
        'jumlah_penduduk' => 'integer',
        'desa_berlistrik_pln' => 'integer',
        'desa_berlistrik_non_pln' => 'integer',
        'desa_berlistrik_jumlah' => 'integer',
        'desa_belum_berlistrik' => 'integer',
        'kk_berlistrik_pln' => 'integer',
        'kk_berlistrik_non_pln' => 'integer',
        'kk_berlistrik_jumlah' => 'integer',
        'rasio_desa_berlistrik' => 'decimal:2',
        'jumlah_kk_belum_berlistrik' => 'integer',
        'rasio_elektrifikasi' => 'decimal:2',
    ];

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
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
     * Hitung total untuk semua kabupaten/kota di suatu tahun
     */
    public static function getTotalByYear($tahun)
    {
        $data = self::where('tahun', $tahun)->get();

        if ($data->isEmpty()) {
            return null;
        }

        $total = [
            'jumlah_desa' => $data->sum('jumlah_desa'),
            'jumlah_kk' => $data->sum('jumlah_kk'),
            'jumlah_penduduk' => $data->sum('jumlah_penduduk'),
            'desa_berlistrik_pln' => $data->sum('desa_berlistrik_pln'),
            'desa_berlistrik_non_pln' => $data->sum('desa_berlistrik_non_pln'),
            'desa_berlistrik_jumlah' => $data->sum('desa_berlistrik_jumlah'),
            'desa_belum_berlistrik' => $data->sum('desa_belum_berlistrik'),
            'kk_berlistrik_pln' => $data->sum('kk_berlistrik_pln'),
            'kk_berlistrik_non_pln' => $data->sum('kk_berlistrik_non_pln'),
            'kk_berlistrik_jumlah' => $data->sum('kk_berlistrik_jumlah'),
            'jumlah_kk_belum_berlistrik' => $data->sum('jumlah_kk_belum_berlistrik'),
        ];

        // Hitung rasio total
        $total['rasio_desa_berlistrik'] = $total['jumlah_desa'] > 0
            ? round(($total['desa_berlistrik_jumlah'] / $total['jumlah_desa']) * 100, 2)
            : 0;
        $total['rasio_elektrifikasi'] = $total['jumlah_kk'] > 0
            ? round(($total['kk_berlistrik_jumlah'] / $total['jumlah_kk']) * 100, 2)
            : 0;

        return $total;
    }
}
