<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Support\AllProductsMegaMenu;
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

        $megaMenuJson = json_encode($megaMenu, JSON_PRETTY_PRINT);
        $productLinks = Product::query()
            ->active()
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => [
                'label' => $product->name,
                'url' => route('products.show', $product),
            ])
            ->values();

        return view('admin.site-settings.edit', compact('settings', 'megaMenuJson', 'productLinks'));
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
            'all_products_mega_menu_json' => ['nullable', 'json'],
        ]);

        if ($request->hasFile('header_logo_file')) {
            $path = $request->file('header_logo_file')->store('site', 'public');
            $validated['header_logo_url'] = asset('storage/' . $path);
        }

        unset($validated['header_logo_file']);

        $menuJson = $validated['all_products_mega_menu_json'] ?? null;
        unset($validated['all_products_mega_menu_json']);

        $menu = AllProductsMegaMenu::normalize(json_decode($menuJson ?: '[]', true));
        $validated['all_products_mega_menu'] = json_encode($menu, JSON_PRETTY_PRINT);

        SiteSetting::setValues($validated);

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return back()->with('status', 'Header and footer settings updated.');
    }
}
