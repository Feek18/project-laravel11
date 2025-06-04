<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matakuliah = MataKuliah::paginate(5);
        return view('components.admin.matkul', compact('matakuliah'));
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
        $validatedData = $request->validate([
            'kode_matkul' => 'required|string|max:255',
            'mata_kuliah' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ]);

        MataKuliah::create($validatedData);

        return redirect()->route('matkul.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $matakuliah = MataKuliah::findOrFail($id);
        return view('components.modals.matkul.edit', compact('matakuliah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $matakuliah = MataKuliah::findOrFail($id);
        $validatedData = $request->validate([
            'kode_matkul' => 'required|string|max:255',
            'mata_kuliah' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ]);

        $matakuliah->update($validatedData);
        return redirect()->route('matkul.index')->with('success', 'Mata kuliah berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $matakuliah = MataKuliah::findOrFail($id);
        $matakuliah->delete();
        return redirect()->route('matkul.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
