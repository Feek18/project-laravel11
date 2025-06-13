<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        // Calculate date range for the selected month/year
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');
        
        // Get all rooms with their current borrowed status and details
        $ruangans = Ruangan::with(['jadwal', 'peminjaman' => function($query) use ($currentDate, $currentTime) {
            $query->whereIn('status_persetujuan', ['pending', 'disetujui'])
                  ->where('status_persetujuan', '!=', 'ditolak')
                  ->where(function($q) use ($currentDate, $currentTime) {
                      // Only include future dates or current date with future/ongoing times
                      $q->where('tanggal_pinjam', '>', $currentDate)
                        ->orWhere(function($subQ) use ($currentDate, $currentTime) {
                            $subQ->where('tanggal_pinjam', '=', $currentDate)
                                 ->where('waktu_selesai', '>=', $currentTime);
                        });
                  })
                  ->with('pengguna')
                  ->orderBy('tanggal_pinjam')
                  ->orderBy('waktu_mulai');
        }])->get()->map(function ($ruangan) {
            $currentTime = now();
            
            // Check if room has any active or future approved bookings
            $hasActiveBorrowing = $ruangan->peminjaman->filter(function ($peminjaman) use ($currentTime) {
                $bookingDate = Carbon::parse($peminjaman->tanggal_pinjam);
                $bookingStart = Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_mulai);
                $bookingEnd = Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_selesai);
                
                // Room is occupied if:
                // 1. There's an approved booking that is currently active
                // 2. There's any approved/pending booking for today or future dates
                return ($peminjaman->status_persetujuan === 'disetujui' && 
                       ($currentTime->between($bookingStart, $bookingEnd) || $bookingDate->isToday() || $bookingDate->isFuture())) ||
                       ($peminjaman->status_persetujuan === 'pending' && 
                       ($bookingDate->isToday() || $bookingDate->isFuture()));
            })->isNotEmpty();
            
            // Find current active booking
            $currentBooking = $ruangan->peminjaman->filter(function ($peminjaman) use ($currentTime) {
                $bookingStart = Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_mulai);
                $bookingEnd = Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_selesai);
                return $currentTime->between($bookingStart, $bookingEnd) && $peminjaman->status_persetujuan === 'disetujui';
            })->first();

            $ruangan->is_currently_used = $hasActiveBorrowing;
            $ruangan->current_booking = $currentBooking;
            $ruangan->upcoming_bookings = $ruangan->peminjaman->take(3);
            
            return $ruangan;
        });
        
        // Get peminjaman data for calendar filtered by selected month/year
        $peminjamans = Peminjaman::with(['pengguna', 'ruangan'])
            ->whereIn('status_persetujuan', ['pending', 'disetujui'])
            ->whereBetween('tanggal_pinjam', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->map(function ($peminjaman) {
            return [
                'id' => $peminjaman->id,
                'title' => $peminjaman->ruangan->nama_ruangan ?? 'Ruangan Tidak Diketahui',
                'description' => $peminjaman->keperluan,
                'start' => $peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_mulai,
                'end' => $peminjaman->tanggal_pinjam . ' ' . $peminjaman->waktu_selesai,
                'status' => $peminjaman->status_persetujuan,
                'pengguna' => $peminjaman->pengguna->nama ?? 'Pengguna Tidak Diketahui',
                'ruangan' => $peminjaman->ruangan->nama_ruangan ?? 'Ruangan Tidak Diketahui',
                'backgroundColor' => $this->getStatusColor($peminjaman->status_persetujuan),
                'borderColor' => $this->getStatusColor($peminjaman->status_persetujuan),
                'type' => 'peminjaman'
            ];
            });

        // Get jadwal data and generate recurring events for the selected month/year
        $jadwalEvents = $this->generateJadwalEvents($startDate, $endDate);
        
        // Combine both peminjaman and jadwal events
        // Convert to regular collection to merge properly
        $allEvents = collect($peminjamans)->merge($jadwalEvents);
        
        // Generate months and years for filter dropdowns
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $years = range(date('Y') - 2, date('Y') + 1); // Previous 2 years to next year
        
        return view('index', compact('ruangans', 'allEvents', 'months', 'years', 'month', 'year'));
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'disetujui':
                return '#10B981'; // green
            case 'ditolak':
                return '#EF4444'; // red
            case 'pending':
            default:
                return '#F59E0B'; // yellow
        }
    }

    /**
     * Generate recurring jadwal events for calendar display
     * Creates calendar events for each jadwal occurrence within a date range
     */
    private function generateJadwalEvents($startDate = null, $endDate = null)
    {
        // Default to current month if no dates provided
        if (!$startDate) {
            $startDate = Carbon::now()->startOfMonth();
        }
        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth()->addMonth(); // Include next month for better coverage
        }

        $jadwalEvents = collect();
        
        // Get all jadwal schedules
        $jadwalSchedules = Jadwal::with(['ruangan', 'matkul'])->get();
        
        foreach ($jadwalSchedules as $jadwal) {
            // Generate events for each occurrence of this jadwal within the date range
            $occurrences = $this->generateJadwalOccurrences($jadwal, $startDate, $endDate);
            $jadwalEvents = $jadwalEvents->merge($occurrences);
        }
        
        return $jadwalEvents;
    }

    /**
     * Generate specific date occurrences for a jadwal schedule
     */
    private function generateJadwalOccurrences($jadwal, $startDate, $endDate)
    {
        $occurrences = collect();
        
        // Map Indonesian day names to Carbon day numbers
        $dayMap = [
            'minggu' => Carbon::SUNDAY,
            'senin' => Carbon::MONDAY,
            'selasa' => Carbon::TUESDAY,
            'rabu' => Carbon::WEDNESDAY,
            'kamis' => Carbon::THURSDAY,
            'jumat' => Carbon::FRIDAY,
            'sabtu' => Carbon::SATURDAY
        ];
        
        $targetDayOfWeek = $dayMap[$jadwal->hari] ?? Carbon::MONDAY;
        
        // Find the first occurrence of the target day within our date range
        $currentDate = $startDate->copy();
        
        // Move to the first occurrence of the target day
        while ($currentDate->dayOfWeek !== $targetDayOfWeek && $currentDate->lte($endDate)) {
            $currentDate->addDay();
        }
        
        // Generate occurrences for each week
        while ($currentDate->lte($endDate)) {
            $startDateTime = $currentDate->copy()->setTimeFromTimeString($jadwal->jam_mulai);
            $endDateTime = $currentDate->copy()->setTimeFromTimeString($jadwal->jam_selesai);
            
            $occurrences->push([
                'id' => 'jadwal_' . $jadwal->id . '_' . $currentDate->format('Y-m-d'),
                'title' => ($jadwal->ruangan->nama_ruangan ?? 'Ruangan') . ' - ' . ($jadwal->matkul->mata_kuliah ?? 'Mata Kuliah'),
                'description' => 'Jadwal Perkuliahan: ' . ($jadwal->matkul->mata_kuliah ?? 'Mata Kuliah'),
                'start' => $startDateTime->format('Y-m-d H:i:s'),
                'end' => $endDateTime->format('Y-m-d H:i:s'),
                'status' => 'jadwal',
                'pengguna' => 'Jadwal Perkuliahan',
                'ruangan' => $jadwal->ruangan->nama_ruangan ?? 'Ruangan Tidak Diketahui',
                'backgroundColor' => '#3B82F6', // blue for jadwal
                'borderColor' => '#2563EB',
                'type' => 'jadwal',
                'hari' => ucfirst($jadwal->hari),
                'mata_kuliah' => $jadwal->matkul->mata_kuliah ?? 'Mata Kuliah',
                'kode_matkul' => $jadwal->matkul->kode_matkul ?? '',
                'recurring' => true
            ]);
            
            // Move to next week
            $currentDate->addWeek();
        }
        
        return $occurrences;
    }
    
    public function show($id)
    {
        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');
        
        $ruangan = Ruangan::with(['peminjaman' => function($query) use ($currentDate, $currentTime) {
            $query->whereIn('status_persetujuan', ['pending', 'disetujui'])
                  ->where(function($q) use ($currentDate, $currentTime) {
                      // Only include future dates or current date with future/ongoing times
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

        \Log::info("Room bookings for room $id:", $ruangan->peminjaman->pluck('status_persetujuan')->toArray());
        
        return view('components.user.pages.detail', compact('ruangan'));
    }
}
