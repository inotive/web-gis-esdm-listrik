<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_Kutim extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_kutim';

    protected $fillable = [
        'Kl_Dat_Das',
        'No_Ruas',
        'Nm_Ruas',
        'Fungsi',
        'Kecamatan',
        'Desa_Kel',
        'Tk_Ruas_Aw',
        'Tk_Ruas_Ak',
        'Panjang',
        'Koord_X_Aw',
        'Koord_Y_Aw',
        'Koord_X_Ak',
        'Koord_Y_Ak',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'Panjang' => 'decimal:8',
        'Koord_X_Aw' => 'decimal:8',
        'Koord_Y_Aw' => 'decimal:8',
        'Koord_X_Ak' => 'decimal:8',
        'Koord_Y_Ak' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}