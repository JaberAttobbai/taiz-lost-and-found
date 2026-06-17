{{--
    =============================================
    الصفحة الرئيسية — عرض جميع الإعلانات (items/index.blade.php)
    =============================================

    تستخدم القالب: x-app-layout (layouts/app.blade.php)

    المتغيرات الواردة من ItemController@index:
    - $items         → مجموعة الإعلانات (paginated، 12 لكل صفحة) مع العلاقات (user, category, neighborhood)
    - $categories    → قائمة الفئات (لعرضها في فلتر البحث)
    - $neighborhoods → قائمة الأحياء (لعرضها في فلتر البحث)

    الأقسام:
    1. Hero Section — عنوان المنصة + زر إضافة إعلان
    2. شريط البحث والفلترة — 4 فلاتر (كلمة مفتاحية، نوع، فئة، حي)
    3. شبكة الإعلانات — عرض بطاقات الإعلانات (3 أعمدة)
    4. حالة فارغة — رسالة عند عدم وجود نتائج
    5. Pagination — روابط تنقل بين الصفحات

    === SEO ===
    - عنوان ووصف ديناميكي حسب الفلاتر النشطة
    - noindex لصفحات البحث والفلاتر (منع محتوى مكرر)
    - rel=prev/next للـ pagination
    - canonical يشير للصفحة النظيفة بدون query params
--}}

{{-- === حساب قيم SEO ديناميكياً === --}}
@php
    // العنوان الديناميكي
    if (request('search')) {
        $seoTitle = 'نتائج البحث عن "' . request('search') . '" — منصة مفقودات تعز';
    } elseif (request('type') === 'lost') {
        $seoTitle = 'المفقودات في تعز — منصة مفقودات وموجودات تعز';
    } elseif (request('type') === 'found') {
        $seoTitle = 'الموجودات في تعز — منصة مفقودات وموجودات تعز';
    } else {
        $seoTitle = 'منصة مفقودات وموجودات تعز — ابحث عن مفقوداتك في تعز';
    }

    // الوصف الديناميكي
    $seoDescription = request('search')
        ? 'نتائج البحث عن "' . request('search') . '" في منصة مفقودات وموجودات تعز. ابحث عن أغراضك المفقودة أو أعلن عما وجدته.'
        : 'منصة مفقودات وموجودات تعز — وجهتك الأولى والأكثر أماناً للبحث عن مفقوداتك أو الإعلان عما وجدته في محافظة تعز. ابحث، أعلن، وتواصل مباشرة.';

    // منع فهرسة صفحات الفلاتر والبحث (duplicate content)
    $hasFilters = request()->anyFilled(['search', 'type', 'category_id', 'neighborhood_id']);
    $seoRobots = ($hasFilters || request('page', 1) > 1) ? 'noindex, follow' : 'index, follow';
@endphp

<x-app-layout
    :title="$seoTitle"
    :description="$seoDescription"
    :meta-robots="$seoRobots"
    :canonical-url="route('home')"
