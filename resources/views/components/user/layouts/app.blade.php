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

    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @if (session('login_success'))
        <script>
            Toastify({
                text: "✅ Berhasil masuk!",
                duration: 3500,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                close: false,
                style: {
                    background: "linear-gradient(135deg, #28a745, #218838)",
                    color: "#fff",
                    fontWeight: "500",
                    fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                    borderRadius: "18px",
                    padding: "12px 20px",
                    boxShadow: "0 6px 16px rgba(0, 0, 0, 0.15)",
                },
                onClick: function() {
                    console.log("Toast clicked!");
                }
            }).showToast();
        </script>
    @endif

</body>

</html>
