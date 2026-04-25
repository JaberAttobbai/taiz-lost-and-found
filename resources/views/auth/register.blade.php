<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-brand-text">إنشاء حساب جديد</h2>
        <p class="text-sm text-gray-500 mt-2">انضم إلينا الآن لتتمكن من إضافة إعلانات المفقودات والموجودات.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-bold text-sm text-brand-text mb-2">الاسم الكامل</label>
            <input id="name" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="أحمد محمد" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600 font-medium" />
        </div>

        <!-- Phone Number -->
        <div>
            <label for="phone_number" class="block font-bold text-sm text-brand-text mb-2">رقم الهاتف</label>
            <input id="phone_number" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 text-right" dir="ltr" type="text" name="phone_number" :value="old('phone_number')" required autocomplete="tel" placeholder="77X XXX XXX" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-sm text-red-600 font-medium" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold text-sm text-brand-text mb-2">البريد الإلكتروني</label>
            <input id="email" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 text-right" dir="ltr" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 font-medium" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block font-bold text-sm text-brand-text mb-2">كلمة المرور</label>
            <input id="password" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 font-medium" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block font-bold text-sm text-brand-text mb-2">تأكيد كلمة المرور</label>
            <input id="password_confirmation" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600 font-medium" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-primary/30 text-white font-bold bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transform transition-all duration-300 hover:-translate-y-0.5 text-lg">
                إنشاء حساب
            </button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                لديك حساب مسبقاً؟ 
                <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-dark transition-colors">
                    تسجيل الدخول
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
