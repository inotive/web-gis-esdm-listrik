<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Rencana_Pembangkit_Tenaga_Listrik_Bontang extends Model
{
    use HasFactory;

    protected $table = 'table__p_t__rencana__pembangkit__tenaga__listrik__bontang';

    protected $fillable = [
        'id_external',
        'nama',
        'arahan',
        'fungsi_eks',
        'fungsi_ren',
        'penjelasan',
        'sumber',
        'geometry',
    ];

    protected $casts = [
        'id_external' => 'int',
        'geometry'    => 'array',
    ];
}
