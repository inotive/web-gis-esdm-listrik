<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Sistem_Jaringan_Energi_Kubar extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__sistem__jaringan__energi__kubar';

    protected $fillable = [
        'objectid',
        'name',
        'descriptio',
        'timestamp',
        'begin',
        'end',
        'altitudemo',
        'tessellate',
        'extrude',
        'visibility',
        'draworder',
        'icon',
        'layer',
        'path',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
        'tessellate' => 'int',
        'extrude'    => 'int',
        'visibility' => 'int',
        'draworder'  => 'int',
    ];
}
