<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengguna = Pengguna::whereNull('user_id')->get();
        $akun = Pengguna::whereNotNull('user_id')->with(['user:id,email'])->paginate(5);
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
