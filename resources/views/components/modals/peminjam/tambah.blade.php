<!-- Main modal -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-gray-200 rounded-lg shadow-sm">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Peminjam
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-500 bg-transparent hover:bg-gray-300 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form id="room-booking-form" action="{{ route('peminjam.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_type" value="peminjaman">
                    {{-- <input type="hidden" name="id_ruang" value="{{ $ruangan->id_ruang }}"> --}}
                    {{-- <input type="hidden" name="id_pengguna" value="{{ auth()->user()->pengguna->id }}"> --}}
                    <div class="mb-4">
                        <label for="id_pengguna" class="block text-sm font-medium text-gray-700">Nama Pengguna</label>
                        <select id="id_pengguna" name="id_pengguna"
                            class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <option value="" disabled selected>Pilih pengguna</option>
                            @foreach ($pengguna as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="id_ruang" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>
                        <select id="id_ruang" name="id_ruang"
                            class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <option value="" disabled selected>Pilih ruangan</option>
                            @foreach ($ruangan as $r)
                                <option value="{{ $r->id_ruang }}">{{ $r->nama_ruangan }} - {{ $r->lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <pre>{{ dd($ruangan->getAttributes()) }}</pre> --}}

                    <div class="mb-4">
                        <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal
                            Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                            class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                             />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu
                                Mulai</label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai"
                                class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                 />
                        </div>

                        <div class="mb-4">
                            <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu
                                Selesai</label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai"
                                class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                 />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan
                            Pinjam</label>
                        <textarea id="keperluan" name="keperluan" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan keperluan anda"></textarea>
                    </div>

                    <!-- Live Conflict Check Results will be inserted here -->
                    <div id="peminjaman-conflict-status" class="mt-4"></div>

                    <button id="jadwal-submit-btn" type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/live-conflict-checker.js') }}"></script>
