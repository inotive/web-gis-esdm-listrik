<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumens';

    protected $fillable = [
        'nama',
        'tipe',
        'parent_id',
        'path',
        'mime_type',
        'size',
        'user_id',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Relasi: Dokumen belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Dokumen belongs to Parent (Folder)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'parent_id');
    }

    /**
     * Relasi: Dokumen memiliki banyak Children (untuk folder)
     */
    public function children(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'parent_id')->orderBy('tipe', 'desc')->orderBy('nama');
    }

    /**
     * Helper: Cek apakah ini folder
     */
    public function isFolder(): bool
    {
        return $this->tipe === 'folder';
    }

    /**
     * Helper: Cek apakah ini file
     */
    public function isFile(): bool
    {
        return $this->tipe === 'file';
    }

    /**
     * Helper: Format ukuran file
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!$this->size) {
            return '-';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Helper: Get full path untuk file
     */
    public function getFullPathAttribute(): string
    {
        return 'dokumen/' . $this->path;
    }

    /**
     * Helper: Get URL untuk file
     */
    public function getUrlAttribute(): string
    {
        if ($this->isFolder()) {
            return route('admin.dokumen.index', ['folder' => $this->id]);
        }
        return asset('storage/' . $this->full_path);
    }
}
