<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanQuestionOption extends Model
{
    use HasFactory;

    protected $table = 'permohonan_question_options';

    protected $fillable = [
        'permohonan_question_id',
        'opsi',
        'keterangan',
        'wajib',
    ];

    protected $casts = [
        'wajib' => 'boolean',
    ];

    /**
     * Relasi: PermohonanQuestionOption belongs to PermohonanQuestion
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(PermohonanQuestion::class, 'permohonan_question_id');
    }
}
