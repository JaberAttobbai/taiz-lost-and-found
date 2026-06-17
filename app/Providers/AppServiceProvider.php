<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

/**
 * مزود خدمات التطبيق (AppServiceProvider).
 *
 * يتم تنفيذه في بداية دورة حياة التطبيق.
 * يُستخدم لتسجيل الخدمات (register) وإعداد التطبيق (boot).
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * تسجيل خدمات التطبيق.
     *
     * يُستخدم لربط الخدمات في حاوية الخدمات (Service Container).
     * فارغ حالياً — لا يوجد خدمات مخصصة للتسجيل.
     */
    public function register(): void
    {
        //
    }

    /**
     * إعداد التطبيق بعد تسجيل جميع الخدمات.
     *
     * يضبط الحد الأقصى لطول النصوص الافتراضي في قاعدة البيانات إلى 191 حرف.
     * هذا مطلوب لـ MySQL/MariaDB مع ترميز utf8mb4 حيث أن:
     * - كل حرف utf8mb4 يأخذ 4 بايت
     * - الفهرس (index) الأقصى في بعض إصدارات MySQL = 767 بايت
     * - 191 × 4 = 764 بايت (أقل من الحد الأقصى)
     *
     * ملاحظة: هذا الإعداد غير ضروري لـ SQLite (المستخدم حالياً) لكنه
     * يضمن التوافق عند الانتقال لـ MySQL في المستقبل.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
