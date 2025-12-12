<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Sistem_Jaringan_Energi_Paser extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__sistem__jaringan__energi__paser';

    protected $fillable = [
        'objectid',
        'jalan',
        'wadmkc',
        'wadmkd',
        'wadmkk',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
    ];
}
