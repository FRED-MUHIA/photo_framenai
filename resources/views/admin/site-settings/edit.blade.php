@extends('layouts.admin')

@section('heading', 'Site Identity, Header, Footer & Menu Settings')

@section('content')
@php
    $initialMegaMenu = json_decode(old('all_products_mega_menu_json', $megaMenuJson), true);
    if (! is_array($initialMegaMenu)) {
        $initialMegaMenu = [];
    }
    $initialThemeMegaMenu = json_decode(old('header_theme_mega_menu_json', $themeMegaMenuJson), true);
    if (! is_array($initialThemeMegaMenu)) {
        $initialThemeMegaMenu = [];
    }
    $initialHeaderNav = json_decode(old('header_nav_json', $headerNavJson), true);
    if (! is_array($initialHeaderNav)) {
        $initialHeaderNav = [];
    }
    $initialFooterColumns = json_decode(old('footer_columns_json', $footerColumnsJson), true);
    if (! is_array($initialFooterColumns)) {
        $initialFooterColumns = [];
    }
    $headerLogoValue = old('header_logo_url', $settings->get('header_logo_url'));
    $headerLogoPreview = \App\Support\PublicAssetUrl::resolve($headerLogoValue);
    $whatsappLogoValue = old('whatsapp_logo_url', $settings->get('whatsapp_logo_url'));
    $whatsappLogoPreview = \App\Support\PublicAssetUrl::resolve($whatsappLogoValue);
    $faviconValue = old('favicon_url', $settings->get('favicon_url'));
    $faviconPreview = \App\Support\PublicAssetUrl::resolve($faviconValue) ?: asset('favicon.svg');
@endphp

