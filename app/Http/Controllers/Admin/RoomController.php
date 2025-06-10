<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ruangan::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '<button onclick="loadEditModal(\'ruangan\', '.$row->id_ruang.')" type="button" class="btn-edit bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </button>';
                    
                    $deleteBtn = '<form method="POST" action="'.route('ruangan.destroy', $row->id_ruang).'" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn-delete bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150" onclick="return confirm(\'Apakah Anda yakin ingin menghapus ruangan ini?\')">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </form>';
                    
                    return '<div class="action-buttons">'.$editBtn.' '.$deleteBtn.'</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $ruangan = Ruangan::all();
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
        
        // If this is an AJAX request, return the modal HTML
        if (request()->ajax()) {
            return view('components.modals.ruangan.edit', compact('ruangan'))->render();
        }
        
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
