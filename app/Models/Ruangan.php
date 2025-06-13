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
        'gambar',
        'nama_ruangan',
        'lokasi',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_ruang');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_ruang', 'id_ruang');
    }
}
