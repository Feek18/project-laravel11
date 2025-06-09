<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>KelasReady | Website</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
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

    {{-- footer --}}
    @include('layouts.footer')

    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
</body>

</html>