>
    {{-- Pagination SEO links --}}
    <x-slot name="extraHead">
        @if($items->previousPageUrl())
        <link rel="prev" href="{{ $items->previousPageUrl() }}">
        @endif
        @if($items->nextPageUrl())
        <link rel="next" href="{{ $items->nextPageUrl() }}">
        @endif
    </x-slot>
    {{-- ===== القسم الأول: Hero Section ===== --}}
    {{-- خلفية كبيرة مع عنوان المنصة ودعوة لإضافة إعلان --}}
    <div class="relative overflow-hidden bg-primary-dark">
        <!-- Background Pattern / Image -->
        {{-- صورة خلفية شفافة من Unsplash + تدرج لوني فوقها --}}
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1519817914152-2a24126edaf4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" class="w-full h-full object-cover opacity-10 mix-blend-overlay" alt="خلفية تعز">
            <div class="absolute inset-0 bg-gradient-to-t from-primary-dark to-primary/80 mix-blend-multiply"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 tracking-tight drop-shadow-md">منصة مفقودات وموجودات تعز</h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto mb-10 leading-relaxed font-medium drop-shadow-sm">وجهتك الأولى والأكثر أماناً للبحث عن مفقوداتك أو الإعلان عما وجدته في محافظة تعز.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('items.create') }}" class="bg-white text-primary hover:bg-gray-50 font-bold py-3.5 px-8 rounded-2xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    أضف إعلاناً الآن
                </a>
            </div>
        </div>
        
        <!-- Decorative bottom wave -->
        {{-- موجة زخرفية في أسفل الـ Hero لربطه بصرياً مع محتوى الصفحة --}}
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-brand-bg" style="clip-path: polygon(0 100%, 100% 100%, 100% 0%, 0% 100%);"></div>
    </div>

    <div class="py-12 relative z-10 bg-brand-bg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ===== رسالة نجاح (Flash Message) ===== --}}
            {{-- تظهر بعد إنشاء/تعديل/حذف إعلان بنجاح --}}
            @if(session('success'))
                <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ===== القسم الثاني: شريط البحث والفلترة ===== --}}
            {{--
                نموذج GET يرسل الفلاتر كـ query parameters للصفحة الرئيسية.
                كل فلتر يحتفظ بقيمته بعد الإرسال عبر request('field_name').
                البيانات المتاحة:
                - $categories: لملء قائمة الفئات
                - $neighborhoods: لملء قائمة الأحياء
            --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 mb-12 transform -translate-y-16">
                <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
                    
                    <!-- Search Keyword -->
                    {{-- حقل البحث النصي: يبحث في العنوان والوصف --}}
                    <div class="md:col-span-1">
                        <label for="search" class="block text-sm font-bold text-brand-text mb-2">ما الذي تبحث عنه؟</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="محفظة، بطاقة، هاتف..." class="w-full pl-10 pr-4 py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300">
                        </div>
                    </div>

                    <!-- Type -->
                    {{-- فلتر النوع: مفقودات فقط / موجودات فقط / الكل --}}
                    <div class="md:col-span-1">
                        <label for="type" class="block text-sm font-bold text-brand-text mb-2">نوع الإعلان</label>
                        <select name="type" id="type" class="w-full py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 cursor-pointer">
                            <option value="">جميع الأنواع</option>
                            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>مفقودات فقط</option>
                            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>موجودات فقط</option>
                        </select>
                    </div>

                    <!-- Category -->
                    {{-- فلتر الفئة: يعرض قائمة الفئات من $categories --}}
                    <div class="md:col-span-1">
                        <label for="category_id" class="block text-sm font-bold text-brand-text mb-2">الفئة</label>
                        <select name="category_id" id="category_id" class="w-full py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 cursor-pointer">
                            <option value="">كل الفئات</option>
                            {{-- @foreach: حلقة تكرار لعرض كل فئة كخيار في القائمة --}}
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Neighborhood -->
                    {{-- فلتر الحي: يعرض قائمة أحياء تعز من $neighborhoods --}}
                    <div class="md:col-span-1">
                        <label for="neighborhood_id" class="block text-sm font-bold text-brand-text mb-2">المنطقة</label>
                        <select name="neighborhood_id" id="neighborhood_id" class="w-full py-3 bg-brand-bg border-transparent focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/20 rounded-xl transition-all duration-300 cursor-pointer">
                            <option value="">كل مناطق تعز</option>
                            @foreach($neighborhoods as $neighborhood)
                                <option value="{{ $neighborhood->id }}" {{ request('neighborhood_id') == $neighborhood->id ? 'selected' : '' }}>{{ $neighborhood->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    {{-- أزرار التحكم: بحث + مسح الفلاتر (يظهر فقط عند وجود فلاتر نشطة) --}}
                    <div class="md:col-span-1 flex gap-3">
                        <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            بحث
                        </button>
                        {{-- زر مسح الفلاتر: يظهر فقط إذا كان هناك فلتر نشط --}}
                        @if(request()->anyFilled(['search', 'type', 'category_id', 'neighborhood_id']))
                            <a href="{{ route('home') }}" class="flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-xl shadow-sm transition-all duration-300 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ===== القسم الثالث: شبكة الإعلانات ===== --}}
            {{--
                @forelse: حلقة تكرار مع حالة فارغة (@empty)
                - إذا وجدت إعلانات → تعرض بطاقات في grid
                - إذا لم توجد → تعرض رسالة "لم نعثر على نتائج"
                
                كل بطاقة تعرض:
                - الصورة (أو placeholder إذا لم تُرفق صورة)
                - Badges: مفقود/موجود + مسترجع (إن وجد)
                - العنوان + مقتطع من الوصف
                - الحي + الفئة
                - اسم الناشر + الوقت النسبي (diffForHumans)
            --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($items as $item)
                    <div class="bg-white overflow-hidden rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <!-- Image Container -->
                        {{-- حاوية الصورة: رابط يأخذ لتفاصيل الإعلان --}}
                        <a href="{{ route('items.show', $item) }}" class="block relative h-56 bg-gray-100 overflow-hidden">
                            {{-- @if: عرض الصورة إذا وُجدت، أو SVG placeholder إذا لم تُوجد --}}
                            @if($item->image_path)
                                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-brand-bg">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            {{-- شارات النوع والحالة: تظهر فوق الصورة --}}
                            <div class="absolute top-4 right-4 flex gap-2">
                                {{-- شارة النوع: أحمر للمفقود، أخضر للموجود --}}
                                @if($item->type === 'lost')
                                    <span class="px-3 py-1 text-xs font-bold bg-red-500/90 backdrop-blur-sm text-white rounded-lg shadow-sm">مفقود</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold bg-primary/90 backdrop-blur-sm text-white rounded-lg shadow-sm">موجود</span>
                                @endif
                                
                                {{-- شارة الحالة: تظهر فقط إذا تم الاسترجاع --}}
                                @if($item->status === 'returned')
                                    <span class="px-3 py-1 text-xs font-bold bg-gray-800/90 backdrop-blur-sm text-white rounded-lg shadow-sm">مسترجع</span>
                                @endif
                            </div>
                        </a>

                        {{-- محتوى البطاقة: العنوان + الوصف + المعلومات --}}
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-brand-text line-clamp-1 mb-2">
                                <a href="{{ route('items.show', $item) }}" class="hover:text-primary transition-colors">
                                    {{ $item->title }}
                                </a>
                            </h3>

                            {{-- مقتطع الوصف: يُقص لسطرين (line-clamp-2) --}}
                            <p class="text-gray-500 line-clamp-2 mb-5 text-sm leading-relaxed">
                                {{ $item->description }}
                            </p>

                            {{-- علامات الحي والفئة --}}
                            <div class="flex flex-wrap gap-2 mb-5 text-xs font-medium">
                                {{-- اسم الحي: $item->neighborhood->name (Eager Loaded) --}}
                                <span class="bg-brand-bg text-gray-600 px-3 py-1.5 rounded-lg flex items-center border border-gray-100">
                                    <svg class="w-4 h-4 ml-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $item->neighborhood->name }}
                                </span>
                                {{-- اسم الفئة: $item->category->name (Eager Loaded) --}}
                                <span class="bg-brand-bg text-gray-600 px-3 py-1.5 rounded-lg flex items-center border border-gray-100">
                                    <svg class="w-4 h-4 ml-1.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    {{ $item->category->name }}
                                </span>
                            </div>

                            {{-- شريط المعلومات السفلي: الناشر + الزمن النسبي --}}
                            <div class="flex justify-between items-center text-sm font-medium border-t border-gray-100 pt-4">
                                <div class="flex items-center gap-2 text-gray-500">
                                    {{-- الحرف الأول من اسم المستخدم كـ avatar --}}
                                    {{-- mb_substr: يدعم الحروف العربية (multi-byte) --}}
                                    <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">
                                        {{ mb_substr($item->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $item->user->name }}</span>
                                </div>
                                {{-- الزمن النسبي: "منذ 5 دقائق"، "منذ يوم"، إلخ --}}
                                <span class="text-gray-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- ===== القسم الرابع: حالة فارغة ===== --}}
                    {{-- تظهر عندما لا توجد إعلانات تطابق البحث --}}
                    <div class="col-span-full py-20 bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
                        <div class="w-24 h-24 bg-primary/5 rounded-full flex items-center justify-center mb-6">
                            <svg class="h-12 w-12 text-primary/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-brand-text mb-3">لم نعثر على نتائج</h3>
                        <p class="text-gray-500 max-w-md mb-8 text-lg">يبدو أنه لا توجد إعلانات تطابق بحثك حالياً. يمكنك تعديل كلمات البحث أو إضافة إعلانك الجديد.</p>
                        <a href="{{ route('items.create') }}" class="bg-primary hover:bg-primary-dark text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-primary/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            إضافة إعلان جديد
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- ===== القسم الخامس: Pagination ===== --}}
            {{-- روابط التنقل بين صفحات النتائج --}}
            {{-- dir="ltr" لأن أرقام الصفحات تُعرض من اليسار لليمين --}}
            <div class="mt-12" dir="ltr">
                {{ $items->links() }}
            </div>

        </div>
    </div>

</x-app-layout>
