<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Services\RoomConflictService;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $ruangan = Ruangan::first();
        return view('components.user.dashboard');
        // return view('components.user.pages.detail', compact('ruangan'));
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
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'keperluan' => 'required|string|max:255',
        ]);

        // Prepare data for validation
        $validatedData = [
            'id_ruang' => $request->id_ruang,
            'tanggal_pinjam' => $request->tanggal_pinjam, 
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'keperluan' => $request->keperluan,
        ];

        // Use RoomConflictService for comprehensive validation
        $conflictService = new RoomConflictService();
        $validation = $conflictService->validateBooking($validatedData, 'peminjaman');

        if (!$validation['valid']) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $validation['messages']));
        }

        Peminjaman::create([
            'id_pengguna' => Auth::user()->pengguna->id,
            'id_ruang' => $request->id_ruang,
            'status_peminjaman' => 'terencana',
            'keperluan' => $request->keperluan,
            'status_persetujuan' => 'pending',
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Peminjaman berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('components.user.pages.detail', compact('ruangan'));
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
        //
    }
}
