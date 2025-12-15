<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_Balikpapan extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_balikpapan';

    protected $fillable = [
        'Kecamatan',
        'F18',
        'F19',
        'F20',
        'F21',
        'KODE_RUAS',
        'NAMA_RUAS',
        'TAHUN_DATA',
        'FUNGSI',
        'LEBAR',
        'PANJANG',
        'KOORD_X_AW',
        'KOORD_Y_AW',
        'KOORD_X_AK',
        'KOORD_Y_AK',
        'Shape_Le_1',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}