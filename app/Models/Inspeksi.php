<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspeksi extends Model
{
    use HasFactory;

    protected $table = 'inspeksis';

    protected $fillable = [
        'tanggal',
        'referensi_izin',
        'perusahaan_id',
        'ditambahkan_oleh',
        'berita_acara',
        'lampiran',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relationship: Inspeksi belongs to User (who added it)
     */
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'ditambahkan_oleh');
    }

    /**
     * Relationship: Inspeksi belongs to Perusahaan
     */
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}
