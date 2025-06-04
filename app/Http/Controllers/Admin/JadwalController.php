<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with('ruangan')->get();
        $ruangan = Ruangan::all();
        return view('components.admin.jadwal', compact('jadwals', 'ruangan'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'id_ruang' => 'required|numeric|exists:ruangan_kelas,id_ruang',
            'nama_perkuliahan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
        ]);

        Jadwal::create($validatedData);

        return redirect()->route('jadwal.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('components.modals.jadwal.edit', compact('jadwal'));
    }


    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        // dd($request->all());
        $validatedData = $request->validate([
            'id_ruang' => 'required|numeric|exists:ruangan_kelas,id_ruang',
            'nama_perkuliahan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
        ]);

        // Ubah ke format H:i:s
        $validatedData['jam_mulai'] = $validatedData['jam_mulai'] . ':00';
        $validatedData['jam_selesai'] = $validatedData['jam_selesai'] . ':00';

        $jadwal->update($validatedData);
        // dd($jadwal);
        return redirect()->route('jadwal.index')->with('success', 'Ruangan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
