<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Peminjam Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white max-w-7xl mx-auto sm:p-6 lg:p-8 rounded-lg shadow">
            {{-- Header Section --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-semibold text-gray-900">Daftar Peminjaman</h3>
                <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                    data-modal-backdrop="static" type="button"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium focus:ring-4 focus:ring-blue-300">
                    Tambah Peminjam
                </button>
            </div>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Data Table --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Peminjam</th>
                            <th class="px-4 py-3">Ruangan</th>
                            <th class="px-4 py-3">Keperluan</th>
                            <th class="px-4 py-3">Status Peminjaman</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Mulai</th>
                            <th class="px-4 py-3">Selesai</th>
                            <th class="px-4 py-3">Persetujuan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjam as $p)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">{{ $p->pengguna->nama }}</td>
                                <td class="px-4 py-3">{{ $p->ruangan->nama_ruangan }} - {{ $p->ruangan->lokasi }}</td>
                                <td class="px-4 py-3">{{ $p->keperluan }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-medium text-white rounded 
                                        {{ $p->status_peminjaman === 'terencana' ? 'bg-green-600' : 'bg-indigo-600' }}">
                                        {{ ucfirst($p->status_peminjaman) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ $p->tanggal_pinjam }}</td>
                                <td class="px-4 py-3">{{ $p->waktu_mulai }}</td>
                                <td class="px-4 py-3">{{ $p->waktu_selesai }}</td>
                                <td class="px-6 py-4">
                                    @if ($p->status_persetujuan === 'pending')
                                        <form action="{{ route('peminjam.persetujuan', $p->id) }}" method="POST"
                                            class="flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <button name="status_persetujuan" value="disetujui"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                Setujui
                                            </button>
                                            <button name="status_persetujuan" value="ditolak"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="inline-block px-3 py-1 text-xs font-medium text-white rounded {{ $p->status_persetujuan === 'disetujui' ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ ucfirst($p->status_persetujuan) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center flex items-center justify-center gap-2">
                                    {{-- Edit Button --}}
                                    <button data-modal-target="edit-modal-{{ $p->id }}"
                                        data-modal-toggle="edit-modal-{{ $p->id }}" data-modal-backdrop="static"
                                        type="button"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-lg transition">
                                        <x-icons.edit-icon class="w-4 h-4" />
                                    </button>

                                    {{-- Delete Form --}}
                                    <form method="POST" action="{{ route('peminjam.destroy', $p->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition">
                                            <x-icons.hapus-icon class="w-4 h-4" />
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            @include('components.modals.peminjam.edit', ['peminjam' => $p])
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tambah Modal --}}
            @include('components.modals.peminjam.tambah')
        </div>
    </div>
</x-app-layout>
