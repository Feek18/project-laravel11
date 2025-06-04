<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = Pengguna::paginate(5);
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
        // dd($pengguna);

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Pengguna::findOrFail($id);
        return view('components.modals.pengguna.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Pengguna::findOrFail($id);
        // dd($user);
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
