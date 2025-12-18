<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_KutaiKartanegara extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_kutai_kartanegara';

    protected $fillable = [
        'NO_LAMA',
        'NO_BARU',
        'NAMA_LAMA',
        'NAMA_BARU',
        'P_Km',
        'KECAMATAN',
        'URUT',
        'PANGKAL',
        'UJUNG',
        'KOOR_PANGK',
        'KOOR_UJUNG',
        'LEBAR_M',
        'FUNGSI',
        'HISTORY',
        'Panjang',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'P_Km' => 'decimal:8',
        'LEBAR_M' => 'decimal:2',
        'Panjang' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}