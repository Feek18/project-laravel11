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
<div class="relative -mt-12 md:-mt-16 lg:-mt-48 px-4 md:px-16 lg:px-32">
    <div id="calendar" class="w-full h-[600px] bg-white rounded-lg shadow-lg"></div>
</div>
