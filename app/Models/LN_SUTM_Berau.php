<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_SUTM_Berau extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__sutm__berau';

    protected $fillable = [
        'objectid',
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
        'manufactur',
        'prioritas',
        'vendor1',
        'bahan_kawa',
        'fasa_jarin',
        'hantaran_n',
        'jenis_kabe',
        'jenis_kond',
        'kode_peral',
        'mainline',
        'panjang_ha',
        'posisi_fas',
        'sirkuit',
        'status_kep',
        'tegangan_j',
        'tingkat_is',
        'ukuran_kaw',
        'status',
        'tujdnumber',
        'serialnum',
        'enabled',
        'globalid_1',
        'created_us',
        'created_da',
        'last_edite',
        'last_edi_1',
        'penyulang',
        'relationsh',
        'lrm',
        'kode_hanta',
        'operatingd',
        'owner_peme',
        'ownersysid',
        'startmeasu',
        'endmeasure',
        'shape_leng',
        'geometry',
    ];

    protected $casts = [
        'geometry'    => 'array',
        'shape_leng'  => 'float',
        'panjang_ha'  => 'float',
        'startmeasu'  => 'float',
        'endmeasure'  => 'float',
        'assetgroup'  => 'int',
        'assettype'   => 'int',
        'enabled'     => 'int',
    ];
}
