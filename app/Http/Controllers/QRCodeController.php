<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QRCodeController extends Controller
{
    /**
     * Get or create pengguna ID for the authenticated user
     */
    private function getOrCreatePenggunaId()
    {
        if (Auth::user()->pengguna) {
            return Auth::user()->pengguna->id;
        }
        
        // Create a basic pengguna record if it doesn't exist
        $pengguna = \App\Models\Pengguna::create([
            'nama' => Auth::user()->name ?? Auth::user()->email,
            'alamat' => 'Address not provided',
            'gender' => 'Tidak diketahui',
            'no_telp' => null,
            'user_id' => Auth::user()->id,
        ]);
        
        return $pengguna->id;
    }

    /**
     * Generate QR code for instant room borrowing
     */
    public function generateInstantQR(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $request->validate([
                'id_ruang' => 'required|exists:ruangan_kelas,id_ruang',
                'keperluan' => 'required|string|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Generate unique token
            $token = Str::random(32);
            
            // Get or create pengguna ID
            $penggunaId = $this->getOrCreatePenggunaId();
            
            // Create instant borrowing record
            $peminjaman = Peminjaman::create([
                'id_pengguna' => $penggunaId,
                'id_ruang' => $request->id_ruang,
                'status_peminjaman' => 'insidental',
                'keperluan' => $request->keperluan,
                'status_persetujuan' => 'pending',
                'tanggal_pinjam' => now()->format('Y-m-d'),
                'waktu_mulai' => now()->format('H:i'),
                'waktu_selesai' => now()->addHours(2)->format('H:i'), // Default 2 hours
                'qr_token' => $token,
            ]);

            // Generate QR code
            $qrCodeContent = route('qr.scan', ['token' => $token]);
            
            // Generate QR code image
            $qrCode = QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->generate($qrCodeContent);

            // Save QR code image
            $fileName = 'qrcodes/peminjaman_' . $peminjaman->id . '.png';
            Storage::disk('public')->put($fileName, $qrCode);
            
            // Update peminjaman record with QR code path
            $peminjaman->update(['qr_code' => $fileName]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil digenerate',
                'qr_code_url' => Storage::url($fileName),
                'peminjaman_id' => $peminjaman->id,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan QR code and process room borrowing
     */
    public function scanQR($token)
    {
        $peminjaman = Peminjaman::where('qr_token', $token)
            ->with(['ruangan', 'pengguna'])
            ->first();

        if (!$peminjaman) {
            return response()->view('qr.invalid', [], 404);
        }

        // Check if QR code is still valid (within 24 hours)
        if ($peminjaman->created_at->diffInHours(now()) > 24) {
            return response()->view('qr.expired');
        }

        return view('qr.scan-result', compact('peminjaman'));
    }

    /**
     * Approve room borrowing via QR scan
     */
    public function approveQR(Request $request, $token)
    {
        $peminjaman = Peminjaman::where('qr_token', $token)->first();

        if (!$peminjaman) {
            return response()->json(['error' => 'Invalid QR token'], 404);
        }

        $peminjaman->update([
            'status_persetujuan' => 'disetujui',
            'waktu_mulai' => now()->format('H:i'),
            'waktu_selesai' => now()->addHours(2)->format('H:i')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman ruangan berhasil disetujui'
        ]);
    }

    /**
     * Generate QR for specific room
     */
    public function generateRoomQR(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan_kelas,id_ruang'
        ]);

        $ruangan = Ruangan::findOrFail($request->ruangan_id);
        
        // Generate room-specific QR code URL
        $qrContent = route('qr.room.borrow', ['room_id' => $ruangan->id_ruang]);
        
        // Generate QR code
        $qrCode = QrCode::format('svg')
            ->size(400)
            ->margin(2)
            ->generate($qrContent);

        return view('qr.room-qr', compact('ruangan', 'qrCode'));
    }

    /**
     * Show room borrowing form via QR scan
     */
    public function showRoomBorrowForm($room_id)
    {
        $ruangan = Ruangan::findOrFail($room_id);
        
        return view('qr.room-borrow-form', compact('ruangan'));
    }

    /**
     * Process room borrowing from QR scan
     */
    public function processRoomBorrow(Request $request, $room_id)
    {
        $request->validate([
            'keperluan' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:8' // hours
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $ruangan = Ruangan::findOrFail($room_id);
        
        // Check if room is available
        $currentTime = now();
        $endTime = $currentTime->copy()->addHours((int) $request->duration);
        
        $conflict = Peminjaman::where('id_ruang', $room_id)
            ->where('tanggal_pinjam', $currentTime->format('Y-m-d'))
            ->where('status_persetujuan', ['pending', 'disetujui'])
            ->where(function($query) use ($currentTime, $endTime) {
                $query->whereBetween('waktu_mulai', [$currentTime->format('H:i'), $endTime->format('H:i')])
                    ->orWhereBetween('waktu_selesai', [$currentTime->format('H:i'), $endTime->format('H:i')])
                    ->orWhere(function($query) use ($currentTime, $endTime) {
                        $query->where('waktu_mulai', '<=', $currentTime->format('H:i'))
                            ->where('waktu_selesai', '>=', $endTime->format('H:i'));
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Ruangan sedang digunakan pada waktu tersebut');
        }

        // Generate unique token
        $token = Str::random(32);
        
        // Get or create pengguna ID
        $penggunaId = $this->getOrCreatePenggunaId();
        
        // Create borrowing record
        $peminjaman = Peminjaman::create([
            'id_pengguna' => $penggunaId,
            'id_ruang' => $room_id,
            'status_peminjaman' => 'insidental',
            'keperluan' => $request->keperluan,
            'status_persetujuan' => 'disetujui', // Auto approve for QR-based borrowing
            'tanggal_pinjam' => $currentTime->format('Y-m-d'),
            'waktu_mulai' => $currentTime->format('H:i'),
            'waktu_selesai' => $endTime->format('H:i'),
            'qr_token' => $token,
        ]);

        return redirect()->route('qr.success', ['id' => $peminjaman->id])
            ->with('success', 'Peminjaman ruangan berhasil! Anda dapat menggunakan ruangan sekarang.');
    }

    /**
     * Show success page after QR borrowing
     */
    public function showSuccess($id)
    {
        $peminjaman = Peminjaman::with(['ruangan', 'pengguna'])->findOrFail($id);
        
        return view('qr.success', compact('peminjaman'));
    }
}
