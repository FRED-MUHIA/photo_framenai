<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Support\AllProductsMegaMenu;
use App\Support\HeaderThemeMegaMenu;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

        return view('admin.site-settings.edit', compact('settings', 'megaMenuJson', 'themeMegaMenuJson', 'productLinks', 'menuLinkOptions'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'header_shipping_text' => ['nullable', 'string', 'max:190'],
            'header_experience_text' => ['nullable', 'string', 'max:190'],
            'header_production_text' => ['nullable', 'string', 'max:190'],
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
            'header_theme_mega_menu_images' => ['nullable', 'array'],
            'header_theme_mega_menu_images.*' => ['nullable', 'array'],
            'header_theme_mega_menu_images.*.*' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('header_logo_file')) {
            $path = $request->file('header_logo_file')->store('site', 'public');
            ImageOptimizer::optimizePublicPath($path);
            $validated['header_logo_url'] = '/storage/' . $path;
        }

        if ($request->hasFile('whatsapp_logo_file')) {
            $path = $request->file('whatsapp_logo_file')->store('site', 'public');
            ImageOptimizer::optimizePublicPath($path);
            $validated['whatsapp_logo_url'] = '/storage/' . $path;
        }

        unset($validated['header_logo_file']);
        unset($validated['whatsapp_logo_file']);
        unset($validated['header_theme_mega_menu_images']);

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
                ImageOptimizer::optimizePublicPath($path);
                $themeMenuPayload[$menuIndex]['items'][$tileIndex]['image'] = '/storage/' . $path;
            }
        }

        $themeMenu = HeaderThemeMegaMenu::normalize($themeMenuPayload);
        $validated['header_theme_mega_menu'] = json_encode($themeMenu, JSON_PRETTY_PRINT);

        SiteSetting::setValues($validated);

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return back()->with('status', 'Header and footer settings updated.');
    }
}
