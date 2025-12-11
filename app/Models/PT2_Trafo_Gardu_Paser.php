<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT2_Trafo_Gardu_Paser extends Model
{
    use HasFactory;

    protected $table = 'pt2_trafo_gardu_paser';

    protected $fillable = [
        'id_prop',
        'name',
        'descriptio',
        'timestamp',
        'begin',
        'end',
        'altitudemo',
        'tessellate',
        'extrude',
        'visibility',
        'draworder',
        'icon',
        'tes_1',
        'tes_2',
        'tes_4',
        'tes_5',
        'tes_6',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array',
        'tessellate' => 'integer',
        'extrude' => 'integer',
        'visibility' => 'integer',
        'draworder' => 'integer',
    ];
}