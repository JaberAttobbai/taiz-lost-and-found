<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItemController;
use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// TEMPORARY: Debug route to diagnose 500 error — REMOVE AFTER FIXING
Route::get('/debug-health', function () {
    $results = [];
    
    // Test 1: Basic response
    $results['php'] = 'OK';
    
    // Test 2: Database connection
    try {
        \DB::connection()->getPdo();
        $results['db_connection'] = 'OK (' . config('database.default') . ')';
    } catch (\Exception $e) {
        $results['db_connection'] = 'FAILED: ' . $e->getMessage();
    }
    
    // Test 3: Items count
    try {
        $results['items_count'] = \App\Models\Item::count();
    } catch (\Exception $e) {
        $results['items_count'] = 'FAILED: ' . $e->getMessage();
    }
    
    // Test 4: Categories count
    try {
        $results['categories_count'] = \App\Models\Category::count();
    } catch (\Exception $e) {
        $results['categories_count'] = 'FAILED: ' . $e->getMessage();
    }
    
    // Test 5: Items with relationships (what homepage does)
    try {
        $items = \App\Models\Item::with(['user', 'category', 'neighborhood'])->latest()->take(1)->get();
        $results['items_with_relations'] = 'OK (' . $items->count() . ' items)';
    } catch (\Exception $e) {
        $results['items_with_relations'] = 'FAILED: ' . $e->getMessage();
    }
    
    // Test 6: APP_URL
    $results['app_url'] = config('app.url');
    $results['app_env'] = config('app.env');
    
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
