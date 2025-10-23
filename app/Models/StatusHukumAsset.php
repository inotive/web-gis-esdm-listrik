<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusHukumAsset extends Model
{
    use HasFactory;

    protected $table = 'status_hukum_asset';
    protected $fillable = ['name', 'deskripsi'];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'status_hukum_id');
    }
}
