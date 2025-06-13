<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>KelasReady | Website</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js (required for toast interactivity) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Flowbite -->
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    
    <style>
        /* Fix calendar popup positioning using correct classes */
        .toastui-calendar-floating-layer {
            position: fixed !important;
            left: 50% !important;
            top: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 9998 !important;
            pointer-events: auto !important;
        }
        
        .toastui-calendar-see-more-container {
            width: 280px !important;
            max-height: 300px !important;
            overflow-y: auto !important;
            pointer-events: auto !important;
        }
        
        /* Ensure calendar container positioning */
        #calendar {
            position: relative;
        }
        
        /* Fix pointer events */
        .toastui-calendar-floating-layer * {
            pointer-events: auto !important;
        }
    </style>
</head>

<body class="font-poppins">
    {{-- navbar --}}
    @include('layouts.header')

    {{-- hero section --}}
    @include('components.sections.hero')

    {{-- room section --}}
    @include('components.sections.room')
    {{-- @include('components.modals.detail') --}}

    {{-- about1 & about2 --}}
    @include('components.sections.about')

    {{-- testtimonials --}}
    <section>
        <div class="flex justify-center flex-col items-center lg:mt-36">
            <h1 class="text-[28px] md:text-4xl lg:text-4xl font-semibold">Testimonial Mereka</h1>
            <p class="text-sm md:text-base lg:text-[16px] max-w-3xl text-center">Berikut adalah kesan dan pesan dari
                para pengguna selama memanfaatkan platform ini sebagai alternatif
                fungsionalitas pendukung dalam kegiatan perkuliahan.</p>
        </div>
    </section>

    {{-- toast --}}
    @include('components.toast')

    {{-- footer --}}
    @include('layouts.footer')
    
    <script>
        // Pass combined events data (peminjaman + jadwal) to JavaScript
        window.peminjamanData = @json($allEvents);
        
        // Smooth scrolling and navigation functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Mobile hamburger menu toggle
            const menuToggle = document.querySelector('[data-collapse-toggle="navbar-hamburger"]');
            const navbar = document.getElementById('navbar-hamburger');
            
            if (menuToggle && navbar) {
                menuToggle.addEventListener('click', function() {
                    navbar.classList.toggle('hidden');
                });
            }
            
            // Toggle rooms visibility
            const toggleBtn = document.getElementById('toggle-rooms-btn');
            const additionalRooms = document.getElementById('additional-rooms');
            const toggleText = document.getElementById('toggle-text');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (toggleBtn && additionalRooms) {
                toggleBtn.addEventListener('click', function() {
                    const isHidden = additionalRooms.classList.contains('hidden');
                    
                    if (isHidden) {
                        additionalRooms.classList.remove('hidden');
                        toggleText.textContent = 'Sembunyikan';
                        toggleIcon.style.transform = 'rotate(180deg)';
                        
                        // Smooth scroll to show the additional rooms
                        setTimeout(() => {
                            additionalRooms.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }, 100);
                    } else {
                        additionalRooms.classList.add('hidden');
                        toggleText.textContent = 'Lihat Semua Ruangan';
                        toggleIcon.style.transform = 'rotate(0deg)';
                        
                        // Scroll back to the rooms section
                        document.getElementById('ruangan').scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            }
            
            // Highlight active navigation item based on scroll position
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('nav a[href^="#"]');
            
            function updateActiveNav() {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (pageYOffset >= sectionTop - 200) {
                        current = section.getAttribute('id');
                    }
                });
                
                navLinks.forEach(link => {
                    link.classList.remove('text-blue-400', 'font-semibold');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('text-blue-400', 'font-semibold');
                    }
                });
            }
            
            window.addEventListener('scroll', updateActiveNav);
            updateActiveNav(); // Call once on page load
        });
    </script>
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
</body>

</html>
