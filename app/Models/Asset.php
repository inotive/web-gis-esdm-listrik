<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'asset';

    protected $fillable = [
        'reg_provinces_id',
        'reg_regencies_id',
        'reg_districts_id',
        'reg_villages_id',
        'kategori_id',
        'unit_kerja_id',
        'status_hukum_id',
        'kode_asset',
        'nama_asset',
        'no_register',
        'nomor_hak',
        'penggunaan_spma',
        'jenis_hak',
        'asal',
        'kat_tanah',
        'kode',
        'nui',
        'nib',
        'luas_m2',
        'panjang_m',
        'lebar_m',
        'alamat',
        'latitude',     
        'longitude',     
        'geojson',
    ];

    protected $casts = [
        'luas_m2' => 'float',
        'panjang_m' => 'float',
        'lebar_m' => 'float',
        'latitude' => 'float',  
        'longitude' => 'float',
        'geojson' => 'json', 
    ];

    /**
     * Relasi ke tabel wilayah
     */
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

    /**
     * Relasi ke tabel lainnya
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAsset::class, 'kategori_id', 'id');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id', 'id');
    }

    public function statusHukum(): BelongsTo
    {
        return $this->belongsTo(StatusHukumAsset::class, 'status_hukum_id', 'id');
    }

    /**
     * ✅ Relasi ke asset_dokumen (One-to-One)
     * Satu asset punya satu dokumen utama
     */
    public function dokumenUtama(): HasOne
    {
        return $this->hasOne(AssetDokumen::class, 'asset_id', 'id');
    }

    /**
     * ✅ Relasi ke asset_dokumen (One-to-Many)
     * Jika satu asset bisa punya banyak dokumen
     */
    public function dokumenList(): HasMany
    {
        return $this->hasMany(AssetDokumen::class, 'asset_id', 'id');
    }

    /**
     * Relasi ke dokumen lainnya (existing)
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(AssetHasDokumen::class, 'asset_id', 'id');
    }

    public function atribut(): HasMany
    {
        return $this->hasMany(AssetHasAttributeAsset::class, 'asset_id', 'id');
    }

    /**
     * ✅ Helper: Cek apakah asset punya sertifikat
     */
    public function hasSertifikat(): bool
    {
        return $this->dokumenUtama()->exists();
    }

    /**
     * ✅ Helper: Get status sertifikat
     */
    public function getStatusSertifikat(): string
    {
        $dokumen = $this->dokumenUtama;
        
        if (!$dokumen) {
            return 'Belum Ada Dokumen';
        }

        if ($dokumen->file_sertif) {
            return 'Sudah Terdigitalisasi';
        }

        return $dokumen->sts_sertif ?? 'Status Tidak Diketahui';
    }

    /**
     * ✅ Accessor: Get file sertifikat from relation
     */
    public function getFileSertifAttribute()
    {
        return $this->dokumenUtama?->file_sertif;
    }

    /**
     * ✅ Accessor: Get link sertifikat from relation
     */
    public function getLinkSertifAttribute()
    {
        return $this->dokumenUtama?->link_sertif;
    }
}