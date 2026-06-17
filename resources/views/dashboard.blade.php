{{--
    =============================================
    لوحة تحكم المستخدم (dashboard.blade.php)
    =============================================

    تستخدم القالب: x-app-layout (layouts/app.blade.php)
    محمية بـ: auth + verified middleware

    المتغيرات الواردة من Closure في web.php:
    - $items → جميع إعلانات المستخدم الحالي (مرتبة من الأحدث)

    تعرض:
    - عداد الإعلانات ($items->count())
    - شبكة بطاقات (2 أعمدة) لكل إعلان مع:
      - صورة مصغرة + العنوان + نوع + حالة + تاريخ
      - أزرار (تعديل + حذف) تظهر عند hover
      - الإعلانات المسترجعة تظهر بتأثير رمادي (grayscale)
    - حالة فارغة إذا لم يكن هناك إعلانات
    - زر "إضافة إعلان جديد"
--}}

<x-app-layout
    title="إعلاناتي — منصة مفقودات تعز"
    meta-robots="noindex, nofollow"
>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <h2 class="font-bold text-2xl text-brand-text leading-tight">
                لوحة التحكم
            </h2>
        </div>
    </x-slot>

    <div class="py-12 relative z-10 bg-brand-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl shadow-primary/5 sm:rounded-3xl border border-gray-100">
                <div class="p-8 sm:p-10">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b border-gray-100 pb-6 gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-brand-text mb-1">إعلاناتي الخاصة</h3>
                            <p class="text-sm text-gray-500">لديك ({{ $items->count() }}) إعلان مسجل في النظام.</p>
                        </div>
                        <a href="{{ route('items.create') }}" class="flex-none bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            إضافة إعلان جديد
                        </a>
                    </div>

                    @if($items->isEmpty())
                        <div class="text-center py-16 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-600 mb-2">لا يوجد لديك إعلانات</h4>
                            <p class="text-gray-400 text-sm max-w-sm mx-auto">لم تقم بنشر أي إعلانات عن مفقودات أو موجودات حتى الآن.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($items as $item)
                                <div class="border {{ $item->status === 'returned' ? 'border-gray-200 bg-gray-50' : 'border-gray-100 bg-white hover:shadow-lg hover:border-primary/20' }} rounded-2xl p-5 flex flex-col sm:flex-row gap-5 items-start transition-all duration-300 relative overflow-hidden group">
                                    
                                    <!-- Image -->
                                    <div class="flex-none">
                                        @if($item->image_path)
                                            <img src="{{ Storage::url($item->image_path) }}" class="w-24 h-24 object-cover rounded-xl shadow-sm {{ $item->status === 'returned' ? 'grayscale opacity-70' : '' }}">
                                        @else
                                            <div class="w-24 h-24 bg-brand-bg border border-gray-100 rounded-xl flex items-center justify-center text-gray-300 {{ $item->status === 'returned' ? 'opacity-70' : '' }}">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0 {{ $item->status === 'returned' ? 'opacity-75' : '' }}">
                                        <a href="{{ route('items.show', $item) }}" class="font-bold text-lg text-brand-text hover:text-primary transition-colors block truncate mb-2">
                                            {{ $item->title }}
                                        </a>
                                        
                                        <div class="flex flex-wrap gap-2 text-xs font-bold mb-3">
                                            @if($item->type === 'lost')
                                                <span class="bg-red-50 text-red-600 px-2 py-1 rounded-lg">مفقود</span>
                                            @else
                                                <span class="bg-primary/10 text-primary px-2 py-1 rounded-lg">موجود</span>
                                            @endif
                                            
                                            @if($item->status === 'returned')
                                                <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded-lg">تم الاسترجاع</span>
                                            @endif
                                        </div>

                                        <div class="text-sm text-gray-500 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $item->created_at->format('Y-m-d') }}
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex sm:flex-col gap-2 w-full sm:w-auto mt-4 sm:mt-0 opacity-100 sm:opacity-0 sm:translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
                                        <a href="{{ route('items.edit', $item) }}" class="flex-1 sm:flex-none text-center px-4 py-2 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg text-sm font-bold transition-colors">
                                            تعديل
                                        </a>
                                        <form action="{{ route('items.destroy', $item) }}" method="POST" id="delete-form-{{ $item->id }}" class="flex-1 sm:flex-none">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                onclick="askConfirm('delete-form-{{ $item->id }}', {
                                                    type: 'danger',
                                                    title: 'حذف الإعلان نهائياً؟',
                                                    message: 'سيتم حذف هذا الإعلان بشكل نهائي ولا يمكن التراجع عن هذه الخطوة. هل أنت متأكد؟',
                                                    confirmText: 'نعم، احذف نهائياً'
                                                })"
                                                class="w-full text-center px-4 py-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg text-sm font-bold transition-colors">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
