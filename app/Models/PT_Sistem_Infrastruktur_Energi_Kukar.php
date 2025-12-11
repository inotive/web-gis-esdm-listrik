<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Sistem_Infrastruktur_Energi_Kukar extends Model
{
    use HasFactory;

    protected $table = 'table__p_t__sistem__infrastruktur__energi__kukar';

    protected $fillable = [
        'objectid',
        'namobj',
        'orde01',
        'orde02',
        'orde03',
        'orde04',
        'jnsrsr',
        'stsjrn',
        'wadmpr',
        'wadmkk',
        'remark',
        'sbdata',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array',
    ];
}