<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';
    protected $fillable = [
        'id_pengguna',
        'id_ruang',
        'status_peminjaman',
        'keperluan',
        'status_persetujuan',
        'tanggal_pinjam',
        'waktu_mulai',
        'waktu_selesai',
        'qr_code',
        'qr_token',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruang', 'id_ruang');
    }

    /**
     * Check for time conflicts with existing bookings
     * 
     * @param int $id_ruang
     * @param string $tanggal_pinjam
     * @param string $waktu_mulai
     * @param string $waktu_selesai
     * @param int|null $exclude_id - ID to exclude from conflict check (for updates)
     * @param array $statuses - Status to check for conflicts (default: ['pending', 'disetujui'])
     * @return bool
     */
    public static function hasTimeConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id = null, $statuses = ['pending', 'disetujui'])
    {
        $query = self::where('id_ruang', $id_ruang)
            ->where('tanggal_pinjam', $tanggal_pinjam)
            ->whereIn('status_persetujuan', $statuses)
            ->where(function ($query) use ($waktu_mulai, $waktu_selesai) {
                $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                    ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai])
                    ->orWhere(function ($query) use ($waktu_mulai, $waktu_selesai) {
                        $query->where('waktu_mulai', '<=', $waktu_mulai)
                            ->where('waktu_selesai', '>=', $waktu_selesai);
                    });
            });

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        return $query->exists();
    }

    /**
     * Check for conflicts with pending bookings only
     */
    public static function hasPendingConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id = null)
    {
        return self::hasTimeConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id, ['pending']);
    }

    /**
     * Check for conflicts with approved bookings only
     */
    public static function hasApprovedConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id = null)
    {
        return self::hasTimeConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id, ['disetujui']);
    }

    /**
     * Get conflicting bookings for a specific room, date and time
     */
    public static function getConflictingBookings($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id = null)
    {
        $query = self::with(['pengguna', 'ruangan'])
            ->where('id_ruang', $id_ruang)
            ->where('tanggal_pinjam', $tanggal_pinjam)
            ->whereIn('status_persetujuan', ['pending', 'disetujui'])
            ->where(function ($query) use ($waktu_mulai, $waktu_selesai) {
                $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                    ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai])
                    ->orWhere(function ($query) use ($waktu_mulai, $waktu_selesai) {
                        $query->where('waktu_mulai', '<=', $waktu_mulai)
                            ->where('waktu_selesai', '>=', $waktu_selesai);
                    });
            });

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        return $query->get();
    }

    /**
     * Check for conflicts with jadwal schedules
     * 
     * @param int $id_ruang
     * @param string $tanggal_pinjam
     * @param string $waktu_mulai
     * @param string $waktu_selesai
     * @return bool
     */
    public static function hasJadwalConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai)
    {
        return Jadwal::hasJadwalConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai);
    }

    /**
     * Check for any conflicts (both peminjaman and jadwal)
     * 
     * @param int $id_ruang
     * @param string $tanggal_pinjam
     * @param string $waktu_mulai
     * @param string $waktu_selesai
     * @param int|null $exclude_id
     * @return array
     */
    public static function checkAllConflicts($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id = null)
    {
        $conflicts = [];

        // Check peminjaman conflicts
        if (self::hasTimeConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id)) {
            $conflicts['peminjaman'] = self::getConflictingBookings($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai, $exclude_id);
        }

        // Check jadwal conflicts
        if (self::hasJadwalConflict($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai)) {
            $conflicts['jadwal'] = Jadwal::getConflictingJadwal($id_ruang, $tanggal_pinjam, $waktu_mulai, $waktu_selesai);
        }

        return $conflicts;
    }

    /**
     * Get all bookings for a specific room and date
     */
    public static function getRoomSchedule($id_ruang, $tanggal_pinjam)
    {
        return self::with(['pengguna'])
            ->where('id_ruang', $id_ruang)
            ->where('tanggal_pinjam', $tanggal_pinjam)
            ->whereIn('status_persetujuan', ['pending', 'disetujui'])
            ->orderBy('waktu_mulai')
            ->get();
    }
}
