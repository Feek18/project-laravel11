<!-- Main modal -->
<div id="jadwal-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-gray-200 rounded-lg shadow-sm">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Jadwal
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-500 bg-transparent hover:bg-gray-300 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="jadwal-modal">
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
                <form class="space-y-4" method="POST" action="{{ route('jadwal.store') }}">
                    @csrf
                    <input type="hidden" name="booking_type" value="jadwal">
                    
                    <div>
                        <label for="id_ruang" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Ruangan</label>
                        <select id="id_ruang" name="id_ruang"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled {{ old('id_ruang') == null ? 'selected' : '' }}>Pilih
                                ruangan</option>
                            @foreach ($ruangan as $r)
                                <option value="{{ $r->id_ruang }}">{{ $r->nama_ruangan }} - {{ $r->lokasi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="id_matkul" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Mata Kuliah</label>
                        <select id="id_matkul" name="id_matkul"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled {{ old('id_ruang') == null ? 'selected' : '' }}>Pilih
                                matkul</option>
                            @foreach ($matkul as $m)
                                <option value="{{ $m->id }}">{{ $m->mata_kuliah }}</option>
                            @endforeach
                        </select>
                        {{-- <label for="nama_perkuliahan" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Perkuliahan</label>
                        <input type="text" name="nama_perkuliahan" id="nama_perkuliahan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan nama perkuliahan" required /> --}}
                    </div>
                    <div>
                        <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan lokasi" required />
                    </div>
                    <div>
                        <label for="hari" class="block mb-2 text-sm font-medium text-gray-900">Hari</label>
                        <select id="hari" name="hari"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected>Pilih hari</option>
                            <option value="minggu" {{ old('hari') == 'minggu' ? 'selected' : '' }}>Minggu</option>
                            <option value="senin" {{ old('hari') == 'senin' ? 'selected' : '' }}>Senin</option>
                            <option value="selasa" {{ old('hari') == 'selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="rabu" {{ old('hari') == 'rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="kamis" {{ old('hari') == 'kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="jumat" {{ old('hari') == 'jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="sabtu" {{ old('hari') == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                        </select>
                    </div>
                    <div>
                        <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-900">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan lokasi" required />
                    </div>
                    <div>
                        <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-900">Jam
                            Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            placeholder="Masukkan lokasi" required />
                    </div>
                    
                    <!-- Live Conflict Check Results will be inserted here -->
                    <div id="jadwal-conflict-status" class="mt-4"></div>
                    
                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/live-conflict-checker.js') }}"></script>
<script>
// Initialize conflict checker when modal is shown
let jadwalConflictChecker = null;

// Auto-update hari field based on tanggal
function updateHariFromTanggal() {
    const tanggalInput = document.querySelector('#jadwal-modal [name="tanggal"]');
    const hariSelect = document.querySelector('#jadwal-modal [name="hari"]');
    
    if (tanggalInput && hariSelect && tanggalInput.value) {
        const date = new Date(tanggalInput.value);
        const dayNames = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        const dayName = dayNames[date.getDay()];
        
        // Set the hari dropdown to match the selected date
        if (hariSelect.querySelector(`option[value="${dayName}"]`)) {
            hariSelect.value = dayName;
        }
    }
}

function initializeJadwalConflictChecker() {
    console.log('Initializing jadwal conflict checker...');
    
    // Destroy existing instance if any
    if (jadwalConflictChecker) {
        jadwalConflictChecker.reset();
        jadwalConflictChecker = null;
    }
    
    // Wait a bit to ensure DOM is ready
    setTimeout(() => {
        // Set up auto-update of hari field
        const tanggalInput = document.querySelector('#jadwal-modal [name="tanggal"]');
        if (tanggalInput) {
            tanggalInput.addEventListener('change', updateHariFromTanggal);
            // Update on initial load if value exists
            updateHariFromTanggal();
        }
        
        // Check if required elements exist
        const form = document.querySelector('#jadwal-modal form');
        const idRuang = document.querySelector('#jadwal-modal [name="id_ruang"]');
        const tanggal = document.querySelector('#jadwal-modal [name="tanggal"]');
        const jamMulai = document.querySelector('#jadwal-modal [name="jam_mulai"]');
        const jamSelesai = document.querySelector('#jadwal-modal [name="jam_selesai"]');
        
        console.log('Form elements found:', {
            form: !!form,
            idRuang: !!idRuang,
            tanggal: !!tanggal,
            jamMulai: !!jamMulai,
            jamSelesai: !!jamSelesai
        });
        
        if (form && idRuang && tanggal && jamMulai && jamSelesai) {
            // Create new instance for this modal
            jadwalConflictChecker = new LiveConflictChecker({
                autoCheck: true,
                showSuggestions: true,
                debounceMs: 800
            });
            
            console.log('Jadwal conflict checker initialized successfully');
        } else {
            console.error('Required form elements not found for conflict checker');
        }
    }, 200);
}

// Listen for modal show events
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('jadwal-modal');
    if (modal) {
        console.log('Setting up modal observer for jadwal-modal');
        
        // Initialize when modal becomes visible
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const isVisible = !modal.classList.contains('hidden');
                    console.log('Modal visibility changed:', isVisible);
                    
                    if (isVisible && !jadwalConflictChecker) {
                        initializeJadwalConflictChecker();
                    } else if (!isVisible && jadwalConflictChecker) {
                        // Clean up when modal is hidden
                        jadwalConflictChecker.reset();
                        jadwalConflictChecker = null;
                        console.log('Conflict checker cleaned up');
                    }
                }
            });
        });
        
        observer.observe(modal, {
            attributes: true,
            attributeFilter: ['class']
        });
        
        // Also listen for flowbite modal events if available
        modal.addEventListener('show.bs.modal', initializeJadwalConflictChecker);
    } else {
        console.error('jadwal-modal not found');
    }
});
</script>
