<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Sistem_Infrastruktur_Energi_Balikpapan extends Model
{
    use HasFactory;

    protected $table = 'table__p_t__sistem__infrastruktur__energi__balikpapan';

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
        'objectid' => 'int',
        'orde01'   => 'int',
        'orde02'   => 'int',
        'orde03'   => 'int',
        'orde04'   => 'int',
        'jnsrsr'   => 'int',
        'stsjrn'   => 'int',
    ];
}
