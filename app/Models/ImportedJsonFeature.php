<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedJsonFeature extends Model
{
    use HasFactory;

    protected $table = 'imported_json_features';

    public function videos()
    {
        return $this->hasMany(JsonVideo::class, 'imported_json_features_id');
    }

    protected $casts = [
        'properties' => 'array',
        'geometry' => 'array',
    ];
}
