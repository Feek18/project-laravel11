<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ruangan->nama_ruangan }} - KelasReady</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-poppins bg-gray-50">
    {{-- navbar --}}
    @include('layouts.header')

    {{-- Room Detail Section --}}
    <section class="py-8 mt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Detail Ruangan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Room Info and Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Room Image and Basic Info -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="relative">
                            <img class="w-full h-64 md:h-80 object-cover" 
                                 src="{{ $ruangan->gambar }}" 
                                 alt="{{ $ruangan->nama_ruangan }}">
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                @if($ruangan->is_currently_used)
                                    <span class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-full shadow-lg">
                                        Sedang Digunakan
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-full shadow-lg">
                                        Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $ruangan->nama_ruangan }}</h1>
                                    <p class="text-gray-600 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $ruangan->lokasi }}
                                    </p>
                                </div>
                            </div>

                            <!-- Current Usage Info -->
                            @if($ruangan->is_currently_used && $ruangan->current_booking)
                                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <h3 class="text-sm font-semibold text-red-800 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Sedang Digunakan Saat Ini
                                    </h3>
                                    <div class="text-sm text-red-700 space-y-1">
                                        <p><strong>Peminjam:</strong> {{ $ruangan->current_booking->pengguna->nama ?? 'N/A' }}</p>
                                        <p><strong>Keperluan:</strong> {{ $ruangan->current_booking->keperluan }}</p>
                                        <p><strong>Waktu:</strong> {{ $ruangan->current_booking->waktu_mulai }} - {{ $ruangan->current_booking->waktu_selesai }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                                {{ ucfirst($ruangan->current_booking->status_persetujuan) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Borrowed Data / Schedule Section -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Jadwal Peminjaman
                        </h2>

                        @if($ruangan->peminjaman->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($ruangan->peminjaman as $booking)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($booking->tanggal_pinjam)->format('d M Y') }}
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        {{ $booking->waktu_mulai }} - {{ $booking->waktu_selesai }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs rounded-full
                                                        {{ $booking->status_persetujuan === 'disetujui' ? 'bg-green-100 text-green-800' : 
                                                           ($booking->status_persetujuan === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ ucfirst($booking->status_persetujuan) }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-1">
                                                    <strong>Peminjam:</strong> {{ $booking->pengguna->nama ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <strong>Keperluan:</strong> {{ $booking->keperluan }}
                                                </p>
                                            </div>
                                            
                                            @if(\Carbon\Carbon::parse($booking->tanggal_pinjam . ' ' . $booking->waktu_mulai)->isFuture())
                                                <div class="mt-2 sm:mt-0 sm:ml-4">
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Akan Datang
                                                    </span>
                                                </div>
                                            @elseif(\Carbon\Carbon::parse($booking->tanggal_pinjam . ' ' . $booking->waktu_mulai)->isToday())
                                                <div class="mt-2 sm:mt-0 sm:ml-4">
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-orange-100 text-orange-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Hari Ini
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Jadwal</h3>
                                <p class="text-gray-500">Ruangan ini belum memiliki jadwal peminjaman.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Booking Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Form Peminjaman Ruangan</h3>
                        <p class="text-sm text-gray-600 mb-6">Silakan isi form peminjaman ruangan di bawah ini</p>
                        
                        <!-- QR Code Instant Borrowing Section -->
                        {{-- <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h4 class="font-medium text-green-800 mb-2">Peminjaman Instant dengan QR Code</h4>
                            <p class="text-sm text-green-600 mb-3">Pinjam ruangan langsung dengan QR Code (Auto approve untuk 2 jam)</p>
                            <button type="button" onclick="generateInstantQR()" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg transition duration-300 mb-2">
                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V4zm2 2V5h1v1h-1z" clip-rule="evenodd"></path>
                                </svg>
                                Generate QR Code Instant
                            </button>
                            <input type="text" id="instant_keperluan" placeholder="Keperluan singkat..." class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
                        </div> --}}

                        <div class="border-t pt-4">
                            <h4 class="font-medium text-gray-800 mb-3">Atau Peminjaman Terjadwal</h4>
                        </div>

                        <form id="room-booking-form" action="{{ route('pemesanan.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="id_ruang" value="{{ $ruangan->id_ruang }}">
                            <input type="hidden" name="booking_type" value="peminjaman">

                            <div>
                                <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-2">Nama Ruangan</label>
                                <input type="text" id="nama_ruangan" name="nama_ruangan"
                                    value="{{ $ruangan->nama_ruangan }}" readonly
                                    class="w-full bg-gray-50 rounded-lg border border-gray-300 p-3 text-gray-600" />
                            </div>

                            <div>
                                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                    class="w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    min="{{ date('Y-m-d') }}" required />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                                    <input type="time" name="waktu_mulai" id="waktu_mulai"
                                        class="w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required />
                                </div>

                                <div>
                                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Selesai</label>
                                    <input type="time" name="waktu_selesai" id="waktu_selesai"
                                        class="w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required />
                                </div>
                            </div>

                            <div>
                                <label for="keperluan" class="block text-sm font-medium text-gray-700 mb-2">Keperluan</label>
                                <textarea name="keperluan" id="keperluan" rows="3"
                                    class="w-full rounded-lg border border-gray-300 p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Jelaskan keperluan penggunaan ruangan..." required></textarea>
                            </div>

                            <!-- Availability Check Result -->
                            <div id="availability-result" class="hidden"></div>

                            <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Ajukan Peminjaman
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.toast')

    <script>
        // QR Code Generation Functions
        function generateInstantQR() {
            document.getElementById('qrModal').classList.remove('hidden');
        }

        function closeQRModal() {
            document.getElementById('qrModal').classList.add('hidden');
            document.getElementById('instant_keperluan').value = '';
            document.getElementById('qr-result').innerHTML = '';
        }

        function processInstantQR() {
            const keperluan = document.getElementById('instant_keperluan').value;
            if (!keperluan.trim()) {
                alert('Silakan isi keperluan terlebih dahulu');
                return;
            }

            // Show loading
            const container = document.getElementById('qr-result');
            container.innerHTML = '<div class="text-center text-gray-600">Generating QR Code...</div>';

            // Send request to generate QR
            fetch('{{ route("qr.generate.instant") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_ruang: {{ $ruangan->id_ruang }},
                    keperluan: keperluan
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    container.innerHTML = `
                        <div class="text-center">
                            <img src="${data.qr_code_url}" alt="QR Code" class="mx-auto mb-2" style="max-width: 200px;">
                            <p class="text-xs text-gray-500">Token: ${data.token}</p>
                            <p class="text-sm text-green-600 mt-2">QR Code berhasil dibuat!</p>
                        </div>
                    `;
                } else {
                    container.innerHTML = `<div class="text-red-500 text-center">${data.message || 'Error generating QR code'}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-red-500 text-center">Error: ' + error.message + '</div>';
            });
        }

        // Form validation and availability check
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('room-booking-form');
            const tanggalInput = document.getElementById('tanggal_pinjam');
            const waktuMulaiInput = document.getElementById('waktu_mulai');
            const waktuSelesaiInput = document.getElementById('waktu_selesai');

            // Check availability when time inputs change
            function checkAvailability() {
                const tanggal = tanggalInput.value;
                const waktuMulai = waktuMulaiInput.value;
                const waktuSelesai = waktuSelesaiInput.value;

                if (tanggal && waktuMulai && waktuSelesai) {
                    // Add availability check logic here if needed
                }
            }

            tanggalInput.addEventListener('change', checkAvailability);
            waktuMulaiInput.addEventListener('change', checkAvailability);
            waktuSelesaiInput.addEventListener('change', checkAvailability);

            // Form submission handling
            form.addEventListener('submit', function(e) {
                // Add any additional validation here
            });
        });
    </script>
</body>
</html>
