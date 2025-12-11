<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Trafo_Gardu_Distribusi_PPU extends Model
{
    use HasFactory;

    protected $table = 'pt_trafo_gardu_distribusi_ppu';

    protected $fillable = [
        'oid_',
        'name',
        'folderpath',
        'symbolid',
        'altmode',
        'base',
        'timespan',
        'timestamp',
        'begintime',
        'endtime',
        'snippet',
        'popupinfo',
        'haslabel',
        'labelid',
        'nama',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array',
        'oid_' => 'integer',
        'symbolid' => 'integer',
        'altmode' => 'integer',
        'base' => 'float',
        'timespan' => 'integer',
        'timestamp' => 'integer',
        'haslabel' => 'integer',
        'labelid' => 'integer',
    ];
}