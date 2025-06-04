<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_matkul',
        'mata_kuliah',
        'semester',
    ];
}
