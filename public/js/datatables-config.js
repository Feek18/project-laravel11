// DataTables common configuration
window.dataTableConfig = {
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
    responsive: true,
    processing: true,
    serverSide: true,
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
    dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4"<"mb-2 sm:mb-0"l><"mb-2 sm:mb-0"f>>rtip',
    drawCallback: function() {
        // Reinitialize Flowbite modals after DataTables draws
        if (typeof window.initFlowbite === 'function') {
            window.initFlowbite();
        }
    }
};

// Common column configuration for index column
window.indexColumn = {
    data: 'DT_RowIndex', 
    name: 'DT_RowIndex', 
    orderable: false, 
    searchable: false,
    width: '5%'
};

// Common column configuration for action column
window.actionColumn = {
    data: 'action', 
    name: 'action', 
    orderable: false, 
    searchable: false,
    width: '15%'
};

// Helper function to initialize DataTable with common config
window.initDataTable = function(tableId, ajaxUrl, columns) {
    const config = Object.assign({}, window.dataTableConfig, {
        ajax: ajaxUrl,
        columns: columns
    });
    
    return $('#' + tableId).DataTable(config);
};
