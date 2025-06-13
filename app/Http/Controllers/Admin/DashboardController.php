<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Jadwal;
use App\Models\Pengguna;
use App\Models\Ruangan;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all peminjaman data for the calendar
        $peminjamans = Peminjaman::with(['pengguna', 'ruangan'])
            ->whereIn('status_persetujuan', ['pending', 'disetujui'])
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
                    'type' => 'peminjaman',
                ];
            });

        // Generate jadwal events for the next 3 months
        $jadwalEvents = $this->generateJadwalEvents();
        
        // Combine both types of events
        $allEvents = $peminjamans->concat($jadwalEvents);

        // Get comprehensive dashboard statistics
        $stats = $this->getDashboardStats();

        return view('components.admin.dashboard', compact('allEvents', 'stats'));
    }

    private function getDashboardStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Get current counts
        $totalUsers = Pengguna::count();
        $totalRooms = Ruangan::count();
        $totalMataKuliah = MataKuliah::count();
        $totalPeminjaman = Peminjaman::count();

        // Get last month counts for percentage calculations
        $lastMonthUsers = Pengguna::where('created_at', '<=', $lastMonthEnd)->count();
        $lastMonthPeminjaman = Peminjaman::where('created_at', '<=', $lastMonthEnd)->count();
        
        // Calculate percentage changes
        $userGrowth = $this->calculatePercentageChange($lastMonthUsers, $totalUsers);
        $peminjamanGrowth = $this->calculatePercentageChange($lastMonthPeminjaman, $totalPeminjaman);

        // Peminjaman statistics
        $peminjamanStats = [
            'pending' => Peminjaman::where('status_persetujuan', 'pending')->count(),
            'disetujui' => Peminjaman::where('status_persetujuan', 'disetujui')->count(),
            'ditolak' => Peminjaman::where('status_persetujuan', 'ditolak')->count(),
            'today' => Peminjaman::whereDate('tanggal_pinjam', $today)->count(),
            'thisMonth' => Peminjaman::where('created_at', '>=', $thisMonth)->count(),
        ];        // Room utilization (rooms that have bookings today)
        $activeRoomsToday = Peminjaman::whereDate('tanggal_pinjam', $today)
            ->where('status_persetujuan', 'disetujui')
            ->distinct('id_ruang')
            ->count();
        
        $roomUtilization = $totalRooms > 0 ? round(($activeRoomsToday / $totalRooms) * 100, 2) : 0;

        // Recent bookings for the activity feed
        $recentBookings = Peminjaman::with(['pengguna', 'ruangan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly booking trend for charts (last 6 months)
        $monthlyBookings = $this->getMonthlyBookingTrend();

        // Status distribution for pie chart
        $statusDistribution = [
            'pending' => $peminjamanStats['pending'],
            'disetujui' => $peminjamanStats['disetujui'],
            'ditolak' => $peminjamanStats['ditolak'],
        ];

        // Most popular rooms
        $popularRooms = Peminjaman::select('id_ruang', DB::raw('count(*) as total'))
            ->with('ruangan')
            ->groupBy('id_ruang')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return [
            'totalUsers' => $totalUsers,
            'totalRooms' => $totalRooms,
            'totalMataKuliah' => $totalMataKuliah,
            'totalPeminjaman' => $totalPeminjaman,
            'userGrowth' => $userGrowth,
            'peminjamanGrowth' => $peminjamanGrowth,
            'peminjaman' => $peminjamanStats,
            'roomUtilization' => $roomUtilization,
            'recentBookings' => $recentBookings,
            'monthlyBookings' => $monthlyBookings,
            'statusDistribution' => $statusDistribution,
            'popularRooms' => $popularRooms,
        ];
    }

    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        return round((($newValue - $oldValue) / $oldValue) * 100, 2);
    }

    private function getMonthlyBookingTrend()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Peminjaman::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $months[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }
        return $months;
    }private function getStatusColor($status)
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

    private function generateJadwalEvents()
    {
        $jadwals = Jadwal::with(['matkul', 'ruangan'])->get();
        $events = collect();
        
        // Generate events for the next 3 months
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(3)->endOfMonth();
        
        foreach ($jadwals as $jadwal) {
            $jadwalEvents = $this->generateJadwalOccurrences($jadwal, $startDate, $endDate);
            $events = $events->concat($jadwalEvents);
        }
        
        return $events;
    }

    private function generateJadwalOccurrences($jadwal, $startDate, $endDate)
    {
        $events = collect();
        $current = $startDate->copy();
        
        // Map Indonesian day names to Carbon day numbers
        $dayMapping = [
            'senin' => 1,    // Monday
            'selasa' => 2,   // Tuesday
            'rabu' => 3,     // Wednesday
            'kamis' => 4,    // Thursday
            'jumat' => 5,    // Friday
            'sabtu' => 6,    // Saturday
            'minggu' => 0,   // Sunday
        ];
        
        $targetDay = $dayMapping[strtolower($jadwal->hari)] ?? null;
        if ($targetDay === null) return $events;
        
        while ($current <= $endDate) {
            if ($current->dayOfWeek === $targetDay) {
                $events->push([
                    'id' => 'jadwal_' . $jadwal->id . '_' . $current->format('Y-m-d'),
                    'title' => $jadwal->matkul->mata_kuliah ?? 'Class Schedule',
                    'description' => 'Recurring class schedule',
                    'start' => $current->format('Y-m-d') . ' ' . $jadwal->jam_mulai,
                    'end' => $current->format('Y-m-d') . ' ' . $jadwal->jam_selesai,
                    'backgroundColor' => '#3B82F6', // blue for jadwal
                    'borderColor' => '#3B82F6',
                    'type' => 'jadwal',
                    'hari' => $jadwal->hari,
                    'ruangan' => $jadwal->ruangan->nama_ruangan ?? 'Unknown Room',
                    'matkul' => $jadwal->matkul->mata_kuliah ?? 'Unknown Subject',
                    'semester' => $jadwal->matkul->semester ?? '',
                    'is_recurring' => true,
                ]);
            }
            $current->addDay();
        }
        
        return $events;
    }

    /**
     * Quick approval/rejection of peminjaman from dashboard
     */
    public function quickApproval(Request $request, $id)
    {
        $request->validate([
            'status_persetujuan' => 'required|in:disetujui,ditolak',
        ]);

        $peminjam = Peminjaman::findOrFail($id);
        
        // Check for conflicts if approving
        if ($request->status_persetujuan === 'disetujui') {
            $conflictChecker = new \App\Services\RoomConflictService();
            $conflicts = $conflictChecker->checkConflicts(
                $peminjam->id_ruang,
                $peminjam->tanggal_pinjam,
                $peminjam->waktu_mulai,
                $peminjam->waktu_selesai,
                $peminjam->id
            );
            
            if (!empty($conflicts)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konflik jadwal terdeteksi! Tidak dapat menyetujui peminjaman.'
                ], 400);
            }
        }

        $peminjam->status_persetujuan = $request->status_persetujuan;
        $peminjam->save();

        $message = $request->status_persetujuan === 'disetujui' 
            ? 'Peminjaman berhasil disetujui!' 
            : 'Peminjaman berhasil ditolak!';

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $request->status_persetujuan
        ]);
    }
}
