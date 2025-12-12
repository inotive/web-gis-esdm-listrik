<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Batas_Provinsi extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__batas__provinsi';

    protected $fillable = [
        'fid_export',
        'wadmpr',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
    ];
}
