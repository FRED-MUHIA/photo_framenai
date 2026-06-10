<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorefrontBreadcrumbs
{
    public static function forCurrentRequest(Request $request): array
    {
        $route = $request->route();
        $routeName = $route?->getName();

        if (! $routeName || $routeName === 'home' || Str::startsWith($routeName, ['admin.', 'locale.', 'storage.'])) {
            return [];
        }

        return match ($routeName) {
            'products.index' => self::productsIndex($request),
            'products.show' => self::product($route->parameter('product')),
            'pages.blog.show' => self::blogPost($route->parameter('post')),
            'pages.design-projects.show' => self::designProject((string) $route->parameter('project')),
            'checkout.success' => self::simple('Order Complete'),
            default => self::namedRoute($routeName),
        };
    }

    public static function toSchema(array $breadcrumbs): array
    {
        $items = collect($breadcrumbs)
            ->values()
            ->map(fn (array $item, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label'],
                'item' => $item['url'] ?? url()->current(),
            ])
            ->all();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    private static function productsIndex(Request $request): array
    {
        $breadcrumbs = self::base();
        $categorySlug = (string) $request->query('category', '');

        if ($categorySlug === '') {
            $breadcrumbs[] = ['label' => 'Products'];

            return $breadcrumbs;
        }

        $category = Category::query()
            ->active()
            ->where('slug', $categorySlug)
            ->first();

        $breadcrumbs[] = ['label' => 'Products', 'url' => route('products.index')];
        $breadcrumbs[] = ['label' => $category?->name ?: Str::headline($categorySlug)];

        return $breadcrumbs;
    }

    private static function product(?Product $product): array
    {
        if (! $product) {
            return self::namedRoute('products.show');
        }

        $product->loadMissing(['category', 'categories']);

        $breadcrumbs = self::base();
        $categories = collect([$product->category])
            ->filter()
            ->merge($product->categories)
            ->unique('id')
            ->values();

        if ($categories->isEmpty()) {
            $breadcrumbs[] = ['label' => 'Products', 'url' => route('products.index')];
        }

        foreach ($categories as $category) {
            $breadcrumbs[] = [
                'label' => $category->name,
                'url' => route('products.index', ['category' => $category->slug]),
            ];
        }

        $breadcrumbs[] = ['label' => $product->name];

        return $breadcrumbs;
    }

    private static function blogPost(?BlogPost $post): array
    {
        return [
            ...self::base(),
            ['label' => 'Blog', 'url' => route('pages.blog')],
            ['label' => $post?->title ?: 'Post'],
        ];
    }

    private static function designProject(string $project): array
    {
        return [
            ...self::base(),
            ['label' => 'Design Services', 'url' => route('pages.design-services')],
            ['label' => Str::headline($project)],
        ];
    }

    private static function namedRoute(string $routeName): array
    {
        $labels = [
            'artwork.create' => 'Upload Artwork',
            'cart.index' => 'Cart',
            'checkout.index' => 'Checkout',
            'dashboard' => 'Dashboard',
            'dashboard.orders.invoice' => 'Invoice',
            'pages.about' => 'About Us',
            'pages.blog' => 'Blog',
            'pages.branded-folders' => 'Branded Folders',
            'pages.certificates' => 'Certificates',
            'pages.contact' => 'Contact',
            'pages.custom-receipts' => 'Custom Branded Receipts, Invoices & Delivery Books',
            'pages.design-services' => 'Design Services',
            'pages.envelopes' => 'Envelopes',
            'pages.flyers-posters' => 'Flyers & Posters',
            'pages.help' => 'Help Center',
            'pages.token-business' => 'Complementary Slips',
            'profile.edit' => 'Profile',
        ];

        return self::simple($labels[$routeName] ?? Str::headline(Str::afterLast($routeName, '.')));
    }

    private static function simple(string $label): array
    {
        return [
            ...self::base(),
            ['label' => $label],
        ];
    }

    private static function base(): array
    {
        return [
            ['label' => 'Home', 'url' => route('home')],
        ];
    }
}
