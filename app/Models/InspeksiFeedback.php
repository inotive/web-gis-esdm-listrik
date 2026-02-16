<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeksiFeedback extends Model
{
    use HasFactory;

    protected $table = 'inspeksi_feedbacks';

    protected $fillable = [
        'inspeksi_id',
        'nama',
        'posisi',
        'kontak',
        'catatan',
        'file_upload',
    ];

    /**
     * Relationship: Feedback belongs to Inspeksi
     */
    public function inspeksi()
    {
        return $this->belongsTo(Inspeksi::class, 'inspeksi_id');
    }
}
