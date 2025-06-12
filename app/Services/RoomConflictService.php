<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Peminjaman;
use App\Traits\RoomConflictChecker;

class RoomConflictService
{
    use RoomConflictChecker;

    /**
     * Validate room booking request for conflicts
     * 
     * @param array $data - booking data (id_ruang, tanggal, waktu_mulai, waktu_selesai)
     * @param string $type - 'jadwal' or 'peminjaman'
     * @param int|null $exclude_id - ID to exclude from conflict check
     * @return array
     */
    public function validateBooking($data, $type = 'peminjaman', $exclude_id = null)
    {
        $result = [
            'valid' => true,
            'conflicts' => [],
            'messages' => [],
            'suggestions' => []
        ];

        // Standardize time format
        $waktu_mulai = $this->normalizeTime($data['waktu_mulai'] ?? $data['jam_mulai']);
        $waktu_selesai = $this->normalizeTime($data['waktu_selesai'] ?? $data['jam_selesai']);
        $tanggal = $data['tanggal'] ?? $data['tanggal_pinjam'];

        // Check conflicts based on booking type
        if ($type === 'jadwal') {
            $conflicts = $this->checkRoomConflicts(
                $data['id_ruang'],
                $tanggal,
                $waktu_mulai,
                $waktu_selesai,
                null,
                $exclude_id
            );
        } else {
            $conflicts = $this->checkRoomConflicts(
                $data['id_ruang'],
                $tanggal,
                $waktu_mulai,
                $waktu_selesai,
                $exclude_id,
                null
            );
        }

        if ($conflicts['has_conflicts']) {
            $result['valid'] = false;
            $result['conflicts'] = $conflicts;
            $result['messages'] = $conflicts['messages'];
            
            // Generate suggestions for alternative times
            $result['suggestions'] = $this->getAlternativeTimeSlots(
                $data['id_ruang'],
                $tanggal,
                $waktu_mulai,
                $waktu_selesai
            );
        }

        return $result;
    }

    /**
     * Get alternative time slots when there are conflicts
     */
    private function getAlternativeTimeSlots($id_ruang, $tanggal, $requested_start, $requested_end)
    {
        $availableSlots = $this->getAvailableTimeSlots($id_ruang, $tanggal);
        $requestedDuration = $this->calculateDurationMinutes($requested_start, $requested_end);
        
        $suggestions = [];
        foreach ($availableSlots as $slot) {
            $slotDuration = $this->calculateDurationMinutes($slot['start'], $slot['end']);
            
            if ($slotDuration >= $requestedDuration) {
                $suggestions[] = [
                    'start_time' => substr($slot['start'], 0, 5), // Format HH:MM
                    'end_time' => $this->addMinutes($slot['start'], $requestedDuration),
                    'available_duration' => $slotDuration . ' minutes'
                ];
            }
        }

        return array_slice($suggestions, 0, 3); // Return top 3 suggestions
    }

    /**
     * Normalize time format to HH:MM:SS
     */
    private function normalizeTime($time)
    {
        if (strlen($time) === 5) { // HH:MM format
            return $time . ':00';
        }
        return $time;
    }

    /**
     * Calculate duration between two times in minutes
     */
    public function calculateDurationMinutes($start, $end)
    {
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        return ($endTime - $startTime) / 60;
    }    /**
     * Add minutes to a time string
     */
    private function addMinutes($time, $minutes)
    {
        $timestamp = strtotime($time) + ($minutes * 60);
        return date('H:i', $timestamp);
    }

