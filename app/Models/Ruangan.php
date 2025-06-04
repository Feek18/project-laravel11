<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;
    protected $table = 'ruangan_kelas';
    protected $primaryKey = 'id_ruang';

    protected $fillable = [
        'nama_ruangan',
        'lokasi',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_ruang');
    }
}
