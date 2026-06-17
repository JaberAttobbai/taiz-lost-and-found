{{--
    =============================================
    نموذج تعديل إعلان موجود (items/edit.blade.php)
    =============================================

    تستخدم القالب: x-app-layout (layouts/app.blade.php)
    محمية بـ: auth middleware + فحص الملكية في Controller

    المتغيرات الواردة من ItemController@edit:
    - $item          → الإعلان المراد تعديله (مع بياناته الحالية)
    - $categories    → قائمة الفئات (لملء <select>)
    - $neighborhoods → قائمة الأحياء (لملء <select>)

    الفرق عن create.blade.php:
    - يستخدم @method('PUT') لإرسال طلب PUT بدلاً من POST
    - يحتوي حقل status إضافي (active/returned)
    - القيم الافتراضية تأتي من $item عبر old('field', $item->field)
    - يعرض الصورة الحالية بجانب منطقة رفع صورة جديدة

    يرسل النموذج PUT إلى route('items.update', $item) عبر UpdateItemRequest.
--}}

<x-app-layout
    title="تعديل الإعلان — منصة مفقودات تعز"
    meta-robots="noindex, nofollow"
>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h2 class="font-bold text-2xl text-brand-text leading-tight">
                تعديل الإعلان
            </h2>
        </div>
    </x-slot>

    <div class="py-12 relative z-10 bg-brand-bg">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl shadow-primary/5 sm:rounded-3xl border border-gray-100">
                <div class="p-8 sm:p-12">
                    
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-xl font-bold text-brand-text mb-2">تحديث بيانات الإعلان</h3>
                        <p class="text-gray-500 text-sm">يمكنك تعديل أي تفاصيل أو تغيير حالة الإعلان إذا تم استرجاع العنصر.</p>
                    </div>

                    <form id="edit-item-form" method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Type & Status in a grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Type (Lost or Found) -->
                            <div>
                                <label class="block font-bold text-brand-text mb-4">نوع الإعلان</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="lost" class="peer sr-only" {{ old('type', $item->type) == 'lost' ? 'checked' : '' }}>
                                        <div class="p-3 rounded-2xl border-2 border-gray-100 peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50 transition-all text-center">
                                            <span class="block font-bold text-brand-text">مفقود</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="found" class="peer sr-only" {{ old('type', $item->type) == 'found' ? 'checked' : '' }}>
                                        <div class="p-3 rounded-2xl border-2 border-gray-100 peer-checked:border-primary peer-checked:bg-primary/5 hover:bg-gray-50 transition-all text-center">
                                            <span class="block font-bold text-brand-text">موجود</span>
                                        </div>
                                    </label>
                                </div>
                                <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('type')" />
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block font-bold text-brand-text mb-4">حالة الإعلان</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="active" class="peer sr-only" {{ old('status', $item->status) == 'active' ? 'checked' : '' }}>
                                        <div class="p-3 rounded-2xl border-2 border-gray-100 peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition-all text-center">
                                            <span class="block font-bold text-brand-text">نشط (مستمر)</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="returned" class="peer sr-only" {{ old('status', $item->status) == 'returned' ? 'checked' : '' }}>
                                        <div class="p-3 rounded-2xl border-2 border-gray-100 peer-checked:border-gray-500 peer-checked:bg-gray-100 hover:bg-gray-50 transition-all text-center opacity-75 peer-checked:opacity-100">
                                            <span class="block font-bold text-gray-700 line-through">تم الاسترجاع</span>
                                        </div>
                                    </label>
                                </div>
                                <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('status')" />
                            </div>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block font-bold text-sm text-brand-text mb-2">العنوان</label>
                            <input id="title" name="title" type="text" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300" value="{{ old('title', $item->title) }}" required autofocus />
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('title')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block font-bold text-sm text-brand-text mb-2">الفئة</label>
                                <select id="category_id" name="category_id" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 cursor-pointer" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
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
                                    @foreach($neighborhoods as $neighborhood)
                                        <option value="{{ $neighborhood->id }}" {{ old('neighborhood_id', $item->neighborhood_id) == $neighborhood->id ? 'selected' : '' }}>
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
                            <textarea id="description" name="description" rows="5" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 resize-y" required>{{ old('description', $item->description) }}</textarea>
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('description')" />
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <label for="contact_phone" class="block font-bold text-sm text-brand-text mb-2">رقم هاتف للتواصل</label>
                            <input id="contact_phone" name="contact_phone" type="text" class="block w-full px-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 text-right" dir="ltr" value="{{ old('contact_phone', $item->contact_phone) }}" required />
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('contact_phone')" />
                        </div>

                        <!-- Image -->
                        <div class="border-t border-gray-100 pt-6">
                            <label for="image" class="block font-bold text-sm text-brand-text mb-4">تحديث الصورة <span class="text-gray-400 font-normal text-xs">(اتركه فارغاً للاحتفاظ بالصورة الحالية)</span></label>
                            
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                @if($item->image_path)
                                    <div class="flex-none">
                                        <p class="text-xs text-gray-500 mb-2 font-bold text-center">الصورة الحالية</p>
                                        <div class="w-32 h-32 rounded-xl border-2 border-gray-100 overflow-hidden shadow-sm">
                                            <img src="{{ Storage::url($item->image_path) }}" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                @endif

                                <div class="flex-1 w-full">
                                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:bg-brand-bg transition-colors h-full items-center">
                                        <div class="space-y-2 text-center">
                                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                                    <span>اضغط لاختيار صورة جديدة</span>
                                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF حتى 5MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-input-error class="mt-2 text-red-600 font-medium text-sm" :messages="$errors->get('image')" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 font-bold text-gray-500 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                إلغاء
                            </a>
                            <button
                                type="button"
                                onclick="askConfirm('edit-item-form', {
                                    type: 'warning',
                                    title: 'حفظ التعديلات؟',
                                    message: 'سيتم تحديث بيانات الإعلان بشكل فوري. تأكد من صحة المعلومات قبل التأكيد.',
                                    confirmText: 'نعم، احفظ التعديلات'
                                })"
                                class="px-8 py-3 bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2"
                            >
                                حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
