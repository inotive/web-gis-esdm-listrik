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
        'perusahaan_id',
        'nama_perusahaan',
        'kontak',
        'jenis',
        'no_pengajuan',
        'no_surat_keluar',
        'no_surat_izin_terbit',
        'tanggal',
        'tanggal_akhir',
        'status_izin',
        'lokasi',
        'titik_koordinat',
        'jumlah',
        'kapasitas',
        'jumlah_kapasitas',
        'total_kapasitas_kva',
        'jenis_penggunaan',
        'sifat_penggunaan',
        'catatan',
        'file_izin',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_akhir' => 'date',
        'kapasitas' => 'decimal:2',
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
