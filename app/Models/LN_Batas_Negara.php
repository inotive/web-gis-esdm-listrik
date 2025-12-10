<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Batas_Negara extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__batas__negara';

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
