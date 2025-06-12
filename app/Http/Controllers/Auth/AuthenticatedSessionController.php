<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // dd(Auth::user());

        $user = User::where('id', Auth::user()->id)->first();

        // Flash pesan sukses login
        session()->flash('success', 'Login berhasil! Selamat datang kembali.');

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            return redirect()->route('profile.edit');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('success', 'Anda berhasil logout!');

        return redirect('/login');
    }
}
