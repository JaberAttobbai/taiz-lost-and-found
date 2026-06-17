<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * مكون القالب الأساسي (AppLayout).
 *
 * يُستخدم في ملفات Blade عبر الوسم: <x-app-layout>
 * يرتبط بملف القالب: resources/views/layouts/app.blade.php
 *
 * هذا المكون يُغلّف جميع صفحات التطبيق العامة والمحمية:
 * - الصفحة الرئيسية (items/index)
 * - صفحات الإعلانات (show, create, edit)
 * - لوحة التحكم (dashboard)
 * - الملف الشخصي (profile)
 *
 * يوفر slot اختياري "header" لعرض عنوان الصفحة.
 */
class AppLayout extends Component
{
    /**
     * الحصول على View المرتبط بهذا المكون.
     *
     * @return View
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
