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

        $categories = Category::query()
            ->active()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredProducts = Product::query()
            ->active()
            ->with('category')
            ->where('is_featured', true)
            ->take(8)
            ->get();

        $topPickProducts = Product::query()
            ->active()
            ->with(['category', 'categories'])
            ->orderByDesc('is_featured')
            ->latest()
            ->take(12)
            ->get();

        $latestBlogPosts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get(['title', 'slug', 'excerpt', 'published_at']);

        return view('home', compact('categories', 'featuredProducts', 'topPickProducts', 'latestBlogPosts', 'homeSections', 'cmsHome'));
    }
}
