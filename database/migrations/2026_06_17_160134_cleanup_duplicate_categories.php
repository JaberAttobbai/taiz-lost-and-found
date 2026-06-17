<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * تنظيف الفئات المكررة في جدول categories.
 *
 * المشكلة: CategorySeeder كان يستخدم Category::create() بدلاً من firstOrCreate.
 * عند تنفيذ db:seed أكثر من مرة على Render، تتكرر نفس الفئات بـ IDs مختلفة.
 *
 * الحل:
 * 1. لكل اسم فئة مكرر → الاحتفاظ بأقل ID فقط
 * 2. تحديث جميع العناصر المرتبطة بالـ IDs المكررة للإشارة إلى الـ ID الأصلي
 * 3. حذف السجلات المكررة
 */
return new class extends Migration
{
    public function up(): void
    {
        // البحث عن الفئات المكررة (أكثر من سجل بنفس الاسم)
        $duplicates = DB::table('categories')
            ->select('name', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('name')
            ->having('cnt', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            // جلب IDs المكررة (ما عدا الأقل/الأصلي)
            $duplicateIds = DB::table('categories')
                ->where('name', $dup->name)
                ->where('id', '!=', $dup->keep_id)
                ->pluck('id');

            // تحديث العناصر المرتبطة بالـ IDs المكررة → الـ ID الأصلي
            DB::table('items')
                ->whereIn('category_id', $duplicateIds)
                ->update(['category_id' => $dup->keep_id]);

            // حذف السجلات المكررة
            DB::table('categories')
                ->whereIn('id', $duplicateIds)
                ->delete();
        }
    }

    public function down(): void
    {
        // لا يمكن التراجع عن حذف التكرارات بشكل موثوق
    }
};
