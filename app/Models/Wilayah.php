<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';

    protected $fillable = [
        'regency_id',
        'district_id',
        'village_id',
        'lat',
        'lng',
        'polygon_geojson',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        // biarkan polygon_geojson sebagai string; bisa Anda cast ke array jika mau:
        // 'polygon_geojson' => 'array',
    ];

    // ====== Relasi ke master ======
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

    // Province via Regency
    public function province()
    {
        return $this->hasOneThrough(
            RegProvince::class,
            RegRegency::class,
            'id',          // FK di regencies yang cocok dengan regency_id kita
            'id',          // PK Province
            'regency_id',  // FK lokal di tabel wilayah
            'province_id'  // FK di regencies menunjuk ke province
        );
    }

    // ====== Scopes mini untuk index ======
    public function scopeSearch($q, ?string $keyword)
    {
        if (!$keyword) return $q;
        return $q->whereHas('village', function ($qq) use ($keyword) {
            $qq->where('name', 'like', "%{$keyword}%");
        });
    }

    public function scopeByRegency($q, ?string $regencyId)
    {
        if (!$regencyId) return $q;
        return $q->where('regency_id', $regencyId);
    }

    public function scopeByDistrict($q, ?string $districtId)
    {
        if (!$districtId) return $q;
        return $q->where('district_id', $districtId);
    }
}
