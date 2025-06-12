<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwals';
    protected $fillable = [
        'id_ruang',
        'id_matkul',
        'tanggal',
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
        $query = self::where('id_ruang', $id_ruang)
            ->where('tanggal', $tanggal)
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
     * Check for conflicts with room borrowing (peminjaman)
     * 
     * @param int $id_ruang
     * @param string $tanggal
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @return bool
     */
    public static function hasPeminjamanConflict($id_ruang, $tanggal, $jam_mulai, $jam_selesai)
    {
        return Peminjaman::hasTimeConflict($id_ruang, $tanggal, $jam_mulai, $jam_selesai, null, ['pending', 'disetujui']);
    }

    /**
     * Check for any conflicts (both jadwal and peminjaman)
     * 
     * @param int $id_ruang
     * @param string $tanggal
     * @param string $jam_mulai
     * @param string $jam_selesai
     * @param int|null $exclude_id
     * @return array
     */
    public static function checkAllConflicts($id_ruang, $tanggal, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $conflicts = [];

        // Check jadwal conflicts
        if (self::hasJadwalConflict($id_ruang, $tanggal, $jam_mulai, $jam_selesai, $exclude_id)) {
            $conflicts['jadwal'] = self::getConflictingJadwal($id_ruang, $tanggal, $jam_mulai, $jam_selesai, $exclude_id);
        }

        // Check peminjaman conflicts
        if (self::hasPeminjamanConflict($id_ruang, $tanggal, $jam_mulai, $jam_selesai)) {
            $conflicts['peminjaman'] = Peminjaman::getConflictingBookings($id_ruang, $tanggal, $jam_mulai, $jam_selesai);
        }

        return $conflicts;
    }

    /**
     * Get conflicting jadwal for a specific room, date and time
     */
    public static function getConflictingJadwal($id_ruang, $tanggal, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $query = self::with(['ruangan', 'matkul'])
            ->where('id_ruang', $id_ruang)
            ->where('tanggal', $tanggal)
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
     * Get all schedules for a specific room and date
     */
    public static function getRoomSchedule($id_ruang, $tanggal)
    {
        return self::with(['matkul'])
            ->where('id_ruang', $id_ruang)
            ->where('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get();
    }
}
