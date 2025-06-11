<!-- Hero Section -->
<section style="background-image: url('{{ asset('img/hero.png') }}')" class="relative min-h-[700px] bg-cover bg-center">
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
            <h3 class="text-4xl font-semibold text-gray-900">Kalender Peminjaman Ruangan</h3>
            <div class="flex items-center space-x-4">
                {{-- Legend --}}
                <div class="flex items-center space-x-4 text-sm">
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
        <div class="mb-4 text-sm text-gray-500">
            <p>📌 Klik pada event untuk melihat detail peminjaman. Kalender ini hanya untuk melihat jadwal peminjaman
                ruangan.</p>
        </div>
        <div id="calendar" class="w-full h-[600px] bg-white rounded-lg shadow-lg"></div>
    </div>
