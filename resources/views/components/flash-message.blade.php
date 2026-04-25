@if(session('success') || session('error'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 4000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0 translate-y-[-20px] scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-[-20px] scale-95"
        class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4"
        role="alert"
    >
        @if(session('success'))
            <div class="flex items-center gap-4 bg-white border-r-4 border-primary rounded-2xl shadow-2xl shadow-primary/20 px-5 py-4">
                <!-- Icon -->
                <div class="flex-none w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <!-- Text -->
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-brand-text text-sm">تمّت العملية بنجاح</p>
                    <p class="text-gray-500 text-xs mt-0.5">{{ session('success') }}</p>
                </div>
                <!-- Close Button -->
                <button @click="show = false" class="flex-none text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <!-- Progress Bar -->
                <div class="absolute bottom-0 right-0 left-0 h-1 rounded-b-2xl overflow-hidden">
                    <div class="h-full bg-primary/30 rounded-b-2xl" style="animation: shrink 4s linear forwards;"></div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-4 bg-white border-r-4 border-red-500 rounded-2xl shadow-2xl shadow-red-500/20 px-5 py-4">
                <!-- Icon -->
                <div class="flex-none w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <!-- Text -->
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-brand-text text-sm">حدث خطأ</p>
                    <p class="text-gray-500 text-xs mt-0.5">{{ session('error') }}</p>
                </div>
                <!-- Close Button -->
                <button @click="show = false" class="flex-none text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <!-- Progress Bar -->
                <div class="absolute bottom-0 right-0 left-0 h-1 rounded-b-2xl overflow-hidden">
                    <div class="h-full bg-red-300 rounded-b-2xl" style="animation: shrink 4s linear forwards;"></div>
                </div>
            </div>
        @endif
    </div>

    <style>
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
@endif
