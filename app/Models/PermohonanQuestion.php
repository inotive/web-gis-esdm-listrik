<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermohonanQuestion extends Model
{
    use HasFactory;

    protected $table = 'permohonan_questions';

    protected $fillable = [
        'urutan',
        'permohonan_id',
        'pertanyaan',
        'tipe',
        'wajib',
    ];

    protected $casts = [
        'wajib' => 'boolean',
    ];

    // constants
    const TIPE_PERMOHONAN = [
        'text' => 'Text (Jawaban Singkat)',
        'textarea' => 'Textarea (Jawaban Panjang)',
        'number' => 'Number (Angka)',
        'date' => 'Date (Tanggal)',
        'file' => 'File (Upload Dokumen)',
        'file_multiple' => 'File Multiple (Upload Banyak Dokumen)',
        'radio' => 'Radio (Pilih Satu)',
        'checkbox' => 'Checkbox (Pilih Banyak)',
        'select' => 'Select (Dropdown)',
    ];

    /**
     * Relasi: PermohonanQuestion belongs to Permohonan
     */
    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    /**
     * Relasi: PermohonanQuestion memiliki banyak PermohonanQuestionOption
     */
    public function options(): HasMany
    {
        return $this->hasMany(PermohonanQuestionOption::class, 'permohonan_question_id');
    }
}
