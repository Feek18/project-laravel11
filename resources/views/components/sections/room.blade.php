<section>
    <div class="container px-4 py-8 sm:px-6 lg:px-20 mt-14">
        <h1 class="text-[28px] md:text-4xl lg:text-3xl font-semibold leading-relaxed lg:mb-2">
            Ruang Kuliah Yang Digunakan
        </h1>
        <p class="text-sm md:text-base lg:text-[16px] mb-6">Daftar kelas yang sedang digunakan</p>
        <div class="flex flex-col justify-center items-center lg:grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($ruangans as $ruangan)
                <div class="max-w-sm bg-white rounded-lg shadow-sm dark:bg-gray-800">
                    <img class="w-[330px] h-[220px] bg-cover object-cover rounded-t-lg" src="{{ $ruangan->gambar }}"
                        alt="" />
                    <div class="p-5">
                        <div class="flex justify-between items-center text-white opacity-75 text-[13px] mb-2">
                            <p>{{ $ruangan->lokasi }}</p>
                            {{-- <p>09.00 - 10.30 WITA</p> --}}
                        </div>
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-white">{{ $ruangan->nama_ruangan }}</h5>
                        <p class="mb-3 font-normal text-gray-700"></p>
                        <a href="{{ route('ruangan.show', $ruangan) }}">
                            <button
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                                type="button">
                                Pesan Sekarang
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                </svg>
                            </button>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
