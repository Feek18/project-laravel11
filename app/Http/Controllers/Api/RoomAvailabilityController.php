<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RoomAvailabilityController extends Controller
{
    /**
     * Check room availability for a specific date and time
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            'tanggal_pinjam' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'exclude_id' => 'nullable|integer', // For updates
        ]);

        $pendingConflict = Peminjaman::hasPendingConflict(
            $request->id_ruang,
            $request->tanggal_pinjam,
            $request->waktu_mulai,
            $request->waktu_selesai,
            $request->exclude_id
        );

        $approvedConflict = Peminjaman::hasApprovedConflict(
            $request->id_ruang,
            $request->tanggal_pinjam,
            $request->waktu_mulai,
            $request->waktu_selesai,
            $request->exclude_id
        );

        $conflictingBookings = Peminjaman::getConflictingBookings(
            $request->id_ruang,
            $request->tanggal_pinjam,
            $request->waktu_mulai,
            $request->waktu_selesai,
            $request->exclude_id
        );

        return response()->json([
            'available' => !$pendingConflict && !$approvedConflict,
            'has_pending_conflict' => $pendingConflict,
            'has_approved_conflict' => $approvedConflict,
            'conflicting_bookings' => $conflictingBookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'pengguna' => $booking->pengguna->nama ?? 'Unknown',
                    'waktu_mulai' => $booking->waktu_mulai,
                    'waktu_selesai' => $booking->waktu_selesai,
                    'status' => $booking->status_persetujuan,
                    'keperluan' => $booking->keperluan,
                ];
            }),
            'message' => $this->getAvailabilityMessage($pendingConflict, $approvedConflict)
        ]);
    }

    /**
     * Get room schedule for a specific date
     */
    public function getRoomSchedule(Request $request)
    {
        $request->validate([
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            'tanggal_pinjam' => 'required|date',
        ]);

        $schedule = Peminjaman::getRoomSchedule(
            $request->id_ruang,
            $request->tanggal_pinjam
        );

        $ruangan = Ruangan::find($request->id_ruang);

        return response()->json([
            'ruangan' => [
                'id' => $ruangan->id_ruang,
                'nama' => $ruangan->nama_ruangan,
                'lokasi' => $ruangan->lokasi,
            ],
            'tanggal' => $request->tanggal_pinjam,
            'schedule' => $schedule->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'pengguna' => $booking->pengguna->nama ?? 'Unknown',
                    'waktu_mulai' => $booking->waktu_mulai,
                    'waktu_selesai' => $booking->waktu_selesai,
                    'status' => $booking->status_persetujuan,
                    'keperluan' => $booking->keperluan,
                ];
            })
        ]);
    }

    /**
     * Get availability message based on conflicts
     */
    private function getAvailabilityMessage($pendingConflict, $approvedConflict)
    {
        if ($approvedConflict) {
            return 'Ruangan sudah dikonfirmasi untuk peminjaman lain pada waktu tersebut.';
        }
        
        if ($pendingConflict) {
            return 'Ruangan sedang dalam proses peminjaman lain pada waktu tersebut (menunggu persetujuan).';
        }
        
        return 'Ruangan tersedia pada waktu yang dipilih.';
    }
}
