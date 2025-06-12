<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $ruangan->nama_ruangan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $ruangan->nama_ruangan }}</h1>
            <p class="text-gray-600 mb-6">{{ $ruangan->lokasi }}</p>
            
            <div class="mb-6 flex justify-center">
                {!! $qrCode !!}
            </div>
            
            <p class="text-sm text-gray-500 mb-4">
                Scan QR code ini untuk meminjam ruangan secara langsung
            </p>
            
            <div class="text-xs text-gray-400">
                <p>QR Code untuk Peminjaman Instant</p>
                <p>{{ $ruangan->nama_ruangan }}</p>
            </div>
            
            <button onclick="window.print()" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Print QR Code
            </button>
        </div>
    </div>
</body>
</html>
