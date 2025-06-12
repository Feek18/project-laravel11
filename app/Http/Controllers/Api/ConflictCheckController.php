<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoomConflictService;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class ConflictCheckController extends Controller
{
    protected $conflictService;

    public function __construct(RoomConflictService $conflictService)
    {
        $this->conflictService = $conflictService;
    }    /**
     * Check for conflicts in real-time
     */
    public function checkConflicts(Request $request)
    {
        // Validate based on booking type
        if ($request->type === 'jadwal') {
            $request->validate([
                'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
                'hari' => 'required|in:minggu,senin,selasa,rabu,kamis,jumat,sabtu',
                'waktu_mulai' => 'required|date_format:H:i',
                'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
                'type' => 'required|in:jadwal,peminjaman',
                'exclude_id' => 'nullable|integer'
            ]);
        } else {
            $request->validate([
                'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
                'tanggal' => 'required|date',
                'waktu_mulai' => 'required|date_format:H:i',
                'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
                'type' => 'required|in:jadwal,peminjaman',
                'exclude_id' => 'nullable|integer'
            ]);
        }

        // Prepare data for validation
        if ($request->type === 'jadwal') {
            $data = [
                'id_ruang' => $request->id_ruang,
                'hari' => $request->hari,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai
            ];
        } else {
            $data = [
                'id_ruang' => $request->id_ruang,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai
            ];
        }

        $validation = $this->conflictService->validateBooking(
            $data, 
            $request->type, 
            $request->exclude_id
        );        $response = [
            'has_conflicts' => !$validation['valid'],
            'conflicts' => [],
            'jadwal_conflicts' => [],
            'peminjaman_conflicts' => [],
            'messages' => $validation['messages'],
            'suggestions' => $validation['suggestions'] ?? [],
            'room_schedule' => []
        ];        if (!$validation['valid']) {
            // For jadwal type, return structured conflicts
            if ($request->type === 'jadwal' && isset($validation['conflicts'])) {
                $response['jadwal_conflicts'] = $validation['conflicts']['jadwal_conflicts'] ?? [];
                $response['peminjaman_conflicts'] = $validation['conflicts']['peminjaman_conflicts'] ?? [];
            } else {
                // For peminjaman, structure the conflicts properly
                if (isset($validation['conflicts']['jadwal'])) {
                    $response['jadwal_conflicts'] = $validation['conflicts']['jadwal'];
                }
                if (isset($validation['conflicts']['peminjaman'])) {
                    $response['peminjaman_conflicts'] = $validation['conflicts']['peminjaman'];
                }
                
                // Also provide legacy format for backward compatibility
                $response['conflicts'] = $this->conflictService->formatConflictDetails($validation['conflicts']);
            }
        }

        // Get room schedule
        if ($request->type === 'jadwal') {
            // For jadwal, show schedule for this day of week
            $response['room_schedule'] = $this->conflictService->getRoomScheduleByDay(
                $request->id_ruang, 
                $request->hari
            );
        } else {
            // For peminjaman, show schedule for specific date
            $response['room_schedule'] = $this->conflictService->getRoomScheduleSummary(
                $request->id_ruang, 
                $request->tanggal
            );
        }

        return response()->json($response);
    }

    /**
     * Get room schedule for a specific date
     */
    public function getRoomSchedule(Request $request)
    {
        $request->validate([
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            'tanggal' => 'required|date'
        ]);

        $schedule = $this->conflictService->getRoomScheduleSummary(
            $request->id_ruang, 
            $request->tanggal
        );

        $room = Ruangan::find($request->id_ruang);

        return response()->json([
            'room_name' => $room->nama_ruangan ?? 'Unknown Room',
            'schedule' => $schedule,
            'date' => $request->tanggal
        ]);
    }

    /**
     * Get available time slots for a room on a specific date
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
            'tanggal' => 'required|date',
            'duration' => 'nullable|integer|min:30|max:480' // 30 minutes to 8 hours
        ]);

        $duration = $request->duration ?? 120; // Default 2 hours
        $availableSlots = $this->conflictService->getAvailableTimeSlots(
            $request->id_ruang, 
            $request->tanggal
        );

        // Filter slots that can accommodate the requested duration
        $suitableSlots = [];
        foreach ($availableSlots as $slot) {
            $slotDuration = $this->conflictService->calculateDurationMinutes($slot['start'], $slot['end']);
            if ($slotDuration >= $duration) {
                $suitableSlots[] = [
                    'start_time' => substr($slot['start'], 0, 5),
                    'end_time' => substr($slot['end'], 0, 5),
                    'duration_minutes' => $slotDuration,
                    'can_fit_requested' => true
                ];
            }
        }

        return response()->json([
            'available_slots' => $suitableSlots,
            'requested_duration' => $duration,
            'total_slots' => count($suitableSlots)
        ]);
    }
}
