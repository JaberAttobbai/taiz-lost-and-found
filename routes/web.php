<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// TEMPORARY: Debug route to diagnose 500 error — REMOVE AFTER FIXING
Route::get('/debug-health', function () {
    $results = [];
    $results['php'] = 'OK';
    
    try {
        \DB::connection()->getPdo();
        $results['db_connection'] = 'OK (' . config('database.default') . ')';
    } catch (\Exception $e) {
        $results['db_connection'] = 'FAILED: ' . $e->getMessage();
    }
    
    $results['items_count'] = \App\Models\Item::count();
    $results['categories_count'] = \App\Models\Category::count();
    $results['neighborhoods_count'] = \App\Models\Neighborhood::count();
    $results['app_url'] = config('app.url');
    
    // Test: Actually render the homepage view (this is what's failing)
    try {
        $query = \App\Models\Item::with(['user', 'category', 'neighborhood'])->latest();
        $items = $query->paginate(12);
        $categories = \App\Models\Category::orderBy('name')->get();
        $neighborhoods = \App\Models\Neighborhood::orderBy('name')->get();
        $html = view('items.index', compact('items', 'categories', 'neighborhoods'))->render();
        $results['view_render'] = 'OK (' . strlen($html) . ' bytes)';
    } catch (\Throwable $e) {
        $results['view_render'] = 'FAILED: ' . $e->getMessage() . ' in ' . basename($e->getFile()) . ':' . $e->getLine();
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

Route::get('/', [ItemController::class, 'index'])->name('home');

Route::resource('items', ItemController::class);

Route::get('/dashboard', function () {
    $items = request()->user()->items()->latest()->get();
    return view('dashboard', compact('items'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
