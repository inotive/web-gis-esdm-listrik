<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegDistrict extends Model
{
    /**
     * Table name
     */
    protected $table = 'reg_districts';

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
        'regency_id',
        'name',
    ];

    /**
     * Relationship: District belongs to Regency (Kabupaten/Kota)
     */
    public function regency()
    {
        return $this->belongsTo(RegRegency::class, 'regency_id', 'id');
    }

    /**
     * Relationship: District has many Villages (Kelurahan/Desa)
     */
    public function villages()
    {
        return $this->hasMany(RegVillage::class, 'district_id', 'id');
    }

    /**
     * Relationship: District belongs to Province through Regency
     */
    public function province()
    {
        return $this->hasOneThrough(
            RegProvince::class,
            RegRegency::class,
            'id',           // Foreign key on regencies table
            'id',           // Foreign key on provinces table
            'regency_id',   // Local key on districts table
            'province_id'   // Local key on regencies table
        );
    }
}
