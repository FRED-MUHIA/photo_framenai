<?php

use App\Http\Controllers\QuoteController;
use App\Models\Product;
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

Route::post('/quote', QuoteController::class)->name('api.quote');
