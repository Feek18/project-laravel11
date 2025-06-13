/**
 * SweetAlert Helper Functions
 * Contains reusable SweetAlert configurations for the application
 */

// Initialize delete confirmation for DataTables
function initializeDeleteConfirmation() {
    // Handle delete buttons with class 'btn-delete'
    $(document).off('click', '.btn-delete').on('click', '.btn-delete', function(e) {
        e.preventDefault();
        
        const form = $(this).closest('form');
        const itemName = $(this).data('item-name') || 'item ini';
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'swal2-popup-custom',
                title: 'swal2-title-custom',
                content: 'swal2-content-custom',
                confirmButton: 'swal2-confirm-custom',
                cancelButton: 'swal2-cancel-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang memproses permintaan Anda',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    customClass: {
                        popup: 'swal2-popup-custom'
                    }
                });
                
                // Submit the form
                form.submit();
            }
        });
    });
    
    // Re-initialize after DataTables redraw
    if (typeof $.fn.DataTable !== 'undefined') {
        $(document).on('draw.dt', function() {
            // Small delay to ensure DOM is ready
            setTimeout(function() {
                initializeDeleteConfirmation();
            }, 100);
        });
    }
}

// Success notification after successful deletion
function showDeleteSuccess(message = 'Data berhasil dihapus!') {
    Swal.fire({
        title: 'Berhasil!',
        text: message,
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

// Error notification
function showDeleteError(message = 'Terjadi kesalahan saat menghapus data!') {
    Swal.fire({
        title: 'Error!',
        text: message,
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

// Generic confirmation dialog
function confirmAction(options = {}) {
    const defaultOptions = {
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        reverseButtons: true
    };
    
    const mergedOptions = { ...defaultOptions, ...options };
    
    return Swal.fire(mergedOptions);
}

// Logout confirmation function
function confirmLogout(formId = 'logout-form') {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            title: 'swal2-title-custom',
            content: 'swal2-content-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Logging out...',
                text: 'Sedang memproses permintaan Anda',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: {
                    popup: 'swal2-popup-custom'
                }
            });
            
            // Submit the logout form
            const form = document.getElementById(formId);
            if (form) {
                form.submit();
            }
        }
    });
}

// Logout confirmation
function confirmLogout(formId = 'logout-form') {
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            title: 'swal2-title-custom',
            content: 'swal2-content-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Logout...',
                text: 'Sedang memproses logout',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: {
                    popup: 'swal2-popup-custom'
                }
            });
            
            // Submit the logout form
            document.getElementById(formId).submit();
        }
    });
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is available
    if (typeof $ !== 'undefined') {
        initializeDeleteConfirmation();
    } else {
        // Wait for jQuery to load if it's not available yet
        const checkJQuery = setInterval(function() {
            if (typeof $ !== 'undefined') {
                clearInterval(checkJQuery);
                initializeDeleteConfirmation();
            }
        }, 100);
    }
});

// Also initialize on jQuery ready if it's already loaded
if (typeof $ !== 'undefined') {
    $(document).ready(function() {
        initializeDeleteConfirmation();
    });
}

// Export functions for global use
window.showDeleteSuccess = showDeleteSuccess;
window.showDeleteError = showDeleteError;
window.confirmAction = confirmAction;
window.confirmLogout = confirmLogout;
