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
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-6">
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
            </div>

            @if($peminjaman->status_persetujuan == 'pending')
                <div class="space-y-3">
                    <button 
                        onclick="approveQR('{{ $peminjaman->qr_token }}')"
                        class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition"
                    >
                        Setujui Peminjaman
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
