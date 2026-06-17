<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * طلب التحقق لتعديل إعلان موجود (UpdateItemRequest).
 *
 * يشبه StoreItemRequest مع إضافتين مهمتين:
 * 1. فحص الصلاحية في authorize() — فقط مالك الإعلان يستطيع تعديله
 * 2. حقل status إضافي — يسمح بتغيير حالة الإعلان (active/returned)
 *
 * هذا يمثل المستوى الثاني من فحص الصلاحية:
 * - المستوى الأول: abort_if في ItemController::edit()
 * - المستوى الثاني: authorize() هنا في UpdateItemRequest
 *
 * التحقق يتم تلقائياً قبل تنفيذ ItemController::update()
 */
class UpdateItemRequest extends FormRequest
{
    /**
     * تحديد صلاحية المستخدم لتنفيذ هذا الطلب.
     *
     * يتحقق أن المستخدم الحالي هو نفسه مالك الإعلان.
     * $this->route('item') يجلب كائن Item من Route Model Binding.
     *
     * @return bool true إذا كان المستخدم هو المالك، false يُرجع خطأ 403
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->route('item')->user_id;
    }

    /**
     * قواعد التحقق من البيانات المدخلة.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // نوع الإعلان: مطلوب، 'lost' أو 'found'
            'type' => ['required', 'in:lost,found'],

            // حالة الإعلان: مطلوبة (حقل إضافي عن StoreItemRequest)
            // 'active' = لا يزال يبحث/ينتظر | 'returned' = تم الاسترجاع
            'status' => ['required', 'in:active,returned'],

            // العنوان: مطلوب، أقصى 255 حرف
            'title' => ['required', 'string', 'max:255'],

            // الوصف: مطلوب
            'description' => ['required', 'string'],

            // الفئة: يجب أن تكون موجودة في قاعدة البيانات
            'category_id' => ['required', 'exists:categories,id'],

            // الحي: يجب أن يكون موجوداً في قاعدة البيانات
            'neighborhood_id' => ['required', 'exists:neighborhoods,id'],

            // رقم الهاتف: مطلوب
            'contact_phone' => ['required', 'string', 'max:20'],

            // الصورة: اختيارية (إذا لم تُرسل، تبقى الصورة القديمة)
            'image' => ['nullable', 'image', 'max:5120'], // Max 5MB
        ];
    }
}
