<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $fillable = [
        'id_pengguna',
        'id_ruang',
        'status_peminjaman',
        'keperluan',
        'status_persetujuan',
        'tanggal_pinjam',
        'waktu_mulai',
        'waktu_selesai',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruang');
    }
}
