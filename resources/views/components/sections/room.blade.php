<section>
    <div class="container px-4 py-8 sm:px-6 lg:px-20 mt-14">
        <h1 class="text-[24px] sm:text-[28px] md:text-4xl lg:text-3xl font-semibold leading-relaxed mb-2">
            Ruang Kuliah Yang Digunakan
        </h1>
        <p class="text-sm md:text-base lg:text-[16px] mb-6 text-gray-600">
            Daftar kelas yang sedang digunakan
        </p>

        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($ruangans as $ruangan)
                <div class="bg-white rounded-lg shadow hover:shadow-md transition duration-200 overflow-hidden">
                    <div class="aspect-video overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ $ruangan->gambar }}"
                            alt="{{ $ruangan->nama_ruangan }}">
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between items-center text-gray-500 text-xs mb-1">
                            <p>{{ $ruangan->lokasi }}</p>
                            {{-- <p>09.00 - 10.30 WITA</p> --}}
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
    </div>
</section>
