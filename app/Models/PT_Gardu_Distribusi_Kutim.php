<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Gardu_Distribusi_Kutim extends Model
{
    use HasFactory;

    protected $table = 'table__p_t__gardu__distribusi__kutim';

    protected $fillable = [
        'classifica',
        'globalid',
        'orig_fid',
        'geometry',
    ];

    protected $casts = [
        'orig_fid' => 'int',
        'geometry' => 'array',
    ];
}
