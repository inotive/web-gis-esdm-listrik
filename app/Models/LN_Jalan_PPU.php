<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_PPU extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_ppu';

    protected $fillable = [
        'OID_',
        'Name',
        'FolderPath',
        'SymbolID',
        'Clamped',
        'Shape_Leng',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}