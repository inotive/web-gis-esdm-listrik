<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_SUTM_PPU extends Model
{
    use HasFactory;

    protected $table = 'table__l_n__sutm__ppu';

    protected $fillable = [
        'objectid',
        'no_ruas',
        'kode_kelas',
        'nama_jalan',
        'nama_pangk',
        'nama_ujung',
        'titk_penge',
        'titik_peng',
        'panjang',
        'lebar',
        'aspal',
        'rijit',
        'perkerasan',
        'tanah',
        'kondisi',
        'th_pekerja',
        'ket',
        'shape_leng',
        'legacy_id',
        'foto',
        'status_jal',
        'fungsi_jal',
        'sistem_jal',
        'nama',
        'dana',
        'nama_jal_1',
        'kode_rtrw',
        'statusrtrw',
        'shape_le_1',
        'geometry',
    ];

    protected $casts = [
        'geometry'    => 'array',
        'shape_leng'  => 'float',
        'shape_le_1'  => 'float',
        'panjang'     => 'float',
        'lebar'       => 'float',
        'aspal'       => 'float',
        'rijit'       => 'float',
        'perkerasan'  => 'float',
        'tanah'       => 'float',
        'no_ruas'     => 'int',
        'kode_kelas'  => 'int',
        'legacy_id'   => 'int',
    ];
}
