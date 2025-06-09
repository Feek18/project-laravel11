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
    const calendar = new Calendar('#calendar', {
        defaultView: 'month',
        taskView: true,
        scheduleView: true,
        useCreationPopup: true,
        useDetailPopup: true,
    });

    // contoh tambah jadwal
    calendar.createSchedules([
        {
            id: '1',
            calendarId: '1',
            title: 'Meeting',
            category: 'time',
            dueDateClass: '',
            start: new Date(),
            end: new Date(new Date().getTime() + 60 * 60 * 1000),
        },
    ]);
});

