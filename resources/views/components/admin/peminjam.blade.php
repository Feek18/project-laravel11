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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <table id="peminjam-table" class="w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Tanggal</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>QR Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>

            {{-- Tambah Modal --}}
            @include('components.modals.peminjam.tambah')
            <!-- Dynamic edit modal container -->
            <div id="dynamic-modal-container"></div>
        </div>
    </div>

    @push('scripts')
    <!-- DataTables Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/datatables-custom.css') }}">
    
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <!-- Shared DataTables Modal Handler -->
    <script src="{{ asset('js/datatables-modal.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('#peminjam-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('peminjam.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nama_peminjam', name: 'pengguna.nama'},
                    {data: 'nama_ruangan', name: 'ruangan.nama_ruangan'},
                    {data: 'keperluan', name: 'keperluan'},
                    {data: 'tanggal_pinjam', name: 'tanggal_pinjam'},
                    {data: 'waktu_mulai', name: 'waktu_mulai'},
                    {data: 'waktu_selesai', name: 'waktu_selesai'},
                    {data: 'status_badge', name: 'status_persetujuan'},
                    {data: 'qr_code_status', name: 'qr_token', orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
                drawCallback: function() {
                    if (typeof window.initFlowbite === 'function') {
                        window.initFlowbite();
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
