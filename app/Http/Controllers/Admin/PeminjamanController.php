<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjam = Peminjaman::all();
        $pengguna = Pengguna::all();
        return view('components.admin.peminjam', compact('peminjam', 'pengguna'));
    }
}
