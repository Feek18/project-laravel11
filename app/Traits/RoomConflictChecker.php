<?php

namespace App\Traits;

use App\Models\Jadwal;
use App\Models\Peminjaman;

trait RoomConflictChecker
{
    /**
     * Check for all types of room conflicts (jadwal and peminjaman)
     * 
     * @param int $id_ruang
     * @param string $tanggal
     * @param string $waktu_mulai
     * @param string $waktu_selesai
     * @param int|null $exclude_peminjaman_id
     * @param int|null $exclude_jadwal_id
     * @return array
     */
    public function checkRoomConflicts($id_ruang, $tanggal, $waktu_mulai, $waktu_selesai, $exclude_peminjaman_id = null, $exclude_jadwal_id = null)
    {
        $conflicts = [
            'has_conflicts' => false,
            'peminjaman' => [],
            'jadwal' => [],
            'messages' => []
        ];

        // Check for jadwal conflicts
        $jadwalConflicts = Jadwal::getConflictingJadwal($id_ruang, $tanggal, $waktu_mulai, $waktu_selesai, $exclude_jadwal_id);
        if ($jadwalConflicts->count() > 0) {
            $conflicts['has_conflicts'] = true;
            $conflicts['jadwal'] = $jadwalConflicts;
            $conflicts['messages'][] = 'Terdapat konflik dengan jadwal perkuliahan pada ruangan dan waktu yang sama.';
        }

        // Check for peminjaman conflicts
        $peminjamanConflicts = Peminjaman::getConflictingBookings($id_ruang, $tanggal, $waktu_mulai, $waktu_selesai, $exclude_peminjaman_id);
        if ($peminjamanConflicts->count() > 0) {
            $conflicts['has_conflicts'] = true;
            $conflicts['peminjaman'] = $peminjamanConflicts;
            $conflicts['messages'][] = 'Terdapat konflik dengan peminjaman ruangan pada waktu yang sama.';
        }

        return $conflicts;
    }

    /**
     * Get formatted conflict details for display
     * 
     * @param array $conflicts
     * @return array
     */
    public function formatConflictDetails($conflicts)
    {
        $details = [];

        if (!empty($conflicts['jadwal'])) {
            foreach ($conflicts['jadwal'] as $jadwal) {
                $details[] = [
                    'type' => 'Jadwal Perkuliahan',
                    'subject' => $jadwal->matkul->mata_kuliah ?? 'Mata Kuliah Tidak Diketahui',
                    'time' => $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai,
                    'room' => $jadwal->ruangan->nama_ruangan ?? 'Ruangan Tidak Diketahui'
                ];
            }
        }

        if (!empty($conflicts['peminjaman'])) {
            foreach ($conflicts['peminjaman'] as $peminjaman) {
                $details[] = [
                    'type' => 'Peminjaman Ruangan',
                    'subject' => $peminjaman->keperluan,
                    'time' => $peminjaman->waktu_mulai . ' - ' . $peminjaman->waktu_selesai,
                    'room' => $peminjaman->ruangan->nama_ruangan ?? 'Ruangan Tidak Diketahui',
                    'user' => $peminjaman->pengguna->nama ?? 'Pengguna Tidak Diketahui',
                    'status' => $peminjaman->status_persetujuan
                ];
            }
        }

        return $details;
    }

    /**
     * Get available time slots for a room on a specific date
     * 
     * @param int $id_ruang
     * @param string $tanggal
     * @param string $start_time (optional, default: 07:00:00)
     * @param string $end_time (optional, default: 22:00:00)
     * @return array
     */
    public function getAvailableTimeSlots($id_ruang, $tanggal, $start_time = '07:00:00', $end_time = '22:00:00')
    {
        // Get all occupied times (both jadwal and peminjaman)
        $jadwalTimes = Jadwal::where('id_ruang', $id_ruang)
            ->where('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get(['jam_mulai', 'jam_selesai']);

        $peminjamanTimes = Peminjaman::where('id_ruang', $id_ruang)
            ->where('tanggal_pinjam', $tanggal)
            ->whereIn('status_persetujuan', ['pending', 'disetujui'])
            ->orderBy('waktu_mulai')
            ->get(['waktu_mulai as jam_mulai', 'waktu_selesai as jam_selesai']);

        // Combine and sort all occupied times
        $occupiedTimes = $jadwalTimes->concat($peminjamanTimes)->sortBy('jam_mulai');

        // Generate available time slots
        $availableSlots = [];
        $currentTime = $start_time;

        foreach ($occupiedTimes as $occupied) {
            if ($currentTime < $occupied->jam_mulai) {
                $availableSlots[] = [
                    'start' => $currentTime,
                    'end' => $occupied->jam_mulai
                ];
            }
            $currentTime = max($currentTime, $occupied->jam_selesai);
        }

        // Add final slot if there's time left
        if ($currentTime < $end_time) {
            $availableSlots[] = [
                'start' => $currentTime,
                'end' => $end_time
            ];
        }

        return $availableSlots;
    }
}
