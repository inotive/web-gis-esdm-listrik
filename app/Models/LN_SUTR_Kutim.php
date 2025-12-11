<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_SUTR_Kutim extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__sutr__kutim';

    protected $fillable = [
        'objectid',
        'classifica',
        'globalid_1',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
    ];
}
