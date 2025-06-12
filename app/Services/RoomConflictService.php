<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Peminjaman;
use App\Traits\RoomConflictChecker;

class RoomConflictService
{
    use RoomConflictChecker;    /**
     * Validate room booking request for conflicts
     * 
     * @param array $data - booking data (id_ruang, tanggal/hari, waktu_mulai, waktu_selesai)
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
        $waktu_selesai = $this->normalizeTime($data['waktu_selesai'] ?? $data['jam_selesai']);        // Check conflicts based on booking type
        if ($type === 'jadwal') {
            // For jadwal, use hari (day of week) directly and check against both jadwal and peminjaman
            $conflicts = Jadwal::checkAllConflictsForJadwal(
                $data['id_ruang'],
                $data['hari'],
                $waktu_mulai,
                $waktu_selesai,
                $exclude_id
            );
            
            // Format conflicts for response
            $hasConflicts = !empty($conflicts);
            $messages = [];
            
            if (isset($conflicts['jadwal']) && $conflicts['jadwal']->count() > 0) {
                $messages[] = 'Terdapat konflik dengan jadwal perkuliahan lain pada hari dan waktu yang sama.';
            }
            
            if (isset($conflicts['peminjaman']) && $conflicts['peminjaman']->count() > 0) {
                $messages[] = 'Terdapat konflik dengan peminjaman ruangan pada hari dan waktu yang sama.';
            }
            
            $conflictData = [
                'has_conflicts' => $hasConflicts,
                'messages' => $messages,
                'jadwal_conflicts' => $conflicts['jadwal'] ?? collect(),
                'peminjaman_conflicts' => $conflicts['peminjaman'] ?? collect(),
            ];
        } else {
            // For peminjaman, use tanggal (specific date)
            $tanggal = $data['tanggal'] ?? $data['tanggal_pinjam'];
            $conflictData = $this->checkRoomConflicts(
                $data['id_ruang'],
                $tanggal,
                $waktu_mulai,
                $waktu_selesai,
                $exclude_id,
                null
            );
        }        if ($conflictData['has_conflicts']) {
            $result['valid'] = false;
            
            // For jadwal, structure the conflicts properly
            if ($type === 'jadwal') {
                $result['conflicts'] = [
                    'jadwal_conflicts' => $conflictData['jadwal_conflicts'],
                    'peminjaman_conflicts' => $conflictData['peminjaman_conflicts']
                ];
            } else {
                $result['conflicts'] = $conflictData;
            }
            
            $result['messages'] = $conflictData['messages'];
            
            // Generate suggestions for alternative times
            if ($type === 'jadwal') {
                $result['suggestions'] = $this->getAlternativeTimeSlotsForDay(
                    $data['id_ruang'],
                    $data['hari'],
                    $waktu_mulai,
                    $waktu_selesai
                );
            } else {
                $tanggal = $data['tanggal'] ?? $data['tanggal_pinjam'];
                $result['suggestions'] = $this->getAlternativeTimeSlots(
                    $data['id_ruang'],
                    $tanggal,
                    $waktu_mulai,
                    $waktu_selesai
                );
            }
        }

        return $result;
    }

    /**
     * Check for conflicts based on day of the week (for jadwal)
     */
    public function checkRoomConflictsByDay($id_ruang, $hari, $waktu_mulai, $waktu_selesai, $exclude_peminjaman_id = null, $exclude_jadwal_id = null)
    {
        $conflicts = [
            'has_conflicts' => false,
            'peminjaman' => [],
            'jadwal' => [],
            'messages' => []
        ];

        // Check for jadwal conflicts (by day)
        $jadwalConflicts = Jadwal::getConflictingJadwalByDay($id_ruang, $hari, $waktu_mulai, $waktu_selesai, $exclude_jadwal_id);
        if ($jadwalConflicts->count() > 0) {
            $conflicts['has_conflicts'] = true;
            $conflicts['jadwal'] = $jadwalConflicts;
            $conflicts['messages'][] = 'Terdapat konflik dengan jadwal perkuliahan lain pada hari dan waktu yang sama.';
        }

        // Note: We don't check peminjaman conflicts for jadwal because jadwal is permanent
        // and takes priority over one-time peminjaman bookings

        return $conflicts;
    }

