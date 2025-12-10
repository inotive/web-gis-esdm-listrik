<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AR_Batas_Kaltim_Full_KK_KC_KD extends Model
{
    use HasFactory;

    protected $table = 'table__a_r__batas__kaltim__full__k_k__k_c__k_d';

    protected $fillable = [
        'objectid',
        'wadmpr',
        'wadmkk',
        'wadmkc',
        'wadmkd',
        'namobj',
        'tipadm',
        'remark',
        'uupp',
        'luaswh',
        'luas',
        'shape_leng',
        'shape_area',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'luaswh'     => 'float',
        'luas'       => 'float',
        'shape_leng' => 'float',
        'shape_area' => 'float',
        'tipadm'     => 'int',
    ];
}
