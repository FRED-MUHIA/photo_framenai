<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Support\AllProductsMegaMenu;
use App\Support\HeaderThemeMegaMenu;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::map();
        $savedMenu = json_decode($settings->get('all_products_mega_menu', ''), true);
        $megaMenu = AllProductsMegaMenu::normalize($savedMenu);

        if (! $settings->has('all_products_mega_menu')) {
            $megaMenu = AllProductsMegaMenu::defaults();
        }

        $savedThemeMenu = json_decode($settings->get('header_theme_mega_menu', ''), true);
        $themeMegaMenu = HeaderThemeMegaMenu::normalize($savedThemeMenu);

        if (! $settings->has('header_theme_mega_menu')) {
            $themeMegaMenu = HeaderThemeMegaMenu::defaults();
        }

        $megaMenuJson = json_encode($megaMenu, JSON_PRETTY_PRINT);
        $themeMegaMenuJson = json_encode($themeMegaMenu, JSON_PRETTY_PRINT);
        $headerNavDefaults = [
            ['label' => 'All Frames', 'url' => '/products'],
            ['label' => 'Popular Frames', 'url' => '/products', 'items' => [['label' => 'Floating Frames', 'url' => '/products?q=floating'], ['label' => 'Wood Frames', 'url' => '/products?q=wood'], ['label' => 'Metal Frames', 'url' => '/products?q=metal'], ['label' => 'Ornate Frames', 'url' => '/products?q=ornate']]],
            ['label' => 'Customize Picture', 'url' => '/customize-frame'],
            ['label' => 'Shop Frames by', 'url' => '/products', 'items' => [['label' => 'Frame Types', 'url' => '/products'], ['label' => 'Frame Colors', 'url' => '/products'], ['label' => 'Decor Styles', 'url' => '/products']]],
            ['label' => 'Custom Prints', 'url' => '/products', 'items' => [['label' => 'Canvas Prints', 'url' => '/products?q=canvas'], ['label' => 'Paper Prints', 'url' => '/products?q=paper'], ['label' => 'Metal Prints', 'url' => '/products?q=metal'], ['label' => 'Wood Prints', 'url' => '/products?q=wood']]],
            ['label' => 'Resources', 'url' => '/help', 'items' => [['label' => 'Help & FAQs', 'url' => '/help'], ['label' => 'Contact Us', 'url' => '/contact']]],
            ['label' => 'Clearance', 'url' => '/products?q=clearance'],
        ];
        $savedHeaderNav = json_decode($settings->get('header_nav_json', ''), true);
        $headerNavJson = json_encode(is_array($savedHeaderNav) ? $savedHeaderNav : $headerNavDefaults, JSON_PRETTY_PRINT);
        $footerColumnsDefaults = [
            ['heading' => 'About Us', 'links' => [['label' => 'Our story', 'url' => '/about'], ['label' => 'Why Photo Frames', 'url' => '/about'], ['label' => 'Ideas & inspiration', 'url' => '/blog'], ['label' => 'Get in touch', 'url' => '/contact']]],
            ['heading' => 'Support', 'links' => [['label' => 'Contact us', 'url' => '/contact'], ['label' => 'Framing help', 'url' => '/help'], ['label' => 'Shipping & returns', 'url' => '/help'], ['label' => 'FAQs', 'url' => '/help'], ['label' => 'My orders', 'url' => '/login']]],
            ['heading' => 'Find Your Frame', 'links' => [['label' => 'Shop by type', 'url' => '#frame-types'], ['label' => 'Shop by color', 'url' => '#frame-colors'], ['label' => 'Shop by décor', 'url' => '#frame-decor'], ['label' => 'Custom prints', 'url' => '#custom-prints'], ['label' => 'Explore all frames', 'url' => '/products']]],
        ];
        $savedFooterColumns = json_decode($settings->get('footer_columns_json', ''), true);
        $footerColumnsJson = json_encode(is_array($savedFooterColumns) ? $savedFooterColumns : $footerColumnsDefaults, JSON_PRETTY_PRINT);
        $productLinks = Product::query()
            ->active()
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => [
                'label' => $product->name,
                'url' => route('products.show', $product),
            ])
            ->values();

        $menuLinkOptions = collect([
            ['label' => 'All Products', 'url' => route('products.index')],
            ['label' => 'Brochures / Flyers', 'url' => route('products.index', ['category' => 'flyers-brochures'])],
            ['label' => 'Posters', 'url' => route('products.index', ['category' => 'posters'])],
            ['label' => 'Banners', 'url' => route('products.index', ['category' => 'banners'])],
            ['label' => 'Business Cards', 'url' => route('products.index', ['category' => 'business-cards'])],
            ['label' => 'Branded Folders Page', 'url' => route('pages.branded-folders')],
            ['label' => 'Certificates Page', 'url' => route('pages.certificates')],
            ['label' => 'Custom Receipts Page', 'url' => route('pages.custom-receipts')],
            ['label' => 'Envelopes Page', 'url' => route('pages.envelopes')],
            ['label' => 'Complementary Slips Page', 'url' => route('pages.token-business')],
            ['label' => 'Design Services Page', 'url' => route('pages.design-services')],
            ['label' => 'Help Page', 'url' => route('pages.help')],
        ])->concat($productLinks)->values();

        return view('admin.site-settings.edit', compact('settings', 'megaMenuJson', 'themeMegaMenuJson', 'productLinks', 'menuLinkOptions', 'headerNavJson', 'footerColumnsJson'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:90'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'favicon_url' => ['nullable', 'string', 'max:255'],
            'favicon_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,ico', 'max:2048'],
            'header_shipping_text' => ['nullable', 'string', 'max:190'],
            'header_experience_text' => ['nullable', 'string', 'max:190'],
            'header_production_text' => ['nullable', 'string', 'max:190'],
            'header_phone' => ['nullable', 'string', 'max:50'],
            'header_email' => ['nullable', 'email', 'max:190'],
            'header_location' => ['nullable', 'string', 'max:100'],
            'header_announcement' => ['nullable', 'string', 'max:190'],
            'header_announcement_detail' => ['nullable', 'string', 'max:190'],
            'header_track_order_label' => ['nullable', 'string', 'max:50'],
            'header_home_link_label' => ['nullable', 'string', 'max:60'],
            'header_search_placeholder' => ['nullable', 'string', 'max:120'],
            'header_live_chat_label' => ['nullable', 'string', 'max:50'],
            'header_brand_label' => ['nullable', 'string', 'max:90'],
            'header_logo_url' => ['nullable', 'string', 'max:255'],
            'header_logo_file' => ['nullable', 'image', 'max:4096'],
            'footer_brand_name' => ['nullable', 'string', 'max:90'],
            'footer_about_text' => ['nullable', 'string', 'max:255'],
            'footer_copyright_text' => ['nullable', 'string', 'max:190'],
            'whatsapp_label' => ['nullable', 'string', 'max:50'],
            'whatsapp_link' => ['nullable', 'url', 'max:255'],
            'whatsapp_logo_url' => ['nullable', 'string', 'max:255'],
            'whatsapp_logo_file' => ['nullable', 'image', 'max:4096'],
            'all_products_mega_menu_json' => ['nullable', 'json'],
            'header_theme_mega_menu_json' => ['nullable', 'json'],
            'header_nav_json' => ['nullable', 'json'],
            'footer_columns_json' => ['nullable', 'json'],
            'footer_contact_heading' => ['nullable', 'string', 'max:120'],
            'footer_contact_text' => ['nullable', 'string', 'max:255'],
            'footer_contact_button' => ['nullable', 'string', 'max:60'],
            'footer_contact_email' => ['nullable', 'email', 'max:190'],
            'footer_location_text' => ['nullable', 'string', 'max:120'],
            'footer_bottom_tagline' => ['nullable', 'string', 'max:190'],
            'footer_back_to_top_label' => ['nullable', 'string', 'max:50'],
            'footer_powered_by_label' => ['nullable', 'string', 'max:90'],
            'footer_powered_by_url' => ['nullable', 'string', 'max:255'],
            'header_theme_mega_menu_images' => ['nullable', 'array'],
            'header_theme_mega_menu_images.*' => ['nullable', 'array'],
            'header_theme_mega_menu_images.*.*' => ['nullable', 'image', 'max:4096'],
            'header_nav_images' => ['nullable', 'array'],
            'header_nav_images.*' => ['nullable', 'array'],
            'header_nav_images.*.*' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('favicon_file')) {
            $path = $request->file('favicon_file')->store('site/favicon', 'public');
            $validated['favicon_url'] = '/storage/' . $path;
        }

        if ($request->hasFile('header_logo_file')) {
            $path = $request->file('header_logo_file')->store('site', 'public');
            ImageOptimizer::optimizeCommonPublicVariants($path);
            $validated['header_logo_url'] = '/storage/' . $path;
        }

        if ($request->hasFile('whatsapp_logo_file')) {
            $path = $request->file('whatsapp_logo_file')->store('site', 'public');
            ImageOptimizer::optimizeCommonPublicVariants($path);
            $validated['whatsapp_logo_url'] = '/storage/' . $path;
        }

        unset($validated['favicon_file']);
        unset($validated['header_logo_file']);
        unset($validated['whatsapp_logo_file']);
        unset($validated['header_theme_mega_menu_images']);
        unset($validated['header_nav_images']);

        $poweredByUrl = trim((string) ($validated['footer_powered_by_url'] ?? ''));
        $validated['footer_powered_by_url'] = $poweredByUrl !== '' ? $this->safeMenuUrl($poweredByUrl) : '';

        $menuJson = $validated['all_products_mega_menu_json'] ?? null;
        unset($validated['all_products_mega_menu_json']);

        $menu = AllProductsMegaMenu::normalize(json_decode($menuJson ?: '[]', true));
        $validated['all_products_mega_menu'] = json_encode($menu, JSON_PRETTY_PRINT);

        $themeMenuJson = $validated['header_theme_mega_menu_json'] ?? null;
        unset($validated['header_theme_mega_menu_json']);

        $themeMenuPayload = json_decode($themeMenuJson ?: '[]', true);

        if (! is_array($themeMenuPayload)) {
            $themeMenuPayload = [];
        }

        foreach ($request->file('header_theme_mega_menu_images', []) as $menuIndex => $tileFiles) {
            if (! is_array($tileFiles)) {
                continue;
            }

            foreach ($tileFiles as $tileIndex => $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }

                if (! isset($themeMenuPayload[$menuIndex]['items'][$tileIndex])) {
                    continue;
                }

                $path = $file->store('site/mega-menu', 'public');
                ImageOptimizer::optimizeCommonPublicVariants($path);
                $themeMenuPayload[$menuIndex]['items'][$tileIndex]['image'] = '/storage/' . $path;
            }
        }

        $themeMenu = HeaderThemeMegaMenu::normalize($themeMenuPayload);
        $validated['header_theme_mega_menu'] = json_encode($themeMenu, JSON_PRETTY_PRINT);

        $headerNavPayload = json_decode($validated['header_nav_json'] ?? '[]', true);
        unset($validated['header_nav_json']);
        if (! is_array($headerNavPayload)) {
            $headerNavPayload = [];
        }
        foreach ($request->file('header_nav_images', []) as $menuIndex => $childFiles) {
            if (! is_array($childFiles)) {
                continue;
            }
            foreach ($childFiles as $childIndex => $file) {
                if (! $file || ! $file->isValid() || ! isset($headerNavPayload[$menuIndex]['items'][$childIndex])) {
                    continue;
                }
                $path = $file->store('site/header-menu', 'public');
                ImageOptimizer::optimizeCommonPublicVariants($path);
                $headerNavPayload[$menuIndex]['items'][$childIndex]['image'] = '/storage/' . $path;
            }
        }
        $headerNavPayload = collect($headerNavPayload)->filter(fn ($item) => is_array($item))
            ->map(fn ($item) => [
                'label' => mb_substr(trim((string) ($item['label'] ?? '')), 0, 90),
                'url' => $this->safeMenuUrl($item['url'] ?? '/'),
                'items' => collect(is_array($item['items'] ?? null) ? $item['items'] : [])
                    ->filter(fn ($child) => is_array($child))
                    ->map(fn ($child) => [
                        'label' => mb_substr(trim((string) ($child['label'] ?? '')), 0, 90),
                        'url' => $this->safeMenuUrl($child['url'] ?? '/'),
                        'image' => trim((string) ($child['image'] ?? '')),
                    ])->values()->all(),
            ])->values()->all();
        $validated['header_nav'] = json_encode($headerNavPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        foreach (['footer_columns_json' => 'footer_columns'] as $input => $setting) {
            $decoded = json_decode($validated[$input] ?? '[]', true);
            unset($validated[$input]);
            $decoded = is_array($decoded) ? array_values($decoded) : [];
            $decoded = collect($decoded)->filter(fn ($column) => is_array($column))
                ->map(fn ($column) => [
                    'heading' => mb_substr(trim((string) ($column['heading'] ?? '')), 0, 100),
                    'links' => collect(is_array($column['links'] ?? null) ? $column['links'] : [])
                        ->filter(fn ($link) => is_array($link))
                        ->map(fn ($link) => ['label' => mb_substr(trim((string) ($link['label'] ?? '')), 0, 100), 'url' => $this->safeMenuUrl($link['url'] ?? '/')])
                        ->values()->all(),
                ])->values()->all();
            $validated[$setting] = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        SiteSetting::setValues($validated);

        return back()->with('status', 'Site identity, header, and footer settings updated.');
    }

    private function safeMenuUrl(mixed $url): string
    {
        $url = trim((string) $url);
        if (preg_match('~^(https?://|mailto:|tel:|/|#)~i', $url) && ! str_starts_with($url, '//')) {
            return mb_substr($url, 0, 255);
        }

        return '/';
    }
}
