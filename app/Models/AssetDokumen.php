<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AssetDokumen extends Model
{
    use HasFactory;

    protected $table = 'asset_dokumen';

    protected $fillable = [
        'asset_id',
        'no_sertif',
        'tgl_sertif',
        'nama_sertifikat',
        // ✅ KOLOM BARU
        'no_dokumen',
        'tanggal_dokumen',
        'tanggal_oleh',
        'tanggal_buku',
        'has_konfir',
        // Existing
        'sts_digit',
        'sts_sertif',
        'ket_sertif',
        'konf_tanah',
        'file_sertif',
        'link_sertif',
    ];

    protected $casts = [
        'tgl_sertif' => 'date',
        // ✅ CAST KOLOM BARU
        'tanggal_dokumen' => 'date',
        'tanggal_oleh' => 'date',
        'tanggal_buku' => 'date',
        'has_konfir' => 'boolean',
    ];

    /**
     * Relasi ke Asset
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id', 'id');
    }

    /**
     * Accessor untuk URL file sertifikat
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_sertif) {
            return null;
        }

        return Storage::url($this->file_sertif);
    }

    /**
     * Accessor untuk cek apakah file exists
     */
    public function getHasFileAttribute(): bool
    {
        return $this->file_sertif && Storage::exists($this->file_sertif);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('sts_sertif', 'LIKE', "%{$status}%");
    }

    /**
     * Scope untuk filter sudah terdigitalisasi
     */
    public function scopeDigitalized($query)
    {
        return $query->whereNotNull('file_sertif');
    }

    /**
     * ✅ Scope untuk filter dokumen yang sudah dikonfirmasi
     */
    public function scopeConfirmed($query)
    {
        return $query->where('has_konfir', true);
    }
}