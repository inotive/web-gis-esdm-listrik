<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtTrafo extends Model
{
    use HasFactory;

    protected $table = 'pt_trafos';

    protected $fillable = [
        'reg_provinces_id',
        'reg_regencies_id',
        'reg_districts_id',
        'reg_villages_id',
        'globalid',
        'objectid',
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
        'streetaddr',
        'city',
        'longitudex',
        'latitudey',
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

        'kapasitas'  => 'float',
        'longitudex' => 'float',
        'latitudey'  => 'float',
        'sourcestar' => 'float',
        'sourceendm' => 'float',

        'enabled'    => 'boolean',
        'geometry'   => 'array',
    ];

    // ===== Relasi wilayah (sesuai yang kamu pakai di PtGardu) =====
    public function province(): BelongsTo
    {
        return $this->belongsTo(RegProvince::class, 'reg_provinces_id', 'id');
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(RegRegency::class, 'reg_regencies_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(RegDistrict::class, 'reg_districts_id', 'id');
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(RegVillage::class, 'reg_villages_id', 'id');
    }
}
