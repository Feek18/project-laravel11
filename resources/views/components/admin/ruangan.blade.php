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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <table id="ruangan-table" class="w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>Lokasi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @include('components.modals.ruangan.tambah')
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
    
    <!-- Flowbite JS for modals -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    
    <!-- DataTables Modal Handler -->
    <script src="{{ asset('js/datatables-modal.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('#ruangan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ruangan.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
                    {data: 'nama_ruangan', name: 'nama_ruangan', width: '35%'},
                    {data: 'lokasi', name: 'lokasi', width: '40%'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, width: '20%'},
                ],
                responsive: true,
                language: {
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ entri",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                    "sInfoPostFix": "",
                    "sSearch": "Cari:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    }
                },
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                dom: 'lfrtip',
                drawCallback: function() {
                    // Reinitialize Flowbite modals after DataTables draws
                    if (typeof window.initFlowbite === 'function') {
                        window.initFlowbite();
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
