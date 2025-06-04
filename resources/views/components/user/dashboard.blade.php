<x-user.layouts.app>
    <x-slot name="header">
        <h2 class="font-normal text-xs text-gray-500 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            <div class="max-w-2xl px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900">
                {{ __('Selamat Datang di Halaman Dashboard, ' . Auth::user()->pengguna->nama) }}
            </div>
        </div>
    </div>
</x-user.layouts.app>
