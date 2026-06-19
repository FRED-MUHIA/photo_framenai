<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly PricingService $pricingService)
    {
    }

    public function index(Request $request)
    {
        $search = $request->string('q')->toString();
        $categorySlug = $request->string('category')->toString();

        $products = Product::query()
            ->active()
            ->with(['category', 'categories'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('short_description', 'like', '%' . $search . '%')
                        ->orWhere('metadata', 'like', '%' . $search . '%');
                });
            })
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->where(function ($inner) use ($categorySlug) {
                    $inner->whereHas('category', fn ($category) => $category->where('slug', $categorySlug))
                        ->orWhereHas('categories', fn ($category) => $category->where('slug', $categorySlug));
                });
            })
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->active()->orderBy('sort_order')->get();
        $selectedCategory = $categories->firstWhere('slug', $categorySlug);

        return view('products.index', compact('products', 'categories', 'search', 'categorySlug', 'selectedCategory'));
    }

    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $product->load([
            'category',
            'categories',
            'mediaItems',
            'optionGroups.values',
            'quantityPrices',
        ]);

        $defaultSelections = $product->defaultSelections();
        $minQuantity = max(1, (int) $product->min_quantity);
        $quantitySuggestions = $product->availableQuantities()->map(fn ($quantity) => (int) $quantity)->values();
        $defaultQuantity = (int) ($quantitySuggestions->first() ?? $product->min_quantity);
        $defaultQuantity = max($minQuantity, $defaultQuantity);
        $defaultQuote = $this->pricingService->quote($product, $defaultSelections, $defaultQuantity);
        $relatedCategoryIds = $product->categories
            ->pluck('id')
            ->when($product->category_id, fn ($ids) => $ids->push($product->category_id))
            ->unique()
            ->values();
        $relatedProducts = Product::query()
            ->active()
            ->with(['category', 'categories'])
            ->whereKeyNot($product->id)
            ->when($relatedCategoryIds->isNotEmpty(), function ($query) use ($relatedCategoryIds) {
                $query->where(function ($inner) use ($relatedCategoryIds) {
                    $inner->whereIn('category_id', $relatedCategoryIds)
                        ->orWhereHas('categories', fn ($category) => $category->whereIn('categories.id', $relatedCategoryIds));
                });
            })
            ->orderByDesc('is_featured')
            ->latest()
            ->take(4)
            ->get();
        $artworks = Artwork::query()
            ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()))
            ->when(! auth()->check(), fn ($query) => $query->where('guest_token', session()->getId()))
            ->latest()
            ->take(8)
            ->get();

        return view('products.show', [
            'product' => $product,
            'defaultSelections' => $defaultSelections,
            'defaultQuantity' => $defaultQuantity,
            'defaultQuote' => $defaultQuote,
            'quantitySuggestions' => $quantitySuggestions,
            'relatedProducts' => $relatedProducts,
            'artworks' => $artworks,
        ]);
    }
}
