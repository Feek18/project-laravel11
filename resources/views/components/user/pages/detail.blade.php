<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KelasReady | Website</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins">
    {{-- navbar --}}
    @include('layouts.header')

    {{-- detail ruangan --}}
    <section>
        <div class="container px-4 sm:px-6 lg:px-20 mt-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2">
                <h1 class="text-2xl md:text-4xl font-semibold">Detail Ruangan</h1>
                <p class="text-sm md:text-md text-gray-600">Prodi D4 Teknologi Rekayasa Perangkat Lunak</p>
            </div>

            <div class="mt-10">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-1">
                        <div
                            class="rounded-xl overflow-hidden shadow-lg transition-transform duration-300 hover:scale-105">
                            <img class="w-full h-[300px] md:h-[420px] object-cover object-center"
                                src="{{ $ruangan->gambar }}" alt="Room Image">
                        </div>
                        <p class="text-sm text-gray-500 mt-4">{{ $ruangan->lokasi }}</p>
                        <h2 class="text-2xl lg:text-4xl font-semibold mt-1">{{ $ruangan->nama_ruangan }}</h2>
                    </div>

                    <div class="w-full lg:w-[400px] bg-white shadow-md rounded-2xl p-6">
                        <h3 class="text-xl font-semibold">Form Peminjaman Ruangan</h3>
                        <p class="text-sm text-gray-600 mb-4">Silakan isi form peminjaman ruangan di bawah ini</p>
                        
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

                        <form id="room-booking-form" action="{{ route('pemesanan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_ruang" value="{{ $ruangan->id_ruang }}">
                            {{-- <div class="mb-4">
                                <label for="nama_pengguna" class="block text-sm font-medium text-gray-700">Nama
                                    Pengguna</label>
                                <input type="text" id="nama_pengguna" name="nama_pengguna"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan nama anda" required />
                            </div> --}}

                            <div class="mb-4">
                                <label for="nama_ruangan" class="block text-sm font-medium text-gray-700">Nama
                                    Ruangan</label>
                                <input type="text" id="nama_ruangan" name="nama_ruangan"
                                    value="{{ $ruangan->nama_ruangan }}" readonly
                                    class="mt-1 block w-full bg-gray-100 rounded-lg border border-gray-300 p-2.5" />
                            </div>
                            {{-- <pre>{{ dd($ruangan->getAttributes()) }}</pre> --}}

                            <div class="mb-4">
                                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal
                                    Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                    required />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu
                                        Mulai</label>
                                    <input type="time" name="waktu_mulai" id="waktu_mulai"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                        required />
                                </div>

                                <div class="mb-4">
                                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu
                                        Selesai</label>
                                    <input type="time" name="waktu_selesai" id="waktu_selesai"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                        required />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan
                                    Pinjam</label>
                                <textarea id="keperluan" name="keperluan" rows="4"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500
                                    placeholder="Masukkan
                                    keperluan anda"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition duration-300">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.toast')

    <!-- QR Code Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <div class="text-center">
                    <h3 class="text-lg font-semibold mb-4">QR Code Peminjaman Instant</h3>
                    <div id="qrCodeContainer" class="mb-4"></div>
                    <p class="text-sm text-gray-600 mb-4">Scan QR code ini untuk konfirmasi peminjaman ruangan</p>
                    <button onclick="closeQRModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateInstantQR() {
            const keperluan = document.getElementById('instant_keperluan').value;
            if (!keperluan.trim()) {
                alert('Mohon isi keperluan terlebih dahulu');
                return;
            }

            // Show loading
            const modal = document.getElementById('qrModal');
            const container = document.getElementById('qrCodeContainer');
            container.innerHTML = '<div class="text-center">Generating QR Code...</div>';
            modal.classList.remove('hidden');

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
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    container.innerHTML = `
                        <img src="${data.qr_code_url}" alt="QR Code" class="mx-auto mb-2" style="max-width: 200px;">
                        <p class="text-xs text-gray-500">Token: ${data.token}</p>
                        ${data.has_pending_conflict ? `
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-yellow-800">Peringatan Konflik</span>
                                </div>
                                <p class="text-xs text-yellow-700 mb-2">${data.conflict_warning}</p>
                                ${data.conflicting_bookings && data.conflicting_bookings.length > 0 ? `
                                    <div class="text-xs text-yellow-700">
                                        <p class="font-medium mb-1">Konflik dengan peminjaman:</p>
                                        <ul class="list-disc list-inside space-y-1">
                                            ${data.conflicting_bookings.map(booking => `
                                                <li>${booking.pengguna} (${booking.waktu_mulai} - ${booking.waktu_selesai}) - ${booking.status}</li>
                                            `).join('')}
                                        </ul>
                                    </div>
                                ` : ''}
                            </div>
                        ` : ''}
                    `;
                } else {
                    let errorContent = `<div class="text-red-500">${data.message || 'Error generating QR code'}</div>`;
                    
                    // Show conflict details for blocked QR generation
                    if (data.has_approved_conflict && data.conflicting_bookings) {
                        errorContent += `
                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-red-800">Ruangan Tidak Tersedia</span>
                                </div>
                                <div class="text-xs text-red-700">
                                    <p class="font-medium mb-1">Konflik dengan peminjaman yang sudah disetujui:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        ${data.conflicting_bookings.map(booking => `
                                            <li>${booking.pengguna} (${booking.waktu_mulai} - ${booking.waktu_selesai}) - ${booking.status}</li>
                                        `).join('')}
                                    </ul>
                                </div>
                            </div>
                        `;
                    }
                    
                    container.innerHTML = errorContent;
                    
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-red-500">Error: ' + error.message + '</div>';
            });
        }

        function closeQRModal() {
            document.getElementById('qrModal').classList.add('hidden');
            document.getElementById('instant_keperluan').value = '';
        }
    </script>

    <!-- Real-time Room Availability Checker -->
    <script src="{{ asset('js/room-availability-checker.js') }}"></script>

</body>

</html>
