import "./bootstrap";
import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
import "flowbite";
import Calendar from "@toast-ui/calendar";
import "@toast-ui/calendar/dist/toastui-calendar.min.css";

import Alpine, { raw } from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Add a simple modal for event details
    const modalHtml = `
        <div id="eventDetailModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
            <div style="background:#fff; padding:24px; border-radius:8px; min-width:300px; max-width:90vw; position:relative;">
                <button id="closeEventDetailModal" style="position:absolute; top:8px; right:8px; background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
                <h2 id="eventDetailTitle"></h2>
                <div id="eventDetailBody"></div>
                <div id="eventDetailTime" style="margin-top:12px; color:#666; font-size:14px;"></div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    const showEventDetailModal = (event) => {
        const raw = event.raw || {};
        
        document.getElementById('eventDetailTitle').textContent = event.title || '';
        
        // Create different content based on event type
        let bodyContent = '';
        if (raw.type === 'jadwal') {
            bodyContent = `
                <div style="margin-bottom: 8px;"><strong>Mata Kuliah:</strong> ${raw.mata_kuliah || 'N/A'}</div>
                <div style="margin-bottom: 8px;"><strong>Semester:</strong> ${raw.semester || 'N/A'}</div>
                <div style="margin-bottom: 8px;"><strong>Ruangan:</strong> ${raw.ruangan || 'N/A'}</div>
                <div style="margin-bottom: 8px;"><strong>Hari:</strong> ${raw.hari || 'N/A'}</div>
                <div style="margin-bottom: 8px; color: #3B82F6;"><strong>Type:</strong> Jadwal Perkuliahan (Recurring)</div>
            `;
        } else {
            bodyContent = `
                <div style="margin-bottom: 8px;"><strong>Keperluan:</strong> ${event.body || 'N/A'}</div>
                <div style="margin-bottom: 8px;"><strong>Peminjam:</strong> ${raw.pengguna || 'N/A'}</div>
                <div style="margin-bottom: 8px;"><strong>Ruangan:</strong> ${raw.ruangan || 'N/A'}</div>
                <div style="margin-bottom: 8px; color: #10B981;"><strong>Type:</strong> Peminjaman Ruangan</div>
            `;
        }
        
        document.getElementById('eventDetailBody').innerHTML = bodyContent;
        
        // Format dates in 24H format
        const formatDate = (date) => {
            if (!date) return '';
            return new Date(date).toLocaleString('en-GB', {
                year: 'numeric',
                month: '2-digit', 
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        };

        
        // Add status information to the modal
        const statusInfo = raw.type === 'jadwal' ? 'Active Schedule' : (raw.status || 'Belum diketahui');
        
        document.getElementById('eventDetailTime').innerHTML = `
            <div>Start: ${formatDate(event.start)}</div>
            <div>End: ${formatDate(event.end)}</div>
            <div style="margin-top:8px; font-weight:bold;">Status: ${statusInfo}</div>
        `;
        document.getElementById('eventDetailModal').style.display = 'flex';
    };
    document.getElementById('closeEventDetailModal').onclick = () => {
        document.getElementById('eventDetailModal').style.display = 'none';
    };

    const calendarElement = document.getElementById('calendar');
    
    if (calendarElement) {
        const calendar = new Calendar('#calendar', {
            defaultView: 'month',
            useCreationPopup: false,
            useDetailPopup: false,
            calendars: [
                {
                    id: 'peminjaman',
                    name: 'Peminjaman Ruangan',
                    backgroundColor: '#10B981',
                    borderColor: '#10B981',
                    dragBackgroundColor: '#10B981',
                },
                {
                    id: 'jadwal',
                    name: 'Jadwal Perkuliahan',
                    backgroundColor: '#3B82F6',
                    borderColor: '#2563EB',
                    dragBackgroundColor: '#3B82F6',
                }
            ],
            // Disable built-in more popup and handle manually
            template: {
                monthMoreTitleDate: function(moreTitle) {
                    return `<span style="color:#333">${moreTitle.ymd}</span>`;
                },
                monthMoreClose: function() {
                    return '<button type="button" style="position:absolute;top:8px;right:8px;background:none;border:none;font-size:16px;cursor:pointer;z-index:10000;">&times;</button>';
                }
            }
        });

        // Load combined event data (peminjaman + jadwal) if available
        // Check for both variable names to support different views
        const eventData = window.allEventsData || window.peminjamanData;
        if (eventData) {
            console.log('Loading combined events data:', eventData);
            const events = eventData.map(eventData => {
                // Determine calendar ID based on event type
                const calendarId = eventData.type === 'jadwal' ? 'jadwal' : 'peminjaman';
                
                // Create appropriate title based on event type
                let title, body;
                if (eventData.type === 'jadwal') {
                    title = `${eventData.matkul || 'Mata Kuliah'} - ${eventData.ruangan}`;
                    body = `Jadwal Perkuliahan: ${eventData.matkul || 'Mata Kuliah'}\nRuangan: ${eventData.ruangan}\nHari: ${eventData.hari}`;
                } else {
                    title = `${eventData.ruangan} - ${eventData.pengguna}`;
                    body = eventData.description;
                }
                
                return {
                    id: eventData.id.toString(),
                    calendarId: calendarId,
                    title: title,
                    body: body,
                    category: 'time',
                    start: new Date(eventData.start),
                    end: new Date(eventData.end),
                    backgroundColor: eventData.backgroundColor,
                    borderColor: eventData.borderColor,
                    color: '#FFFFFF',
                    raw: {
                        status: eventData.status || 'Belum diketahui',
                        type: eventData.type,
                        pengguna: eventData.pengguna,
                        ruangan: eventData.ruangan,
                        recurring: eventData.is_recurring || false,
                        hari: eventData.hari || null,
                        mata_kuliah: eventData.matkul || null,
                        semester: eventData.semester || null
                    },
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

        // Fix for popup positioning - only for floating layers, not interfering with clicks
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1 && node.classList && node.classList.contains('toastui-calendar-floating-layer')) {
                        setTimeout(() => {
                            node.style.position = 'fixed';
                            node.style.left = '50%';
                            node.style.top = '50%';
                            node.style.transform = 'translate(-50%, -50%)';
                            node.style.zIndex = '9998';
                            node.style.pointerEvents = 'auto';
                        }, 0);
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Event handlers
        calendar.on('beforeCreateEvent', function(event) {
            console.log('Creating new event:', event);
        });

        calendar.on('clickEvent', function(event) {
            const eventData = event.event;
            // Show custom modal with event details
            showEventDetailModal(eventData);
        });

        // Fallback for older versions
        calendar.on('clickSchedule', function(event) {
            const schedule = event.schedule;
            const raw = schedule.raw || {};
        });
    }
});

