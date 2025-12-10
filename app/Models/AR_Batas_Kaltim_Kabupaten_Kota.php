<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AR_Batas_Kaltim_Kabupaten_Kota extends Model
{
    use HasFactory;

    protected $table = 'table__a_r__batas__kaltim__kabupaten__kota';

    protected $fillable = [
        'objectid',
        'wadmpr',
        'wadmkk',
        'shape_leng',
        'shape_area',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
        'shape_area' => 'float',
    ];
}
