<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';
    protected $fillable = ['provinsi_id', 'nama'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
