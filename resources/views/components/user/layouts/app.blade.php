<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/sweetalert-custom.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins antialiased bg-[#F5F6FB]">
    <div class="min-h-screen">
        @include('components.user.layouts.navigation')

        <!-- Page Heading -->
        {{-- @isset($header)
            <header>
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset --}}

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @include('components.toast')

    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
    
    <!-- SweetAlert Helper -->
    <script src="{{ asset('js/sweetalert-helper.js') }}"></script>
    <script src="{{ asset('js/session-messages.js') }}"></script>
    
    <!-- Pass Laravel session messages to JavaScript -->
    <script>
        @if(session('success'))
            window.sessionSuccess = @json(session('success'));
        @endif
        @if(session('error'))
            window.sessionError = @json(session('error'));
        @endif
        @if(session('warning'))
            window.sessionWarning = @json(session('warning'));
        @endif
        @if(session('info'))
            window.sessionInfo = @json(session('info'));
        @endif
    </script>

    @stack('scripts')

</body>

</html>
