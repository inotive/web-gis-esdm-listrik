<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriDokumen extends Model
{
    use HasFactory;

    protected $table = 'kategori_dokumen';
    protected $fillable = ['name', 'deskripsi'];

    public function dokumen()
    {
        return $this->hasMany(AssetHasDokumen::class, 'kategori_dokumen_id');
    }
}
