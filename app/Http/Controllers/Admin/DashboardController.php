<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        return view('components.admin.dashboard', compact('allEvents'));
    }    private function getStatusColor($status)
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
}
