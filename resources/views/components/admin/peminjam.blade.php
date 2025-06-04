<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white max-w-7xl mx-auto sm:p-6 lg:p-8 rounded-lg">
            <div class="px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900 mb-8">
                {{ __('Data Peminjam Ruangan') }}
                <div class="flex justify-end mb-2">
                    <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                        data-modal-backdrop="static" type="button"
                        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Tambah Peminjam
                    </button>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Peminjam
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Ruangan
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status Peminjaman
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Tanggal Pinjam
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Waktu Mulai
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Waktu Selesai
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status Persetujuan
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Apple MacBook Pro 17"
                            </th>
                            <td class="px-6 py-4">
                                Silver
                            </td>
                            <td class="px-6 py-4">
                                Laptop
                            </td>
                            <td class="px-6 py-4">
                                $2999
                            </td>
                            <td class="px-6 py-4">
                                Edit
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- @include('components.modals.pengguna.tambah') --}}
        </div>
    </div>
</x-app-layout>
