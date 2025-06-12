<!-- Main modal -->
<div id="edit-modal-{{ $peminjam->id }}" tabindex="-1" aria-hidden="true"
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
                    data-modal-hide="edit-modal-{{ $peminjam->id }}">
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
                <form action="{{ route('peminjam.update', $peminjam->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="booking_type" value="peminjaman">
                    <input type="hidden" name="id" value="{{ $peminjam->id }}">

                    {{-- <input type="hidden" name="debug" value="test123"> --}}

                    <div class="mb-4">
                        <label for="id_pengguna" class="block text-sm font-medium text-gray-700">Nama
                            Pengguna</label>
                        <select id="id_pengguna" name="id_pengguna"
                            class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" disabled
                                {{ old('id_pengguna', $peminjam->id_pengguna ?? '') == '' ? 'selected' : '' }}>
                                Pilih pengguna
                            </option>
                            @foreach ($pengguna as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('id_pengguna', $peminjam->id_pengguna ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="id_ruang" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>
                        <select id="id_ruang" name="id_ruang"
                            class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" disabled
                                {{ old('id_ruang', $peminjam->id_ruang ?? '') == '' ? 'selected' : '' }}>
                                Pilih ruangan
                            </option>
                            @foreach ($ruangan as $r)
                                <option value="{{ $r->id_ruang }}"
                                    {{ old('id_ruang', $peminjam->id_ruang ?? '') == $r->id_ruang ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }} - {{ $r->lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="status_peminjaman" class="block text-sm font-medium text-gray-700">Status
                                Peminjaman</label>
                            <select id="status_peminjaman" name="status_peminjaman"
                                class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="" disabled
                                    {{ old('status_peminjaman', $peminjam->status_peminjaman ?? '') == '' ? 'selected' : '' }}>
                                    Pilih status peminjaman
                                </option>
                                <option value="terencana"
                                    {{ old('status_peminjaman', $peminjam->status_peminjaman ?? '') == 'terencana' ? 'selected' : '' }}>
                                    Terencana
                                </option>
                                <option value="insidental"
                                    {{ old('status_peminjaman', $peminjam->status_peminjaman ?? '') == 'insidental' ? 'selected' : '' }}>
                                    Insidental
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal
                                Pinjam</label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('tanggal_pinjam', $peminjam->tanggal_pinjam ?? '') }}" required />
                        </div>
                    </div>

                    {{-- <pre>{{ dd($ruangan->getAttributes()) }}</pre> --}}

                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="waktu_mulai" class="block text-sm font-medium text-gray-700">Waktu
                                Mulai</label>
                            <input type="time" name="waktu_mulai" id="waktu_mulai"
                                class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('waktu_mulai', isset($peminjam->waktu_mulai) ? \Carbon\Carbon::parse($peminjam->waktu_mulai)->format('H:i') : '') }}"
                                required />
                        </div>

                        <div class="mb-4">
                            <label for="waktu_selesai" class="block text-sm font-medium text-gray-700">Waktu
                                Selesai</label>
                            <input type="time" name="waktu_selesai" id="waktu_selesai"
                                class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('waktu_selesai', isset($peminjam->waktu_selesai) ? \Carbon\Carbon::parse($peminjam->waktu_selesai)->format('H:i') : '') }}"
                                required />

                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status_persetujuan" class="block text-sm font-medium text-gray-700">Status
                            Persetujuan</label>
                        <select id="status_persetujuan" name="status_persetujuan"
                            class="mt-1 block w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="" disabled
                                {{ old('status_persetujuan', $peminjam->status_persetujuan ?? '') == '' ? 'selected' : '' }}>
                                Pilih status persetujuan
                            </option>
                            <option value="pending"
                                {{ old('status_persetujuan', $peminjam->status_persetujuan ?? '') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="disetujui"
                                {{ old('status_persetujuan', $peminjam->status_persetujuan ?? '') == 'disetujui' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="ditolak"
                                {{ old('status_persetujuan', $peminjam->status_persetujuan ?? '') == 'ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan
                            Pinjam</label>
                        <textarea id="keperluan" name="keperluan" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            required>{{ old('keperluan', $peminjam->keperluan ?? '') }}</textarea>
                    </div>

                    <!-- Live Conflict Check Results will be inserted here -->

                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/live-conflict-checker.js') }}"></script>
