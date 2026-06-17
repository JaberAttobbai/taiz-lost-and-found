<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * متحكم الملف الشخصي (ProfileController).
 *
 * يدير صفحات الملف الشخصي للمستخدم المسجل:
 * - عرض نموذج تعديل البيانات الشخصية
 * - تحديث الاسم والإيميل ورقم الهاتف
 * - حذف الحساب نهائياً
 *
 * جميع الدوال محمية بـ auth middleware (معرّف في routes/web.php).
 */
class ProfileController extends Controller
{
    /**
     * عرض نموذج تعديل الملف الشخصي.
     *
     * يمرر كائن المستخدم الحالي للـ View لعرض بياناته في حقول النموذج.
     * الصفحة تتضمن 3 أقسام (partials):
     * - تحديث المعلومات الشخصية
     * - تغيير كلمة المرور
     * - حذف الحساب
     *
     * @param  Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * تحديث بيانات الملف الشخصي (الاسم، الإيميل، رقم الهاتف).
     *
     * التحقق يتم عبر ProfileUpdateRequest الذي يضمن:
     * - الاسم مطلوب وأقصى 255 حرف
     * - الإيميل فريد (مع استثناء المستخدم الحالي)
     * - رقم الهاتف فريد (مع استثناء المستخدم الحالي)
     *
     * إذا تم تغيير الإيميل، يتم إلغاء التحقق (email_verified_at = null)
     * لإجبار المستخدم على التحقق من الإيميل الجديد.
     *
     * @param  ProfileUpdateRequest $request الطلب المتحقق منه
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // تعبئة بيانات المستخدم بالقيم الجديدة (بدون حفظ)
        $request->user()->fill($request->validated());

        // إذا تغير الإيميل → إلغاء التحقق السابق
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // حفظ التغييرات في قاعدة البيانات
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * حذف حساب المستخدم نهائياً.
     *
     * تدفق العملية:
     * 1. التحقق من كلمة المرور الحالية (طبقة أمان إضافية)
     * 2. تسجيل خروج المستخدم
     * 3. حذف سجل المستخدم من قاعدة البيانات
     *    (هذا يحذف تلقائياً جميع إعلاناته بسبب cascadeOnDelete)
     * 4. إبطال الجلسة وتجديد CSRF token
     * 5. إعادة توجيه للصفحة الرئيسية
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // التحقق من كلمة المرور قبل الحذف (في bag منفصل لعزل الأخطاء)
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // تسجيل الخروج أولاً ثم حذف المستخدم
        Auth::logout();

        $user->delete();

        // إبطال الجلسة وتجديد رمز CSRF لمنع أي استخدام بعد الحذف
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
