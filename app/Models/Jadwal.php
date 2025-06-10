<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';
    protected $fillable = [
        'id_ruang',
        'id_matkul',
        'tanggal',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruang', 'id_ruang');
    }

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id');
    }
}
