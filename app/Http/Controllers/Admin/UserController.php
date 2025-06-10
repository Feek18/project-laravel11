<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengguna::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editBtn = '<button onclick="loadEditModal(\'pengguna\', '.$row->id.')" type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </button>';
                    
                    $deleteBtn = '<form method="POST" action="'.route('pengguna.destroy', $row->id).'" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150" onclick="return confirm(\'Apakah Anda yakin ingin menghapus pengguna ini?\')">
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

        $users = Pengguna::all();
        return view('components.admin.pengguna', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'gender' => 'required|in:pria,wanita',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'gender' => $request->gender,
            'no_telp' => $request->no_telp,
            'gambar' => null,
        ]);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Pengguna::findOrFail($id);
        
        // If this is an AJAX request, return the modal HTML
        if (request()->ajax()) {
            return view('components.modals.pengguna.edit', compact('user'))->render();
        }
        
        return view('components.modals.pengguna.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Pengguna::findOrFail($id);
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'gender' => 'required|in:pria,wanita',
            'no_telp' => 'nullable|string|max:20',
        ]);

        $user->update($validatedData);
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
