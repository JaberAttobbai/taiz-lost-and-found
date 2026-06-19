<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

/**
 * نموذج المستخدم (User) — مستخدمو منصة مفقودات تعز.
 *
 * يستخدم PHP 8.2 Attributes لتعريف الحقول القابلة للتعبئة والمخفية
 * بدلاً من الخصائص التقليدية ($fillable, $hidden).
 *
 * الحقول المخصصة:
 * - phone_number: رقم الهاتف (فريد)، يُستخدم كوسيلة تواصل أساسية.
 *
 * @property int         $id                المعرف الفريد
 * @property string      $name              الاسم الكامل
 * @property string      $email             البريد الإلكتروني (فريد)
 * @property string      $phone_number      رقم الهاتف (فريد)
 * @property \Carbon\Carbon|null $email_verified_at تاريخ التحقق من البريد
 * @property string      $password          كلمة المرور (مُشفرة تلقائياً via cast)
 * @property string|null $remember_token    رمز "تذكرني"
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Item> $items إعلانات المستخدم
 */
#[Fillable(['name', 'email', 'phone_number', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * تحديد الحقول التي يجب تحويل نوعها تلقائياً (Casting).
     *
     * - email_verified_at: يُحوَّل إلى كائن Carbon (تاريخ/وقت)
     * - password: يُشفَّر تلقائياً عبر Hash::make() عند التعيين
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * علاقة: المستخدم لديه عدة إعلانات.
     * الاستخدام: $user->items  أو  auth()->user()->items()->latest()->get()
     *
     * @return HasMany<Item, self>
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * إرسال إشعار إعادة تعيين كلمة المرور عبر Queue.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
