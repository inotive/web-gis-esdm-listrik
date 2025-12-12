<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT1_Trafo_Gardu_Paser extends Model
{
    use HasFactory;

    protected $table = 'pt1_trafo_gardu_paser';

    protected $fillable = [
        'id_prop',
        'name',
        'descript',
        'type',
        'comment',
        'symbol',
        'datetimes',
        'elevation',
        'nama',
        'data',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array',
        'id_prop' => 'integer',
        'elevation' => 'float',
    ];
}