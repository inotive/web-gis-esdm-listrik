<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegVillage extends Model
{
    /**
     * Table name
     */
    protected $table = 'reg_villages';

    /**
     * Primary key
     */
    protected $primaryKey = 'id';

    /**
     * Primary key type
     */
    protected $keyType = 'string';

    /**
     * Incrementing
     */
    public $incrementing = false;

    /**
     * Timestamps
     */
    public $timestamps = false;

    /**
     * Fillable fields
     */
    protected $fillable = [
        'id',
        'district_id',
        'name',
    ];

    /**
     * Relationship: Village belongs to District (Kecamatan)
     */
    public function district()
    {
        return $this->belongsTo(RegDistrict::class, 'district_id', 'id');
    }

    /**
     * Relationship: Village belongs to Regency (Kabupaten/Kota) through District
     */
    public function regency()
    {
        return $this->hasOneThrough(
            RegRegency::class,
            RegDistrict::class,
            'id',           // Foreign key on districts table
            'id',           // Foreign key on regencies table
            'district_id',  // Local key on villages table
            'regency_id'    // Local key on districts table
        );
    }

    /**
     * Relationship: Village belongs to Province through District and Regency
     */
    public function province()
    {
        return $this->district()
            ->with('regency.province');
    }

    /**
     * Relationship: Village has one DataBerlistrik
     * Match berdasarkan nama desa (case-insensitive)
     */
    public function dataBerlistrik()
    {
        return $this->hasOne(DataBerlistrik::class, 'NAMOBJ', 'name');
    }

    /**
     * Helper: Check if village has electricity
     */
    public function hasBerlistrik(): bool
    {
        return $this->dataBerlistrik()->exists();
    }

    /**
     * Helper: Get electricity source (PLN/Non-PLN)
     */
    public function getSumberListrikAttribute(): ?string
    {
        return $this->dataBerlistrik?->sumber_listrik;
    }

    /**
     * Helper: Get status berlistrik badge
     */
    public function getStatusBerlistrikAttribute(): string
    {
        if (!$this->dataBerlistrik) {
            return '<span class="badge badge-secondary">Tidak Ada Data</span>';
        }

        $sumber = $this->dataBerlistrik->sumber_listrik;
        if ($sumber === 'PLN') {
            return '<span class="badge badge-success">Berlistrik PLN</span>';
        } elseif ($sumber === 'Non-PLN') {
            return '<span class="badge badge-info">Berlistrik Non-PLN</span>';
        }

        return '<span class="badge badge-warning">Berlistrik</span>';
    }
}
