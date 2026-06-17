<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * نموذج الإعلان (Item) — النموذج الرئيسي في التطبيق.
 *
 * يمثل إعلاناً عن غرض مفقود (lost) أو موجود (found) في محافظة تعز.
 * كل إعلان مرتبط بمستخدم (ناشر)، فئة (نوع الغرض)، وحي (موقع الفقدان/العثور).
 *
 * @property int    $id               المعرف الفريد
 * @property string $title            عنوان الإعلان (مثال: "محفظة جلدية سوداء")
 * @property string $description      الوصف التفصيلي للغرض
 * @property string|null $image_path  مسار الصورة في storage/items/ (nullable)
 * @property string $type             نوع الإعلان: 'lost' (مفقود) أو 'found' (موجود)
 * @property string $status           حالة الإعلان: 'active' (نشط) أو 'returned' (تم الاسترجاع)
 * @property string $contact_phone    رقم الهاتف للتواصل
 * @property int    $user_id          معرف المستخدم الناشر (FK → users.id)
 * @property int    $category_id      معرف الفئة (FK → categories.id)
 * @property int    $neighborhood_id  معرف الحي (FK → neighborhoods.id)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User         $user         المستخدم الذي نشر الإعلان
 * @property-read Category     $category     فئة الغرض (إلكترونيات، مستندات، إلخ)
 * @property-read Neighborhood $neighborhood الحي/المنطقة في تعز
 */
class Item extends Model
{
    /**
     * الحقول القابلة للتعبئة الجماعية (Mass Assignment).
     * هذه الحقول فقط يمكن ملؤها عبر Item::create() أو $item->update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'type',
        'status',
        'contact_phone',
        'user_id',
        'category_id',
        'neighborhood_id',
    ];

    /**
     * علاقة: الإعلان ينتمي لمستخدم واحد (الناشر).
     * الاستخدام: $item->user->name
     *
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة: الإعلان ينتمي لفئة واحدة.
     * الاستخدام: $item->category->name
     *
     * @return BelongsTo<Category, self>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * علاقة: الإعلان ينتمي لحي/منطقة واحدة في تعز.
     * الاستخدام: $item->neighborhood->name
     *
     * @return BelongsTo<Neighborhood, self>
     */
    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }
}
