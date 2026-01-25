<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JsonVideo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function importedJsonFeature()
    {
        return $this->belongsTo(ImportedJsonFeature::class, 'imported_json_features_id');
    }
}
