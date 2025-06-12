<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Ruangan;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $ruangan = Ruangan::first();
        return view('components.user.dashboard');
        // return view('components.user.pages.detail', compact('ruangan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'keperluan' => 'required|string|max:255',
        ]);
        // $id_ruang = $request->input('id_ruang');
        // Cek bentrok waktu pada ruangan yang sama, tanggal sama, dan status masih pending
        $conflict = Peminjaman::where('id_ruang', $request->id_ruang)
            ->where('tanggal_pinjam', $request->tanggal_pinjam)
            ->where('status_persetujuan', 'pending')
            ->where(function ($query) use ($request) {
                $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_selesai])
                    ->orWhereBetween('waktu_selesai', [$request->waktu_mulai, $request->waktu_selesai])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('waktu_mulai', '<=', $request->waktu_mulai)
                            ->where('waktu_selesai', '>=', $request->waktu_selesai);
                    });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('warning', 'Ruangan sedang dalam proses peminjaman lain pada waktu tersebut (masih menunggu persetujuan).');
        }

        Peminjaman::create([
            'id_pengguna' => Auth::user()->pengguna->id,
            'id_ruang' => $request->id_ruang,
            'status_peminjaman' => 'terencana',
            'keperluan' => $request->keperluan,
            'status_persetujuan' => 'pending',
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Peminjaman berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('components.user.pages.detail', compact('ruangan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
