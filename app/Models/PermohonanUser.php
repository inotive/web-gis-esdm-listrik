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
        'perusahaan_id',

        'status',
        'jawaban',
        'keterangan',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'approved_at' => 'datetime',
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

    /**
     * Relasi: PermohonanUser belongs to Perusahaan
     */
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }


    /**
     * Header relation: PermohonanUser belongs to User (approver)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the status attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        if ($value === 'selesai') {
            // Check if documents relation is loaded to avoid N+1 if possible, or just access it.
            // Using logic: if any document has expired validity date, status is expired.
            foreach ($this->documents as $doc) {
                if ($doc->masa_berlaku && $doc->masa_berlaku->endOfDay()->isPast()) {
                    return 'expired';
                }
            }
        }

        return $value;
    }


}
