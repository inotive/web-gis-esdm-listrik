<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN2_SUTM_PPU extends Model
{
    use HasFactory;

    protected $table = 'table__l_n2__sutm__ppu';

    protected $fillable = [
        'objectid',
        'fid_jalan',
        'namobj',
        'fcode',
        'remark',
        'metadata',
        'srs_id',
        'arhrjl',
        'autrjl',
        'fgsrjl',
        'jarrjl',
        'jparjl',
        'kllrjl',
        'konrjl',
        'kpmstr',
        'lkonof',
        'lksbsp',
        'lksrta',
        'llhrrt',
        'locrjl',
        'lbrbhj',
        'lbrjln',
        'matrjl',
        'medrjl',
        'spcrjl',
        'starjl',
        'tolrjl',
        'utkrjl',
        'vlcprt',
        'wlyrjl',
        'tgl_sk',
        'jlnlyg',
        'klsrjl',
        'jalanlistr',
        'fid_batasp',
        'wadmkc',
        'wadmkd',
        'wadmkk',
        'wadmpr',
        'shape_leng',
        'panjang',
        'geometry',
    ];

    protected $casts = [
        'geometry'   => 'array',
        'shape_leng' => 'float',
        'panjang'    => 'float',
        'fid_jalan'  => 'int',
        'arhrjl'     => 'int',
        'autrjl'     => 'int',
        'fgsrjl'     => 'int',
        'jarrjl'     => 'int',
        'jparjl'     => 'int',
        'konrjl'     => 'int',
        'kpmstr'     => 'int',
        'llhrrt'     => 'int',
        'locrjl'     => 'int',
        'lbrbhj'     => 'int',
        'lbrjln'     => 'int',
        'matrjl'     => 'int',
        'medrjl'     => 'int',
        'spcrjl'     => 'int',
        'starjl'     => 'int',
        'tolrjl'     => 'int',
        'utkrjl'     => 'int',
        'vlcprt'     => 'int',
        'wlyrjl'     => 'int',
        'jlnlyg'     => 'int',
        'klsrjl'     => 'int',
        'fid_batasp' => 'int',
    ];
}
