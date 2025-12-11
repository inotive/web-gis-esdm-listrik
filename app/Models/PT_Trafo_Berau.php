<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PT_Trafo_Berau extends Model
{
    use HasFactory;

    protected $table = 'pt_trafo_berau';

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
        'serialnum',
        'status',
        'tujdnumber',
        'vendor1',
        'fasa_trafo',
        'jenis_traf',
        'kapasitas',
        'kode_peral',
        'no_trafo',
        'owner_peme',
        'peruntukan',
        'posisi_fas',
        'rujukan_ko',
        'status_kep',
        'tap_change',
        'tegangan_t',
        'th_buat',
        'prioritas',
        'enabled',
        'globalid_1',
        'created_us',
        'created_da',
        'last_edite',
        'last_edi_1',
        'relationsh',
        'kode_hanta',
        'operatingd',
        'ownersysid',
        'sourcestar',
        'sourceendm',
        'no_slo',
        'sloactived',
        'penyulang',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array',
        'tglgambar' => 'datetime',
        'tglupdate' => 'datetime',
        'installdat' => 'datetime',
        'created_da' => 'datetime',
        'last_edi_1' => 'datetime',
        'operatingd' => 'datetime',
        'sloactived' => 'datetime',
        'enabled' => 'boolean',
        'sourcestar' => 'integer',
        'sourceendm' => 'integer',
        'kapasitas' => 'integer',
    ];
}