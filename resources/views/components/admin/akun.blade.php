    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Akun') }}
            </h2>
        </x-slot>
        {{-- test --}}
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <table id="akun-table" class="w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nama</th>
                                <th>No. Telepon</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @include('components.modals.akun.tambah')
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
            $('#akun-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('akun.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'email', name: 'user.email'},
                    {data: 'nama', name: 'nama'},
                    {data: 'no_telp', name: 'no_telp'},
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
