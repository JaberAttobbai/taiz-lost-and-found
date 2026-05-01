<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate a dynamic XML sitemap with caching.
     *
     * Cached for 30 minutes to reduce DB load on Render.
     * Includes homepage + all public item detail pages.
     * Excludes login/register (low SEO value, auth pages).
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $xml = Cache::remember('sitemap_xml', now()->addMinutes(30), function () {
            return $this->generateSitemap();
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('X-Robots-Tag', 'noindex'); // Sitemap itself should not be indexed
    }

    /**
     * Build the sitemap XML string from database records.
     */
    private function generateSitemap(): string
    {
        // Hardcoded to production URL — config('app.url') returns localhost on Render
        // and url() is unreliable with caching (first request may be a health check)
        $baseUrl = 'https://taiz-lost-and-found.onrender.com';

        // Fetch all items with only the columns we need
        $items = Item::select('id', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // --- Homepage (highest priority) ---
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . $baseUrl . '</loc>' . "\n";
        $xml .= '    <lastmod>' . now()->toDateString() . '</lastmod>' . "\n";
        $xml .= '    <changefreq>daily</changefreq>' . "\n";
        $xml .= '    <priority>1.0</priority>' . "\n";
        $xml .= '  </url>' . "\n";

        // --- Individual item pages (dynamic from DB) ---
        foreach ($items as $item) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . $baseUrl . '/items/' . $item->id . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $item->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
            $xml .= '    <changefreq>weekly</changefreq>' . "\n";
            $xml .= '    <priority>0.8</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>' . "\n";

        return $xml;
    }
}
