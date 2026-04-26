<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Taiz-Lost-Found</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-brand-text antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-brand-bg relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 w-full h-96 bg-primary-dark/5" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0 80%);"></div>

            <div class="relative z-10 w-full sm:max-w-md flex flex-col items-center">
                <a href="/" class="mb-8 block transform hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/logo.png') }}" alt="اللوجو" class="w-32 h-auto drop-shadow-md">
                </a>

                <div class="w-full px-8 py-10 bg-white shadow-xl shadow-primary/5 overflow-hidden sm:rounded-3xl border border-gray-100">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
