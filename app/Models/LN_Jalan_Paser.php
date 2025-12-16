<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LN_Jalan_Paser extends Model
{
    use HasFactory;

    protected $table = 't_ln_jalan_paser';

    protected $fillable = [
        'OBJECTID_1',
        'OBJECTID_2',
        'OBJECTID',
        'Kl_Dat_Das',
        'Nm_Ruas',
        'Thn_Data',
        'Status',
        'Fungsi',
        'Mendukung',
        'Ura_Dukung',
        'Kd_Bd_PU',
        'Kd_Jns_inf',
        'Kd_Inf',
        'Propinsi',
        'Kab_Kot',
        'Kecamatan',
        'Desa_Kel',
        'Tk_Ruas_Aw',
        'Tk_Ruas_Ak',
        'Kd_Patok',
        'Nm_Lintas',
        'Km_Awal',
        'Km_Akhir',
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
        'pnj',
        'Id_Paser',
        'Shape_Leng',
        'X_Ak',
        'Y_Ak',
        'X_Aw',
        'Y_Aw',
        'No',
        'geom'
    ];

    protected $casts = [
        'geom' => 'string',
        'OBJECTID_1' => 'integer',
        'OBJECTID_2' => 'integer',
        'OBJECTID' => 'integer',
        'Thn_Data' => 'integer',
        'Km_Awal' => 'decimal:8',
        'Km_Akhir' => 'decimal:8',
        'Kon_Baik' => 'decimal:8',
        'Kon_Sdg' => 'decimal:8',
        'Kon_Rgn' => 'decimal:8',
        'Kon_Rusak' => 'decimal:8',
        'Kon_Mntp' => 'decimal:8',
        'Kon_T_Mntp' => 'decimal:8',
        'Panjang' => 'decimal:8',
        'Lbr_Keras' => 'decimal:2',
        'LHRT' => 'decimal:2',
        'VCR' => 'decimal:2',
        'Tipe_Jln' => 'integer',
        'MST' => 'decimal:2',
        'Tanah_Kri' => 'decimal:2',
        'Macadam' => 'decimal:2',
        'Aspal' => 'decimal:2',
        'Rigid' => 'decimal:2',
        'Thn_Pen_Ak' => 'integer',
        'pnj' => 'decimal:8',
        'Id_Paser' => 'integer',
        'Shape_Leng' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}