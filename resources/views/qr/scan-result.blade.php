<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Scan QR Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Detail Peminjaman</h1>
                <p class="text-gray-600">Informasi peminjaman ruangan</p>
            </div>            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="font-medium text-gray-800 mb-3">Informasi Peminjaman:</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Ruangan:</span>
                        <span class="font-medium">{{ $peminjaman->ruangan->nama_ruangan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Lokasi:</span>
                        <span class="font-medium">{{ $peminjaman->ruangan->lokasi }}</span>
                    </div>                    <div class="flex justify-between">
                        <span>Peminjam:</span>
                        <span class="font-medium">{{ $peminjaman->pengguna?->nama ?? $peminjaman->user?->email ?? 'Unknown User' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Waktu:</span>
                        <span class="font-medium">{{ $peminjaman->waktu_mulai }} - {{ $peminjaman->waktu_selesai }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Keperluan:</span>
                        <span class="font-medium">{{ $peminjaman->keperluan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status:</span>
                        <span class="font-medium 
                            @if($peminjaman->status_persetujuan == 'pending') text-yellow-600
                            @elseif($peminjaman->status_persetujuan == 'disetujui') text-green-600
                            @else text-red-600
                            @endif">
                            {{ ucfirst($peminjaman->status_persetujuan) }}
                        </span>
                    </div>
                </div>

                @php
                    // Check for current conflicts
                    $currentConflicts = \App\Models\Peminjaman::getConflictingBookings(
                        $peminjaman->id_ruang,
                        $peminjaman->tanggal_pinjam,
                        $peminjaman->waktu_mulai,
                        $peminjaman->waktu_selesai,
                        $peminjaman->id
                    );
                    $hasApprovedConflicts = $currentConflicts->where('status_persetujuan', 'disetujui')->count() > 0;
                    $hasPendingConflicts = $currentConflicts->where('status_persetujuan', 'pending')->count() > 0;
                @endphp

                @if($currentConflicts->count() > 0)
                    <div class="mt-4 p-3 rounded-lg border 
                        @if($hasApprovedConflicts) bg-red-50 border-red-200 @else bg-yellow-50 border-yellow-200 @endif">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 mr-2 @if($hasApprovedConflicts) text-red-600 @else text-yellow-600 @endif" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium @if($hasApprovedConflicts) text-red-800 @else text-yellow-800 @endif">
                                @if($hasApprovedConflicts) Konflik Terdeteksi! @else Peringatan Konflik @endif
                            </span>
                        </div>
                        <div class="text-xs @if($hasApprovedConflicts) text-red-700 @else text-yellow-700 @endif">
                            <p class="font-medium mb-1">Konflik dengan peminjaman lain:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($currentConflicts as $conflict)
                                    <li>{{ $conflict->pengguna->nama ?? 'Unknown' }} ({{ $conflict->waktu_mulai }} - {{ $conflict->waktu_selesai }}) - 
                                        <span class="font-medium">{{ ucfirst($conflict->status_persetujuan) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            @if($hasApprovedConflicts)
                                <p class="mt-2 font-medium text-red-800">⚠️ Peminjaman ini tidak dapat disetujui karena bertabrakan dengan peminjaman yang sudah dikonfirmasi.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>            @if($peminjaman->status_persetujuan == 'pending')
                @php
                    // Check if approval should be blocked due to conflicts
                    $canApprove = !$hasApprovedConflicts;
                @endphp
                
                <div class="space-y-3">
                    <button 
                        onclick="approveQR('{{ $peminjaman->qr_token }}')"
                        @if(!$canApprove) disabled @endif
                        class="w-full py-2 px-4 rounded-lg transition
                            @if($canApprove) 
                                bg-green-600 text-white hover:bg-green-700
                            @else 
                                bg-gray-300 text-gray-500 cursor-not-allowed
                            @endif"
                    >
                        @if($canApprove) 
                            Setujui Peminjaman 
                        @else 
                            Tidak Dapat Disetujui (Ada Konflik)
                        @endif
                    </button>
                    <button 
                        onclick="rejectQR('{{ $peminjaman->qr_token }}')"
                        class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition"
                    >
                        Tolak Peminjaman
                    </button>
                </div>
            @elseif($peminjaman->status_persetujuan == 'disetujui')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    Peminjaman telah disetujui dan ruangan dapat digunakan
                </div>
            @else
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    Peminjaman telah ditolak
                </div>
            @endif

            <div class="mt-4 text-center">
                <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">Kembali ke Beranda</a>
            </div>

            <div class="mt-6 text-xs text-gray-500 text-center">
                <p>ID: {{ $peminjaman->qr_token }}</p>
                <p>Dibuat: {{ $peminjaman->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <script>
        function approveQR(token) {
            if (confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')) {
                fetch(`/qr/approve/${token}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan dalam memproses permintaan');
                });
            }
        }

        function rejectQR(token) {
            if (confirm('Apakah Anda yakin ingin menolak peminjaman ini?')) {
                // Similar implementation for rejection
                alert('Fitur penolakan akan segera tersedia');
            }
        }
    </script>
</body>
</html>
