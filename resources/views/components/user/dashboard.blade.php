<x-user.layouts.app>
    <x-slot name="header">
        <h2 class="font-normal text-xs text-gray-500 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            <div class="max-w-2xl px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900">
                {{ __('Selamat Datang di Halaman Dashboard, ' . (Auth::user()->pengguna?->nama ?? (Auth::user()->name ?? Auth::user()->email))) }}
            </div>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 md:gap-6 mt-6">
                {{-- Metric Item: Customers --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl ">
                        {{-- Ganti dengan ikon SVG atau komponen ikon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                        </svg>
                    </div>

                    <div class="flex items-end justify-between mt-5">
                        <div>
                            <span class="text-sm text-gray-700">Pesanan</span>
                            <h4 class="mt-2 font-bold text-gray-800 text-2xl">
                                {{-- {{ number_format($jmlhPesanan) }} --}}
                            </h4>
                        </div>
                        {{-- <x-icons.badge color="success">
                            <x-icons.arrow-up-icon class="w-4 h-4 text-green-700 inline-block" />
                            <span class="text-[15px]">11.01%</span> <!-- Bisa juga kamu hitung secara dinamis -->
                        </x-icons.badge> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-user.layouts.app>
