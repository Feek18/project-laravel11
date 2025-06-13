<section id="ruangan">
    <div class="container px-4 py-8 sm:px-6 lg:px-20 mt-14">
        <div class="text-center mb-8">
            <h1 class="text-[24px] sm:text-[28px] md:text-4xl lg:text-3xl font-semibold leading-relaxed mb-2">
                Ruang Kuliah Yang Tersedia
            </h1>
            <p class="text-sm md:text-base lg:text-[16px] mb-6 text-gray-600">
                Pilih ruangan kuliah sesuai kebutuhan Anda
            </p>
        </div>

        <!-- Show first 8 rooms initially -->
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="rooms-grid">
            @foreach ($ruangans->take(8) as $ruangan)
                <div class="bg-white rounded-lg shadow hover:shadow-md transition duration-200 overflow-hidden room-card">
                    <div class="aspect-video overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ $ruangan->gambar }}"
                            alt="{{ $ruangan->nama_ruangan }}">
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between items-center text-gray-500 text-xs mb-1">
                            <p>{{ $ruangan->lokasi }}</p>
                        </div>
                        <h5 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ $ruangan->nama_ruangan }}
                        </h5>

                        <a href="{{ route('ruangan.show', $ruangan) }}">
                            <button
                                class="mt-3 inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition">
                                Pesan Sekarang
                                <svg class="ml-2 w-4 h-4" fill="none" viewBox="0 0 14 10"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5h12M13 5L9 1M13 5L9 9" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Hidden rooms (shown when "Lihat Semua" is clicked) -->
        @if($ruangans->count() > 8)
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6 hidden" id="additional-rooms">
                @foreach ($ruangans->skip(8) as $ruangan)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition duration-200 overflow-hidden room-card">
                        <div class="aspect-video overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{ $ruangan->gambar }}"
                                alt="{{ $ruangan->nama_ruangan }}">
                        </div>

                        <div class="p-4">
                            <div class="flex justify-between items-center text-gray-500 text-xs mb-1">
                                <p>{{ $ruangan->lokasi }}</p>
                            </div>
                            <h5 class="text-lg font-semibold text-gray-800 mb-1">
                                {{ $ruangan->nama_ruangan }}
                            </h5>

                            <a href="{{ route('ruangan.show', $ruangan) }}">
                                <button
                                    class="mt-3 inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition">
                                    Pesan Sekarang
                                    <svg class="ml-2 w-4 h-4" fill="none" viewBox="0 0 14 10"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 5h12M13 5L9 1M13 5L9 9" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Show/Hide Toggle Button -->
            <div class="text-center mt-8">
                <button id="toggle-rooms-btn" 
                    class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition">
                    <span id="toggle-text">Lihat Semua Ruangan</span>
                    <svg id="toggle-icon" class="ml-2 w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if($ruangans->isEmpty())
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Belum Ada Ruangan</h3>
                <p class="text-gray-500">Ruangan belum tersedia saat ini.</p>
            </div>
        @endif
    </div>
</section>
