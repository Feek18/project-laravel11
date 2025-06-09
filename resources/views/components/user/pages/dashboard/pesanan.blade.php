<x-user.layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ruangan') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:ml-64">
        <div class="p-4 mt-14">
            <div class="bg-white max-w-7xl mx-auto sm:p-6 lg:p-8 rounded-lg">
                <div class="px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900 mb-8">
                    {{ __('History Pesanan') }}
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
                                    Tanggal Pinjam
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Waktu Mulai
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Waktu Selesai
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Keperluan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @php dd($users); @endphp --}}
                            {{-- @foreach ($users as $u)
                                <tr class="bg-white">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                        {{ $loop->iteration }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $u->nama }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $u->alamat }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $u->gender }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $u->no_telp }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Tombol Edit -->
                                            <button type="button" data-modal-target="edit-modal-{{ $u->id }}"
                                                data-modal-toggle="edit-modal-{{ $u->id }}"
                                                data-modal-backdrop="static"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition duration-150">
                                                <x-icons.edit-icon class="w-4 h-4" />
                                            </button>
    
                                            <!-- Tombol Hapus -->
                                            <form method="POST" action="{{ route('pengguna.destroy', $u->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition duration-150"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    <x-icons.hapus-icon class="w-4 h-4" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
    
                                </tr>
                                @include('components.modals.pengguna.edit', ['user' => $u])
                            @endforeach --}}
                        </tbody>
                    </table>
                    {{-- <div class="m-8">
                        {{ $users->links('pagination::tailwind') }}
                    </div> --}}
                </div>
                {{-- @include('components.modals.pengguna.tambah') --}}
            </div>
        </div>
    </div>
</x-user.layouts.app>
