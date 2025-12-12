<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Sistem_Jaringan_Energi_Kukar extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__sistem__jaringan__energi__kukar';

    protected $fillable = [
        'objectid',
        'namobj',
        'orde01',
        'orde02',
        'orde03',
        'orde04',
        'jnsrsr',
        'stsjrn',
        'wadmpr',
        'wadmkk',
        'remark',
        'sbdata',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
    ];
}
