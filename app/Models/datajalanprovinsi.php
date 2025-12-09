<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataJalanProvinsi extends Model
{
    use HasFactory;

    protected $table = 'table_data_jalan_provinsi';

    protected $fillable = [
        'objectid',
        'kl_dat_das',
        'nm_ruas',
        'thn_data',
        'status',
        'fungsi',
        'mendukung',
        'ura_dukung',
        'kd_bd_pu',
        'kd_jns_inf',
        'kd_inf',
        'propinsi',
        'kab_kot',
        'kecamatan',
        'desa_kel',
        'tk_ruas_aw',
        'tk_ruas_ak',
        'kd_patok',
        'km_awal',
        'km_akhir',
        'nm_lintas',
        'kon_baik',
        'kon_sdg',
        'kon_rgn',
        'kon_rusak',
        'kon_mntp',
        'kon_t_mntp',
        'panjang',
        'lbr_keras',
        'lhrt',
        'vcr',
        'tipe_jln',
        'mst',
        'tanah_kri',
        'macadam',
        'aspal',
        'rigid',
        'thn_pen_ak',
        'jns_pen',
        'koord_x_aw',
        'koord_y_aw',
        'koord_x_ak',
        'koord_y_ak',
        'shape_leng',
        'status_j_1',
        'keterangan',
        'masuk',
        'panjangjal',
        'geometry',
    ];

    protected $casts = [
        'thn_data'    => 'integer',
        'km_awal'     => 'float',
        'km_akhir'    => 'float',
        'kon_baik'    => 'float',
        'kon_sdg'     => 'float',
        'kon_rgn'     => 'float',
        'kon_rusak'   => 'float',
        'kon_mntp'    => 'float',
        'kon_t_mntp'  => 'float',
        'panjang'     => 'float',
        'lbr_keras'   => 'float',
        'lhrt'        => 'float',
        'vcr'         => 'float',
        'tipe_jln'    => 'integer',
        'mst'         => 'integer',
        'tanah_kri'   => 'float',
        'macadam'     => 'float',
        'aspal'       => 'float',
        'rigid'       => 'float',
        'thn_pen_ak'  => 'integer',
        'koord_x_aw'  => 'float',
        'koord_y_aw'  => 'float',
        'koord_x_ak'  => 'float',
        'koord_y_ak'  => 'float',
        'shape_leng'  => 'float',
        'panjangjal'  => 'float',
        'geometry'    => 'array',   // Auto decode JSON jadi array
    ];
}
