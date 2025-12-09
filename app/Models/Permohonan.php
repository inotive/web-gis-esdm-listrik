<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permohonan extends Model
{
    use HasFactory;

    protected $table = 'permohonans';

    protected $fillable = [
        'nama',
        'jenis_permohonan',
        'keterangan',
    ];

    /**
     * Relasi: Permohonan memiliki banyak PermohonanQuestion
     */
    public function questions(): HasMany
    {
        return $this->hasMany(PermohonanQuestion::class, 'permohonan_id');
    }
}
