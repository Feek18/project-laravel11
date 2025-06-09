<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

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
            ];
            });

        return view('components.admin.dashboard', compact('peminjamans'));
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
}
