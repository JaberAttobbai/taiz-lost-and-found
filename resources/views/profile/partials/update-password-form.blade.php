<section>
    <header>
        <h2 class="text-xl font-bold text-brand-text">
            تحديث كلمة المرور
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            تأكد من استخدام كلمة مرور طويلة وعشوائية للحفاظ على أمان حسابك.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block font-bold text-sm text-brand-text mb-2">كلمة المرور الحالية</label>
            <input id="update_password_current_password" name="current_password" type="password" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600 font-medium text-sm" />
        </div>

        <div>
            <label for="update_password_password" class="block font-bold text-sm text-brand-text mb-2">كلمة المرور الجديدة</label>
            <input id="update_password_password" name="password" type="password" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600 font-medium text-sm" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-bold text-sm text-brand-text mb-2">تأكيد كلمة المرور الجديدة</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600 font-medium text-sm" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-8 py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                تحديث كلمة المرور
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-green-600 bg-green-50 px-4 py-2 rounded-lg">
                    تم التحديث بنجاح.
                </p>
            @endif
        </div>
    </form>
</section>
