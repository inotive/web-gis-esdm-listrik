<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Relationship: Perusahaan memiliki banyak Permohonan
     * Note: Relasi ini memerlukan kolom perusahaan_id di tabel permohonans
     */
    public function permohonans(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'perusahaan_id');
    }

    /**
     * Relationship: Perusahaan memiliki banyak PermohonanUser
     * Note: Relasi ini memerlukan kolom perusahaan_id di tabel permohonan_users
     */
    public function permohonanUsers(): HasMany
    {
        return $this->hasMany(PermohonanUser::class, 'perusahaan_id');
    }

    /**
     * Relationship: Perusahaan memiliki banyak Perizinan
     */
    public function perizinans(): HasMany
    {
        return $this->hasMany(Perizinan::class, 'perusahaan_id');
    }
}