    /**
     * Get room schedule summary for a specific date
     */
    public function getRoomScheduleSummary($id_ruang, $tanggal)
    {
        $jadwalSchedule = Jadwal::getRoomSchedule($id_ruang, $tanggal);
        $peminjamanSchedule = Peminjaman::getRoomSchedule($id_ruang, $tanggal);

        $schedule = [];

        // Add jadwal to schedule (based on day of week)
        foreach ($jadwalSchedule as $jadwal) {
            $schedule[] = [
                'type' => 'Jadwal Perkuliahan',
                'title' => $jadwal->matkul->mata_kuliah ?? 'Mata Kuliah',
                'start_time' => substr($jadwal->jam_mulai, 0, 5),
                'end_time' => substr($jadwal->jam_selesai, 0, 5),
                'status' => 'Confirmed',
                'details' => 'Kode: ' . ($jadwal->matkul->kode_matkul ?? 'N/A') . ' | Setiap ' . ucfirst($jadwal->hari),
                'is_recurring' => true, // Mark jadwal as recurring
                'day' => $jadwal->hari
            ];
        }

        // Add peminjaman to schedule (specific date)
        foreach ($peminjamanSchedule as $peminjaman) {
            $schedule[] = [
                'type' => 'Peminjaman',
                'title' => $peminjaman->keperluan,
                'start_time' => substr($peminjaman->waktu_mulai, 0, 5),
                'end_time' => substr($peminjaman->waktu_selesai, 0, 5),
                'status' => ucfirst($peminjaman->status_persetujuan),
                'details' => 'Peminjam: ' . ($peminjaman->pengguna->nama ?? 'N/A'),
                'is_recurring' => false, // Peminjaman is one-time
                'date' => $peminjaman->tanggal_pinjam
            ];
        }

        // Sort by start time
        usort($schedule, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });

