<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MataKuliah::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '<button onclick="loadEditModal(\'matkul\', '.$row->id.')" type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </button>';
                    
                    $deleteBtn = '<form method="POST" action="'.route('matkul.destroy', $row->id).'" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150" onclick="return confirm(\'Apakah Anda yakin ingin menghapus mata kuliah ini?\')">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </form>';
                    
                    return '<div class="flex items-center gap-2">'.$editBtn.' '.$deleteBtn.'</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $matakuliah = MataKuliah::all();
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
        
        // If this is an AJAX request, return the modal HTML
        if (request()->ajax()) {
            return view('components.modals.matakuliah.edit', compact('matakuliah'))->render();
        }
        
        return view('components.modals.matakuliah.edit', compact('matakuliah'));
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
