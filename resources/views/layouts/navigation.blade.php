{{--
    =============================================
    شريط التنقل (layouts/navigation.blade.php)
    =============================================

    يُضمن في القالب الأساسي عبر @include('layouts.navigation').
    يستخدم Alpine.js لإدارة فتح/إغلاق القائمة المحمولة.

    الأقسام:
    1. Desktop Nav: الشعار + روابط التنقل + زر إضافة إعلان + قائمة المستخدم
    2. Mobile Nav (Hamburger): نفس المحتوى بتنسيق عمودي
    
    الحالات:
    - @auth: يعرض اسم المستخدم + قائمة (حسابي، تسجيل خروج) + زر إضافة إعلان
    - @guest: يعرض زر "تسجيل الدخول" + زر "حساب جديد"

    يستخدم:
    - x-nav-link: مكون رابط التنقل (مع حالة active)
    - x-dropdown: قائمة منسدلة لإعدادات المستخدم
    - x-responsive-nav-link: رابط للقائمة المحمولة
--}}
<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-50 transition-all duration-300 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="group flex items-center gap-2">
                        <!-- إضافة تأثير الحركة للوجو -->
                        <img src="{{ asset('images/logo.png') }}" alt="مفقودات تعز" class="h-14 w-auto object-contain transform transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1 group-hover:drop-shadow-md">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:flex gap-6">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="font-bold text-brand-text hover:text-primary border-primary transition-colors">
                        الرئيسية
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold text-brand-text hover:text-primary border-primary transition-colors">
                            إعلاناتي
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                @auth
                    <!-- Quick Add Button -->
                    <a href="{{ route('items.create') }}" class="hidden lg:flex items-center gap-2 text-sm font-bold bg-primary text-white hover:bg-primary-dark px-5 py-2.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        إضافة إعلان
                    </a>

                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 shadow-sm text-sm font-bold rounded-full text-brand-text hover:text-primary hover:border-primary-light focus:outline-none transition-all duration-300">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-primary to-primary-light text-white flex items-center justify-center ml-2 text-sm shadow-inner">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>

                                <div class="mr-2">
                                    <svg class="fill-current h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-2">
                                <x-dropdown-link :href="route('profile.edit')" class="rounded-lg hover:bg-brand-bg font-medium flex items-center gap-2 text-brand-text hover:text-primary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    حسابي
                                </x-dropdown-link>

                                <div class="border-t border-gray-100 my-1"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="rounded-lg hover:bg-red-50 text-red-600 font-medium flex items-center gap-2 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        تسجيل الخروج
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-brand-text hover:text-primary px-4 py-2 rounded-xl transition-colors">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white px-6 py-2.5 rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 transition-all duration-300">
                            حساب جديد
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-brand-text hover:text-primary hover:bg-brand-bg focus:outline-none focus:bg-brand-bg focus:text-primary transition duration-300 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-brand-text hover:text-primary hover:bg-brand-bg">
                الرئيسية
            </x-responsive-nav-link>
            @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-brand-text hover:text-primary hover:bg-brand-bg">
                إعلاناتي
            </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 bg-brand-bg/50">
            @auth
                <div class="px-4 mb-3">
                    <div class="font-bold text-base text-brand-text">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('items.create')" class="text-primary font-bold hover:bg-primary/10">
                        إضافة إعلان جديد
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('profile.edit')" class="text-brand-text hover:text-primary hover:bg-white">
                        حسابي
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="text-red-600 hover:text-red-700 hover:bg-red-50 font-bold">
                            تسجيل الخروج
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 pb-3">
                    <x-responsive-nav-link :href="route('login')" class="text-brand-text hover:text-primary">
                        تسجيل الدخول
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" class="text-primary font-bold">
                        حساب جديد
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