        return $schedule;
    }

    /**
     * Get room schedule summary for a specific day of week (for jadwal)
     */
    public function getRoomScheduleByDay($id_ruang, $hari)
    {
        $jadwalSchedule = Jadwal::getRoomScheduleByDay($id_ruang, $hari);

        $schedule = [];

        // Add jadwal to schedule (based on day of week)
        foreach ($jadwalSchedule as $jadwal) {
            $schedule[] = [
                'type' => 'Jadwal Perkuliahan',
                'title' => $jadwal->matkul->mata_kuliah ?? 'Mata Kuliah',
                'start_time' => substr($jadwal->jam_mulai, 0, 5),
                'end_time' => substr($jadwal->jam_selesai, 0, 5),
                'status' => 'Confirmed',
                'details' => 'Kode: ' . ($jadwal->matkul->kode_matkul ?? 'N/A') . ' | Setiap ' . ucfirst($hari),
                'is_recurring' => true, // Mark jadwal as recurring
                'day' => $hari
            ];
        }

        // Sort by start time
        usort($schedule, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });

        return $schedule;
    }

    /**
     * Format conflict details for user display
     */
    public function formatConflictDetails($conflicts)
    {
        $details = [];        if (!empty($conflicts['jadwal'])) {
            foreach ($conflicts['jadwal'] as $jadwal) {
                $details[] = [
                    'type' => 'Jadwal Perkuliahan',
                    'title' => $jadwal->matkul->mata_kuliah ?? 'Mata Kuliah',
                    'time' => substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5),
                    'room' => $jadwal->ruangan->nama_ruangan ?? 'Ruangan',
                    'status' => 'Confirmed',
                    'details' => 'Kode MK: ' . ($jadwal->matkul->kode_matkul ?? 'N/A') . ' | Setiap ' . ucfirst($jadwal->hari),
                    'conflict_source' => 'jadwal_perkuliahan',
                    'id' => $jadwal->id,
                    'recurring' => true, // Jadwal is recurring by day
                    'day' => $jadwal->hari
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

    /**
     * Get alternative time slots for a specific day of week (for jadwal)
     */
    private function getAlternativeTimeSlotsForDay($id_ruang, $hari, $requested_start, $requested_end)
    {
        $availableSlots = $this->getAvailableTimeSlotsForDay($id_ruang, $hari);
        $requestedDuration = $this->calculateDurationMinutes($requested_start, $requested_end);
        
        $suggestions = [];
        foreach ($availableSlots as $slot) {
            $slotDuration = $this->calculateDurationMinutes($slot['start'], $slot['end']);
            
            if ($slotDuration >= $requestedDuration) {
                $suggestions[] = [
                    'start_time' => substr($slot['start'], 0, 5), // Format HH:MM
                    'end_time' => $this->addMinutes($slot['start'], $requestedDuration),
                    'available_duration' => $this->formatDuration($slotDuration)
                ];
            }
        }

        return array_slice($suggestions, 0, 5); // Return max 5 suggestions
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
                    'available_duration' => $this->formatDuration($slotDuration)
                ];
            }
        }

        return array_slice($suggestions, 0, 5); // Return max 5 suggestions
    }

    /**
     * Get available time slots for a room on a specific day of week (for jadwal)
     */
    public function getAvailableTimeSlotsForDay($id_ruang, $hari)
    {
        // Get all jadwal bookings for this day
        $jadwalSchedule = Jadwal::getRoomScheduleByDay($id_ruang, $hari);

        $bookedSlots = [];

        // Add jadwal slots (recurring by day)
        foreach ($jadwalSchedule as $jadwal) {
            $bookedSlots[] = [
                'start' => $jadwal->jam_mulai,
                'end' => $jadwal->jam_selesai,
                'type' => 'jadwal',
                'recurring' => true
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
     * Get available time slots for a room on a specific date
     */
    public function getAvailableTimeSlots($id_ruang, $tanggal)
    {
        // Get all bookings for the date
        $jadwalSchedule = Jadwal::getRoomSchedule($id_ruang, $tanggal);
        $peminjamanSchedule = Peminjaman::getRoomSchedule($id_ruang, $tanggal);

        $bookedSlots = [];

        // Add jadwal slots (recurring by day)
        foreach ($jadwalSchedule as $jadwal) {
            $bookedSlots[] = [
                'start' => $jadwal->jam_mulai,
                'end' => $jadwal->jam_selesai,
                'type' => 'jadwal',
                'recurring' => true
            ];
        }

        // Add peminjaman slots (specific date)
        foreach ($peminjamanSchedule as $peminjaman) {
            $bookedSlots[] = [
                'start' => $peminjaman->waktu_mulai,
                'end' => $peminjaman->waktu_selesai,
                'type' => 'peminjaman',
                'recurring' => false
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
     * Normalize time format to HH:MM:SS
     */
    private function normalizeTime($time)
    {
        if (!$time) return null;
        
        // If already in HH:MM:SS format, return as is
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            return $time;
        }
        
        // If in HH:MM format, add seconds
        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time . ':00';
        }
        
        return $time;
    }

    /**
     * Calculate duration in minutes between two times
     */
    private function calculateDurationMinutes($start, $end)
    {
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        return ($endTime - $startTime) / 60;
    }

    /**
     * Add minutes to a time string
     */
    private function addMinutes($time, $minutes)
    {
        $timestamp = strtotime($time);
        $newTimestamp = $timestamp + ($minutes * 60);
        return date('H:i', $newTimestamp);
    }

    /**
     * Format duration in minutes to human readable format
     */
    private function formatDuration($minutes)
    {
        if ($minutes < 60) {
            return $minutes . ' min';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes === 0) {
            return $hours . ' hr';
        }
        
        return $hours . ' hr ' . $remainingMinutes . ' min';
    }
}
