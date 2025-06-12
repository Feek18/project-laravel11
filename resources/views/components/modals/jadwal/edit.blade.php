<!-- Main modal -->
<div id="edit-jadwal-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-gray-200 rounded-lg shadow-sm">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    Edit Jadwal
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-500 bg-transparent hover:bg-gray-300 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="edit-jadwal-modal">
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
                <form class="space-y-4" method="POST" action="{{ route('jadwal.update', $jadwal->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="booking_type" value="jadwal">
                    <input type="hidden" name="id" value="{{ $jadwal->id }}">
                    {{-- @php
                        dd($jadwal);
                    @endphp --}}
                    <div>
                        <label for="id_ruang" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Pengguna</label>
                        <select id="id_ruang" name="id_ruang"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled {{ old('id_ruang') == null ? 'selected' : '' }}>Pilih
                                ruangan</option>
                            @foreach ($ruangan as $r)
                                <option value="{{ $r->id_ruang }}"
                                    {{ (old('id_ruang') !== null ? old('id_ruang') == $r->id_ruang : $jadwal->id_ruang == $r->id_ruang) ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div>
                        <label for="id_matkul" class="block mb-2 text-sm font-medium text-gray-900">Mata Kuliah</label>
                        <select id="id_matkul" name="id_matkul"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled
                                {{ old('id_matkul', $jadwal->id_matkul) == null ? 'selected' : '' }}>
                                Pilih mata kuliah
                            </option>
                            @foreach ($matkul as $mk)
                                <option value="{{ $mk->id }}"
                                    {{ old('id_matkul', $jadwal->id_matkul) == $mk->id ? 'selected' : '' }}>
                                    {{ $mk->mata_kuliah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hari" class="block mb-2 text-sm font-medium text-gray-900">Hari</label>
                        <select id="hari" name="hari"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="minggu" {{ old('hari', $jadwal->hari) == 'minggu' ? 'selected' : '' }}>
                                Minggu
                            </option>
                            <option value="senin" {{ old('hari', $jadwal->hari) == 'senin' ? 'selected' : '' }}>Senin
                            </option>
                            <option value="selasa" {{ old('hari', $jadwal->hari) == 'selasa' ? 'selected' : '' }}>
                                Selasa
                            </option>
                            <option value="rabu" {{ old('hari', $jadwal->hari) == 'rabu' ? 'selected' : '' }}>Rabu
                            </option>
                            <option value="kamis" {{ old('hari', $jadwal->hari) == 'kamis' ? 'selected' : '' }}>Kamis
                            </option>
                            <option value="jumat" {{ old('hari', $jadwal->hari) == 'jumat' ? 'selected' : '' }}>Jumat
                            </option>
                            <option value="sabtu" {{ old('hari', $jadwal->hari) == 'sabtu' ? 'selected' : '' }}>Sabtu
                            </option>
                        </select>

                    </div>
                    <div>
                        <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-900">Jam
                            Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan lokasi" required
                            value="{{ old('jam_mulai', \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_mulai)->format('H:i')) }}" />
                    </div>
                    <div>
                        <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-900">Jam
                            Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan lokasi" required
                            value="{{ old('jam_selesai', \Carbon\Carbon::createFromFormat('H:i:s', $jadwal->jam_selesai)->format('H:i')) }}" />
                    </div>
                    
                    <!-- Live Conflict Check Results will be inserted here -->
                    
                    <button id="jadwal-submit-btn" type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/live-conflict-checker.js') }}"></script>
<script>
// Initialize conflict checker for edit form
document.addEventListener('DOMContentLoaded', function() {
    console.log('Edit modal script loaded - day-based jadwal');
});
</script>
