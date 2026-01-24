<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinans';

    protected $fillable = [
        'nama',
        'perusahaan_id',
        'kontak',
        'jenis',
        'no_pengajuan',
        'no_surat_keluar',
        'tanggal',
        'lokasi',
        'status_kelistrikan',
        'titik_koordinat',
        'jumlah_kapasitas',
        'total_kapasitas_kva',
        'jenis_penggunaan',
        'sifat_penggunaan',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_kapasitas_kva' => 'decimal:2',
    ];

    /**
     * Relasi: Perizinan belongs to Perusahaan
     */
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    /**
     * Relasi: Perizinan memiliki banyak PerizinanDocument
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PerizinanDocument::class, 'perizinan_id');
    }
}
