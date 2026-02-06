<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermohonanUser extends Model
{
    use HasFactory, \App\Helpers\UploadFile;

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
        'nama_pemohon_import',
        'jenis_permohonan_import',
        'nama_perusahaan_import',
    ];

    protected $casts = [
        'jawaban' => 'array',
        'approved_at' => 'datetime',
    ];

    // Accessor for Applicant Name (User Name or Imported Name)
    public function getApplicantNameAttribute()
    {
        return $this->user ? $this->user->name : $this->nama_pemohon_import;
    }

    // Accessor for Permohonan Type Name (Category Name or Imported Name)
    public function getTypeNameAttribute()
    {
        return $this->permohonan ? $this->permohonan->nama : $this->jenis_permohonan_import;
    }

    // Accessor for Company Name (Perusahaan Name or Imported Name)
    public function getCompanyNameAttribute()
    {
        return $this->perusahaan ? $this->perusahaan->nama : $this->nama_perusahaan_import;
    }

    protected static function booted()
    {
        static::deleting(function ($permohonanUser) {
            // 1. Clean up associated documents
            foreach ($permohonanUser->documents as $document) {
                if ($document->dokumen) {
                    // Delete physical file from permohonan-documents
                    $permohonanUser->deleteFile($document->dokumen->path, 'permohonan-documents');
                    // Delete the Dokumen model record
                    $document->dokumen->delete();
                }
                // Delete the relationship record
                $document->delete();
            }

            // 2. Clean up files in jawaban array
            if (is_array($permohonanUser->jawaban)) {
                foreach ($permohonanUser->jawaban as $value) {
                    if (is_array($value)) {
                        foreach ($value as $file) {
                            if (is_string($file)) $permohonanUser->deleteFile($file, 'permohonan-jawaban');
                        }
                    } elseif (is_string($value)) {
                        $permohonanUser->deleteFile($value, 'permohonan-jawaban');
                    }
                }
            }
        });
    }

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
