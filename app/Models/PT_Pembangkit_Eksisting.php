<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Pembangkit_Eksisting extends Model
{
    use HasFactory;

    protected $table = 'table__p_t__pembangkit__eksisting';

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
        'remark',
        'sbdata',
        'j_pmbngkt',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array',
        'stsjrn'   => 'int',
        'jnsrsr'   => 'int',
        'orde01'   => 'int',
        'orde02'   => 'int',
        'orde03'   => 'int',
        'orde04'   => 'int',
        'objectid' => 'int',
    ];
}
