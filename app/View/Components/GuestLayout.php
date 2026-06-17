<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * مكون قالب الزوار (GuestLayout).
 *
 * يُستخدم في ملفات Blade عبر الوسم: <x-guest-layout>
 * يرتبط بملف القالب: resources/views/layouts/guest.blade.php
 *
 * يُغلّف صفحات المصادقة فقط:
 * - تسجيل الدخول (auth/login)
 * - إنشاء حساب جديد (auth/register)
 * - استعادة كلمة المرور (auth/forgot-password, auth/reset-password)
 * - تأكيد كلمة المرور (auth/confirm-password)
 * - التحقق من البريد (auth/verify-email)
 *
 * يعرض الشعار + بطاقة بيضاء مركزية مع خلفية زخرفية.
 */
class GuestLayout extends Component
{
    /**
     * الحصول على View المرتبط بهذا المكون.
     *
     * @return View
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
