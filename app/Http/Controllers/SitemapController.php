<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Neighborhood;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

/**
 * متحكم خريطة الموقع المحسّن (SitemapController).
 *
 * يولّد ملف sitemap.xml ديناميكياً لتحسين فهرسة محركات البحث (SEO).
 *
 * التحسينات:
 * - يشمل فقط الإعلانات النشطة (active) — يستبعد المسترجعة (returned)
 * - يدعم image:image للإعلانات التي تحتوي صور
 * - يستخدم config('app.url') مع fallback ذكي
 * - مخزن مؤقتاً (cached) لمدة 30 دقيقة
 * - يرسل X-Robots-Tag: noindex لمنع فهرسة ملف الـ sitemap نفسه
 */
class SitemapController extends Controller
{
    /**
     * الرابط الأساسي للموقع (production URL).
     * يُستخدم كـ fallback عندما يكون config('app.url') غير صحيح.
     */
    private const PRODUCTION_URL = 'https://taiz-lost-and-found.onrender.com';

    /**
     * توليد وإرجاع ملف sitemap.xml مع تخزين مؤقت.
     *
     * @return Response
     */
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_xml_v2', now()->addMinutes(30), function () {
            return $this->generateSitemap();
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('X-Robots-Tag', 'noindex')
            ->header('Cache-Control', 'public, max-age=1800'); // 30 minutes browser cache
    }

    /**
     * بناء محتوى XML لخريطة الموقع.
     *
     * يتضمن:
     * 1. الصفحة الرئيسية (priority: 1.0, changefreq: daily)
     * 2. الإعلانات النشطة فقط (priority: 0.8, changefreq: weekly)
     *    - مع دعم image:image للإعلانات التي تحتوي صور
     *
     * @return string محتوى XML
     */
    private function generateSitemap(): string
    {
        $baseUrl = $this->getBaseUrl();

        // جلب الإعلانات النشطة فقط — الإعلانات المسترجعة لا تُضاف للـ sitemap
        $items = Item::select('id', 'title', 'image_path', 'updated_at')
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->get();

        // بناء XML مع دعم مساحة أسماء الصور (image namespace)
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $xml .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        // === الصفحة الرئيسية (الأعلى أولوية) ===
        $xml .= $this->buildUrlEntry(
            loc: $baseUrl,
            lastmod: now()->toDateString(),
            changefreq: 'daily',
            priority: '1.0'
        );

        // === صفحات الإعلانات الفردية ===
        foreach ($items as $item) {
            $imageUrl = null;
            if ($item->image_path) {
                // بناء رابط الصورة الكامل
                $imageUrl = $baseUrl . '/storage/' . $item->image_path;
            }

            $xml .= $this->buildUrlEntry(
                loc: $baseUrl . '/items/' . $item->id,
                lastmod: $item->updated_at->format('Y-m-d'),
                changefreq: 'weekly',
                priority: '0.8',
                imageUrl: $imageUrl,
                imageTitle: $item->title
            );
        }

        $xml .= '</urlset>' . "\n";

        return $xml;
    }

    /**
     * بناء عنصر <url> واحد في الـ sitemap.
     *
     * @param string      $loc        الرابط الكامل
     * @param string      $lastmod    تاريخ آخر تعديل
     * @param string      $changefreq تكرار التحديث
     * @param string      $priority   الأولوية (0.0 - 1.0)
     * @param string|null $imageUrl   رابط الصورة (اختياري)
     * @param string|null $imageTitle عنوان الصورة (اختياري)
     * @return string
     */
    private function buildUrlEntry(
        string $loc,
        string $lastmod,
        string $changefreq,
        string $priority,
        ?string $imageUrl = null,
        ?string $imageTitle = null
    ): string {
        $entry = '  <url>' . "\n";
        $entry .= '    <loc>' . htmlspecialchars($loc) . '</loc>' . "\n";
        $entry .= '    <lastmod>' . $lastmod . '</lastmod>' . "\n";
        $entry .= '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $entry .= '    <priority>' . $priority . '</priority>' . "\n";

        // إضافة بيانات الصورة إذا وجدت (يساعد Google Images في فهرستها)
        if ($imageUrl) {
            $entry .= '    <image:image>' . "\n";
            $entry .= '      <image:loc>' . htmlspecialchars($imageUrl) . '</image:loc>' . "\n";
            if ($imageTitle) {
                $entry .= '      <image:title>' . htmlspecialchars($imageTitle) . '</image:title>' . "\n";
            }
            $entry .= '    </image:image>' . "\n";
        }

        $entry .= '  </url>' . "\n";

        return $entry;
    }

    /**
     * الحصول على الرابط الأساسي الصحيح.
     *
     * يستخدم config('app.url') إذا كان صحيحاً (ليس localhost)،
     * وإلا يستخدم الرابط الثابت كـ fallback.
     *
     * @return string
     */
    private function getBaseUrl(): string
    {
        $configUrl = config('app.url');

        // إذا كان config يحتوي رابط حقيقي (ليس localhost)
        if ($configUrl && !str_contains($configUrl, 'localhost') && !str_contains($configUrl, '127.0.0.1')) {
            return rtrim($configUrl, '/');
        }

        return self::PRODUCTION_URL;
    }
}
