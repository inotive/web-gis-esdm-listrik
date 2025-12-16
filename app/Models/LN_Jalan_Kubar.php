<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_Kubar extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_kubar';

    protected $fillable = [
        'Nm_Ruas',
        'Fungsi',
        'Panjang',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'Panjang' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}