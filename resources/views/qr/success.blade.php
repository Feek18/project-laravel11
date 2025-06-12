<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-green-600 mb-2">Peminjaman Berhasil!</h1>
                <p class="text-gray-600">Ruangan berhasil dipinjam dan siap digunakan</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-6 text-left">
                <h3 class="font-medium text-gray-800 mb-3">Detail Peminjaman:</h3>
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
                        <span class="font-medium text-green-600">{{ ucfirst($peminjaman->status_persetujuan) }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('pesanRuangan.index') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                    Lihat Pesanan Saya
                </a>
                <a href="{{ route('profile.edit') }}" class="block w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="mt-6 text-xs text-gray-500">
                <p>ID Peminjaman: #{{ $peminjaman->id }}</p>
                <p>{{ $peminjaman->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
