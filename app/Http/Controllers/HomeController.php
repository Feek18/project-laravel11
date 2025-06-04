<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class HomeController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::all()->slice(0, 4);
        // dd($ruangans);
        return view('index', compact('ruangans'));
    }
    public function show($id)
    {
        $ruangan = Ruangan::find($id);
        return view('components.user.pages.detail', compact('ruangan'));
    }
}
