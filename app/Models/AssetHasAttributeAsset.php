<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetHasAttributeAsset extends Model
{
    use HasFactory;

    protected $table = 'asset_has_attribute_asset';
    protected $fillable = ['asset_id', 'attribute_asset_id', 'value'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function attribute()
    {
        return $this->belongsTo(AttributeAsset::class, 'attribute_asset_id');
    }
}
