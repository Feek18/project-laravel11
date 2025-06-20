<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwals';
    protected $fillable = [
        'id_ruang',
        'id_matkul',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruang', 'id_ruang');
    }

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id');
    }

    /**
     * Check for time conflicts with existing jadwal schedules based on day of week
     * 
     * @param int $id_ruang
     * @param string $hari - day of the week (minggu, senin, etc.)
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @param int|null $exclude_id - ID to exclude from conflict check (for updates)
     * @return bool
     */
    public static function hasJadwalConflictByDay($id_ruang, $hari, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $query = self::where('id_ruang', $id_ruang)
            ->where('hari', $hari)
            ->where(function ($query) use ($jam_mulai, $jam_selesai) {
                $query->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                    ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
                    ->orWhere(function ($query) use ($jam_mulai, $jam_selesai) {
                        $query->where('jam_mulai', '<=', $jam_mulai)
                            ->where('jam_selesai', '>=', $jam_selesai);
                    });
            });

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        return $query->exists();
    }

    /**
     * Check for time conflicts with existing jadwal schedules
     * 
     * @param int $id_ruang
     * @param string $tanggal
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @param int|null $exclude_id - ID to exclude from conflict check (for updates)
     * @return bool
     */
    public static function hasJadwalConflict($id_ruang, $tanggal, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        // For jadwal, we check by day of the week instead of specific date
        $dayOfWeek = self::getDayOfWeekFromDate($tanggal);

        return self::hasJadwalConflictByDay($id_ruang, $dayOfWeek, $jam_mulai, $jam_selesai, $exclude_id);
    }

    /**
     * Convert date to Indonesian day name (for jadwal conflicts by day)
     * 
     * @param string $tanggal
     * @return string
     */
    public static function getDayOfWeekFromDate($tanggal)
    {
        $dayMap = [
            'Sunday' => 'minggu',
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu'
        ];

        $englishDay = date('l', strtotime($tanggal));
        return $dayMap[$englishDay] ?? 'senin';
    }

    /**
     * Check for any conflicts with peminjaman when creating/updating jadwal
     * Since jadwal is recurring (weekly), check against all future peminjaman on the same day
     * 
     * @param int $id_ruang
     * @param string $hari - day of the week (minggu, senin, etc.)
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @param int|null $exclude_id
     * @return array
     */
    public static function checkPeminjamanConflictsForJadwal($id_ruang, $hari, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        // Get all future peminjaman that fall on the same day of week as this jadwal
        $dayMap = [
            'minggu' => 0,
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6
        ];

        $targetDayNumber = $dayMap[$hari] ?? 1;

        $conflictingPeminjaman = Peminjaman::with(['pengguna', 'ruangan'])
            ->where('id_ruang', $id_ruang)
            ->where('tanggal_pinjam', '>=', now()->toDateString()) // Only future bookings
            ->whereIn('status_persetujuan', ['pending', 'disetujui'])
            ->whereRaw('DAYOFWEEK(tanggal_pinjam) - 1 = ?', [$targetDayNumber]) // Same day of week
            ->where(function ($query) use ($jam_mulai, $jam_selesai) {
                $query->whereBetween('waktu_mulai', [$jam_mulai, $jam_selesai])
                    ->orWhereBetween('waktu_selesai', [$jam_mulai, $jam_selesai])
                    ->orWhere(function ($query) use ($jam_mulai, $jam_selesai) {
                        $query->where('waktu_mulai', '<=', $jam_mulai)
                            ->where('waktu_selesai', '>=', $jam_selesai);
                    });
            })
            ->get();

        return $conflictingPeminjaman;
    }

    /**
     * Check for any conflicts (both jadwal and peminjaman) when creating/updating jadwal
     * 
     * @param int $id_ruang
     * @param string $hari - day of the week (minggu, senin, etc.)
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @param int|null $exclude_id
     * @return array
     */
    public static function checkAllConflictsForJadwal($id_ruang, $hari, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $conflicts = [];

        // Check jadwal conflicts (other recurring schedules on same day)
        if (self::hasJadwalConflictByDay($id_ruang, $hari, $jam_mulai, $jam_selesai, $exclude_id)) {
            $conflicts['jadwal'] = self::getConflictingJadwalByDay($id_ruang, $hari, $jam_mulai, $jam_selesai, $exclude_id);
        }

        // Check peminjaman conflicts (future bookings on same day of week)
        $conflictingPeminjaman = self::checkPeminjamanConflictsForJadwal($id_ruang, $hari, $jam_mulai, $jam_selesai, $exclude_id);
        if ($conflictingPeminjaman->count() > 0) {
            $conflicts['peminjaman'] = $conflictingPeminjaman;
        }

        return $conflicts;
    }

    /**
     * Get conflicting jadwal for a specific room and day of week
     */
    public static function getConflictingJadwalByDay($id_ruang, $hari, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $query = self::with(['ruangan', 'matkul'])
            ->where('id_ruang', $id_ruang)
            ->where('hari', $hari)
            ->where(function ($query) use ($jam_mulai, $jam_selesai) {
                $query->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                    ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
                    ->orWhere(function ($query) use ($jam_mulai, $jam_selesai) {
                        $query->where('jam_mulai', '<=', $jam_mulai)
                            ->where('jam_selesai', '>=', $jam_selesai);
                    });
            });

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        return $query->get();
    }

    /**
     * Get conflicting jadwal for a specific room, date and time
     */
    public static function getConflictingJadwal($id_ruang, $tanggal, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        // For jadwal, we check by day of the week instead of specific date
        $dayOfWeek = self::getDayOfWeekFromDate($tanggal);

        return self::getConflictingJadwalByDay($id_ruang, $dayOfWeek, $jam_mulai, $jam_selesai, $exclude_id);
    }

    /**
     * Get all schedules for a specific room and day of week
     */
    public static function getRoomScheduleByDay($id_ruang, $hari)
    {
        return self::with(['matkul'])
            ->where('id_ruang', $id_ruang)
            ->where('hari', $hari)
            ->orderBy('jam_mulai')
            ->get();
    }

    /**
     * Get all schedules for a specific room and date
     */
    public static function getRoomSchedule($id_ruang, $tanggal)
    {
        // For room schedule display, show jadwal by day of the week
        $dayOfWeek = self::getDayOfWeekFromDate($tanggal);

        return self::getRoomScheduleByDay($id_ruang, $dayOfWeek);
    }
}
