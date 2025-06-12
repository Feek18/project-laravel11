<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam Ruangan - {{ $ruangan->nama_ruangan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pinjam Ruangan</h1>
                <h2 class="text-xl text-blue-600">{{ $ruangan->nama_ruangan }}</h2>
                <p class="text-gray-600">{{ $ruangan->lokasi }}</p>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(!Auth::check())
                <div class="text-center">
                    <p class="text-gray-600 mb-4">Silakan login terlebih dahulu untuk meminjam ruangan</p>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Login
                    </a>
                </div>
            @else
                <form action="{{ route('qr.room.process', $ruangan->id_ruang) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                        <textarea 
                            name="keperluan" 
                            id="keperluan" 
                            rows="3" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Jelaskan keperluan penggunaan ruangan..."
                            required
                        >{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durasi (Jam)</label>
                        <select 
                            name="duration" 
                            id="duration" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="">Pilih durasi</option>
                            <option value="1" {{ old('duration') == '1' ? 'selected' : '' }}>1 Jam</option>
                            <option value="2" {{ old('duration') == '2' ? 'selected' : '' }}>2 Jam</option>
                            <option value="3" {{ old('duration') == '3' ? 'selected' : '' }}>3 Jam</option>
                            <option value="4" {{ old('duration') == '4' ? 'selected' : '' }}>4 Jam</option>
                            <option value="6" {{ old('duration') == '6' ? 'selected' : '' }}>6 Jam</option>
                            <option value="8" {{ old('duration') == '8' ? 'selected' : '' }}>8 Jam</option>
                        </select>
                        @error('duration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-800 mb-2">Detail Peminjaman:</h3>                        <div class="text-sm text-gray-600">
                            <p><strong>Peminjam:</strong> {{ Auth::user()->pengguna?->nama ?? Auth::user()->email ?? 'User' }}</p>
                            <p><strong>Tanggal:</strong> {{ date('d/m/Y') }}</p>
                            <p><strong>Waktu Mulai:</strong> {{ date('H:i') }}</p>
                            <p><strong>Status:</strong> <span class="text-green-600">Instant (Langsung Disetujui)</span></p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                        Pinjam Ruangan Sekarang
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
