<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Gardu_Berau extends Model
{
    use HasFactory;

    protected $table = 'table__p_t__gardu__berau';

    protected $fillable = [
        'globalid',
        'assetgroup',
        'assettype',
        'tglgambar',
        'usergambar',
        'tglupdate',
        'userupdate',
        'assetnum',
        'classifica',
        'descriptio',
        'installdat',
        'location',
        'prioritas',
        'vendor1',
        'jenis_pela',
        'kode_peral',
        'status_kep',
        'status_rc',
        'type_gardu',
        'status',
        'tujdnumber',
        'globalid_1',
        'created_us',
        'created_da',
        'last_edite',
        'last_edi_1',
        'parent_loc',
        'operatingd',
        'formatteda',
        'streetaddr',
        'city',
        'kode_konst',
        'owner_peme',
        'ownersysid',
        'penyulang',
        'no_slo',
        'sloactived',
        'longitudex',
        'latitudey',
        'shape_leng',
        'shape_area',
        'orig_fid',
        'geometry',
    ];

    protected $casts = [
        'tglgambar'  => 'datetime',
        'tglupdate'  => 'datetime',
        'installdat' => 'datetime',
        'created_da' => 'datetime',
        'last_edi_1' => 'datetime',
        'operatingd' => 'datetime',
        'sloactived' => 'datetime',
        'assetgroup' => 'int',
        'assettype'  => 'int',
        'longitudex' => 'float',
        'latitudey'  => 'float',
        'shape_leng' => 'float',
        'shape_area' => 'float',
        'orig_fid'   => 'int',
        'geometry'   => 'array',
    ];
}
