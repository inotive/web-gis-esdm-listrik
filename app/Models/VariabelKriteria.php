<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariabelKriteria extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'keterangan', 'bobot'];
}
