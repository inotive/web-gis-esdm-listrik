<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermohonanUser extends Model
{
    use HasFactory;

    protected $table = 'permohonan_users';

    protected $fillable = [
        'permohonan_id',
        'user_id',
        'status',
        'jawaban',
        'keterangan',
    ];

    protected $casts = [
        'jawaban' => 'array',
    ];

    /**
     * Relasi: PermohonanUser belongs to Permohonan
     */
    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    /**
     * Relasi: PermohonanUser belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: PermohonanUser memiliki banyak PermohonanUserDocument
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PermohonanUserDocument::class, 'permohonan_user_id');
    }
}
