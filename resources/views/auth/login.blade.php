<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-brand-text">تسجيل الدخول</h2>
        <p class="text-sm text-gray-500 mt-2">مرحباً بعودتك! يرجى إدخال بياناتك للمتابعة.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold text-sm text-brand-text mb-2">البريد الإلكتروني</label>
            <input id="email" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 font-medium" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block font-bold text-sm text-brand-text">كلمة المرور</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-primary hover:text-primary-dark transition-colors" href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <input id="password" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 font-medium" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary focus:ring-offset-0 w-5 h-5 transition duration-150" name="remember">
                <span class="mr-2 text-sm font-medium text-gray-600">تذكرني في المرات القادمة</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-primary/30 text-white font-bold bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transform transition-all duration-300 hover:-translate-y-0.5 text-lg">
                تسجيل الدخول
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                ليس لديك حساب؟ 
                <a href="{{ route('register') }}" class="font-bold text-primary hover:text-primary-dark transition-colors">
                    سجل الآن
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
