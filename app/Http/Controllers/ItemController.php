<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Neighborhood;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * متحكم الإعلانات (ItemController) — المتحكم الرئيسي في التطبيق.
 *
 * يدير جميع عمليات CRUD على الإعلانات (مفقودات/موجودات):
 * - عرض القائمة مع بحث وفلاتر متعددة
 * - عرض تفاصيل إعلان واحد
 * - إنشاء إعلان جديد (للمستخدمين المسجلين فقط)
 * - تعديل إعلان (فقط لصاحبه)
 * - حذف إعلان (فقط لصاحبه)
 *
 * يطبق واجهة HasMiddleware لتحديد الحماية على كل دالة:
 * - index و show: متاحتان للجميع (بدون تسجيل دخول)
 * - باقي الدوال: تتطلب تسجيل دخول (auth middleware)
 */
class ItemController extends Controller implements HasMiddleware
{
    /**
     * تحديد الـ Middleware المطبق على هذا المتحكم.
     *
     * يتم تطبيق middleware 'auth' على جميع الدوال ما عدا:
     * - index: الصفحة الرئيسية (عامة للجميع)
     * - show: صفحة تفاصيل الإعلان (عامة للجميع)
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    /**
     * عرض الصفحة الرئيسية — قائمة الإعلانات مع البحث والفلترة.
     *
     * تدفق البحث:
     * 1. بناء Query أساسي مع Eager Loading للعلاقات (user, category, neighborhood)
     * 2. تطبيق الفلاتر الأربعة (كل فلتر يُطبق فقط إذا أرسل المستخدم قيمة):
     *    - search: بحث بالكلمة المفتاحية في العنوان أو الوصف (LIKE)
     *    - type: تصفية حسب النوع (lost أو found)
     *    - category_id: تصفية حسب الفئة
     *    - neighborhood_id: تصفية حسب الحي
     * 3. عرض النتائج مع Pagination (12 إعلان لكل صفحة)
     *
     * withQueryString() يحافظ على معاملات الفلاتر في روابط التنقل بين الصفحات.
     *
     * @param  Request $request الطلب الحالي (يحتوي على معاملات البحث)
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // بناء الاستعلام الأساسي مع تحميل العلاقات مسبقاً (Eager Loading)
        // لتفادي مشكلة N+1 Queries عند عرض بيانات كل إعلان
        $query = Item::with(['user', 'category', 'neighborhood'])->latest();

        // 1. Filter by Keyword (Title or Description)
        // يبحث في العنوان والوصف معاً باستخدام LIKE
        // مغلف بـ where(function...) لضمان صحة المنطق مع OR
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $q->where(function($query) use ($searchTerm) {
                $query->where('title', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm);
            });
        });

        // 2. Filter by Type (lost/found)
        // يعرض فقط المفقودات أو الموجودات حسب اختيار المستخدم
        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        // 3. Filter by Category
        // يصفي حسب فئة الغرض (إلكترونيات، مستندات، إلخ)
        $query->when($request->filled('category_id'), function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        // 4. Filter by Neighborhood
        // يصفي حسب الحي/المنطقة في تعز
        $query->when($request->filled('neighborhood_id'), function ($q) use ($request) {
            $q->where('neighborhood_id', $request->neighborhood_id);
        });

        // تنفيذ الاستعلام مع تقسيم النتائج لصفحات (12 عنصر لكل صفحة)
        // withQueryString() يحافظ على الفلاتر في روابط الصفحات
        $items = $query->paginate(12)->withQueryString();

        // جلب الفئات والأحياء لعرضها في قوائم الفلترة
        $categories = Category::orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();

        return view('items.index', compact('items', 'categories', 'neighborhoods'));
    }

    /**
     * عرض نموذج إنشاء إعلان جديد.
     *
     * يجلب قوائم الفئات والأحياء لملء عناصر الـ <select> في النموذج.
     * هذه الدالة محمية بـ auth middleware (يجب تسجيل الدخول).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();

        return view('items.create', compact('categories', 'neighborhoods'));
    }

    /**
     * حفظ إعلان جديد في قاعدة البيانات.
     *
     * تدفق العملية:
     * 1. التحقق من البيانات عبر StoreItemRequest (تلقائي)
     * 2. رفع الصورة إلى storage/app/public/items/ (إن وجدت)
     * 3. ربط الإعلان بالمستخدم الحالي (auth()->id())
     * 4. تعيين الحالة الأولية كـ 'active'
     * 5. إنشاء السجل في قاعدة البيانات
     * 6. إعادة توجيه للصفحة الرئيسية مع رسالة نجاح
     *
     * @param  StoreItemRequest $request الطلب المتحقق منه
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreItemRequest $request)
    {
        // الحصول على البيانات المتحقق منها (validated) فقط
        $validated = $request->validated();

        // رفع الصورة إلى مجلد 'items' في قرص 'public' إذا أرفقها المستخدم
        // store() يولّد اسم فريد للملف تلقائياً
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        // ربط الإعلان بالمستخدم الحالي وتعيين الحالة الأولية
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'active';

        // إنشاء سجل الإعلان في جدول items
        Item::create($validated);

        return redirect()->route('home')->with('success', '🎉 تم نشر إعلانك بنجاح! سيظهر الآن للجميع.');
    }

    /**
     * عرض تفاصيل إعلان محدد.
     *
     * يستخدم Route Model Binding لجلب الإعلان تلقائياً من الـ URL.
     * يقوم بتحميل العلاقات (user, category, neighborhood) عبر Eager Loading.
     * هذه الصفحة عامة (لا تتطلب تسجيل دخول).
     *
     * @param  Item $item الإعلان المراد عرضه (يُجلب تلقائياً من {item} في URL)
     * @return \Illuminate\View\View
     */
    public function show(Item $item)
    {
        // تحميل العلاقات بعد جلب الإعلان (Lazy Eager Loading)
        $item->load(['user', 'category', 'neighborhood']);
        
        return view('items.show', compact('item'));
    }

