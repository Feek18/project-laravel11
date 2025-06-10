/**
 * DataTables Dynamic Modal Handler
 * Handles AJAX loading of edit modals for DataTables
 */

// Global function to load edit modal dynamically
function loadEditModal(type, id) {
    const modalContainer = document.getElementById('dynamic-modal-container');
    
    if (!modalContainer) {
        console.error('Dynamic modal container not found. Make sure to add <div id="dynamic-modal-container"></div> to your view.');
        return;
    }
    
    // Show loading state
    modalContainer.innerHTML = '<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center"><div class="bg-white p-4 rounded-lg shadow-lg"><div class="text-center">Loading...</div></div></div>';
    
    // Fetch modal content via AJAX
    fetch(`/${type}/${id}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        // Insert modal HTML
        modalContainer.innerHTML = html;
        
        // Initialize Flowbite modal
        let modalElement = modalContainer.querySelector('[id^="edit-modal-"]') || 
                          modalContainer.querySelector('[id^="matkul-edit-modal-"]') ||
                          modalContainer.querySelector('[id^="edit-jadwal-modal"]');
        
        if (modalElement) {
            // Import Flowbite Modal if available
            if (typeof Modal !== 'undefined') {
                const modalInstance = new Modal(modalElement);
                modalInstance.show();
                
                // Set up close handlers
                const closeButtons = modalElement.querySelectorAll('[data-modal-hide]');
                closeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        modalInstance.hide();
                        setTimeout(() => {
                            modalContainer.innerHTML = '';
                        }, 300);
                    });
                });
                
                // Close on backdrop click
                modalElement.addEventListener('click', function(e) {
                    if (e.target === modalElement) {
                        modalInstance.hide();
                        setTimeout(() => {
                            modalContainer.innerHTML = '';
                        }, 300);
                    }
                });
                
            } else {
                // Fallback if Flowbite is not available
                modalElement.classList.remove('hidden');
                modalElement.style.display = 'flex';
                
                // Set up close handlers
                const closeButtons = modalElement.querySelectorAll('[data-modal-hide], .modal-close');
                closeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        modalElement.classList.add('hidden');
                        modalElement.style.display = 'none';
                        setTimeout(() => {
                            modalContainer.innerHTML = '';
                        }, 300);
                    });
                });
            }
        } else {
            console.error('Modal element not found in the loaded HTML');
            modalContainer.innerHTML = '<div class="text-red-500 p-4">Modal element not found</div>';
        }
    })
    .catch(error => {
        console.error('Error loading modal:', error);
        modalContainer.innerHTML = `
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <div class="text-red-500">Error loading modal: ${error.message}</div>
                    <button onclick="this.closest('.fixed').remove()" class="mt-2 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Close</button>
                </div>
            </div>
        `;
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DataTables Modal Handler loaded');
});
