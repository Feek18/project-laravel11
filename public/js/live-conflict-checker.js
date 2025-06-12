/**
 * Live Conflict Checker
 * Real-time validation for room booking conflicts
 */

class LiveConflictChecker {
    constructor(options = {}) {
        this.options = {
            checkUrl: '/api/check-conflicts',
            scheduleUrl: '/api/room-schedule-detailed',
            availableSlotsUrl: '/api/available-slots',
            debounceMs: 500,
            autoCheck: true,
            showSuggestions: true,
            ...options
        };
        
        this.isChecking = false;
        this.lastCheck = null;
        this.debounceTimer = null;
        
        this.init();
    }

    init() {
        this.setupCSRFToken();
        this.bindEvents();
        this.createStatusElements();
    }

    setupCSRFToken() {
        // Get CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            this.csrfToken = token.getAttribute('content');
        }
    }    bindEvents() {
        // Find form fields
        this.fields = {
            id_ruang: document.querySelector('[name="id_ruang"]'),
            tanggal: document.querySelector('[name="tanggal"]') || document.querySelector('[name="tanggal_pinjam"]'),
            waktu_mulai: document.querySelector('[name="waktu_mulai"]') || document.querySelector('[name="jam_mulai"]'),
            waktu_selesai: document.querySelector('[name="waktu_selesai"]') || document.querySelector('[name="jam_selesai"]'),
            type: document.querySelector('[name="booking_type"]'), // Hidden field to specify type
            hari: document.querySelector('[name="hari"]') // For jadwal only
        };

        // Bind change events
        Object.values(this.fields).forEach(field => {
            if (field) {
                field.addEventListener('change', () => this.scheduleCheck());
                field.addEventListener('input', () => this.scheduleCheck());
            }
        });

        // Detect booking type from URL or form
        this.bookingType = this.detectBookingType();
    }

    detectBookingType() {
        // Check if type field exists
        if (this.fields.type && this.fields.type.value) {
            return this.fields.type.value;
        }

        // Detect from URL
        const path = window.location.pathname;
        if (path.includes('jadwal')) {
            return 'jadwal';
        } else if (path.includes('peminjam') || path.includes('pemesanan')) {
            return 'peminjaman';
        }

        return 'peminjaman'; // Default
    }    createStatusElements() {
        // Create conflict status container
        const form = document.querySelector('form');
        if (!form) return;

        // Check if a specific status container already exists (e.g., in modals)
        let statusContainer = document.getElementById('jadwal-conflict-status') || 
                             document.getElementById('peminjaman-conflict-status') ||
                             document.getElementById('conflict-status');
        
        if (!statusContainer) {
            // Create new container if none exists
            statusContainer = document.createElement('div');
            statusContainer.id = 'conflict-status';
            statusContainer.className = 'mt-4';
            
            // Insert before submit button
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.parentNode.insertBefore(statusContainer, submitButton);
            } else {
                form.appendChild(statusContainer);
            }
        }

        this.statusContainer = statusContainer;
        console.log('Status container set:', statusContainer.id);
    }

    scheduleCheck() {
        if (!this.options.autoCheck) return;

        // Clear existing timer
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }

        // Schedule new check
        this.debounceTimer = setTimeout(() => {
            this.checkConflicts();
        }, this.options.debounceMs);
    }

    async checkConflicts() {
        // Validate required fields
        if (!this.validateFields()) {
            this.clearStatus();
            return;
        }

        // Prevent multiple simultaneous checks
        if (this.isChecking) return;

        this.isChecking = true;
        this.showCheckingStatus();        try {
            const formData = this.getFormData();
            console.log('Sending conflict check data:', formData);
            const response = await this.makeRequest(this.options.checkUrl, 'POST', formData);
            
            if (response.ok) {
                const data = await response.json();
                console.log('Conflict check response:', data);
                this.displayResults(data);
            } else {
                console.error('Conflict check failed with status:', response.status);
                this.showError('Failed to check conflicts. Please try again.');
            }
        } catch (error) {
            console.error('Conflict check error:', error);
            this.showError('Network error while checking conflicts.');
        } finally {
            this.isChecking = false;
        }
    }    validateFields() {
        // For jadwal, we don't need tanggal - only hari (day) matters
        if (this.bookingType === 'jadwal') {
            const required = ['id_ruang', 'waktu_mulai', 'waktu_selesai'];
            
            for (let fieldName of required) {
                const field = this.fields[fieldName];
                if (!field || !field.value.trim()) {
                    return false;
                }
            }
            
            // For jadwal, check if hari field exists and has value
            const hariField = this.fields.hari;
            if (!hariField || !hariField.value.trim() || hariField.value === 'Pilih hari') {
                return false;
            }
        } else {
            // For peminjaman, we still need tanggal
            const required = ['id_ruang', 'tanggal', 'waktu_mulai', 'waktu_selesai'];
            
            for (let fieldName of required) {
                const field = this.fields[fieldName];
                if (!field || !field.value.trim()) {
                    return false;
                }
            }
        }
        
        return true;
    }    getFormData() {
        const excludeId = this.getExcludeId();
        
        if (this.bookingType === 'jadwal') {
            // For jadwal, we use hari instead of tanggal
            return {
                id_ruang: this.fields.id_ruang.value,
                hari: this.fields.hari.value,
                waktu_mulai: this.fields.waktu_mulai.value,
                waktu_selesai: this.fields.waktu_selesai.value,
                type: this.bookingType,
                exclude_id: excludeId
            };
        } else {
            // For peminjaman, we use tanggal
            return {
                id_ruang: this.fields.id_ruang.value,
                tanggal: this.fields.tanggal.value,
                waktu_mulai: this.fields.waktu_mulai.value,
                waktu_selesai: this.fields.waktu_selesai.value,
                type: this.bookingType,
                exclude_id: excludeId
            };
        }
    }

    getExcludeId() {
        // For edit forms, try to get the ID being edited
        const urlParts = window.location.pathname.split('/');
        const lastPart = urlParts[urlParts.length - 1];
        
        if (lastPart && !isNaN(lastPart)) {
            return parseInt(lastPart);
        }

        // Check for hidden ID field
        const idField = document.querySelector('[name="id"]');
        if (idField && idField.value) {
            return parseInt(idField.value);
        }

        return null;
    }

    async makeRequest(url, method = 'GET', data = null) {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        };

        if (this.csrfToken) {
            options.headers['X-CSRF-TOKEN'] = this.csrfToken;
        }

        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }

        return fetch(url, options);
    }

    showCheckingStatus() {
        if (!this.statusContainer) return;

        this.statusContainer.innerHTML = `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-3"></div>
                    <p class="text-blue-700 text-sm">Checking for conflicts...</p>
                </div>
            </div>
        `;
    }    displayResults(data) {
        if (!this.statusContainer) return;

        if (data.has_conflicts) {
            this.displayConflicts(data);
        } else {
            this.showAvailable(data);
        }
    }showConflicts(data) {
        // Get current form values for overlap calculation
        const currentStart = this.fields.waktu_mulai?.value || this.fields.jam_mulai?.value;
        const currentEnd = this.fields.waktu_selesai?.value || this.fields.jam_selesai?.value;
        
        const conflictsList = data.conflicts.map(conflict => {
            // Calculate exact overlap period
            const overlapInfo = this.calculateOverlap(currentStart, currentEnd, conflict.time);
            
            // For jadwal conflicts, show recurring information
            const recurringInfo = conflict.recurring ? 
                `<div class="mt-2 p-2 bg-orange-50 border border-orange-200 rounded">
                    <div class="text-xs text-orange-700 font-medium mb-1">🔄 Recurring Schedule:</div>
                    <div class="text-xs text-orange-600">This is a permanent class schedule that occurs every <strong>${conflict.day}</strong></div>
                </div>` : '';
            
            return `
                <div class="bg-red-50 border border-red-200 rounded p-3 mb-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold text-red-800">${conflict.type}</h4>
                                    <p class="text-sm font-medium text-red-700">${conflict.title}</p>
                                </div>
                                ${conflict.status ? `<span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-medium">${conflict.status}</span>` : ''}
                            </div>
                            
                            <div class="space-y-2">
                                <div class="bg-white border border-red-200 rounded p-2">
                                    <div class="text-xs text-red-600 font-medium mb-1">⏰ Time Conflict Details:</div>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div>
                                            <span class="text-gray-600">Your time:</span><br>
                                            <span class="font-mono text-red-700">${currentStart} - ${currentEnd}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Existing booking:</span><br>
                                            <span class="font-mono text-red-700">${conflict.time}</span>
                                        </div>
                                    </div>
                                    ${overlapInfo ? `
                                        <div class="mt-2 pt-2 border-t border-red-200">
                                            <span class="text-red-600 font-medium text-xs">🔄 Overlap period: </span>
                                            <span class="font-mono text-red-800 text-xs">${overlapInfo}</span>
                                        </div>
                                    ` : ''}
                                </div>
                                
                                ${recurringInfo}
                                
                                ${conflict.details ? `
                                    <div class="text-xs text-red-600">
                                        <span class="font-medium">Details:</span> ${conflict.details}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');const suggestionsHtml = this.options.showSuggestions && data.suggestions && data.suggestions.length > 0 
            ? this.createSuggestionsHtml(data.suggestions)
            : '';

        this.statusContainer.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start mb-4">
                    <div class="w-7 h-7 bg-red-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-red-800 text-lg">⚠️ Konflik Terdeteksi</h3>
                        <p class="text-red-600 text-sm mt-1">Waktu yang dipilih bertabrakan dengan ${data.conflicts.length} pemesanan yang sudah ada${data.conflicts.length > 1 ? 's' : ''}:</p>
                    </div>
                </div>                <div class="space-y-3 mb-4">
                    ${conflictsList}
                </div>
                
                ${suggestionsHtml}
            </div>
        `;

        // Disable submit button
        this.toggleSubmitButton(false);
    }    displayConflicts(data) {
        if (!this.statusContainer) return;

        // Check if we have conflicts in the new format (for jadwal) or old format (for peminjaman)
        const hasJadwalConflicts = data.jadwal_conflicts && data.jadwal_conflicts.length > 0;
        const hasPeminjamanConflicts = data.peminjaman_conflicts && data.peminjaman_conflicts.length > 0;
        const hasLegacyConflicts = data.conflicts && data.conflicts.length > 0;

        if (!hasJadwalConflicts && !hasPeminjamanConflicts && !hasLegacyConflicts) {
            this.displayNoConflicts();
            return;
        }

        const currentStart = this.fields.waktu_mulai.value;
        const currentEnd = this.fields.waktu_selesai.value;
        
        let conflictsHtml = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-red-800 font-semibold">Time Conflict Detected!</h3>
                </div>
                
                <div class="bg-red-100 border border-red-200 rounded p-3">
                    <div class="text-sm text-red-700 mb-2">
                        <strong>Your requested time:</strong> <span class="font-mono">${currentStart} - ${currentEnd}</span>
                    </div>
        `;

        // Handle jadwal conflicts
        if (hasJadwalConflicts) {
            conflictsHtml += `
                <div class="mb-3">
                    <h4 class="text-red-800 font-medium mb-2">⚠️ Conflicting Class Schedules (Recurring):</h4>
                    <div class="space-y-2">
            `;
            
            data.jadwal_conflicts.forEach(conflict => {
                const overlapInfo = this.calculateOverlap(currentStart, currentEnd, conflict.jam_mulai, conflict.jam_selesai);
                conflictsHtml += `
                    <div class="bg-red-100 border border-red-300 rounded p-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-medium text-red-800">${conflict.matkul?.mata_kuliah || 'Class Schedule'}</div>
                                <div class="text-sm text-red-700">Every ${conflict.hari.charAt(0).toUpperCase() + conflict.hari.slice(1)}</div>
                                <div class="text-sm text-red-600">Time: ${conflict.jam_mulai} - ${conflict.jam_selesai}</div>
                                ${overlapInfo ? `<div class="text-xs text-red-600 mt-1">Overlap: ${overlapInfo}</div>` : ''}
                            </div>
                            <span class="bg-red-500 text-black text-xs px-2 py-1 rounded">Recurring</span>
                        </div>
                    </div>
                `;
            });
            
            conflictsHtml += `</div></div>`;
        }

        // Handle peminjaman conflicts
        if (hasPeminjamanConflicts) {
            conflictsHtml += `
                <div class="mb-3">
                    <h4 class="text-red-800 font-medium mb-2">⚠️ Conflicting Room Bookings:</h4>
                    <div class="space-y-2">
            `;
            
            data.peminjaman_conflicts.forEach(conflict => {
                const overlapInfo = this.calculateOverlap(currentStart, currentEnd, conflict.waktu_mulai, conflict.waktu_selesai);
                conflictsHtml += `
                    <div class="bg-red-100 border border-red-300 rounded p-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-medium text-red-800">${conflict.keperluan || 'Room Booking'}</div>
                                <div class="text-sm text-red-700">Date: ${conflict.tanggal_pinjam}</div>
                                <div class="text-sm text-red-600">Time: ${conflict.waktu_mulai} - ${conflict.waktu_selesai}</div>
                                <div class="text-sm text-red-600">Booked by: ${conflict.pengguna?.nama || 'Unknown'}</div>
                                ${overlapInfo ? `<div class="text-xs text-red-600 mt-1">Overlap: ${overlapInfo}</div>` : ''}
                            </div>
                            <span class="bg-orange-500 text-black text-xs px-2 py-1 rounded">${conflict.status_persetujuan}</span>
                        </div>
                    </div>
                `;
            });
            
            conflictsHtml += `</div></div>`;
        }

        // Handle legacy conflicts (for peminjaman using old format)

        conflictsHtml += `
                </div>
                
                <div class="text-sm text-red-600">
                </div>
            </div>
        `;        this.statusContainer.innerHTML = conflictsHtml;

        // Disable submit button
        this.toggleSubmitButton(false);
    }

    calculateOverlap(start1, end1, start2, end2) {
        if (!start1 || !end1 || !start2 || !end2) return null;
        
        // Convert times to minutes for comparison
        const start1Min = this.timeToMinutes(start1);
        const end1Min = this.timeToMinutes(end1);
        const start2Min = this.timeToMinutes(start2);
        const end2Min = this.timeToMinutes(end2);
        
        // Calculate overlap
        const overlapStart = Math.max(start1Min, start2Min);
        const overlapEnd = Math.min(end1Min, end2Min);
        
        if (overlapStart < overlapEnd) {
            return `${this.minutesToTime(overlapStart)} - ${this.minutesToTime(overlapEnd)}`;
        }
        
        return null;
    }

    showAvailable(data) {
        const scheduleHtml = data.room_schedule.length > 0 
            ? this.createScheduleHtml(data.room_schedule)
            : '<p class="text-gray-500 text-sm">No other bookings on this date.</p>';

        this.statusContainer.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start mb-3">
                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-medium text-green-800">✅ Tidak Ada Konflik</h3>
                        <p class="text-green-600 text-sm mt-1">Waktu yang dipilih tersedia untuk pemesanan.</p>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="font-medium text-gray-800 mb-2">Jadwal Saat Ini:</h4>
                    ${scheduleHtml}
                </div>
            </div>
        `;

        // Enable submit button
        this.toggleSubmitButton(true);
    }

    createSuggestionsHtml(suggestions) {
        if (!suggestions.length) return '';

        const suggestionsList = suggestions.map(suggestion => `
            <div class="bg-yellow-50 border border-yellow-200 rounded p-2 cursor-pointer hover:bg-yellow-100 transition-colors"
                 onclick="liveChecker.applySuggestion('${suggestion.start_time}', '${suggestion.end_time}')">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-yellow-800">${suggestion.start_time} - ${suggestion.end_time}</span>
                    <span class="text-xs text-yellow-600">${suggestion.available_duration}</span>
                </div>
            </div>
        `).join('');

        return `
            <div class="mt-4 pt-4 border-t border-red-200">
                <h4 class="font-medium text-red-800 mb-2">💡 Alternative Time Suggestions:</h4>
                <div class="space-y-2">
                    ${suggestionsList}
                </div>
                <p class="text-xs text-red-600 mt-2">Click on a suggestion to apply it automatically.</p>
            </div>
        `;
    }    createScheduleHtml(schedule) {
        const scheduleList = schedule.map(item => `
            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded border">
                <div>
                    <span class="font-medium text-gray-800">${item.title}</span>
                    <span class="text-sm text-gray-600 ml-2">(${item.type})</span>
                    ${item.is_recurring ? '<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-1">Recurring</span>' : ''}
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-800">${item.start_time} - ${item.end_time}</div>
                    <div class="text-xs text-gray-500">${item.status}</div>
                    ${item.is_recurring ? `<div class="text-xs text-blue-600">Every ${item.day}</div>` : ''}
                </div>
            </div>
        `).join('');

        return `<div class="space-y-2">${scheduleList}</div>`;
    }

    applySuggestion(startTime, endTime) {
        if (this.fields.waktu_mulai) {
            this.fields.waktu_mulai.value = startTime;
        }
        if (this.fields.waktu_selesai) {
            this.fields.waktu_selesai.value = endTime;
        }

        // Trigger change events
        this.fields.waktu_mulai?.dispatchEvent(new Event('change'));
        this.fields.waktu_selesai?.dispatchEvent(new Event('change'));

        // Re-check conflicts
        setTimeout(() => this.checkConflicts(), 100);
    }

    toggleSubmitButton(enabled) {
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = !enabled;
            if (enabled) {
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.classList.add('hover:bg-blue-800');
            } else {
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                submitButton.classList.remove('hover:bg-blue-800');
            }
        }
    }

    showError(message) {
        if (!this.statusContainer) return;

        this.statusContainer.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <p class="text-red-700 text-sm">${message}</p>
                </div>
            </div>
        `;
    }

    clearStatus() {
        if (this.statusContainer) {
            this.statusContainer.innerHTML = '';
        }
        this.toggleSubmitButton(true);
    }

    displayNoConflicts() {
        if (!this.statusContainer) return;
        
        this.statusContainer.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-green-800 font-semibold">No Conflicts Detected</h3>
                </div>
                <p class="text-green-700 text-sm mt-2">The selected time slot is available for booking.</p>
            </div>
        `;

        // Enable submit button
        this.toggleSubmitButton(true);
    }

    timeToMinutes(time) {
        const [hours, minutes] = time.split(':').map(Number);
        return hours * 60 + minutes;
    }

    minutesToTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}`;
    }

    // Public methods for manual control
    manualCheck() {
        this.checkConflicts();
    }

    reset() {
        this.clearStatus();
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }
    }
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on forms that have room booking fields
    const hasRoomField = document.querySelector('[name="id_ruang"]');
    const hasTimeFields = document.querySelector('[name="waktu_mulai"], [name="jam_mulai"]');
    
    if (hasRoomField && hasTimeFields) {
        window.liveChecker = new LiveConflictChecker({
            autoCheck: true,
            showSuggestions: true,
            debounceMs: 800 // Slightly longer delay to avoid too many requests
        });
    }
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LiveConflictChecker;
}
