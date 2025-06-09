<x-app-layout>
    <x-slot name="header">
        <h2 class="font-normal text-xs text-gray-500 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900">
                {{ __('Selamat Datang di Halaman Dashboard KelasReady') }}
            </div>
            <div class="mt-1 px-4 sm:px-6 lg:px-0 text-gray-800">
                {{ __('Anda dapat mengelola segala kegiatan pembelajaran di lingkungan kampus') }}
            </div>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 md:gap-6 mt-6">
                {{-- Metric Item: Customers --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl ">
                        {{-- Ganti dengan ikon SVG atau komponen ikon --}}
                        <x-icons.group-icon class="text-gray-700 size-8" />
                    </div>

                    <div class="flex items-end justify-between mt-5">
                        <div>
                            <span class="text-sm text-gray-700">Pelanggan</span>
                            <h4 class="mt-2 font-bold text-gray-800 text-2xl">3,782</h4>
                        </div>
                        <x-icons.badge color="success">
                            <x-icons.arrow-up-icon class="w-4 h-4 text-green-700 inline-block" />
                            <span class="text-[15px]">11.01%</span>
                        </x-icons.badge>
                    </div>
                </div>
                {{-- Metric Item: Rooms --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl ">
                        <x-icons.ruangan-kuliah-icon class="text-gray-700 size-6" />
                    </div>

                    <div class="flex
                            items-end justify-between mt-5">
                        <div>
                            <span class="text-sm text-gray-700">Ruangan Kuliah</span>
                            <h4 class="mt-2 font-bold text-gray-800 text-2xl">2,000</h4>
                        </div>
                        <x-icons.badge color="success">
                            <x-icons.arrow-up-icon class="w-4 h-4 text-green-700 inline-block" />
                            <span class="text-[15px]">3.05%</span>
                        </x-icons.badge>
                    </div>
                </div>
                {{-- Metric Item: Orders --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl ">
                        <x-icons.box-icon-line class="text-gray-700 size-6" />
                    </div>

                    <div class="flex
                            items-end justify-between mt-5">
                        <div>
                            <span class="text-sm text-gray-700">Peminjaman</span>
                            <h4 class="mt-2 font-bold text-gray-800 text-2xl">5,359</h4>
                        </div>
                        <x-icons.badge color="error">
                            <x-icons.arrow-down-icon class="w-4 h-4 text-red-700 inline-block" />
                            <span class="text-[15px]">9.05%</span>
                        </x-icons.badge>
                    </div>
                </div>
            </div>

            {{-- Calendar Section --}}
            <div class="mt-8">
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Kalender Peminjaman Ruangan</h3>
                    <div id="calendar" style="height: 600px;"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- Pass peminjaman data to JavaScript --}}
    <script>
        window.peminjamanData = @json($peminjamans);
    </script>
</x-app-layout>
