<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeAsset extends Model
{
    use HasFactory;

    protected $table = 'attribute_asset';
    protected $fillable = ['name', 'type'];

    public function nilai()
    {
        return $this->hasMany(AssetHasAttributeAsset::class, 'attribute_asset_id');
    }
}
