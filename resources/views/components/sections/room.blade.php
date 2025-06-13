<section id="ruangan" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Ruang Kuliah Yang Tersedia
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Pilih ruangan kuliah sesuai kebutuhan Anda. Lihat status ketersediaan dan detail peminjaman.
            </p>
        </div>

        <!-- Search and Filter Controls -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search Input -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" id="room-search" placeholder="Cari nama ruangan atau lokasi..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-2">
                    <button class="filter-btn active px-4 py-2 rounded-lg font-medium transition-all duration-200"
                        data-filter="all">
                        Semua Ruangan
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg font-medium transition-all duration-200"
                        data-filter="available">
                        Tersedia
                    </button>
                    <button class="filter-btn px-4 py-2 rounded-lg font-medium transition-all duration-200"
                        data-filter="scheduled">
                        Ada Jadwal
                    </button>
                </div>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3" id="rooms-container">
            @foreach ($ruangans as $ruangan)
                <div class="room-card flex flex-col bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-200"
                    data-status="{{ $ruangan->is_currently_used ? ($ruangan->current_booking ? 'currently-used' : 'scheduled') : 'available' }}"
                    data-name="{{ strtolower($ruangan->nama_ruangan) }}"
                    data-location="{{ strtolower($ruangan->lokasi) }}">

                    <!-- Room Image -->
                    <div class="relative aspect-video overflow-hidden">
                        <img class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                            src="{{ $ruangan->gambar }}" alt="{{ $ruangan->nama_ruangan }}">

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if ($ruangan->is_currently_used)
                                @if ($ruangan->current_booking)
                                    <span
                                        class="px-3 py-1 bg-red-500 text-white text-xs font-medium rounded-full shadow-lg">
                                        Sedang Digunakan
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-orange-500 text-white text-xs font-medium rounded-full shadow-lg">
                                        Ada Jadwal
                                    </span>
                                @endif
                            @else
                                <span
                                    class="px-3 py-1 bg-green-500 text-white text-xs font-medium rounded-full shadow-lg">
                                    Tersedia
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Room Info -->
                    <div class="p-6 flex flex-col flex-grow">
                        <!-- Basic Info -->
                        <div class="mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-900">{{ $ruangan->nama_ruangan }}</h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $ruangan->lokasi }}
                            </p>
                        </div>

                        <!-- Current Usage Info -->
                        @if ($ruangan->is_currently_used)
                            @if ($ruangan->current_booking)
                                <!-- Currently being used -->
                                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <h4 class="text-sm font-semibold text-red-800 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Sedang Digunakan
                                    </h4>
                                    <div class="text-xs text-red-700 space-y-1">
                                        <p><strong>Peminjam:</strong>
                                            {{ $ruangan->current_booking->pengguna->nama ?? 'N/A' }}</p>
                                        <p><strong>Keperluan:</strong>
                                            {{ Str::limit($ruangan->current_booking->keperluan, 30) }}</p>
                                        <p><strong>Waktu:</strong> {{ $ruangan->current_booking->waktu_mulai }} -
                                            {{ $ruangan->current_booking->waktu_selesai }}</p>
                                    </div>
                                </div>
                            @else
                                <!-- Has future bookings -->
                                <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <h4 class="text-sm font-semibold text-orange-800 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Ada Jadwal Terjadwal
                                    </h4>
                                    <div class="text-xs text-orange-700">
                                        <p>Ruangan memiliki jadwal peminjaman yang akan datang</p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Room is available -->
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <h4 class="text-sm font-semibold text-green-800 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tersedia
                                </h4>
                                <div class="text-xs text-green-700">
                                    <p>Ruangan dapat dipinjam saat ini</p>
                                </div>
                            </div>
                        @endif

                        <!-- Upcoming Bookings -->
                        @if ($ruangan->upcoming_bookings->isNotEmpty())
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Jadwal Mendatang
                                </h4>
                                <div class="space-y-2 max-h-24 overflow-y-auto">
                                    @foreach ($ruangan->upcoming_bookings as $booking)
                                        <div class="text-xs bg-gray-50 p-2 rounded border">
                                            <div class="flex justify-between items-start">
                                                <span
                                                    class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($booking->tanggal_pinjam)->format('d/m') }}</span>
                                                <span
                                                    class="text-xs px-2 py-1 rounded 
                                                    {{ $booking->status_persetujuan === 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ ucfirst($booking->status_persetujuan) }}
                                                </span>
                                            </div>
                                            <p class="text-gray-600 truncate">
                                                {{ $booking->waktu_mulai }}-{{ $booking->waktu_selesai }} |
                                                {{ $booking->pengguna->nama ?? 'N/A' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        @auth
                            @if (!auth()->user()->hasRole('admin'))
                                <div class="mt-auto">
                                    <a href="{{ route('ruangan.show', $ruangan) }}" class="block">
                                        <button
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                                            <span>Pesan Sekarang</span>
                                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </button>
                                    </a>
                                </div>
                            @else
                                <div
                                    class="w-full bg-gray-400 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center cursor-not-allowed">
                                    <span>Admin View Only</span>
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block">
                                <button
                                    class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center">
                                    <span>Login untuk Pesan</span>
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </button>
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="hidden text-center py-12">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.287 0-4.348-.773-6-2.062M15 21H3a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Tidak Ada Ruangan Ditemukan</h3>
            <p class="text-gray-500">Coba ubah kriteria pencarian atau filter Anda.</p>
        </div>

        @if ($ruangans->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Ruangan</h3>
                <p class="text-gray-500">Ruangan belum tersedia saat ini.</p>
            </div>
        @endif
    </div>

    <!-- JavaScript for Search and Filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('room-search');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const roomCards = document.querySelectorAll('.room-card');
            const noResults = document.getElementById('no-results');

            let currentFilter = 'all';
            let currentSearch = '';

            // Filter button styles
            function updateFilterButtonStyles() {
                filterButtons.forEach(btn => {
                    if (btn.dataset.filter === currentFilter) {
                        btn.classList.add('bg-blue-600', 'text-white', 'shadow-md');
                        btn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                    } else {
                        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md');
                        btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                    }
                });
            }

            // Filter and search function
            function filterRooms() {
                let visibleCount = 0;

                roomCards.forEach(card => {
                    const status = card.dataset.status;
                    const name = card.dataset.name;
                    const location = card.dataset.location;

                    const matchesFilter = currentFilter === 'all' || status === currentFilter;
                    const matchesSearch = currentSearch === '' ||
                        name.includes(currentSearch.toLowerCase()) ||
                        location.includes(currentSearch.toLowerCase());

                    if (matchesFilter && matchesSearch) {
                        card.style.display = 'block';
                        card.classList.add('animate-fadeIn');
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                        card.classList.remove('animate-fadeIn');
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0 && roomCards.length > 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }

            // Search input event
            searchInput.addEventListener('input', function() {
                currentSearch = this.value;
                filterRooms();
            });

            // Filter button events
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentFilter = this.dataset.filter;
                    updateFilterButtonStyles();
                    filterRooms();
                });
            });

            // Initialize
            updateFilterButtonStyles();
        });
    </script>

    <!-- CSS for animations -->
    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-btn.active {
            background-color: #2563eb;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .room-card:hover {
            transform: translateY(-2px);
        }

        /* Custom scrollbar for upcoming bookings */
        .max-h-24::-webkit-scrollbar {
            width: 4px;
        }

        .max-h-24::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 2px;
        }

        .max-h-24::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .max-h-24::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</section>
