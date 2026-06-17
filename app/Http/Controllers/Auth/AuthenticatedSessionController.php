<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * متحكم جلسات المصادقة (AuthenticatedSessionController).
 *
 * يدير عملية تسجيل الدخول والخروج.
 * من Laravel Breeze بدون تعديلات.
 *
 * - create(): عرض صفحة تسجيل الدخول (محمية بـ guest middleware)
 * - store(): معالجة بيانات الدخول والتحقق منها
 * - destroy(): تسجيل الخروج وإبطال الجلسة
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول.
     *
     * محمية بـ 'guest' middleware — فقط الزوار يمكنهم رؤيتها.
     * المستخدمون المسجلون يُعاد توجيههم للـ dashboard.
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * معالجة طلب تسجيل الدخول.
     *
     * تدفق العملية:
     * 1. LoginRequest::authenticate() يتحقق من البريد وكلمة المرور
     * 2. تجديد الجلسة (session regeneration) لمنع session fixation attacks
     * 3. إعادة توجيه للصفحة المقصودة أصلاً (intended) أو الـ dashboard
     *
     * @param  LoginRequest $request طلب مخصص يتعامل مع throttling والتحقق
     * @return RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // التحقق من بيانات الدخول (يرمي exception عند الفشل)
        $request->authenticate();

        // تجديد الجلسة لمنع هجمات تثبيت الجلسة
        $request->session()->regenerate();

        // التوجيه للصفحة المقصودة أو dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * تسجيل خروج المستخدم وإنهاء الجلسة.
     *
     * تدفق العملية:
     * 1. تسجيل الخروج من guard 'web'
     * 2. إبطال الجلسة الحالية
     * 3. تجديد رمز CSRF
     * 4. إعادة توجيه للصفحة الرئيسية
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
