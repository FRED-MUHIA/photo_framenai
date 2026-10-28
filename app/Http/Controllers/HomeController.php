<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Support\HomepageContentDefaults;

class HomeController extends Controller
{
    public function __invoke()
    {
        $cmsHome = CmsPage::query()->active()->where('slug', 'home')->first();

        $homeSections = HomepageContentDefaults::mergedSections($cmsHome?->sections);
        $storefrontDefaults = collect(HomepageContentDefaults::storefrontSections())->keyBy('component');
        $storefrontContent = collect($cmsHome?->sections ?? [])
            ->filter(fn ($section) => is_array($section) && isset($section['component']))
            ->keyBy('component');
        $storefrontSections = $storefrontDefaults->map(function (array $defaults, string $component) use ($storefrontContent) {
            $saved = $storefrontContent->get($component, []);
            $resolved = array_merge($defaults, $saved);
            if (isset($saved['items']) && is_array($saved['items'])) {
                $resolved['items'] = $saved['items'];
            }

            return $resolved;
        });
        $homepageCategorySlugs = collect(data_get($storefrontSections->get('storefront_categories'), 'category_slugs', []))
            ->filter(fn ($slug) => is_string($slug) && trim($slug) !== '')
            ->values();

        $activeProductImages = fn ($query) => $query->active()
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->orderByDesc('is_featured')
            ->orderBy('name');

        $categories = Category::query()
            ->active()
            ->when($homepageCategorySlugs->isNotEmpty(), fn ($query) => $query->whereIn('slug', $homepageCategorySlugs))
            ->where(fn ($query) => $query
                ->whereHas('products', fn ($products) => $products->active())
                ->orWhereHas('linkedProducts', fn ($products) => $products->active()))
            ->with([
                'products' => $activeProductImages,
                'linkedProducts' => $activeProductImages,
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(12)
            ->get();

        $frameTypeProducts = $categories
            ->flatMap(fn (Category $category) => $category->products->merge($category->linkedProducts))
            ->unique('id')
            ->take(12)
            ->values();

        $featuredProducts = Product::query()
            ->active()
            ->with('category')
            ->where('is_featured', true)
            ->take(8)
            ->get();

        $topPickProducts = Product::query()
            ->active()
            ->where(function ($query) {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->active())
                    ->orWhereHas('categories', fn ($categoryQuery) => $categoryQuery->active());
            })
            ->with(['category', 'categories'])
            ->inRandomOrder()
            ->take(40)
            ->get();

        $sliderProducts = Product::query()->active()
            ->whereNotNull('thumbnail')->where('thumbnail', '!=', '')
            ->inRandomOrder()->take(8)->get();

        $latestBlogPosts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get(['title', 'slug', 'excerpt', 'published_at']);

        return view('home', compact('categories', 'frameTypeProducts', 'featuredProducts', 'topPickProducts', 'sliderProducts', 'latestBlogPosts', 'homeSections', 'storefrontSections', 'cmsHome'));
    }
}
