<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Sistem_Jaringan_Energi_Kubar_UP2KB extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__sistem__jaringan__energi__kubar__up2kb';

    protected $fillable = [
        'objectid',
        'descriptio',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
    ];
}
