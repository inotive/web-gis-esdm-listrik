<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perusahaan extends Model
{
    protected $table = 'perusahaans';

    protected $fillable = [
        'nama',
        'nama_pimpinan',
        'alamat',
        'village_id',
        'kontak',
        'jenis_usaha',
        'kabupaten_kota',
    ];

    /**
     * Relationship: Perusahaan memiliki banyak PembangkitListrik
     */
    public function pembangkitListriks(): HasMany
    {
        return $this->hasMany(PembangkitListrik::class, 'perusahaan_id');
    }

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

    /**
     * Relationship: Perusahaan memiliki banyak Gardu
     */
    public function gardus(): HasMany
    {
        return $this->hasMany(Gardu::class, 'perusahaan_id');
    }

    /**
     * Relationship: Perusahaan memiliki banyak InfrastrukturJaringan
     */
    public function infrastrukturJaringans(): HasMany
    {
        return $this->hasMany(InfrastrukturJaringan::class, 'perusahaan_id');
    }

    /**
     * Relationship: Perusahaan memiliki banyak PembangkitLokal
     */
    public function pembangkitLokals(): HasMany
    {
        return $this->hasMany(PembangkitLokal::class, 'perusahaan_id');
    }

    /**
     * Relationship: Perusahaan memiliki banyak Inspeksi
     */
    public function inspeksis(): HasMany
    {
        return $this->hasMany(Inspeksi::class, 'perusahaan_id');
    }
}
