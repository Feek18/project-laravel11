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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <table id="pesanan-table" class="w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ruangan</th>
                                <th>Tanggal Pinjam</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- DataTables Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/datatables-custom.css') }}">
    
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#pesanan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pesanRuangan.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nama_ruangan', name: 'ruangan.nama_ruangan'},
                    {data: 'tanggal_pinjam', name: 'tanggal_pinjam'},
                    {data: 'waktu_mulai', name: 'waktu_mulai'},
                    {data: 'waktu_selesai', name: 'waktu_selesai'},
                    {data: 'keperluan', name: 'keperluan'},
                    {data: 'status_badge', name: 'status_persetujuan'},
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
</x-user.layouts.app>
