<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::all(); // Show all rooms instead of just 4
        
        // Get peminjaman data
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
                'type' => 'peminjaman'
            ];
            });

        // Get jadwal data and generate recurring events for the current month view
        $jadwalEvents = $this->generateJadwalEvents();
        
        // Combine both peminjaman and jadwal events
        $allEvents = $peminjamans->merge($jadwalEvents);
        
        return view('index', compact('ruangans', 'allEvents'));
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
        $ruangan = Ruangan::find($id);
        return view('components.user.pages.detail', compact('ruangan'));
    }
}
