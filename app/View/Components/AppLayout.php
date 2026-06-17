<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * مكون القالب الأساسي (AppLayout).
 *
 * يُستخدم في ملفات Blade عبر الوسم: <x-app-layout>
 * يرتبط بملف القالب: resources/views/layouts/app.blade.php
 *
 * === SEO Props ===
 * يقبل خصائص SEO اختيارية تُمرر من كل صفحة:
 * - title: عنوان الصفحة
 * - description: وصف الصفحة
 * - metaRobots: تعليمات لمحركات البحث (index/noindex)
 * - canonicalUrl: الرابط الأساسي
 * - ogType: نوع Open Graph (website/article)
 * - ogImage: صورة المشاركة
 * - schema: Schema.org JSON-LD مخصص
 * - extraHead: محتوى إضافي للـ <head>
 */
class AppLayout extends Component
{
    public function __construct(
        public string $title = 'منصة مفقودات وموجودات تعز — ابحث عن مفقوداتك في تعز',
        public string $description = 'منصة مفقودات وموجودات تعز — وجهتك الأولى والأكثر أماناً للبحث عن مفقوداتك أو الإعلان عما وجدته في محافظة تعز. ابحث، أعلن، وتواصل مباشرة.',
        public string $metaRobots = 'index, follow',
        public ?string $canonicalUrl = null,
        public string $ogType = 'website',
        public ?string $ogImage = null,
    ) {}

    /**
     * الحصول على View المرتبط بهذا المكون.
     *
     * @return View
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
