<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfrastrukturJaringan extends Model
{
    // Gunakan nama tabel eksplisit agar tidak jadi "infrastruktur_jaringans"
    protected $table = 'infrastruktur_jaringan';

    protected $fillable = [
        'jaringan',          // 'distribusi' | 'transmisi'
        'jenis',             // teks
        'panjang_jaringan',  // decimal
        'perusahaan_id',
    ];

    protected $casts = [
        'panjang_jaringan' => 'decimal:2',
    ];

    /**
     * Relasi ke perusahaan
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id', 'id');
    }
}
