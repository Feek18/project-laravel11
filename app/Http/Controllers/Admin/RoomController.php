<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {

        $ruangan = Ruangan::paginate(5);
        return view('components.admin.ruangan', compact('ruangan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        Ruangan::create($validatedData);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('components.modals.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $validatedData = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        $ruangan->update($validatedData);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diupdate.');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
