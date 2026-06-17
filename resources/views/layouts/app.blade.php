{{--
    =============================================
    القالب الأساسي للتطبيق (layouts/app.blade.php)
    =============================================

    يُستخدم عبر: <x-app-layout> في جميع الصفحات العامة والمحمية.
    المكون المرتبط: App\View\Components\AppLayout

    البنية:
    1. <head>: SEO meta tags + Schema.org + Google Fonts (Cairo) + Vite assets
    2. Navigation: شريط التنقل (من layouts/navigation.blade.php)
    3. Header (اختياري): يظهر إذا مُرر slot "header" من الصفحة الفرعية
    4. Main: المحتوى الرئيسي ($slot الافتراضي)
    5. Flash Message: إشعارات النجاح/الخطأ
    6. Confirm Modal: نافذة تأكيد عامة للعمليات الحساسة

    الاتجاه: RTL (من اليمين لليسار) — dir="rtl"
    الخط: Cairo (عربي)
    الألوان: معرفة في app.css

    === نظام SEO ===
    - @section('title')         → عنوان الصفحة (يُعرض في tab المتصفح + نتائج Google)
    - @section('description')   → وصف الصفحة (يظهر أسفل العنوان في Google)
    - @section('meta_robots')   → تعليمات لمحركات البحث (index/noindex)
    - @section('og_image')      → صورة عند المشاركة على فيسبوك/واتساب
    - @section('og_type')       → نوع المحتوى (website/article)
    - @section('schema')        → Schema.org JSON-LD مخصص لكل صفحة
    - @section('extra_head')    → أي meta tags إضافية خاصة بصفحة معينة
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- ===== SEO: العنوان ===== --}}
        {{-- كل صفحة تمرر عنوانها الخاص عبر @section('title') --}}
        <title>@yield('title', 'منصة مفقودات وموجودات تعز — ابحث عن مفقوداتك في تعز')</title>

        {{-- ===== SEO: وصف الصفحة ===== --}}
        <meta name="description" content="@yield('description', 'منصة مفقودات وموجودات تعز — وجهتك الأولى والأكثر أماناً للبحث عن مفقوداتك أو الإعلان عما وجدته في محافظة تعز. ابحث، أعلن، وتواصل مباشرة.')">

        {{-- ===== SEO: تعليمات محركات البحث ===== --}}
        {{-- الافتراضي: السماح بالفهرسة. الصفحات الخاصة تتجاوز بـ noindex --}}
        <meta name="robots" content="@yield('meta_robots', 'index, follow')">

        {{-- ===== SEO: Canonical URL ===== --}}
        {{-- يمنع المحتوى المكرر — يخبر Google بالرابط الأصلي للصفحة --}}
        {{-- url()->current() يُرجع الرابط بدون query parameters --}}
        <link rel="canonical" href="@yield('canonical_url', url()->current())">

        {{-- ===== SEO: Open Graph (Facebook, WhatsApp, Telegram) ===== --}}
        <meta property="og:site_name" content="منصة مفقودات وموجودات تعز">
        <meta property="og:locale" content="ar_YE">
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:title" content="@yield('title', 'منصة مفقودات وموجودات تعز')">
        <meta property="og:description" content="@yield('description', 'منصة مفقودات وموجودات تعز — وجهتك الأولى للبحث عن مفقوداتك أو الإعلان عما وجدته في محافظة تعز.')">
        <meta property="og:url" content="@yield('canonical_url', url()->current())">
        <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">

        {{-- ===== SEO: Twitter Cards ===== --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', 'منصة مفقودات وموجودات تعز')">
        <meta name="twitter:description" content="@yield('description', 'منصة مفقودات وموجودات تعز — وجهتك الأولى للبحث عن مفقوداتك أو الإعلان عما وجدته في محافظة تعز.')">
        <meta name="twitter:image" content="@yield('og_image', asset('images/logo.png'))">

        {{-- ===== SEO: Schema.org JSON-LD الافتراضي (WebSite + Organization) ===== --}}
        {{-- يظهر في جميع الصفحات. الصفحات الفردية تضيف schema إضافي --}}
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "منصة مفقودات وموجودات تعز",
          "alternateName": "Taiz Lost and Found",
          "url": "{{ config('app.url', 'https://taiz-lost-and-found.onrender.com') }}",
          "description": "منصة إلكترونية لنشر إعلانات المفقودات والموجودات في محافظة تعز، اليمن",
          "inLanguage": "ar",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ config('app.url', 'https://taiz-lost-and-found.onrender.com') }}/?search={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        }
        </script>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "منصة مفقودات وموجودات تعز",
          "url": "{{ config('app.url', 'https://taiz-lost-and-found.onrender.com') }}",
          "logo": "{{ asset('images/logo.png') }}",
          "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service",
            "availableLanguage": "Arabic"
          }
        }
        </script>

        {{-- Schema.org إضافي خاص بكل صفحة (مثل Article للإعلانات) --}}
        @yield('schema')

        {{-- ===== SEO: Pagination Links ===== --}}
        {{-- تُضاف من صفحات تحتوي pagination (index) --}}
        @yield('extra_head')

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

        {{-- Google Fonts: Cairo (خط عربي) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

        {{-- Vite Assets (CSS + JS) --}}
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
