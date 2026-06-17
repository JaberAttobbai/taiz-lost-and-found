{{-- === SEO: منع فهرسة صفحة الملف الشخصي === --}}
@section('meta_robots', 'noindex, nofollow')
@section('title', 'الملف الشخصي — منصة مفقودات تعز')

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <h2 class="font-bold text-2xl text-brand-text leading-tight">
                الملف الشخصي
            </h2>
        </div>
    </x-slot>

    <div class="py-12 relative z-10 bg-brand-bg min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Update Profile Information -->
            <div class="bg-white overflow-hidden shadow-xl shadow-primary/5 sm:rounded-3xl border border-gray-100 p-8 sm:p-10">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white overflow-hidden shadow-xl shadow-primary/5 sm:rounded-3xl border border-gray-100 p-8 sm:p-10">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User -->
            <div class="bg-white overflow-hidden shadow-xl shadow-red-500/5 sm:rounded-3xl border border-red-100 p-8 sm:p-10">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
