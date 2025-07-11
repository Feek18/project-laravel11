// Real-time room availability checker
class RoomAvailabilityChecker {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.debounceTimer = null;
        this.isAuthenticated = !!this.csrfToken; // Simple check for authentication
        this.init();
    }

    init() {
        // Initialize availability checker on room booking forms
        this.initAvailabilityChecker();
        this.initScheduleViewer();
    }    initAvailabilityChecker() {
        const form = document.querySelector('#room-booking-form');
        if (!form) return;

        const roomSelect = form.querySelector('[name="id_ruang"]');
        const dateInput = form.querySelector('[name="tanggal_pinjam"]');
        const startTimeInput = form.querySelector('[name="waktu_mulai"]');
        const endTimeInput = form.querySelector('[name="waktu_selesai"]');
        
        if (!roomSelect || !dateInput || !startTimeInput || !endTimeInput) return;

        // Store original button text on initialization
        this.storeOriginalButtonText(form);

        // Add event listeners for real-time checking
        [roomSelect, dateInput, startTimeInput, endTimeInput].forEach(input => {
            input.addEventListener('change', () => this.checkAvailability(form));
            input.addEventListener('input', () => this.debouncedCheck(form));
        });

        // Prevent form submission if there are conflicts
        form.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    storeOriginalButtonText(form) {
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton && submitButton.tagName === 'BUTTON' && !submitButton.dataset.originalText) {
            submitButton.dataset.originalText = submitButton.textContent.trim();
        }
    }

    debouncedCheck(form) {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.checkAvailability(form), 500);
    }    async checkAvailability(form) {
        const formData = new FormData(form);
        const data = {
            id_ruang: formData.get('id_ruang'),
            tanggal_pinjam: formData.get('tanggal_pinjam'),
            waktu_mulai: formData.get('waktu_mulai'),
            waktu_selesai: formData.get('waktu_selesai'),
            exclude_id: formData.get('exclude_id') // For edit forms
        };

        // Check if all required fields are filled
        if (!data.id_ruang || !data.tanggal_pinjam || !data.waktu_mulai || !data.waktu_selesai) {
            this.clearAvailabilityMessage();
            return;
        }        try {
            this.showLoadingMessage();
            
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };
            
            // Only add CSRF token if available
            if (this.csrfToken) {
                headers['X-CSRF-TOKEN'] = this.csrfToken;
            }
            
            const response = await fetch('/api/check-room-availability', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                if (response.status === 401) {
                    this.showAuthenticationMessage();
                    return;
                } else if (response.status === 419) {
                    this.showCSRFMessage();
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            this.displayAvailabilityResult(result);
            
        } catch (error) {
            console.error('Error checking availability:', error);
            this.showErrorMessage('Terjadi kesalahan saat mengecek ketersediaan ruangan.');
        }
    }displayAvailabilityResult(result) {
        const container = this.getOrCreateAvailabilityContainer();
        
        if (result.available) {
            container.innerHTML = this.createAvailableMessage(result.message);
            this.enableSubmitButton();
        } else {
            container.innerHTML = this.createUnavailableMessage(result);
            this.disableSubmitButton(result);
        }
    }

    createAvailableMessage(message) {
        return `
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">Ruangan Tersedia</span>
                <span class="ml-2">${message}</span>
            </div>
        `;
    }

    createUnavailableMessage(result) {
        const conflictType = result.has_approved_conflict ? 'error' : 'warning';
        const iconColor = conflictType === 'error' ? 'text-red-800' : 'text-yellow-800';
        const bgColor = conflictType === 'error' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200';
        
        let conflictDetails = '';
        if (result.conflicting_bookings && result.conflicting_bookings.length > 0) {
            conflictDetails = `
                <div class="mt-2">
                    <p class="font-medium mb-1">Konflik dengan peminjaman:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        ${result.conflicting_bookings.map(booking => `
                            <li>${booking.pengguna} (${booking.waktu_mulai} - ${booking.waktu_selesai}) - ${booking.status}</li>
                        `).join('')}
                    </ul>
                </div>
            `;
        }

        return `
            <div class="${bgColor} ${iconColor} px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">Ruangan Tidak Tersedia</span>
                </div>
                <p class="mt-1">${result.message}</p>
                ${conflictDetails}
            </div>
        `;
    }

    showLoadingMessage() {
        const container = this.getOrCreateAvailabilityContainer();
        container.innerHTML = `
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg flex items-center">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-800 mr-2"></div>
                <span>Mengecek ketersediaan ruangan...</span>
            </div>
        `;
    }    showErrorMessage(message) {
        const container = this.getOrCreateAvailabilityContainer();
        container.innerHTML = `
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;
    }

    showAuthenticationMessage() {
        const container = this.getOrCreateAvailabilityContainer();
        container.innerHTML = `
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <span class="font-medium">Silakan login untuk mengecek ketersediaan ruangan secara real-time.</span>
                    <p class="mt-1 text-sm">Anda masih dapat melakukan pemesanan, namun sistem akan mengecek konflik saat proses konfirmasi.</p>
                </div>
            </div>
        `;
        this.enableSubmitButton(); // Allow submission for guests
    }

    showCSRFMessage() {
        const container = this.getOrCreateAvailabilityContainer();
        container.innerHTML = `
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <span class="font-medium">Session expired. Silakan refresh halaman.</span>
                    <button onclick="window.location.reload()" class="ml-2 text-sm underline hover:no-underline">Refresh</button>
                </div>
            </div>
        `;
        this.enableSubmitButton(); // Allow submission, let backend handle CSRF
    }clearAvailabilityMessage() {
        const container = document.getElementById('availability-checker-result');
        if (container) {
            container.innerHTML = '';
        }
        console.log('Clearing availability message and enabling submit button');
        this.enableSubmitButton(); // Re-enable when clearing
    }    enableSubmitButton() {
        const form = document.querySelector('#room-booking-form');
        if (!form) return;

        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            submitButton.disabled = false;
            
            // Remove custom disabled class and restore original styling
            submitButton.classList.remove('room-availability-disabled');
            submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
            
            // Reset button text if it was changed for any conflict type
            if (submitButton.tagName === 'BUTTON' && 
                (submitButton.textContent.includes('Tidak Tersedia') || 
                 submitButton.textContent.includes('Ada Konflik') ||
                 submitButton.textContent.includes('❌') ||
                 submitButton.textContent.includes('⚠️'))) {
                const originalText = submitButton.dataset.originalText || 'Submit';
                submitButton.textContent = originalText;
            }
        }
    }disableSubmitButton(result) {
        const form = document.querySelector('#room-booking-form');
        if (!form) return;

        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            console.log('Disabling submit button. Current text:', submitButton.textContent);
            
            // Store original text if not already stored
            if (!submitButton.dataset.originalText && submitButton.tagName === 'BUTTON') {
                submitButton.dataset.originalText = submitButton.textContent.trim();
                console.log('Storing original text:', submitButton.dataset.originalText);
            }

            submitButton.disabled = true;
            
            // Use custom CSS class instead of inline Tailwind classes
            submitButton.classList.add('room-availability-disabled');
            submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'bg-green-600', 'hover:bg-green-700');
            
            // Update button text based on conflict type
            if (submitButton.tagName === 'BUTTON') {
                let newText;
                if (result.has_approved_conflict) {
                    newText = '❌ Ruangan Tidak Tersedia';
                } else if (result.has_pending_conflict) {
                    newText = '⚠️ Ada Konflik Pending';
                }
                
                if (newText) {
                    console.log('Changing button text to:', newText);
                    submitButton.textContent = newText;
                }
            }
        }
    }

    handleFormSubmit(event) {
        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        
        // Check if submit button is disabled due to conflicts
        if (submitButton && submitButton.disabled) {
            event.preventDefault();
            
            // Show additional warning
            this.showSubmitWarning();
            return false;
        }

        // Allow submission if no conflicts detected
        return true;
    }

    showSubmitWarning() {
        const container = this.getOrCreateAvailabilityContainer();
        const warningDiv = document.createElement('div');
        warningDiv.className = 'mt-2 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg flex items-center animate-pulse';
        warningDiv.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">Tidak dapat submit! Silakan pilih waktu lain yang tidak berkonflik.</span>
        `;
        
        // Remove existing warning if any
        const existingWarning = container.querySelector('.animate-pulse');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        container.appendChild(warningDiv);
        
        // Remove warning after 5 seconds
        setTimeout(() => {
            if (warningDiv.parentNode) {
                warningDiv.remove();
            }
        }, 5000);
    }

    getOrCreateAvailabilityContainer() {
        let container = document.getElementById('availability-checker-result');
        
        if (!container) {
            container = document.createElement('div');
            container.id = 'availability-checker-result';
            container.className = 'mt-4';
            
            // Try to insert after the form fields
            const form = document.querySelector('#room-booking-form');
            if (form) {
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.parentNode.insertBefore(container, submitButton);
                } else {
                    form.appendChild(container);
                }
            }
        }
        
        return container;
    }

    async initScheduleViewer() {
        // Add schedule viewing functionality if needed
        const scheduleButtons = document.querySelectorAll('[data-action="view-schedule"]');
        scheduleButtons.forEach(button => {
            button.addEventListener('click', (e) => this.showRoomSchedule(e.target));
        });
    }

    async showRoomSchedule(button) {
        const roomId = button.dataset.roomId;
        const date = button.dataset.date || new Date().toISOString().split('T')[0];
        
        try {
            const response = await fetch(`/api/room-schedule?id_ruang=${roomId}&tanggal_pinjam=${date}`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            this.displayScheduleModal(result);
            
        } catch (error) {
            console.error('Error fetching schedule:', error);
        }
    }

    displayScheduleModal(scheduleData) {
        // Create and show schedule modal (implementation depends on your modal system)
        console.log('Room Schedule:', scheduleData);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new RoomAvailabilityChecker();
});

// Export for use in other scripts
window.RoomAvailabilityChecker = RoomAvailabilityChecker;
