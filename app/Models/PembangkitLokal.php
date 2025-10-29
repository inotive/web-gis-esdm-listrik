<?php

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
     * Helper label lokasi (village, district, regency, province)
     */
    public function lokasiLabel(): string
    {
        $w = $this->wilayah;
        if (!$w) return '';
        $v = optional($w->village)->name;
        $d = optional($w->district)->name;
        $r = optional($w->regency)->name;
        $p = optional(optional($w->regency)->province)->name;

        return collect([$v, $d, $r, $p])->filter()->implode(', ');
    }
}
