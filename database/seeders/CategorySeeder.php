<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

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
            Category::create(['name' => $category]);
        }
    }
}
