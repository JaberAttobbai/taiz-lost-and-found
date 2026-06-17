<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * نموذج الفئة (Category) — تصنيف الأغراض المفقودة/الموجودة.
 *
 * أمثلة على الفئات: إلكترونيات، مستندات، مفاتيح، محافظ، هواتف، إلخ.
 * كل فئة تحتوي على عدة إعلانات (علاقة One-to-Many مع Item).
 *
 * @property int    $id   المعرف الفريد
 * @property string $name اسم الفئة (مثال: "إلكترونيات")
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Item> $items الإعلانات المنتمية لهذه الفئة
 */
class Category extends Model
{
    /**
     * الحقول القابلة للتعبئة الجماعية.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * علاقة: الفئة تحتوي على عدة إعلانات.
     * الاستخدام: $category->items  (مجموعة من Item)
     *
     * @return HasMany<Item, self>
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
