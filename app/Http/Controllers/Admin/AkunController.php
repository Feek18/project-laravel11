<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengguna::whereNotNull('user_id')->with(['user:id,email'])->select('penggunas.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('email', function($row){
                    return $row->user->email ?? '-';
                })
                ->addColumn('action', function($row){
                    $deleteBtn = '<form method="POST" action="'.route('akun.destroy', $row->user->id).'" style="display: inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150" onclick="return confirm(\'Apakah Anda yakin ingin menghapus akun ini?\')">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </form>';
                    
                    return $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $pengguna = Pengguna::whereNull('user_id')->get();
        $akun = Pengguna::whereNotNull('user_id')->with(['user:id,email'])->get();
        return view('components.admin.akun', compact('pengguna', 'akun'));
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'pengguna' => 'nullable|exists:penggunas,id',
            'password' => 'required|string|min:3|max:8',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('pengguna');
        if ($request->filled('pengguna')) {
            $pengguna = Pengguna::whereNull('user_id')->find($request->pengguna);
            if ($pengguna) {
                $pengguna->user_id = $user->id;
                $pengguna->save();
            }
        }

        return redirect()->route('akun.index')->with('success', 'Pengguna berhasil ditambahkan.');
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
        $user = User::with('pengguna')->find($id);
        if ($user->pengguna) {
            $user->pengguna->user_id = null;
            $user->pengguna->save();
        }

        $user->delete();
        return redirect()->route('akun.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
