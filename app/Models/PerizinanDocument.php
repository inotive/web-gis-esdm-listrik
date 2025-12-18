<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerizinanDocument extends Model
{
    use HasFactory;

    protected $table = 'perizinan_documents';

    protected $fillable = [
        'perizinan_id',
        'dokumen_id',
        'nama',
        'no_surat_izin_terbit',
        'tanggal_terbit',
        'tanggal_akhir',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_akhir' => 'date',
    ];

    /**
     * Relasi: PerizinanDocument belongs to Perizinan
     */
    public function perizinan(): BelongsTo
    {
        return $this->belongsTo(Perizinan::class, 'perizinan_id');
    }

    /**
     * Relasi: PerizinanDocument belongs to Dokumen
     */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}
