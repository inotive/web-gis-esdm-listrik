<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegVillage extends Model
{
    /**
     * Table name
     */
    protected $table = 'reg_villages';

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
        'district_id',
        'name',
    ];

    /**
     * Relationship: Village belongs to District (Kecamatan)
     */
    public function district()
    {
        return $this->belongsTo(RegDistrict::class, 'district_id', 'id');
    }

    /**
     * Relationship: Village belongs to Regency (Kabupaten/Kota) through District
     */
    public function regency()
    {
        return $this->hasOneThrough(
            RegRegency::class,
            RegDistrict::class,
            'id',           // Foreign key on districts table
            'id',           // Foreign key on regencies table
            'district_id',  // Local key on villages table
            'regency_id'    // Local key on districts table
        );
    }

    /**
     * Relationship: Village belongs to Province through District and Regency
     */
    public function province()
    {
        return $this->district()
            ->with('regency.province');
    }
}
