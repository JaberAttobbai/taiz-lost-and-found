<?php

/**
 * =============================================
 * مسارات الويب الرئيسية (Web Routes)
 * =============================================
 *
 * هذا الملف يحتوي على جميع المسارات العامة والمحمية للتطبيق.
 *
 * التقسيم:
 * 1. مسارات عامة (بدون تسجيل دخول): الصفحة الرئيسية، تفاصيل إعلان، sitemap
 * 2. مسارات محمية بـ auth: إنشاء/تعديل/حذف إعلانات، الملف الشخصي
 * 3. مسارات المصادقة: مستوردة من auth.php (Breeze)
 *
 * ملاحظة: ItemController يدير الحماية داخلياً عبر HasMiddleware
 * (index و show عامتان، الباقي يتطلب auth)
 */

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| خريطة الموقع (Sitemap) — SEO
|--------------------------------------------------------------------------
| يولّد ملف sitemap.xml ديناميكياً لمحركات البحث.
| مخزّن مؤقتاً لمدة 30 دقيقة لتقليل الضغط على قاعدة البيانات.
*/
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| الصفحة الرئيسية
|--------------------------------------------------------------------------
| تعرض جميع الإعلانات مع إمكانية البحث والفلترة.
| هذا المسار عام (لا يتطلب تسجيل دخول).
*/
Route::get('/', [ItemController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| مسارات CRUD للإعلانات (Resource Routes)
|--------------------------------------------------------------------------
| يُنشئ تلقائياً جميع مسارات CRUD السبعة:
| GET    /items           → index   (عامة)
| GET    /items/create    → create  (auth)
| POST   /items           → store   (auth)
| GET    /items/{item}    → show    (عامة)
| GET    /items/{item}/edit → edit  (auth)
| PUT    /items/{item}    → update  (auth)
| DELETE /items/{item}    → destroy (auth)
|
| ملاحظة: الحماية (auth) تُدار داخل ItemController عبر HasMiddleware
*/
Route::resource('items', ItemController::class);

/*
|--------------------------------------------------------------------------
| [مؤقت] مسار تشخيصي لفحص حالة Queue والإيميلات
|--------------------------------------------------------------------------
| يعرض الوظائف المعلقة والفاشلة — يُحذف بعد حل مشكلة الإيميل.
*/
Route::get('/debug/mail-status', function () {
    $pending = \DB::table('jobs')->count();
    $failed = \DB::table('failed_jobs')->latest()->take(5)->get(['id', 'payload', 'exception', 'failed_at']);
    
    return response()->json([
        'pending_jobs' => $pending,
        'failed_jobs_count' => \DB::table('failed_jobs')->count(),
        'recent_failures' => $failed->map(function ($job) {
            return [
                'id' => $job->id,
                'failed_at' => $job->failed_at,
                'error' => \Str::limit($job->exception, 500),
            ];
        }),
    ]);
});

/*
|--------------------------------------------------------------------------
| لوحة التحكم (Dashboard)
|--------------------------------------------------------------------------
| صفحة خاصة بالمستخدم المسجل تعرض إعلاناته فقط.
| محمية بـ auth + verified (يجب التحقق من البريد).
| تجلب إعلانات المستخدم الحالي مرتبة من الأحدث.
*/
Route::get('/dashboard', function () {
    // جلب جميع إعلانات المستخدم الحالي مرتبة من الأحدث
    $items = request()->user()->items()->latest()->get();
    return view('dashboard', compact('items'));
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| مسارات الملف الشخصي (Profile Routes)
|--------------------------------------------------------------------------
| جميعها محمية بـ auth middleware:
| GET    /profile → عرض نموذج التعديل
| PATCH  /profile → حفظ التعديلات
| DELETE /profile → حذف الحساب نهائياً
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| مسارات المصادقة (Authentication Routes)
|--------------------------------------------------------------------------
| يستورد مسارات Laravel Breeze: login, register, logout,
| forgot-password, reset-password, verify-email, etc.
*/
require __DIR__.'/auth.php';
