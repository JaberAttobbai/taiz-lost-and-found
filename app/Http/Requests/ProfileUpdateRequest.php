<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * طلب التحقق لتحديث الملف الشخصي (ProfileUpdateRequest).
 *
 * يتحقق من صحة بيانات الملف الشخصي عند التعديل:
 * - الاسم: مطلوب وأقصى 255 حرف
 * - الإيميل: فريد عبر النظام (مع تجاهل المستخدم الحالي)
 * - رقم الهاتف: فريد عبر النظام (مع تجاهل المستخدم الحالي)
 *
 * ملاحظة: لا يتضمن authorize() لأن الصلاحية مضمونة
 * عبر auth middleware في المسارات.
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * قواعد التحقق من بيانات الملف الشخصي.
     *
     * Rule::unique(...)->ignore($this->user()->id)
     * يضمن فحص التفرد مع استثناء المستخدم الحالي —
     * حتى لا يحصل خطأ "الإيميل مستخدم مسبقاً" إذا لم يغيّر إيميله.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // الاسم: مطلوب ونصي
            'name' => ['required', 'string', 'max:255'],

            // الإيميل: مطلوب، حروف صغيرة، فريد (مع استثناء المستخدم الحالي)
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // رقم الهاتف: مطلوب، فريد (مع استثناء المستخدم الحالي)
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
