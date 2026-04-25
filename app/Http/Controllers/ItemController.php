<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Neighborhood;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ItemController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::with(['user', 'category', 'neighborhood'])->latest();

        // 1. Filter by Keyword (Title or Description)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search . '%';
            $q->where(function($query) use ($searchTerm) {
                $query->where('title', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm);
            });
        });

        // 2. Filter by Type (lost/found)
        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->type);
        });

        // 3. Filter by Category
        $query->when($request->filled('category_id'), function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        // 4. Filter by Neighborhood
        $query->when($request->filled('neighborhood_id'), function ($q) use ($request) {
            $q->where('neighborhood_id', $request->neighborhood_id);
        });

        $items = $query->paginate(12)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();

        return view('items.index', compact('items', 'categories', 'neighborhoods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();

        return view('items.create', compact('categories', 'neighborhoods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'active';

        Item::create($validated);

        return redirect()->route('home')->with('success', '🎉 تم نشر إعلانك بنجاح! سيظهر الآن للجميع.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['user', 'category', 'neighborhood']);
        
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        abort_if(auth()->id() !== $item->user_id, 403, 'غير مصرح لك بتعديل هذا الإعلان.');

        $categories = Category::orderBy('name')->get();
        $neighborhoods = Neighborhood::orderBy('name')->get();

        return view('items.edit', compact('item', 'categories', 'neighborhoods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);

        return redirect()->route('items.show', $item)->with('success', '✅ تم حفظ التعديلات على إعلانك بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        abort_if(auth()->id() !== $item->user_id, 403, 'غير مصرح لك بحذف هذا الإعلان.');

        $item->delete();

        return redirect()->route('home')->with('success', '🗑️ تم حذف الإعلان بشكل نهائي.');
    }
}
