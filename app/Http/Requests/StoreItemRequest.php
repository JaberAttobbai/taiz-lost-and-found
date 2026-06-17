<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * طلب التحقق لإنشاء إعلان جديد (StoreItemRequest).
 *
 * يتحقق من صحة جميع البيانات المطلوبة لإنشاء إعلان:
 * - نوع الإعلان (مفقود/موجود)
 * - العنوان والوصف
 * - الفئة والحي (يجب أن يكونا موجودين في قاعدة البيانات)
 * - رقم الهاتف
 * - الصورة (اختيارية، حتى 5MB)
 *
 * التحقق يتم تلقائياً قبل تنفيذ ItemController::store()
 * — إذا فشل التحقق، يتم إعادة المستخدم للنموذج مع رسائل الخطأ.
 */
class StoreItemRequest extends FormRequest
{
    /**
     * تحديد صلاحية المستخدم لتنفيذ هذا الطلب.
     *
     * يُرجع true دائماً لأن الحماية تتم عبر auth middleware في المتحكم.
     * أي مستخدم مسجل دخول يستطيع إنشاء إعلان.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق من البيانات المدخلة.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // نوع الإعلان: مطلوب، يجب أن يكون 'lost' أو 'found'
            'type' => ['required', 'in:lost,found'],

            // عنوان الإعلان: مطلوب، نصي، أقصى 255 حرف
            'title' => ['required', 'string', 'max:255'],

            // الوصف التفصيلي: مطلوب ونصي (بدون حد أقصى)
            'description' => ['required', 'string'],

            // الفئة: مطلوبة ويجب أن تكون موجودة في جدول categories
            'category_id' => ['required', 'exists:categories,id'],

            // الحي: مطلوب ويجب أن يكون موجوداً في جدول neighborhoods
            'neighborhood_id' => ['required', 'exists:neighborhoods,id'],

            // رقم الهاتف: مطلوب، أقصى 20 حرف
            'contact_phone' => ['required', 'string', 'max:20'],

            // الصورة: اختيارية، يجب أن تكون صورة صالحة، أقصى حجم 5MB (5120 كيلوبايت)
            'image' => ['nullable', 'image', 'max:5120'], // Max 5MB
        ];
    }
}
