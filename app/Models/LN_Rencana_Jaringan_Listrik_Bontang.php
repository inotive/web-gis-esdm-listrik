<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Rencana_Jaringan_Listrik_Bontang extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__rencana__jaringan__listrik__bontang';

    protected $fillable = [
        'source_id',
        'rencana',
        'fungsi_eks',
        'fungsi_ren',
        'keterangan',
        'sumber',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array',
    ];
}
