<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Batas_KabupatenKota extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__batas__kabupatenkota';

    protected $fillable = [
        'fid_ar_bat',
        'wadmpr',
        'wadmkk',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
    ];
}
