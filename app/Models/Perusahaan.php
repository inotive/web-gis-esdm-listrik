<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'perusahaans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'village_id',
    ];

    /**
     * Relationship: Perusahaan belongs to Village (Desa)
     */
    public function village()
    {
        return $this->belongsTo(RegVillage::class, 'village_id', 'id');
    }
}
