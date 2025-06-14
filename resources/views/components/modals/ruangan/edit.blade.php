<!-- Main modal -->
<div id="edit-ruangan-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-gray-200 rounded-lg shadow-sm">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    Edit Data Pengguna
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-500 bg-transparent hover:bg-gray-300 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="edit-modal-{{ $ruangan->id_ruang }}">
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
                <form class="space-y-4" method="POST" action="{{ route('ruangan.update', $ruangan->id_ruang) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_ruangan" class="block mb-2 text-sm font-medium text-gray-900 ">Nama
                            Ruangan</label>
                        <input type="text" name="nama_ruangan" id="nama_ruangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" placeholder="Masukkan email anda"/>
                    </div>
                    <div class="mb-4">
                        <label for="lokasi" class="block mb-2 text-sm font-medium text-gray-900 ">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            value="{{ old('lokasi', $ruangan->lokasi) }}" placeholder="Masukkan nama anda"/>
                    </div>
                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
