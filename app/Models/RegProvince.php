<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegProvince extends Model
{
    /**
     * Table name
     */
    protected $table = 'reg_provinces';

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
        'name',
    ];

    /**
     * Relationship: Province has many Regencies (Kabupaten/Kota)
     */
    public function regencies()
    {
        return $this->hasMany(RegRegency::class, 'province_id', 'id');
    }

    /**
     * Relationship: Province has many Districts (Kecamatan) through Regencies
     */
    public function districts()
    {
        return $this->hasManyThrough(
            RegDistrict::class,
            RegRegency::class,
            'province_id', // Foreign key on regencies table
            'regency_id',   // Foreign key on districts table
            'id',           // Local key on provinces table
            'id'            // Local key on regencies table
        );
    }
}
