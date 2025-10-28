<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gardu extends Model
{
    protected $table = 'gardus';

    protected $fillable = [
        'nama',
        'lokasi',
        'jenis_gardu_distribusi',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
    ];

    /**
     * Relasi ke master wilayah (tanpa FK di DB).
     */
    public function province()
    {
        return $this->belongsTo(RegProvince::class, 'province_id', 'id');
    }

    public function regency()
    {
        return $this->belongsTo(RegRegency::class, 'regency_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(RegDistrict::class, 'district_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(RegVillage::class, 'village_id', 'id');
    }
}
