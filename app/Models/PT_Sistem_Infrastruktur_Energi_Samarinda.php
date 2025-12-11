<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Sistem_Infrastruktur_Energi_Samarinda extends Model
{
    use HasFactory;

    protected $table = 'pt_sistem_infrastruktur_energi_samarinda';

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