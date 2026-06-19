<?php

use App\Http\Controllers\QuoteController;
use App\Models\Product;
use App\Support\PublicAssetUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', function () {
    return Product::query()
        ->active()
        ->with('category:id,name,slug')
        ->select('id', 'category_id', 'name', 'slug', 'base_price', 'short_description', 'thumbnail')
        ->paginate(20);
});

Route::get('/product-suggestions', function (Request $request) {
    $search = trim($request->string('q')->toString());

    if (mb_strlen($search) < 2) {
        return response()->json([]);
    }

    return Product::query()
        ->active()
        ->with(['category:id,name,slug', 'categories:id,name,slug'])
        ->select('id', 'category_id', 'name', 'slug', 'base_price', 'short_description', 'thumbnail')
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('short_description', 'like', '%' . $search . '%')
                ->orWhere('metadata', 'like', '%' . $search . '%')
                ->orWhereHas('category', fn ($category) => $category->where('name', 'like', '%' . $search . '%'))
                ->orWhereHas('categories', fn ($category) => $category->where('name', 'like', '%' . $search . '%'));
        })
        ->orderByDesc('is_featured')
        ->orderBy('name')
        ->take(6)
        ->get()
        ->map(fn (Product $product) => [
            'name' => $product->name,
            'category' => $product->categories->pluck('name')->join(' / ') ?: $product->category?->name,
            'price' => config('shop.currency_symbol') . ' ' . number_format((float) $product->base_price, 2),
            'thumbnail' => PublicAssetUrl::resolve($product->thumbnail),
            'url' => route('products.show', $product),
        ])
        ->values();
});

Route::post('/quote', QuoteController::class)->name('api.quote');
