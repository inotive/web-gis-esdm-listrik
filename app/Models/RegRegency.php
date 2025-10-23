<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegRegency extends Model
{
    /**
     * Table name
     */
    protected $table = 'reg_regencies';

    /**
     * Primary key
     */
    protected $primaryKey = 'id';

    /**
     * Primary key type
     */
    protected $keyType = 'string';

    /**
     * Incrementing
     */
    public $incrementing = false;

    /**
     * Timestamps
     */
    public $timestamps = false;

    /**
     * Fillable fields
     */
    protected $fillable = [
        'id',
        'province_id',
        'name',
    ];

    /**
     * Relationship: Regency belongs to Province
     */
    public function province()
    {
        return $this->belongsTo(RegProvince::class, 'province_id', 'id');
    }

    /**
     * Relationship: Regency has many Districts (Kecamatan)
     */
    public function districts()
    {
        return $this->hasMany(RegDistrict::class, 'regency_id', 'id');
    }

    /**
     * Relationship: Regency has many Villages (Kelurahan/Desa) through Districts
     */
    public function villages()
    {
        return $this->hasManyThrough(
            RegVillage::class,
            RegDistrict::class,
            'regency_id',   // Foreign key on districts table
            'district_id',  // Foreign key on villages table
            'id',           // Local key on regencies table
            'id'            // Local key on districts table
        );
    }
}
