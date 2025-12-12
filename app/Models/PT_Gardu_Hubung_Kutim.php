<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Gardu_Hubung_Kutim extends Model
{
    use HasFactory;

    protected $table = 'table__p_t__gardu__hubung__kutim';

    protected $fillable = [
        'nama',
        'globalid',
        'orig_fid',
        'geometry',
    ];

    protected $casts = [
        'orig_fid' => 'int',
        'geometry' => 'array',
    ];
}
