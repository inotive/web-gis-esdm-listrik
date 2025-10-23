<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriAsset extends Model
{
    use HasFactory;

    protected $table = 'kategori_asset';
    protected $fillable = ['kode', 'name', 'deskripsi'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'kategori_id');
    }
}
