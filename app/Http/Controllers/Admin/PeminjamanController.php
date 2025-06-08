<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjam = Peminjaman::all();
        $ruangan = Ruangan::all();
        $pengguna = Pengguna::all();
        return view('components.admin.peminjam', compact('peminjam', 'ruangan', 'pengguna'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'id_pengguna' => 'required|exists:penggunas,id',
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            // 'status_peminjaman' => 'required|in:terencana,insidental',
            'keperluan' => 'required|string|max:255',
            // 'status_persetujuan' => 'required|in:pending,disetujui,ditolak',
            'tanggal_pinjam' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ]);

        Peminjaman::create($validatedData);

        return redirect()->route('peminjam.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $peminjam = Peminjaman::findOrFail($id);
        return view('components.modals.peminjam.edit');
    }


    public function update(Request $request, $id)
    {
        $peminjam = Peminjaman::findOrFail($id);

        // dd($request->all()); // Pastikan key "debug" muncul
        $validated = $request->validate([
            'id_pengguna' => 'required|exists:penggunas,id',
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            'status_peminjaman' => 'required|in:terencana,insidental',
            'keperluan' => 'required|string|max:255',
            'status_persetujuan' => 'required|in:pending,disetujui,ditolak',
            'tanggal_pinjam' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ]);

        $peminjam->update($validated);

        return redirect()->route('peminjam.index')->with('success', 'Peminjaman berhasil diupdate.');
    }

    public function destroy($id)
    {
        $peminjam = Peminjaman::findOrFail($id);
        $peminjam->delete();
        return redirect()->route('peminjam.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function statusPersetujuan(Request $request, $id)
    {
        $request->validate([
            'status_persetujuan' => 'required|in:disetujui,ditolak',
        ]);

        $peminjam = Peminjaman::findOrFail($id);
        $peminjam->status_persetujuan = $request->status_persetujuan;
        $peminjam->save();
        
        return redirect()->route('peminjam.index')->with('success', 'Status persetujuan berhasil diubah.');
    }
}
