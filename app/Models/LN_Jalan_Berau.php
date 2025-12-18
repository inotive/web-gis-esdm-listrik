<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_Berau extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_berau';

    protected $fillable = [
        'OBJECTID',
        'NO_RUAS',
        'NAMA_RUAS',
        'KAB_KOTA',
        'TTK_PNGKAL',
        'TTK_AKHIR',
        'PANJANG',
        'JKP_2',
        'JKP_3',
        'JKP_4',
        'JLP',
        'Jling_P',
        'JAS',
        'JKS',
        'JLS',
        'Jling_S',
        'FUNGSI',
        'STATUS',
        'Shape_Leng',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}