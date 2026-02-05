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

    const DEFAULT_JENIS_PERIZINAN = [
        'IUPTLS',
        'IUJPTL',
        'SKTP',
        'SKT-TL',
        'STP-TL',
        'IO'
    ];

    protected $fillable = [
        'perusahaan_id',
        'nama_perusahaan',
        'kontak',
        'jenis',
        'no_pengajuan',
        'no_surat_keluar',
        'no_surat_izin_terbit',
        'tanggal',
        'tanggal_terbit',
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
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_terbit' => 'date',
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

    /**
     * Accessor: Hitung status berdasarkan tanggal akhir
     */
    public function getCalculatedStatusAttribute(): string
    {
        if (!$this->tanggal_akhir) {
            return 'Berakhir'; // Default if no end date
        }

        $today = now()->startOfDay();
        $endDate = $this->tanggal_akhir->startOfDay();
        $warningDate = $today->copy()->addDays(30);

        if ($endDate->lt($today)) {
            return 'Berakhir';
        } elseif ($endDate->lte($warningDate)) {
            return 'Mau Berakhir';
        } else {
            return 'Sedang Aktif';
        }
    }
}
