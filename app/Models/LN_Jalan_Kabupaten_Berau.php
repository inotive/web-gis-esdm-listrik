<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_Kabupaten_Berau extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__jalan__kabupaten__berau';

    protected $fillable = [
        'objectid',
        'no_ruas',
        'nama_ruas',
        'kab_kota',
        'ttk_pngkal',
        'ttk_akhir',
        'panjang',
        'jkp_2',
        'jkp_3',
        'jkp_4',
        'jlp',
        'jling_p',
        'jas',
        'jks',
        'jls',
        'jling_s',
        'fungsi',
        'status',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'panjang'    => 'float',
        'jling_p'    => 'float',
        'shape_leng' => 'float',
    ];
}