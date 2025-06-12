<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white max-w-7xl mx-auto sm:p-6 lg:p-8 rounded-lg">
            <div class="px-4 sm:px-6 lg:px-0 text-3xl font-semibold text-gray-900 mb-8">
                {{ __('Jadwal Ruangan Kuliah') }}
                <div class="flex justify-end mb-2">
                    <button data-modal-target="jadwal-modal" data-modal-toggle="jadwal-modal" data-modal-backdrop="static"
                        type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 ">Tambah
                        jadwal</button>
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc ml-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <table id="jadwal-table" class="w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>Nama Perkuliahan</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @include('components.modals.jadwal.tambah')
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
            $('#jadwal-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('jadwal.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'ruangan_nama', name: 'ruangan.nama_ruangan'},
                    {data: 'nama_perkuliahan', name: 'nama_perkuliahan'},
                    {data: 'hari', name: 'hari'},
                    {data: 'jam_mulai', name: 'jam_mulai'},
                    {data: 'jam_selesai', name: 'jam_selesai'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                responsive: true,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                },
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
