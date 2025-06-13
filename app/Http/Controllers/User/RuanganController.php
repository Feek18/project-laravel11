<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('components.user.pages.detail');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');
        
        $ruangan = Ruangan::with(['peminjaman' => function($query) use ($currentDate, $currentTime) {
            $query->whereIn('status_persetujuan', ['pending', 'disetujui'])
                  ->where(function($q) use ($currentDate, $currentTime) {
                      // Only include future dates or current date with future times
                      $q->where('tanggal_pinjam', '>', $currentDate)
                        ->orWhere(function($subQ) use ($currentDate, $currentTime) {
                            $subQ->where('tanggal_pinjam', '=', $currentDate)
                                 ->where('waktu_selesai', '>=', $currentTime);
                        });
                  })
                  ->with('pengguna')
                  ->orderBy('tanggal_pinjam')
                  ->orderBy('waktu_mulai');
        }])->findOrFail($id);

        // Check if room is currently being used
        $currentDateTime = now();
        $currentBooking = $ruangan->peminjaman->filter(function ($peminjaman) use ($currentDateTime) {
            $bookingStart = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_mulai);
            $bookingEnd = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_selesai);
            return $currentDateTime->between($bookingStart, $bookingEnd) && $peminjaman->status_persetujuan === 'disetujui';
        })->first();

        // Add room status information
        $ruangan->is_currently_used = $currentBooking !== null;
        $ruangan->current_booking = $currentBooking;
        
        // Get upcoming bookings (future bookings only)
        $ruangan->upcoming_bookings = $ruangan->peminjaman->filter(function($booking) use ($currentDateTime) {
            $bookingStart = \Carbon\Carbon::parse($booking->tanggal_pinjam . ' ' . $booking->waktu_mulai);
            return $bookingStart->isAfter($currentDateTime);
        })->take(5);

        // Get today's remaining bookings (for current date only)
        $ruangan->todays_bookings = $ruangan->peminjaman->filter(function($booking) use ($currentDate, $currentDateTime) {
            return $booking->tanggal_pinjam === $currentDate;
        });

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
