<x-user.layouts.app>
    <x-slot name="header">
        <h2 class="font-normal text-xs text-gray-500 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            <div class="max-w-2xl px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900">
                {{ __('Selamat Datang di Halaman Dashboard, ' . (Auth::user()->pengguna?->nama ?? Auth::user()->name ?? Auth::user()->email)) }}
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
                            <span class="text-sm text-gray-700">Pesanan</span>
                            <h4 class="mt-2 font-bold text-gray-800 text-2xl">3,782</h4>
                        </div>
                        <x-icons.badge color="success">
                            <x-icons.arrow-up-icon class="w-4 h-4 text-green-700 inline-block" />
                            <span class="text-[15px]">11.01%</span>
                        </x-icons.badge>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-user.layouts.app>
