/**
 * Laravel Session Message Handler for SweetAlert2
 * Automatically displays SweetAlert notifications for Laravel session messages
 */

// Function to show session messages
function handleSessionMessages() {
    // Success messages
    if (window.sessionSuccess) {
        Swal.fire({
            title: 'Berhasil!',
            text: window.sessionSuccess,
            icon: 'success',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    }
    
    // Error messages
    if (window.sessionError) {
        Swal.fire({
            title: 'Error!',
            text: window.sessionError,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    }
    
    // Warning messages
    if (window.sessionWarning) {
        Swal.fire({
            title: 'Peringatan!',
            text: window.sessionWarning,
            icon: 'warning',
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    }
    
    // Info messages
    if (window.sessionInfo) {
        Swal.fire({
            title: 'Informasi',
            text: window.sessionInfo,
            icon: 'info',
            confirmButtonColor: '#17a2b8',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    }
}

// Initialize session message handling
document.addEventListener('DOMContentLoaded', function() {
    handleSessionMessages();
});

// Also handle messages when page is loaded via AJAX (for DataTables)
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        handleSessionMessages();
    });
}
