<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_Bontang extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_bontang';

    protected $fillable = [
        'Kl_Dat_Das',
        'Nm_Ruas',
        'Thn_Data',
        'Status',
        'Fungsi',
        'Mendukung',
        'Ura_Dukung',
        'Kd_Bd_PU',
        'Kd_Jns_Inf',
        'Kd_Inf',
        'Propinsi',
        'Kab_Kota',
        'Kecamatan',
        'Desa_Kel',
        'Tk_Ruas_Aw',
        'Tk_Ruas_Ak',
        'Kd_Patok',
        'Km_Awal',
        'Km_Akhir',
        'Nm_Lintas',
        'Kon_Baik',
        'Kon_Sdg',
        'Kon_Rgn',
        'Kon_Rusak',
        'Kon_Mntp',
        'Kon_T_Mntp',
        'Panjang',
        'Lbr_Keras',
        'LHRT',
        'VCR',
        'Tipe_Jln',
        'MST',
        'Tipe_Keras',
        'Tanah_Kri',
        'Macadam',
        'Aspal',
        'Rigid',
        'Thn_Pen_Ak',
        'Jns_Pen',
        'Koord_X_Aw',
        'Koord_Y_Aw',
        'Koord_X_Ak',
        'Koord_Y_Ak',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'Thn_Data' => 'integer',
        'Koord_X_Aw' => 'decimal:8',
        'Koord_Y_Aw' => 'decimal:8',
        'Koord_X_Ak' => 'decimal:8',
        'Koord_Y_Ak' => 'decimal:8',
        'Panjang' => 'decimal:8',
        'Kon_Baik' => 'decimal:8',
        'Kon_Sdg' => 'decimal:8',
        'Kon_Rgn' => 'decimal:8',
        'Kon_Rusak' => 'decimal:8',
        'Kon_Mntp' => 'decimal:8',
        'Kon_T_Mntp' => 'decimal:8',
        'Lbr_Keras' => 'decimal:2',
        'LHRT' => 'decimal:8',
        'VCR' => 'decimal:8',
        'Tipe_Jln' => 'integer',
        'MST' => 'decimal:8',
        'Tanah_Kri' => 'decimal:8',
        'Macadam' => 'decimal:8',
        'Aspal' => 'decimal:8',
        'Rigid' => 'decimal:8',
        'Km_Awal' => 'decimal:8',
        'Km_Akhir' => 'decimal:8',
    ];
}