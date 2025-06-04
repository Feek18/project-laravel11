<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white max-w-7xl mx-auto sm:p-6 lg:p-8 rounded-lg">
            <div class="px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900 mb-8">
                {{ __('Mata Kuliah') }}
                <div class="flex justify-end mb-2">
                    <button data-modal-target="matkul-modal" data-modal-toggle="matkul-modal" data-modal-backdrop="static"
                        type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 ">Tambah
                        Mata Kuliah</button>
                </div>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                No
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Kode MataKuliah
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama MataKuliah
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Semester
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($matakuliah as $mk)
                            <tr class="bg-white">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                    {{ $loop->iteration }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $mk->kode_matkul }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $mk->mata_kuliah }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $mk->semester }}
                                </td>
                                <td class="flex items-center gap-2 px-6 py-4">
                                    <button data-modal-target="matkul-edit-modal-{{ $mk->id }}"
                                        data-modal-toggle="matkul-edit-modal-{{ $mk->id }}"
                                        data-modal-backdrop="static" type="button"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                                        <x-icons.edit-icon class="w-4 h-4" />
                                    </button>

                                    <form method="POST" action="{{ route('matkul.destroy', $mk->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150">
                                            <x-icons.hapus-icon class="w-4 h-4" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @include('components.modals.matakuliah.edit', ['matakuliah' => $mk])
                        @endforeach
                    </tbody>
                </table>
                <div class="m-8">
                    {{ $matakuliah->links('pagination::tailwind') }}
                </div>
            </div>
            @include('components.modals.matakuliah.tambah')
        </div>
    </div>
</x-app-layout>
