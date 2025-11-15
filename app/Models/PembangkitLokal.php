<?php
// app/Models/PembangkitLokal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembangkitLokal extends Model
{
    protected $table = 'pembangkit_lokals';

    protected $fillable = [
        'wilayah_id',
        'kapasitas_gardu',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id', 'id');
    }

    /**
     * Accessor untuk mendapatkan lokasi lengkap dari wilayah
     */
    public function getLokasiLengkapAttribute()
    {
        if (!$this->wilayah) return '—';

        $parts = array_filter([
            optional($this->wilayah->village)->name,
            optional($this->wilayah->district)->name,
            optional($this->wilayah->regency)->name,
            optional(optional($this->wilayah->regency)->province)->name,
        ]);

        return implode(', ', $parts) ?: '—';
    }

    /**
     * Helper label lokasi (backward compatibility)
     */
    public function lokasiLabel(): string
    {
        return $this->lokasi_lengkap;
    }
}