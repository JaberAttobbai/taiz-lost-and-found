<x-guest-layout>
    <div dir="rtl" class="text-right">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">استعادة كلمة المرور</h2>
        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ __('هل نسيت كلمة المرور الخاصة بك؟ لا توجد مشكلة. فقط أدخل عنوان بريدك الإلكتروني أدناه، وسنقوم بإرسال رابط يتيح لك تعيين كلمة مرور جديدة بكل سهولة.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 font-bold text-green-600" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('البريد الإلكتروني')" class="text-lg font-semibold" />
                <x-text-input id="email" class="block mt-2 w-full text-left" dir="ltr" type="email" name="email" :value="old('email')" required autofocus placeholder="example@gmail.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    {{ __('إرسال رابط الاسترجاع عبر الإيميل') }}
                </button>
            </div>
            
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold underline">
                    العودة لصفحة تسجيل الدخول
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
