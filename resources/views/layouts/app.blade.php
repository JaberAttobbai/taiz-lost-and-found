<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="google-site-verification" content="google2ed3bbda07a07420" />

        <title>@yield('title', 'منصة مفقودات وموجودات تعز')</title>
        
        <meta name="description" content="@yield('description', 'منصة مفقودات وموجودات تعز. وجهتك الأولى والأكثر أماناً للبحث عن مفقوداتك أو الإعلان عما وجدته في محافظة تعز.')">
        <link rel="canonical" href="{{ url()->current() }}">

        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "منصة مفقودات وموجودات تعز",
          "url": "https://taiz-lost-and-found.onrender.com/"
        }
        </script>
        
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-brand-text bg-brand-bg">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <x-flash-message />

        <x-confirm-modal />
    </body>
</html>
