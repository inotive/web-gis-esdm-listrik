<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetHasDokumen extends Model
{
    use HasFactory;

    protected $table = 'asset_has_dokumen';
    protected $fillable = ['asset_id', 'kategori_dokumen_id', 'url'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function kategoriDokumen()
    {
        return $this->belongsTo(KategoriDokumen::class, 'kategori_dokumen_id');
    }
}
