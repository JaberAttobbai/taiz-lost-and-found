<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <h2 class="font-bold text-2xl text-brand-text leading-tight">
                إضافة إعلان جديد
            </h2>
        </div>
    </x-slot>

    <div class="py-12 relative z-10 bg-brand-bg">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl shadow-primary/5 sm:rounded-3xl border border-gray-100">
                <div class="p-8 sm:p-12">
                    
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-xl font-bold text-brand-text mb-2">تفاصيل المفقودات / الموجودات</h3>
                        <p class="text-gray-500 text-sm">يرجى تعبئة النموذج أدناه بأكبر قدر ممكن من الدقة لتسهيل عملية الوصول للعنصر.</p>
                    </div>

                    <form id="create-item-form" method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- Type (Lost or Found) -->
                        <div>
                            <label class="block font-bold text-brand-text mb-4">نوع الإعلان</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="lost" class="peer sr-only" {{ old('type', 'lost') == 'lost' ? 'checked' : '' }}>
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50 transition-all text-center">
                                        <div class="w-12 h-12 mx-auto bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <span class="block font-bold text-lg text-brand-text">فقدت شيئاً</span>
                                        <span class="block text-sm text-gray-500 mt-1">أبحث عن غرض ضائع مني</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="found" class="peer sr-only" {{ old('type') == 'found' ? 'checked' : '' }}>
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-primary peer-checked:bg-primary/5 hover:bg-gray-50 transition-all text-center">
                                        <div class="w-12 h-12 mx-auto bg-primary/10 text-primary rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span class="block font-bold text-lg text-brand-text">عثرت على شيء</span>
                                        <span class="block text-sm text-gray-500 mt-1">وجدت غرضاً وأريد إعادته</span>
                                    </div>
                                </label>
                            </div>
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('type')" />
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block font-bold text-sm text-brand-text mb-2">العنوان</label>
                            <input id="title" name="title" type="text" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" value="{{ old('title') }}" required autofocus placeholder="مثال: محفظة جلدية سوداء، مفاتيح سيارة تويوتا..." />
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('title')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block font-bold text-sm text-brand-text mb-2">الفئة</label>
                                <select id="category_id" name="category_id" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 cursor-pointer" required>
                                    <option value="" disabled selected>اختر الفئة المناسبة...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('category_id')" />
                            </div>

                            <!-- Neighborhood -->
                            <div>
                                <label for="neighborhood_id" class="block font-bold text-sm text-brand-text mb-2">الحي / المنطقة في تعز</label>
                                <select id="neighborhood_id" name="neighborhood_id" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 cursor-pointer" required>
                                    <option value="" disabled selected>أين فقدته/وجدته؟...</option>
                                    @foreach($neighborhoods as $neighborhood)
                                        <option value="{{ $neighborhood->id }}" {{ old('neighborhood_id') == $neighborhood->id ? 'selected' : '' }}>
                                            {{ $neighborhood->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('neighborhood_id')" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block font-bold text-sm text-brand-text mb-2">التفاصيل الكاملة</label>
                            <p class="text-xs text-gray-500 mb-2">يرجى كتابة أكبر قدر من التفاصيل مثل اللون، العلامات المميزة، متى تم الفقدان/العثور عليه.</p>
                            <textarea id="description" name="description" rows="5" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 resize-y" required placeholder="اكتب التفاصيل هنا...">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('description')" />
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <label for="contact_phone" class="block font-bold text-sm text-brand-text mb-2">رقم هاتف للتواصل (واتساب / اتصال)</label>
                            <input id="contact_phone" name="contact_phone" type="text" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 text-right" dir="ltr" value="{{ old('contact_phone', auth()->user()->phone_number ?? '') }}" required placeholder="7XX XXX XXX" />
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('contact_phone')" />
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block font-bold text-sm text-brand-text mb-2">إرفاق صورة</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:bg-brand-bg transition-colors">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                            <span>اضغط لاختيار صورة</span>
                                            <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pr-1">أو اسحب وأفلت هنا</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF حتى 5MB</p>
                                </div>
                            </div>
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('home') }}" class="px-6 py-3 font-bold text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                إلغاء
                            </a>
                            <button
                                type="button"
                                onclick="askConfirm('create-item-form', {
                                    type: 'success',
                                    title: 'نشر الإعلان؟',
                                    message: 'سيتم نشر إعلانك الآن وسيظهر للجميع على المنصة. هل أنت متأكد من المعلومات المدخلة؟',
                                    confirmText: 'نعم، انشر الإعلان'
                                })"
                                class="px-8 py-3 bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                نشر الإعلان
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
