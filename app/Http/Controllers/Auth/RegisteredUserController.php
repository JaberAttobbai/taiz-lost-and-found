<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * متحكم التسجيل (RegisteredUserController).
 *
 * يدير عملية إنشاء حساب جديد في المنصة.
 * مخصص عن نسخة Breeze الأصلية بإضافة حقل phone_number.
 *
 * محمي بـ 'guest' middleware — فقط الزوار (غير المسجلين) يمكنهم الوصول.
 */
class RegisteredUserController extends Controller
{
    /**
     * عرض صفحة التسجيل.
     *
     * يُعرض نموذج التسجيل الذي يطلب:
     * - الاسم الكامل
     * - البريد الإلكتروني
     * - رقم الهاتف (حقل مخصص)
     * - كلمة المرور + تأكيدها
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * معالجة طلب التسجيل وإنشاء حساب جديد.
     *
     * تدفق العملية:
     * 1. التحقق من صحة البيانات (الاسم، الإيميل، الهاتف، كلمة المرور)
     * 2. إنشاء سجل المستخدم في قاعدة البيانات
     * 3. تشفير كلمة المرور عبر Hash::make()
     * 4. إطلاق حدث Registered (يمكن ربطه بإرسال بريد تحقق)
     * 5. تسجيل دخول المستخدم تلقائياً
     * 6. إعادة توجيه للوحة التحكم
     *
     * ملاحظة: phone_number حقل مخصص مضاف للمشروع
     * (ليس من Breeze الأصلي) — يُستخدم كوسيلة تواصل في الإعلانات.
     *
     * @param  Request $request
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // التحقق من صحة بيانات التسجيل
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'max:20', 'unique:'.User::class], // حقل مخصص
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // إنشاء المستخدم مع تشفير كلمة المرور
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // إطلاق حدث التسجيل (يمكن ربطه بـ Listener لإرسال بريد تحقق)
        event(new Registered($user));

        // تسجيل دخول المستخدم تلقائياً بعد التسجيل
        Auth::login($user);

        // التوجيه للوحة التحكم
        return redirect(route('dashboard', absolute: false));
    }
}
