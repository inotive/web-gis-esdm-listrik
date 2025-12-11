<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN2_SUTM_Paser extends Model
{
    use HasFactory;

    protected $table = 'table__l_n2__sutm__paser';

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
