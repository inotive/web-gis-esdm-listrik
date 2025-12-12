<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataJalanNasional extends Model
{
    use HasFactory;

    protected $table = 'table_data_jalan';

    protected $fillable = [
        'objectid',
        'fungsi_jal',
        'nama_jln',
        'sumber',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'shape_leng' => 'float',
        'geometry'   => 'array',   // otomatis decode json ke array
    ];
}
