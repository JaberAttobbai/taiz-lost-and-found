<x-guest-layout>
    <div dir="rtl" class="text-right">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">تأكيد البريد الإلكتروني</h2>
        <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ __('شكراً لتسجيلك! قبل البدء، يرجى تأكيد بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه إليك. إذا لم تستلم الرسالة، يمكننا إرسالها مرة أخرى.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-bold text-sm text-green-600">
                {{ __('تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني المسجل.') }}
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                        {{ __('إعادة إرسال رابط التحقق') }}
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-semibold">
                    {{ __('تسجيل الخروج') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