<form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="space-y-6 rounded-xl bg-white p-5 shadow-sm">
    @csrf
    @method('PUT')

    <section>
        <h2 class="text-lg font-bold">Site Identity & SEO</h2>
        <p class="mt-1 text-xs text-slate-500">These values control the public header brand, default page title, search description, and browser icon.</p>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <div>
                <label for="site_name" class="mb-2 block text-sm font-bold">Site Name</label>
                <input id="site_name" type="text" name="site_name" value="{{ old('site_name', $settings->get('site_name', config('app.name', 'Photo Frames'))) }}" class="w-full rounded-xl border-slate-300" placeholder="Photo Frames">
            </div>
            <div>
                <label for="site_tagline" class="mb-2 block text-sm font-bold">Tagline / Default SEO Description</label>
                <input id="site_tagline" type="text" name="site_tagline" value="{{ old('site_tagline', $settings->get('site_tagline', config('app.tagline'))) }}" class="w-full rounded-xl border-slate-300" placeholder="Your site tagline">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-bold">Favicon</label>
                <div class="mb-3 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <img loading="lazy" decoding="async" src="{{ $faviconPreview }}" alt="Current favicon" class="h-12 w-12 rounded-lg object-contain">
                    <p class="text-xs text-slate-500">Use a square PNG, JPG, WebP, or ICO image, up to 2 MB.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <input type="file" name="favicon_file" accept=".png,.jpg,.jpeg,.webp,.ico,image/png,image/jpeg,image/webp,image/x-icon,image/vnd.microsoft.icon" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    <input type="text" name="favicon_url" value="{{ $faviconValue }}" class="rounded-xl border-slate-300" placeholder="Or paste a favicon URL">
                </div>
            </div>
        </div>
    </section>

    <section>
        <h2 class="text-lg font-bold">Header</h2>
        <p class="mt-1 text-xs text-slate-500">These values control the public storefront header and main navigation menu.</p>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <input type="text" name="header_live_chat_label" value="{{ old('header_live_chat_label', $settings->get('header_live_chat_label', 'Live Chat')) }}" class="rounded-xl border-slate-300" placeholder="Live chat label">
            <input type="text" name="header_brand_label" value="{{ old('header_brand_label', $settings->get('header_brand_label', $settings->get('site_name', 'Photo Frames'))) }}" class="rounded-xl border-slate-300" placeholder="Header brand label">
            <input type="text" name="header_phone" value="{{ old('header_phone', $settings->get('header_phone', '+254 745 730 906')) }}" class="rounded-xl border-slate-300" placeholder="Phone shown in header">
            <input type="email" name="header_email" value="{{ old('header_email', $settings->get('header_email', 'orders@photostore.co.ke')) }}" class="rounded-xl border-slate-300" placeholder="Email shown in header">
            <input type="text" name="header_location" value="{{ old('header_location', $settings->get('header_location', 'Nairobi, Kenya')) }}" class="rounded-xl border-slate-300" placeholder="Location shown in header">
            <input type="text" name="header_announcement" value="{{ old('header_announcement', $settings->get('header_announcement', 'Your memories. Beautifully framed.')) }}" class="rounded-xl border-slate-300" placeholder="Header announcement">
            <input type="text" name="header_announcement_detail" value="{{ old('header_announcement_detail', $settings->get('header_announcement_detail', 'Made for your space, delivered across Kenya.')) }}" class="rounded-xl border-slate-300" placeholder="Header announcement detail">
            <input type="text" name="header_track_order_label" value="{{ old('header_track_order_label', $settings->get('header_track_order_label', 'Track order')) }}" class="rounded-xl border-slate-300" placeholder="Track order label">
            <input type="text" name="header_home_link_label" value="{{ old('header_home_link_label', $settings->get('header_home_link_label', '← Back to homepage')) }}" class="rounded-xl border-slate-300" placeholder="Compact page back link">
            <input type="text" name="header_search_placeholder" value="{{ old('header_search_placeholder', $settings->get('header_search_placeholder', 'Search frames by color, material, style…')) }}" class="rounded-xl border-slate-300 sm:col-span-2" placeholder="Search placeholder">
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-bold">Header Logo</label>
                @if($headerLogoPreview)
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <img loading="lazy" decoding="async" src="{{ $headerLogoPreview }}" alt="Header logo" class="h-12 w-auto max-w-[220px] object-contain">
                    </div>
                @endif
                <div class="grid gap-3 sm:grid-cols-2">
                    <input type="file" name="header_logo_file" accept="image/*" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    <input type="text" name="header_logo_url" value="{{ $headerLogoValue }}" class="rounded-xl border-slate-300" placeholder="Or paste logo URL">
                </div>
            </div>
            <input type="text" name="header_shipping_text" value="{{ old('header_shipping_text', $settings->get('header_shipping_text', 'Free standard shipping')) }}" class="rounded-xl border-slate-300" placeholder="Shipping text">
            <input type="text" name="header_experience_text" value="{{ old('header_experience_text', $settings->get('header_experience_text', '40 years of experience')) }}" class="rounded-xl border-slate-300" placeholder="Experience text">
            <input type="text" name="header_production_text" value="{{ old('header_production_text', $settings->get('header_production_text', 'Our own production')) }}" class="rounded-xl border-slate-300 sm:col-span-2" placeholder="Production text">
        </div>
    </section>

    <section class="rounded-xl border border-slate-200 p-4">
        <div x-data="storefrontNavigationBuilder(@js($initialHeaderNav))">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div><h2 class="text-lg font-bold">Storefront Navigation Menu</h2><p class="mt-1 text-xs text-slate-500">Add, remove, and rename links. Add child links to show a dropdown. Use site paths such as /products or full URLs.</p></div>
                <button type="button" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white" @click="addItem()">Add menu link</button>
            </div>
            <div class="mt-3 space-y-3">
                <template x-for="(item, index) in items" :key="item.id">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <div class="grid gap-2 sm:grid-cols-[1fr_1fr_auto]">
                            <input type="text" x-model="item.label" placeholder="Menu label" class="rounded-lg border-slate-300 text-sm">
                            <input type="text" x-model="item.url" placeholder="Link URL, e.g. /products" class="rounded-lg border-slate-300 text-sm">
                            <button type="button" class="text-xs font-bold text-rose-700" @click="removeItem(index)">Remove</button>
                        </div>
                        <div class="mt-2 border-l-2 border-slate-200 pl-3">
                            <div class="mb-2 flex items-center justify-between"><span class="text-xs font-bold text-slate-600">Dropdown links</span><button type="button" class="text-xs font-bold text-slate-700" @click="addChild(index)">Add dropdown link</button></div>
                            <template x-for="(child, childIndex) in item.items" :key="child.id">
                                <div class="mb-2 rounded-lg border border-slate-200 bg-white p-2">
                                    <div class="grid gap-2 sm:grid-cols-[1fr_1fr_auto]">
                                        <input type="text" x-model="child.label" placeholder="Dropdown label" class="rounded-lg border-slate-300 text-xs">
                                        <input type="text" x-model="child.url" placeholder="Link URL" class="rounded-lg border-slate-300 text-xs">
                                        <button type="button" class="text-xs font-bold text-rose-700" @click="removeChild(index, childIndex)">Remove</button>
                                    </div>
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <input type="text" x-model="child.image" placeholder="Image URL (optional)" class="min-w-48 flex-1 rounded-lg border-slate-300 text-xs">
                                        <input type="file" accept="image/*" :name="`header_nav_images[${index}][${childIndex}]`" class="max-w-full rounded border border-slate-300 px-2 py-1 text-xs" @change="previewChildImage(index, childIndex, $event)">
                                        <template x-if="child.preview || child.image"><img :src="child.preview || child.image" :alt="child.label || 'Dropdown image preview'" class="h-12 w-16 rounded object-cover"></template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <input type="hidden" name="header_nav_json" :value="json">
        </div>
    </section>

    <section
        class="rounded-xl border border-slate-200 p-4"
        x-data="allProductsMegaMenuBuilder(@js($initialMegaMenu), @js($productLinks))"
    >
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold">All Products Mega Menu</h2>
                <p class="mt-1 text-xs text-slate-500">Add columns, remove products, and link each item to a product page or custom URL.</p>
            </div>
            <button type="button" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white" @click="addColumn()">Add Column</button>
        </div>

        <template x-if="columns.length === 0">
            <p class="rounded-lg bg-slate-50 p-3 text-xs text-slate-500">No menu columns yet. Add a column to start.</p>
        </template>

        <div class="space-y-4">
            <template x-for="(column, columnIndex) in columns" :key="column.id">
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs font-bold uppercase text-slate-500">Column <span x-text="columnIndex + 1"></span></p>
                        <div class="flex items-center gap-3">
                            <button type="button" class="text-xs font-bold text-slate-700" @click="moveColumnUp(columnIndex)" x-show="columnIndex > 0">Move Up</button>
                            <button type="button" class="text-xs font-bold text-slate-700" @click="moveColumnDown(columnIndex)" x-show="columnIndex < columns.length - 1">Move Down</button>
                            <button type="button" class="text-xs font-bold text-rose-700" @click="removeColumn(columnIndex)">Remove Column</button>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <input type="text" x-model="column.title" placeholder="Column heading" class="rounded-lg border-slate-300 text-sm">
                        <input type="text" x-model="column.view_all_url" placeholder="View all URL (optional)" class="rounded-lg border-slate-300 text-sm">
                    </div>

                    <div class="mt-3 rounded-lg border border-slate-200 bg-white p-3">
                        <div class="mb-2 flex items-center justify-between">
                            <p class="text-xs font-bold uppercase text-slate-500">Products / Links</p>
                            <button type="button" class="rounded bg-slate-800 px-2 py-1 text-[11px] font-bold text-white" @click="addItem(columnIndex)">Add Product</button>
                        </div>

                        <template x-if="column.items.length === 0">
                            <p class="text-xs text-slate-500">No products in this column.</p>
                        </template>

                        <div class="space-y-2">
                            <template x-for="(item, itemIndex) in column.items" :key="item.id">
                                <div class="rounded border border-slate-200 bg-slate-50 p-2">
                                    <div class="mb-2 flex items-center justify-between">
                                        <p class="text-[11px] font-bold uppercase text-slate-500">Item <span x-text="itemIndex + 1"></span></p>
                                        <div class="flex items-center gap-3">
                                            <button type="button" class="text-[11px] font-bold text-slate-700" @click="moveItemUp(columnIndex, itemIndex)" x-show="itemIndex > 0">Move Up</button>
                                            <button type="button" class="text-[11px] font-bold text-slate-700" @click="moveItemDown(columnIndex, itemIndex)" x-show="itemIndex < column.items.length - 1">Move Down</button>
                                            <button type="button" class="text-[11px] font-bold text-rose-700" @click="removeItem(columnIndex, itemIndex)">Remove</button>
                                        </div>
                                    </div>
                                    <div class="grid gap-2 sm:grid-cols-3">
                                        <select class="rounded border-slate-300 text-xs" @change="applyProduct(columnIndex, itemIndex, $event.target.value); $event.target.value = ''">
                                            <option value="">Link existing product...</option>
                                            <template x-for="product in productLinks" :key="product.url">
                                                <option :value="product.url" x-text="product.label"></option>
                                            </template>
                                        </select>
                                        <input type="text" x-model="item.label" placeholder="Menu label" class="rounded border-slate-300 text-xs">
                                        <input type="text" x-model="item.url" placeholder="/products/product-slug or https://..." class="rounded border-slate-300 text-xs">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <input type="hidden" name="all_products_mega_menu_json" :value="json">
    </section>

    <section
        class="rounded-xl border border-slate-200 p-4"
        x-data="headerThemeMegaMenuBuilder(@js($initialThemeMegaMenu), @js($menuLinkOptions))"
    >
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold">Top Category Mega Menus</h2>
                <p class="mt-1 text-xs text-slate-500">Edit the navigation tabs and every dropdown tile, including the Office supplies area.</p>
            </div>
            <button type="button" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white" @click="addMenu()">Add Menu</button>
        </div>

        <template x-if="menus.length === 0">
            <p class="rounded-lg bg-slate-50 p-3 text-xs text-slate-500">No category menus yet. Add one to start.</p>
        </template>

        <div class="space-y-4">
            <template x-for="(menu, menuIndex) in menus" :key="menu.id">
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs font-bold uppercase text-slate-500">Menu <span x-text="menuIndex + 1"></span></p>
                        <div class="flex items-center gap-3">
                            <button type="button" class="text-xs font-bold text-slate-700" @click="moveMenuUp(menuIndex)" x-show="menuIndex > 0">Move Up</button>
                            <button type="button" class="text-xs font-bold text-slate-700" @click="moveMenuDown(menuIndex)" x-show="menuIndex < menus.length - 1">Move Down</button>
                            <button type="button" class="text-xs font-bold text-rose-700" @click="removeMenu(menuIndex)">Remove Menu</button>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <input type="text" x-model="menu.label" placeholder="Nav label" class="rounded-lg border-slate-300 text-sm">
                        <input type="text" x-model="menu.url" placeholder="Show all URL" class="rounded-lg border-slate-300 text-sm">
                        <input type="text" x-model="menu.show_all_label" placeholder="Show all label" class="rounded-lg border-slate-300 text-sm">
                        <select class="rounded-lg border-slate-300 text-sm" @change="applyLinkToMenu(menuIndex, $event.target.value); $event.target.value = ''">
                            <option value="">Use existing link...</option>
                            <template x-for="link in linkOptions" :key="link.url + link.label">
                                <option :value="link.url" x-text="link.label"></option>
                            </template>
                        </select>
                    </div>

                    <div class="mt-3 rounded-lg border border-slate-200 bg-white p-3">
                        <div class="mb-2 flex items-center justify-between">
                            <p class="text-xs font-bold uppercase text-slate-500">Dropdown Tiles</p>
                            <button type="button" class="rounded bg-slate-800 px-2 py-1 text-[11px] font-bold text-white" @click="addTile(menuIndex)">Add Tile</button>
                        </div>

                        <template x-if="menu.items.length === 0">
                            <p class="text-xs text-slate-500">No tiles in this menu.</p>
                        </template>

                        <div class="space-y-2">
                            <template x-for="(tile, tileIndex) in menu.items" :key="tile.id">
                                <div data-tile-editor class="rounded border border-slate-200 bg-slate-50 p-2">
                                    <div class="mb-2 flex items-center justify-between">
                                        <p class="text-[11px] font-bold uppercase text-slate-500">Tile <span x-text="tileIndex + 1"></span></p>
                                        <div class="flex items-center gap-3">
                                            <button type="button" class="text-[11px] font-bold text-slate-700" @click="moveTileUp(menuIndex, tileIndex)" x-show="tileIndex > 0">Move Up</button>
                                            <button type="button" class="text-[11px] font-bold text-slate-700" @click="moveTileDown(menuIndex, tileIndex)" x-show="tileIndex < menu.items.length - 1">Move Down</button>
                                            <button type="button" class="text-[11px] font-bold text-rose-700" @click="removeTile(menuIndex, tileIndex)">Remove</button>
                                        </div>
                                    </div>

                                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                                        <select class="rounded border-slate-300 text-xs" @change="applyLinkToTile(menuIndex, tileIndex, $event.target.value); $event.target.value = ''">
                                            <option value="">Link existing page/product...</option>
                                            <template x-for="link in linkOptions" :key="link.url + link.label">
                                                <option :value="link.url" x-text="link.label"></option>
                                            </template>
                                        </select>
                                        <input type="text" x-model="tile.title" placeholder="Tile title" class="rounded border-slate-300 text-xs">
                                        <div class="space-y-1">
                                            <textarea x-model="tile.subtitle" rows="2" placeholder="Subtitle" data-subtitle-input class="w-full rounded border-slate-300 text-xs resize-y"></textarea>
                                            <button type="button" class="rounded border border-slate-300 bg-white px-2 py-1 text-[11px] font-bold text-slate-700" @click="linkSelectedSubtitle(menuIndex, tileIndex, $el.closest('[data-tile-editor]').querySelector('[data-subtitle-input]'))">Link Selected</button>
                                        </div>
                                        <input type="text" x-model="tile.url" placeholder="Tile URL (optional)" class="rounded border-slate-300 text-xs">
                                    </div>
                                    <div class="mt-2 grid gap-2 sm:grid-cols-2">
                                        <input type="text" x-model="tile.image" placeholder="Image URL" class="rounded border-slate-300 text-xs">
                                        <input type="file" accept="image/*" :name="`header_theme_mega_menu_images[${menuIndex}][${tileIndex}]`" class="rounded border border-slate-300 bg-white px-2 py-1.5 text-xs" @change="previewTileImage(menuIndex, tileIndex, $event)">
                                    </div>
                                    <template x-if="tile.preview || tile.image">
                                        <img :src="tile.preview || tile.image" :alt="tile.title || 'Menu tile preview'" class="mt-2 h-16 w-28 rounded object-cover">
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <input type="hidden" name="header_theme_mega_menu_json" :value="json">
    </section>

    <section>
        <h2 class="text-lg font-bold">Footer</h2>
        <p class="mt-1 text-xs text-slate-500">Edit the footer text, contact area, and link columns shown across the storefront.</p>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <input type="text" name="footer_brand_name" value="{{ old('footer_brand_name', $settings->get('footer_brand_name', config('app.name', 'Photo Frames'))) }}" class="rounded-xl border-slate-300" placeholder="Footer brand">
            <input type="text" name="footer_copyright_text" value="{{ old('footer_copyright_text', $settings->get('footer_copyright_text', 'All rights reserved.')) }}" class="rounded-xl border-slate-300" placeholder="Copyright text">
            <textarea name="footer_about_text" rows="3" class="rounded-xl border-slate-300 sm:col-span-2" placeholder="Footer about text">{{ old('footer_about_text', $settings->get('footer_about_text', config('app.tagline'))) }}</textarea>
            <input type="text" name="footer_contact_heading" value="{{ old('footer_contact_heading', $settings->get('footer_contact_heading', 'A little framing advice?')) }}" class="rounded-xl border-slate-300" placeholder="Contact section heading">
            <input type="text" name="footer_contact_button" value="{{ old('footer_contact_button', $settings->get('footer_contact_button', 'Talk to us')) }}" class="rounded-xl border-slate-300" placeholder="Contact button label">
            <textarea name="footer_contact_text" rows="2" class="rounded-xl border-slate-300 sm:col-span-2" placeholder="Contact section text">{{ old('footer_contact_text', $settings->get('footer_contact_text', 'From choosing a finish to finding the right size, let’s make something you’ll love.')) }}</textarea>
            <input type="email" name="footer_contact_email" value="{{ old('footer_contact_email', $settings->get('footer_contact_email', 'info@photoframes.co.ke')) }}" class="rounded-xl border-slate-300" placeholder="Footer contact email">
            <input type="text" name="footer_location_text" value="{{ old('footer_location_text', $settings->get('footer_location_text', 'Nairobi, Kenya · Nationwide delivery')) }}" class="rounded-xl border-slate-300" placeholder="Footer location">
            <input type="text" name="footer_bottom_tagline" value="{{ old('footer_bottom_tagline', $settings->get('footer_bottom_tagline', 'Made for the moments that matter.')) }}" class="rounded-xl border-slate-300" placeholder="Footer bottom tagline">
            <input type="text" name="footer_back_to_top_label" value="{{ old('footer_back_to_top_label', $settings->get('footer_back_to_top_label', 'Back to top ↑')) }}" class="rounded-xl border-slate-300" placeholder="Back to top link">
            <input type="text" name="footer_powered_by_label" value="{{ old('footer_powered_by_label', $settings->get('footer_powered_by_label', 'Powered by Nai Prints')) }}" class="rounded-xl border-slate-300" placeholder="Powered by label">
            <input type="text" name="footer_powered_by_url" value="{{ old('footer_powered_by_url', $settings->get('footer_powered_by_url', '')) }}" class="rounded-xl border-slate-300" placeholder="Powered by link URL (optional)">
            <div class="sm:col-span-2">
                <label class="mb-1 block text-sm font-bold">Footer Link Columns</label>
                <p class="mb-2 text-xs text-slate-500">Edit the column headings and links displayed in the footer.</p>
                <div x-data="storefrontFooterBuilder(@js($initialFooterColumns))" class="rounded-xl border border-slate-200 p-3">
                    <div class="mb-3 flex justify-end"><button type="button" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-bold text-white" @click="addColumn()">Add footer column</button></div>
                    <div class="space-y-3">
                        <template x-for="(column, columnIndex) in columns" :key="column.id">
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <div class="mb-2 grid gap-2 sm:grid-cols-[1fr_auto]"><input type="text" x-model="column.heading" placeholder="Column heading" class="rounded-lg border-slate-300 text-sm"><button type="button" class="text-xs font-bold text-rose-700" @click="removeColumn(columnIndex)">Remove column</button></div>
                                <div class="space-y-2">
                                    <template x-for="(link, linkIndex) in column.links" :key="link.id">
                                        <div class="grid gap-2 sm:grid-cols-[1fr_1fr_auto]"><input type="text" x-model="link.label" placeholder="Link label" class="rounded-lg border-slate-300 text-xs"><input type="text" x-model="link.url" placeholder="Link URL" class="rounded-lg border-slate-300 text-xs"><button type="button" class="text-xs font-bold text-rose-700" @click="removeLink(columnIndex, linkIndex)">Remove</button></div>
                                    </template>
                                </div>
                                <button type="button" class="mt-2 text-xs font-bold text-slate-700" @click="addLink(columnIndex)">Add link</button>
                            </div>
                        </template>
                    </div>
                    <input type="hidden" name="footer_columns_json" :value="json">
                </div>
            </div>
        </div>
    </section>

    <section>
        <h2 class="text-lg font-bold">WhatsApp Button</h2>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <input type="text" name="whatsapp_label" value="{{ old('whatsapp_label', $settings->get('whatsapp_label', 'WhatsApp')) }}" class="rounded-xl border-slate-300" placeholder="Button label">
            <input type="url" name="whatsapp_link" value="{{ old('whatsapp_link', $settings->get('whatsapp_link', config('shop.whatsapp_link'))) }}" class="rounded-xl border-slate-300" placeholder="https://wa.me/...">
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-bold">Popup Logo</label>
                @if($whatsappLogoPreview)
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <img loading="lazy" decoding="async" src="{{ $whatsappLogoPreview }}" alt="WhatsApp popup logo" class="h-12 w-12 rounded-full object-cover">
                    </div>
                @endif
                <div class="grid gap-3 sm:grid-cols-2">
                    <input type="file" name="whatsapp_logo_file" accept="image/*" class="rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    <input type="text" name="whatsapp_logo_url" value="{{ $whatsappLogoValue }}" class="rounded-xl border-slate-300" placeholder="Or paste popup logo URL">
                </div>
            </div>
        </div>
    </section>

    <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">Save Settings</button>
