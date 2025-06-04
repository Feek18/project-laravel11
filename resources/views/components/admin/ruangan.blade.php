<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white max-w-7xl mx-auto sm:p-6 lg:p-8 rounded-lg">
            <div class="px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900 mb-8">
                {{ __('Data Ruangan Kuliah') }}
                <div class="flex justify-end mb-2">
                    <button data-modal-target="ruangan-modal" data-modal-toggle="ruangan-modal"
                        data-modal-backdrop="static" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 ">Tambah
                        Ruangan</button>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                    <thead class="text-xs text-black uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Ruangan
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Lokasi
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ruangan as $r)
                            <tr class="bg-white border-b border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $r->nama_ruangan }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $r->lokasi }}
                                </td>
                                <td class="flex items-center gap-2 px-6 py-4">
                                    <button data-modal-target="edit-modal-{{ $r->id_ruang }}"
                                        data-modal-toggle="edit-modal-{{ $r->id_ruang }}" data-modal-backdrop="static"
                                        type="button"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                                        <x-icons.edit-icon class="w-4 h-4" />
                                    </button>
                                    <form method="POST" action="{{ route('ruangan.destroy', $r->id_ruang) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150">
                                            <x-icons.hapus-icon class="w-4 h-4" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @include('components.modals.ruangan.edit', ['ruangan' => $r])
                        @endforeach
                    </tbody>
                </table>
                <div class="m-8">
                    {{ $ruangan->links('pagination::tailwind') }}
                </div>
            </div>
            @include('components.modals.ruangan.tambah')
        </div>
    </div>
</x-app-layout>
