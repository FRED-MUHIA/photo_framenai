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

        $activeProductImages = fn ($query) => $query->active()
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->orderByDesc('is_featured')
            ->orderBy('name');

        $categories = Category::query()
            ->active()
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

        $imageIsAvailable = static function (?string $url): bool {
            if (! $url) {
                return false;
            }

            $parts = parse_url($url) ?: [];
            $host = strtolower($parts['host'] ?? '');
            $appHost = strtolower(parse_url((string) config('app.url'), PHP_URL_HOST) ?: '');
            if ($host !== '' && $appHost !== '' && $host !== $appHost) {
                return true;
            }

            $path = rawurldecode($parts['path'] ?? $url);

            return is_file(public_path(ltrim($path, '/')));
        };

        $categories->each(function (Category $category) use ($imageIsAvailable): void {
            $productImages = $category->products->pluck('thumbnail')
                ->merge($category->linkedProducts->pluck('thumbnail'));
            $category->setAttribute('homepage_image', collect([$category->image])
                ->merge($productImages)
                ->first($imageIsAvailable));
        });

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

        return view('home', compact('categories', 'featuredProducts', 'topPickProducts', 'sliderProducts', 'latestBlogPosts', 'homeSections', 'storefrontSections', 'cmsHome'));
    }
}