</form>
@endsection

@push('scripts')
<script>
    function allProductsMegaMenuBuilder(initialColumns, productLinks) {
        let nextId = 1;
        const normalizeItem = (item = {}) => ({
            id: nextId++,
            label: item.label || '',
            url: item.url || ''
        });
        const normalizeColumn = (column = {}) => ({
            id: nextId++,
            title: column.title || '',
            view_all_url: column.view_all_url || '',
            items: Array.isArray(column.items) ? column.items.map((item) => normalizeItem(item)) : []
        });
        const swap = (items, from, to) => {
            if (to < 0 || to >= items.length) return;
            const current = items[from];
            items[from] = items[to];
            items[to] = current;
        };

        return {
            columns: Array.isArray(initialColumns) ? initialColumns.map((column) => normalizeColumn(column)) : [],
            productLinks: Array.isArray(productLinks) ? productLinks : [],
            addColumn() {
                this.columns.push(normalizeColumn({ title: 'New Column', items: [] }));
            },
            removeColumn(index) {
                this.columns.splice(index, 1);
            },
            moveColumnUp(index) {
                swap(this.columns, index, index - 1);
            },
            moveColumnDown(index) {
                swap(this.columns, index, index + 1);
            },
            addItem(columnIndex) {
                this.columns[columnIndex].items.push(normalizeItem());
            },
            removeItem(columnIndex, itemIndex) {
                this.columns[columnIndex].items.splice(itemIndex, 1);
            },
            moveItemUp(columnIndex, itemIndex) {
                swap(this.columns[columnIndex].items, itemIndex, itemIndex - 1);
            },
            moveItemDown(columnIndex, itemIndex) {
                swap(this.columns[columnIndex].items, itemIndex, itemIndex + 1);
            },
            applyProduct(columnIndex, itemIndex, url) {
                const product = this.productLinks.find((entry) => entry.url === url);
                if (!product) return;
                this.columns[columnIndex].items[itemIndex].label = product.label;
                this.columns[columnIndex].items[itemIndex].url = product.url;
            },
            get json() {
                return JSON.stringify(this.columns.map((column) => ({
                    title: column.title,
                    view_all_url: column.view_all_url,
                    items: column.items.map((item) => ({
                        label: item.label,
                        url: item.url
                    }))
                })));
            }
        };
    }

    function headerThemeMegaMenuBuilder(initialMenus, linkOptions) {
        let nextId = 1;
        const slug = (value) => String(value || '')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '_')
            .replace(/^_+|_+$/g, '');
        const normalizeTile = (tile = {}) => ({
            id: nextId++,
            title: tile.title || '',
            subtitle: tile.subtitle || '',
            image: tile.image || '',
            url: tile.url || '',
            preview: ''
        });
        const normalizeMenu = (menu = {}) => ({
            id: nextId++,
            key: menu.key || '',
            label: menu.label || '',
            url: menu.url || '',
            show_all_label: menu.show_all_label || '',
            items: Array.isArray(menu.items) ? menu.items.map((tile) => normalizeTile(tile)) : []
        });
        const swap = (items, from, to) => {
            if (to < 0 || to >= items.length) return;
            const current = items[from];
            items[from] = items[to];
            items[to] = current;
        };

        return {
            menus: Array.isArray(initialMenus) ? initialMenus.map((menu) => normalizeMenu(menu)) : [],
            linkOptions: Array.isArray(linkOptions) ? linkOptions : [],
            addMenu() {
                this.menus.push(normalizeMenu({ label: 'New Menu', show_all_label: 'SHOW ALL NEW MENU', items: [] }));
            },
            removeMenu(index) {
                this.menus.splice(index, 1);
            },
            moveMenuUp(index) {
                swap(this.menus, index, index - 1);
            },
            moveMenuDown(index) {
                swap(this.menus, index, index + 1);
            },
            addTile(menuIndex) {
                this.menus[menuIndex].items.push(normalizeTile());
            },
            removeTile(menuIndex, tileIndex) {
                this.menus[menuIndex].items.splice(tileIndex, 1);
            },
            moveTileUp(menuIndex, tileIndex) {
                swap(this.menus[menuIndex].items, tileIndex, tileIndex - 1);
            },
            moveTileDown(menuIndex, tileIndex) {
                swap(this.menus[menuIndex].items, tileIndex, tileIndex + 1);
            },
            applyLinkToMenu(menuIndex, url) {
                const link = this.linkOptions.find((entry) => entry.url === url);
                if (!link) return;
                this.menus[menuIndex].url = link.url;
            },
            applyLinkToTile(menuIndex, tileIndex, url) {
                const link = this.linkOptions.find((entry) => entry.url === url);
                if (!link) return;
                this.menus[menuIndex].items[tileIndex].url = link.url;
            },
            previewTileImage(menuIndex, tileIndex, event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                if (!file) return;
                this.menus[menuIndex].items[tileIndex].preview = URL.createObjectURL(file);
            },
            linkSelectedSubtitle(menuIndex, tileIndex, input) {
                if (!input) return;

                const tile = this.menus[menuIndex].items[tileIndex];
                const start = input.selectionStart ?? 0;
                const end = input.selectionEnd ?? start;
                const selectedText = tile.subtitle.slice(start, end);

                if (!selectedText.trim()) {
                    input.focus();
                    return;
                }

                const url = window.prompt('Enter link URL');

                if (!url || !url.trim()) {
                    input.focus();
                    return;
                }

                const linkedText = `[${selectedText}](${url.trim()})`;
                tile.subtitle = `${tile.subtitle.slice(0, start)}${linkedText}${tile.subtitle.slice(end)}`;

                this.$nextTick(() => {
                    input.focus();
                    input.setSelectionRange(start, start + linkedText.length);
                });
            },
            get json() {
                return JSON.stringify(this.menus.map((menu, index) => ({
                    key: menu.key || slug(menu.label) || `menu_${index + 1}`,
                    label: menu.label,
                    url: menu.url,
                    show_all_label: menu.show_all_label,
                    items: menu.items.map((tile) => ({
                        title: tile.title,
                        subtitle: tile.subtitle,
                        image: tile.image,
                        url: tile.url
                    }))
                })));
            }
        };
    }

    function storefrontNavigationBuilder(initialItems) {
        let nextId = 1;
        const normalize = (item = {}) => ({
            id: nextId++,
            label: item.label || '',
            url: item.url || '/',
            items: Array.isArray(item.items) ? item.items.map(child => ({ id: nextId++, label: child.label || '', url: child.url || '/', image: child.image || '', preview: '' })) : []
        });
        return {
            items: Array.isArray(initialItems) ? initialItems.map(normalize) : [],
            addItem() { this.items.push(normalize({ label: 'New link', url: '/' })); },
            removeItem(index) { this.items.splice(index, 1); },
            addChild(index) { this.items[index].items.push({ id: nextId++, label: '', url: '/' }); },
            removeChild(index, childIndex) { this.items[index].items.splice(childIndex, 1); },
            previewChildImage(index, childIndex, event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                if (file) this.items[index].items[childIndex].preview = URL.createObjectURL(file);
            },
            get json() {
                return JSON.stringify(this.items.map(item => ({
                    label: item.label,
                    url: item.url,
                    items: item.items.map(child => ({ label: child.label, url: child.url, image: child.image }))
                })));
            }
        };
    }

    function storefrontFooterBuilder(initialColumns) {
        let nextId = 1;
        const normalize = (column = {}) => ({
            id: nextId++,
            heading: column.heading || '',
            links: Array.isArray(column.links) ? column.links.map(link => ({ id: nextId++, label: link.label || '', url: link.url || '/' })) : []
        });
        return {
            columns: Array.isArray(initialColumns) ? initialColumns.map(normalize) : [],
            addColumn() { this.columns.push(normalize({ heading: 'New column' })); },
            removeColumn(index) { this.columns.splice(index, 1); },
            addLink(index) { this.columns[index].links.push({ id: nextId++, label: '', url: '/' }); },
            removeLink(index, linkIndex) { this.columns[index].links.splice(linkIndex, 1); },
            get json() {
                return JSON.stringify(this.columns.map(column => ({
                    heading: column.heading,
                    links: column.links.map(link => ({ label: link.label, url: link.url }))
                })));
            }
        };
    }
</script>
@endpush
