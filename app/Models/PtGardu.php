<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtGardu extends Model
{
    use HasFactory;

    protected $table = 'pt_gardus';

    protected $fillable = [
        'reg_provinces_id',
        'reg_regencies_id',
        'reg_districts_id',
        'reg_villages_id',
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

        'longitudex' => 'float',
        'latitudey'  => 'float',
        'shape_leng' => 'float',
        'shape_area' => 'float',

        // geometry disimpan sebagai JSON di DB dan otomatis di-cast jadi array
        'geometry'   => 'array',
    ];

    /**
     * Relasi ke provinsi (reg_provinces)
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(RegProvince::class, 'reg_provinces_id', 'id');
    }

    /**
     * Relasi ke kabupaten/kota (reg_regencies)
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(RegRegency::class, 'reg_regencies_id', 'id');
    }

    /**
     * Relasi ke kecamatan (reg_districts)
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(RegDistrict::class, 'reg_districts_id', 'id');
    }

    /**
     * Relasi ke kelurahan/desa (reg_villages)
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(RegVillage::class, 'reg_villages_id', 'id');
    }
}
