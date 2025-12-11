<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Batas_Desa extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__batas__desa';

    protected $fillable = [
        'fid_ar_bat',
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
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'luaswh'     => 'float',
        'luas'       => 'float',
        'shape_leng' => 'float',
        'tipadm'     => 'int',
    ];
}
