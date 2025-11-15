<?php
// app/Models/Gardu.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gardu extends Model
{
    protected $table = 'gardus';

    protected $fillable = [
        'nama',
        'lokasi',
        'jenis_gardu_distribusi',
        'wilayah_id',
    ];

    /**
     * Relasi ke wilayah
     */
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id', 'id');
    }

    /**
     * Relasi ke province via wilayah
     */
    public function province()
    {
        return $this->hasOneThrough(
            RegProvince::class,
            Wilayah::class,
            'id',           // FK di wilayah (wilayah_id di gardu)
            'id',           // PK di province
            'wilayah_id',   // FK lokal di gardu
            'regency_id'    // FK di wilayah yang menunjuk ke regency, lalu ke province
        )->join('reg_regencies', 'reg_regencies.id', '=', 'wilayah.regency_id')
         ->where('reg_provinces.id', '=', \DB::raw('reg_regencies.province_id'));
    }

    /**
     * Relasi ke regency via wilayah
     */
    public function regency()
    {
        return $this->hasOneThrough(
            RegRegency::class,
            Wilayah::class,
            'id',           // FK di wilayah
            'id',           // PK di regency
            'wilayah_id',   // FK lokal di gardu
            'regency_id'    // FK di wilayah
        );
    }

    /**
     * Relasi ke district via wilayah
     */
    public function district()
    {
        return $this->hasOneThrough(
            RegDistrict::class,
            Wilayah::class,
            'id',           // FK di wilayah
            'id',           // PK di district
            'wilayah_id',   // FK lokal di gardu
            'district_id'   // FK di wilayah
        );
    }

    /**
     * Relasi ke village via wilayah
     */
    public function village()
    {
        return $this->hasOneThrough(
            RegVillage::class,
            Wilayah::class,
            'id',           // FK di wilayah
            'id',           // PK di village
            'wilayah_id',   // FK lokal di gardu
            'village_id'    // FK di wilayah
        );
    }

    /**
     * Accessor untuk mendapatkan lokasi lengkap dari wilayah
     */
    public function getLokasiLengkapAttribute()
    {
        if (!$this->wilayah) {
            return $this->lokasi ?: '—';
        }

        $parts = array_filter([
            optional($this->wilayah->village)->name,
            optional($this->wilayah->district)->name,
            optional($this->wilayah->regency)->name,
            optional($this->wilayah->province)->name,
        ]);

        return implode(', ', $parts) ?: $this->lokasi ?: '—';
    }
}