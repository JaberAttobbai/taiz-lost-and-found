<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Seeder لإنشاء الفئات الأساسية للمفقودات والموجودات.
 *
 * يستخدم firstOrCreate لمنع تكرار الفئات عند تنفيذ db:seed أكثر من مرة.
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'الإلكترونيات والجوالات',
            'الوثائق والبطائق',
            'المفاتيح والمحافظ',
            'الحقائب والملابس',
            'المجوهرات والساعات',
            'أخرى'
        ];

        foreach ($categories as $category) {
            // firstOrCreate: يبحث أولاً عن الفئة بالاسم، وإذا لم تُوجد يُنشئها
            // هذا يمنع تكرار الفئات عند تنفيذ السيدر أكثر من مرة
            Category::firstOrCreate(['name' => $category]);
        }
    }
}
