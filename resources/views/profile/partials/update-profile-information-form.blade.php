<section>
    <header>
        <h2 class="text-xl font-bold text-brand-text">
            معلومات الملف الشخصي
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            قم بتحديث معلومات حسابك ورقم هاتفك والبريد الإلكتروني الخاص بك.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block font-bold text-sm text-brand-text mb-2">الاسم الكامل</label>
            <input id="name" name="name" type="text" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="phone_number" class="block font-bold text-sm text-brand-text mb-2">رقم الهاتف</label>
            <input id="phone_number" name="phone_number" type="text" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 text-right" dir="ltr" value="{{ old('phone_number', $user->phone_number) }}" required autocomplete="tel" />
            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('phone_number')" />
        </div>

        <div>
            <label for="email" class="block font-bold text-sm text-brand-text mb-2">البريد الإلكتروني</label>
            <input id="email" name="email" type="email" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 text-right" dir="ltr" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <p class="text-sm text-yellow-800">
                        لم يتم التحقق من بريدك الإلكتروني بعد.
                        <button form="send-verification" class="font-bold underline text-yellow-700 hover:text-yellow-900 rounded-md focus:outline-none">
                            اضغط هنا لإعادة إرسال رابط التحقق.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-sm text-green-600">
                            تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-300">
                حفظ التعديلات
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-green-600 bg-green-50 px-4 py-2 rounded-lg">
                    تم الحفظ بنجاح.
                </p>
            @endif
        </div>
    </form>
</section>