    /**
     * Get available time slots for a room on a specific date
     */
    public function getAvailableTimeSlots($id_ruang, $tanggal)
    {
        // Get all bookings for the date
        $jadwalSchedule = Jadwal::getRoomSchedule($id_ruang, $tanggal);
        $peminjamanSchedule = Peminjaman::getRoomSchedule($id_ruang, $tanggal);

        $bookedSlots = [];

        // Add jadwal slots
        foreach ($jadwalSchedule as $jadwal) {
            $bookedSlots[] = [
                'start' => $jadwal->jam_mulai,
                'end' => $jadwal->jam_selesai
            ];
        }

        // Add peminjaman slots
        foreach ($peminjamanSchedule as $peminjaman) {
            $bookedSlots[] = [
                'start' => $peminjaman->waktu_mulai,
                'end' => $peminjaman->waktu_selesai
            ];
        }

        // Sort by start time
        usort($bookedSlots, function($a, $b) {
            return strcmp($a['start'], $b['start']);
        });

        // Find available slots between 07:00 and 22:00
        $availableSlots = [];
        $dayStart = '07:00:00';
        $dayEnd = '22:00:00';
        $currentTime = $dayStart;

        foreach ($bookedSlots as $booking) {
            // If there's a gap between current time and next booking
            if ($currentTime < $booking['start']) {
                $availableSlots[] = [
                    'start' => $currentTime,
                    'end' => $booking['start']
                ];
            }
            // Move current time to end of booking
            $currentTime = max($currentTime, $booking['end']);
        }

        // Add final slot if any time left in day
        if ($currentTime < $dayEnd) {
            $availableSlots[] = [
                'start' => $currentTime,
                'end' => $dayEnd
            ];
        }

        return $availableSlots;
    }

    /**
     * Get room schedule summary for a specific date
     */
    public function getRoomScheduleSummary($id_ruang, $tanggal)
    {
        $jadwalSchedule = Jadwal::getRoomSchedule($id_ruang, $tanggal);
        $peminjamanSchedule = Peminjaman::getRoomSchedule($id_ruang, $tanggal);

        $schedule = [];

        // Add jadwal to schedule
        foreach ($jadwalSchedule as $jadwal) {
            $schedule[] = [
                'type' => 'Jadwal Perkuliahan',
                'title' => $jadwal->matkul->mata_kuliah ?? 'Mata Kuliah',
                'start_time' => substr($jadwal->jam_mulai, 0, 5),
                'end_time' => substr($jadwal->jam_selesai, 0, 5),
                'status' => 'Confirmed',
                'details' => 'Kode: ' . ($jadwal->matkul->kode_matkul ?? 'N/A')
            ];
        }

        // Add peminjaman to schedule
        foreach ($peminjamanSchedule as $peminjaman) {
            $schedule[] = [
                'type' => 'Peminjaman',
                'title' => $peminjaman->keperluan,
                'start_time' => substr($peminjaman->waktu_mulai, 0, 5),
                'end_time' => substr($peminjaman->waktu_selesai, 0, 5),
                'status' => ucfirst($peminjaman->status_persetujuan),
                'details' => 'Peminjam: ' . ($peminjaman->pengguna->nama ?? 'N/A')
            ];
        }

        // Sort by start time
        usort($schedule, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });

        return $schedule;
    }    /**
     * Format conflict details for user display
     */
    public function formatConflictDetails($conflicts)
    {
        $details = [];

        if (!empty($conflicts['jadwal'])) {
            foreach ($conflicts['jadwal'] as $jadwal) {
                $details[] = [
                    'type' => 'Jadwal Perkuliahan',
                    'title' => $jadwal->matkul->mata_kuliah ?? 'Mata Kuliah',
                    'time' => substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5),
                    'room' => $jadwal->ruangan->nama_ruangan ?? 'Ruangan',
                    'status' => 'Confirmed',
                    'details' => 'Kode MK: ' . ($jadwal->matkul->kode_matkul ?? 'N/A') . ' | Hari: ' . ucfirst($jadwal->hari),
                    'conflict_source' => 'jadwal_perkuliahan',
                    'id' => $jadwal->id
                ];
            }
        }

        if (!empty($conflicts['peminjaman'])) {
            foreach ($conflicts['peminjaman'] as $peminjaman) {
                $details[] = [
                    'type' => 'Peminjaman Ruangan',
                    'title' => $peminjaman->keperluan,
                    'time' => substr($peminjaman->waktu_mulai, 0, 5) . ' - ' . substr($peminjaman->waktu_selesai, 0, 5),
                    'room' => $peminjaman->ruangan->nama_ruangan ?? 'Ruangan',
                    'status' => ucfirst($peminjaman->status_persetujuan),
                    'details' => 'Peminjam: ' . ($peminjaman->pengguna->nama ?? 'N/A') . ' | Tanggal: ' . $peminjaman->tanggal_pinjam,
                    'conflict_source' => 'peminjaman_ruangan',
                    'id' => $peminjaman->id
                ];
            }
        }

        return $details;
    }
}