    /**
     * عرض نموذج تعديل إعلان موجود.
     *
     * محمية بمستويين:
     * 1. auth middleware — يجب تسجيل الدخول
     * 2. abort_if — يتحقق أن المستخدم الحالي هو مالك الإعلان
     *
     * @param  Item $item الإعلان المراد تعديله
     * @return \Illuminate\View\View
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 إذا لم يكن المالك
     */
    public function edit(Item $item)
    {
        // فحص الصلاحية: فقط صاحب الإعلان يستطيع تعديله
        abort_if(auth()->id() !== $item->user_id, 403, 'غير مصرح لك بتعديل هذا الإعلان.');

        $categories = Category::orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories', 'neighborhoods'));
    }

    /**
     * تحديث بيانات إعلان موجود.
     *
     * الصلاحية محققة في مستويين:
     * 1. UpdateItemRequest::authorize() — يتحقق من ملكية الإعلان
     * 2. القواعد (rules) — تتحقق من صحة البيانات
     *
     * إذا أرسل المستخدم صورة جديدة، يتم استبدال مسار الصورة.
     * ملاحظة: الصورة القديمة لا تُحذف حالياً من التخزين (تحسين مقترح).
     *
     * @param  UpdateItemRequest $request الطلب المتحقق منه (يشمل فحص الصلاحية)
     * @param  Item              $item    الإعلان المراد تحديثه
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();

        // استبدال الصورة إذا أرسل المستخدم صورة جديدة
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        // تحديث حقول الإعلان بالبيانات الجديدة
        $item->update($validated);

        return redirect()->route('items.show', $item)->with('success', '✅ تم حفظ التعديلات على إعلانك بنجاح!');
    }

    /**
     * حذف إعلان نهائياً من قاعدة البيانات.
     *
     * محمية بـ:
     * 1. auth middleware — يجب تسجيل الدخول
     * 2. abort_if — فقط صاحب الإعلان يستطيع حذفه
     *
     * ملاحظة: لا يتم حذف ملف الصورة من التخزين حالياً (تحسين مقترح).
     *
     * @param  Item $item الإعلان المراد حذفه
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException 403 إذا لم يكن المالك
     */
    public function destroy(Item $item)
    {
        // فحص الصلاحية: فقط صاحب الإعلان يستطيع حذفه
        abort_if(auth()->id() !== $item->user_id, 403, 'غير مصرح لك بحذف هذا الإعلان.');

        $item->delete();

        return redirect()->route('home')->with('success', '🗑️ تم حذف الإعلان بشكل نهائي.');
    }
}
