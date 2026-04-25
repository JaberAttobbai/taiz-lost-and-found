<section class="space-y-6" x-data="{ showDeleteModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <header>
        <h2 class="text-xl font-bold text-red-600">
            حذف الحساب
        </h2>
        <p class="mt-2 text-sm text-gray-500">
            بمجرد حذف حسابك، سيتم حذف جميع الموارد والبيانات (بما فيها الإعلانات) نهائياً. يرجى التفكير جيداً قبل القيام بهذه الخطوة.
        </p>
    </header>

    {{-- Trigger Button --}}
    <button
        type="button"
        @click="showDeleteModal = true"
        class="px-8 py-3 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold rounded-xl transition-colors duration-300 border border-red-100 hover:border-transparent flex items-center gap-2"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        حذف حسابي نهائياً
    </button>

    {{-- Delete Modal --}}
    <div
        x-show="showDeleteModal"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div
            x-show="showDeleteModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="showDeleteModal = false"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        ></div>

        {{-- Modal Card --}}
        <div
            x-show="showDeleteModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 translate-y-4"
            class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10"
        >
            {{-- Header --}}
            <div class="p-8 text-center">
                <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-brand-text mb-2">هل أنت متأكد من حذف حسابك؟</h3>
                <p class="text-gray-500 text-sm leading-relaxed">
                    سيتم حذف جميع بياناتك وإعلاناتك بشكل دائم ولا يمكن التراجع عن هذا الإجراء.
                </p>
            </div>

            {{-- Password Form --}}
            <form method="post" action="{{ route('profile.destroy') }}" class="px-8 pb-8">
                @csrf
                @method('delete')

                <div class="mb-6">
                    <label for="password_delete" class="block font-bold text-sm text-brand-text mb-2">
                        أدخل كلمة المرور لتأكيد الحذف
                    </label>
                    <input
                        id="password_delete"
                        name="password"
                        type="password"
                        class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-500/20 rounded-xl transition-all duration-300"
                        placeholder="••••••••"
                        required
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600 font-medium text-sm" />
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="showDeleteModal = false"
                        class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors"
                    >
                        إلغاء
                    </button>
                    <button
                        type="submit"
                        class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-red-500/30"
                    >
                        تأكيد الحذف
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
