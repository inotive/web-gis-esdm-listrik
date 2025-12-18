<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanUserDocument extends Model
{
    use HasFactory;

    protected $table = 'permohonan_user_documents';

    protected $fillable = [
        'user_id',
        'permohonan_user_id',
        'nama',
        'masa_berlaku',
        'dokumen_id',
    ];

    protected $casts = [
        'masa_berlaku' => 'date',
    ];

    /**
     * Relasi: PermohonanUserDocument belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: PermohonanUserDocument belongs to PermohonanUser
     */
    public function permohonanUser(): BelongsTo
    {
        return $this->belongsTo(PermohonanUser::class, 'permohonan_user_id');
    }

    /**
     * Relasi: PermohonanUserDocument belongs to Dokumen
     */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}
