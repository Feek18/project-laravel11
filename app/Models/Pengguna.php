<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'penggunas';
    protected $fillable = ['user_id', 'nama', 'alamat', 'no_telp', 'gender', 'gambar'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_pengguna');
    }

}
