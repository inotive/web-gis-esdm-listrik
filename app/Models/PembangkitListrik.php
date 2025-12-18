<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembangkitListrik extends Model
{
    protected $table = 'pembangkit_listriks';

    protected $fillable = [
        'perusahaan_id',
        'perizinan_listrik_id',
        'lokasi',
        'koordinat',
        'jumlah_unit',
        'kapasitas',
        'total_kapasitas',
        'jenis',
        'sifat_penggunaan',
        'catatan',
    ];

    protected $casts = [
        'jumlah_unit' => 'integer',
        'kapasitas' => 'decimal:2',
        'total_kapasitas' => 'decimal:2',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function perizinanListrik(): BelongsTo
    {
        return $this->belongsTo(PerizinanListrik::class);
    }
}
