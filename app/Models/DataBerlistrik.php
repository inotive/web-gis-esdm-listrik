<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBerlistrik extends Model
{
    use HasFactory;

    protected $table = 'table_data_berlistrik';

    protected $fillable = [
        'NAMOBJ',
        'LUASWH',
        'TIPADM',
        'WADMKC',
        'WADMKD',
        'WADMKK',
        'WADMPR',
        'H_Survei',
    ];

    protected $casts = [
        // 'H_Survei' => 'decimal:2',
        'LUASWH' => 'decimal:2',
    ];
}