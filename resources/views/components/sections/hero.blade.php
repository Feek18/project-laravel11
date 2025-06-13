<!-- Hero Section -->
<section id="beranda" style="background-image: url('{{ asset('img/hero.png') }}')" class="relative min-h-[700px] bg-cover bg-center">
    {{-- overlay --}}
    <div class="absolute inset-0 bg-black opacity-30"></div>

    <div
        class="relative z-10 flex flex-col items-center justify-center h-full px-6 md:px-16 lg:px-32 pt-28 font-poppins space-y-10 text-white text-center max-w-7xl mx-auto">
        <div class="space-y-6">
            <h1 class="text-3xl md:text-5xl lg:text-[64px] font-bold leading-tight">
                Solusi Cerdas untuk Monitoring dan Peminjaman Ruang Kuliah
            </h1>
            <p class="text-sm sm:text-base md:text-lg">
                Platform ini dirancang untuk mempermudah pengelolaan ruang kelas, menghemat waktu, dan memastikan
                ruangan selalu tersedia sesuai kebutuhan Anda dengan efisien dan tepat waktu.
            </p>
        </div>
    </div>
</section>

<!-- Kalender Offside -->
<div class="relative -mt-12 md:-mt-16 lg:-mt-48">
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mx-4 md:mx-16 lg:mx-32">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-4xl font-semibold text-gray-900">Kalender Ruangan & Jadwal</h3>
            <div class="flex items-center space-x-4">
                {{-- Legend --}}
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span class="text-gray-600">Jadwal Kuliah</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-gray-600">Disetujui</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span class="text-gray-600">Pending</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Filter Form --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
            <form id="calendar-filter-form" method="GET" action="{{ route('home') }}" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label for="month" class="text-sm font-medium text-gray-700">Bulan:</label>
                    <select name="month" id="month" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Bulan</option>
                        @foreach($months as $value => $label)
                            <option value="{{ $value }}" {{ request('month') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-center gap-2">
                    <label for="year" class="text-sm font-medium text-gray-700">Tahun:</label>
                    <select name="year" id="year" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="mb-4 text-sm text-gray-500">
            <p>📌 Klik pada event untuk melihat detail. Kalender menampilkan jadwal perkuliahan (biru) dan peminjaman ruangan (hijau/kuning).</p>
        </div>
        <div id="calendar" class="w-full h-[600px] bg-white rounded-lg shadow-lg"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when month or year selection changes
    const monthSelect = document.getElementById('month');
    const yearSelect = document.getElementById('year');
    const filterForm = document.getElementById('calendar-filter-form');
    
    function autoSubmitForm() {
        filterForm.submit();
    }
    
    if (monthSelect) {
        monthSelect.addEventListener('change', autoSubmitForm);
    }
    
    if (yearSelect) {
        yearSelect.addEventListener('change', autoSubmitForm);
    }
});
</script>
