<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('pengguna');

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update email di tabel users
        $user->fill($request->only('email'));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // Simpan gambar dulu dengan disk 'public'
        $path = $request->file('gambar')->store('gambar', 'public');

        // Baru update atau buat data pengguna
        $user->pengguna()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'gambar' => $path, // ✅ path hasil upload
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'no_telp' => $request->input('no_telp'),
                'gender' => $request->input('gender'),
            ]
        );

        return Redirect::route('profile.edit')->with('success', 'Profile berhasil diupdate');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
