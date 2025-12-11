<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Trafo_Gardu_Kubar extends Model
{
    use HasFactory;

    protected $table = 'pt_trafo_gardu_kubar';

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
        'kapasitas',
        'feeder',
        'zona',
        'nilai_pent',
        'latitude',
        'longitude',
        'layer',
        'path',
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