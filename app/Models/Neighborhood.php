<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * نموذج الحي/المنطقة (Neighborhood) — تحديد الموقع الجغرافي في تعز.
 *
 * يمثل حياً أو منطقة في محافظة تعز، يُستخدم لتحديد مكان فقدان/إيجاد الغرض.
 * أمثلة: المظفر، صالة، الحوبان، الروضة، إلخ.
 *
 * @property int    $id   المعرف الفريد
 * @property string $name اسم الحي/المنطقة (مثال: "المظفر")
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Item> $items الإعلانات في هذا الحي
 */
class Neighborhood extends Model
{
    /**
     * الحقول القابلة للتعبئة الجماعية.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * علاقة: الحي يحتوي على عدة إعلانات.
     * الاستخدام: $neighborhood->items  (مجموعة من Item)
     *
     * @return HasMany<Item, self>
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
