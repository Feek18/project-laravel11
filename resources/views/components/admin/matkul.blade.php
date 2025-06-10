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

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <table id="matkul-table" class="w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode MataKuliah</th>
                            <th>Nama MataKuliah</th>
                            <th>Semester</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @include('components.modals.matakuliah.tambah')
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
            $('#matkul-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('matkul.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'kode_matkul', name: 'kode_matkul'},
                    {data: 'mata_kuliah', name: 'mata_kuliah'},
                    {data: 'semester', name: 'semester'},
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
