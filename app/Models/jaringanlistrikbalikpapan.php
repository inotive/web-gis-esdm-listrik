<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JaringanListrikBalikpapan extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama tabel di migration
    protected $table = 'table__jaringan__listrik__balikpapan';

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
        'length',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'orde01'    => 'integer',
        'orde02'    => 'integer',
        'orde03'    => 'integer',
        'orde04'    => 'integer',
        'jnsrsr'    => 'integer',
        'stsjrn'    => 'integer',
        'length'    => 'float',
        'shape_leng'=> 'float',
        'geometry'  => 'array', // geometry disimpan sebagai JSON
    ];
}
