    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Akun') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="bg-white max-w-7xl mx-auto sm:p-6 lg:p-8 rounded-lg">
                <div class="px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900 mb-8">
                    {{ __('Data Akun') }}
                    <div class="flex justify-end mb-2">
                        <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                            data-modal-backdrop="static" type="button"
                            class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Tambah Akun Pengguna
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
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    No. Telepon
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($akun as $a)
                                <tr class="bg-white">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $loop->iteration }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $a->user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $a->nama }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $a->no_telp }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('akun.destroy', $a->user->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                <x-icons.hapus-icon class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="m-8">
                        {{ $users->links('pagination::tailwind') }}
                    </div> --}}
                </div>
                @include('components.modals.akun.tambah')
            </div>
        </div>
    </x-app-layout>
