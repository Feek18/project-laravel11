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

        // dd($request->all());    
        $user = $request->user();

        // Update data email (users table)
        $user->fill($request->only('email'));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // Update atau buat data pengguna (penggunas table)
        $user->pengguna()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'gambar' => $request->input('gambar'),
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'no_telp' => $request->input('no_telp'),
                'gender' => $request->input('gender'),
            ]
        );
        $path = $request->file('gambar')->store('images', 'public');
        $user->pengguna->gambar = $path;
        $user->pengguna->save();

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
