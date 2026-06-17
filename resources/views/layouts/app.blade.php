{{--
    =============================================
    القالب الأساسي للتطبيق (layouts/app.blade.php)
    =============================================

    يُستخدم عبر: <x-app-layout> في جميع الصفحات العامة والمحمية.
    المكون المرتبط: App\View\Components\AppLayout

    === SEO Props (تُمرر من كل صفحة) ===
    - :title         → عنوان الصفحة
    - :description   → وصف الصفحة
    - :meta-robots   → تعليمات لمحركات البحث
    - :canonical-url → الرابط الأساسي
    - :og-type       → نوع Open Graph
    - :og-image      → صورة المشاركة

    === Slots ===
    - header  → عنوان الصفحة (اختياري)
    - schema  → Schema.org JSON-LD مخصص (اختياري)
    - extraHead → محتوى إضافي لـ <head> (اختياري)
    - $slot   → المحتوى الرئيسي
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- ===== SEO: العنوان ===== --}}
        <title>{{ $title }}</title>

        {{-- ===== SEO: وصف الصفحة ===== --}}
        <meta name="description" content="{{ $description }}">

        {{-- ===== SEO: تعليمات محركات البحث ===== --}}
        <meta name="robots" content="{{ $metaRobots }}">

        {{-- ===== SEO: Canonical URL ===== --}}
        <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">

        {{-- ===== SEO: Open Graph (Facebook, WhatsApp, Telegram) ===== --}}
        <meta property="og:site_name" content="منصة مفقودات وموجودات تعز">
        <meta property="og:locale" content="ar_YE">
        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
        <meta property="og:image" content="{{ $ogImage ?? asset('images/logo.png') }}">

        {{-- ===== SEO: Twitter Cards ===== --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $ogImage ?? asset('images/logo.png') }}">

        {{-- ===== SEO: Schema.org JSON-LD (WebSite + Organization) ===== --}}
        {{-- يُبنى بـ PHP لتفادي تعارض @context مع Blade directives --}}
        @php
            $appUrl = config('app.url', 'https://taiz-lost-and-found.onrender.com');
            $logoUrl = asset('images/logo.png');

            $schemaWebsite = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'منصة مفقودات وموجودات تعز',
                'alternateName' => 'Taiz Lost and Found',
                'url' => $appUrl,
                'description' => 'منصة إلكترونية لنشر إعلانات المفقودات والموجودات في محافظة تعز، اليمن',
                'inLanguage' => 'ar',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => $appUrl . '/?search={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ];

            $schemaOrg = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'منصة مفقودات وموجودات تعز',
                'url' => $appUrl,
                'logo' => $logoUrl,
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer service',
                    'availableLanguage' => 'Arabic',
                ],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($schemaWebsite, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        <script type="application/ld+json">{!! json_encode($schemaOrg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>

        {{-- Schema.org إضافي خاص بكل صفحة --}}
        {{ $schema ?? '' }}

        {{-- محتوى إضافي في head (مثل rel=prev/next) --}}
        {{ $extraHead ?? '' }}

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

        {{-- Google Fonts: Cairo --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

        {{-- Vite Assets --}}
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
