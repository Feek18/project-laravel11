// Real-time room availability checker
class RoomAvailabilityChecker {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.debounceTimer = null;
        this.init();
    }

    init() {
        // Initialize availability checker on room booking forms
        this.initAvailabilityChecker();
        this.initScheduleViewer();
    }

    initAvailabilityChecker() {
        const form = document.querySelector('#room-booking-form');
        if (!form) return;

        const roomSelect = form.querySelector('[name="id_ruang"]');
        const dateInput = form.querySelector('[name="tanggal_pinjam"]');
        const startTimeInput = form.querySelector('[name="waktu_mulai"]');
        const endTimeInput = form.querySelector('[name="waktu_selesai"]');
        
        if (!roomSelect || !dateInput || !startTimeInput || !endTimeInput) return;

        // Add event listeners for real-time checking
        [roomSelect, dateInput, startTimeInput, endTimeInput].forEach(input => {
            input.addEventListener('change', () => this.checkAvailability(form));
            input.addEventListener('input', () => this.debouncedCheck(form));
        });
    }

    debouncedCheck(form) {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => this.checkAvailability(form), 500);
    }

    async checkAvailability(form) {
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
        }

        try {
            this.showLoadingMessage();
            
            const response = await fetch('/api/check-room-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            this.displayAvailabilityResult(result);
            
        } catch (error) {
            console.error('Error checking availability:', error);
            this.showErrorMessage('Terjadi kesalahan saat mengecek ketersediaan ruangan.');
        }
    }

    displayAvailabilityResult(result) {
        const container = this.getOrCreateAvailabilityContainer();
        
        if (result.available) {
            container.innerHTML = this.createAvailableMessage(result.message);
        } else {
            container.innerHTML = this.createUnavailableMessage(result);
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
    }

    showErrorMessage(message) {
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

    clearAvailabilityMessage() {
        const container = document.getElementById('availability-checker-result');
        if (container) {
            container.innerHTML = '';
        }
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
