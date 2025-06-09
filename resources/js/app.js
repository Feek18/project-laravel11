import "./bootstrap";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
import "flowbite";
import Calendar from "@toast-ui/calendar";
import "@toast-ui/calendar/dist/toastui-calendar.min.css";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const calendarElement = document.getElementById('calendar');
    
    if (calendarElement) {
        const calendar = new Calendar('#calendar', {
            defaultView: 'month',
            useCreationPopup: true,
            useDetailPopup: true,
            calendars: [
                {
                    id: 'peminjaman',
                    name: 'Peminjaman Ruangan',
                    backgroundColor: '#10B981',
                    borderColor: '#10B981',
                    dragBackgroundColor: '#10B981',
                }
            ]
        });

        // Load peminjaman data if available
        if (typeof window.peminjamanData !== 'undefined') {
            const events = window.peminjamanData.map(peminjaman => {
                return {
                    id: peminjaman.id.toString(),
                    calendarId: 'peminjaman',
                    title: `${peminjaman.ruangan} - ${peminjaman.pengguna}`,
                    body: peminjaman.description,
                    category: 'time',
                    start: new Date(peminjaman.start),
                    end: new Date(peminjaman.end),
                    backgroundColor: peminjaman.backgroundColor,
                    borderColor: peminjaman.borderColor,
                    color: '#FFFFFF',
                };
            });
            
            // Use createEvents instead of createSchedules
            try {
                calendar.createEvents(events);
            } catch (error) {
                console.log('createEvents failed, trying alternative method:', error);
                // Fallback for older versions
                if (calendar.createSchedules) {
                    calendar.createSchedules(events);
                } else {
                    // Manual event creation for compatibility
                    events.forEach(event => {
                        try {
                            calendar.createEvent(event.calendarId, event);
                        } catch (e) {
                            console.log('Event creation failed for:', event, e);
                        }
                    });
                }
            }
        }

        // Event handlers
        calendar.on('beforeCreateEvent', function(event) {
            console.log('Creating new event:', event);
        });

        calendar.on('clickEvent', function(event) {
            const eventData = event.event;
            console.log('Clicked event:', eventData);
            const raw = eventData.raw || {};
        });

        // Fallback for older versions
        calendar.on('clickSchedule', function(event) {
            const schedule = event.schedule;
            const raw = schedule.raw || {};
        });
    }
});

