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
                <input id="site_name" type="text" name="site_name" value="{{ old('site_name', $settings->get('site_name', config('app.name', 'Nai Printers'))) }}" class="w-full rounded-xl border-slate-300" placeholder="Nai Printers">
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
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <input type="text" name="header_live_chat_label" value="{{ old('header_live_chat_label', $settings->get('header_live_chat_label', 'Live Chat')) }}" class="rounded-xl border-slate-300" placeholder="Live chat label">
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
                                <div class="rounded border border-slate-200 bg-slate-50 p-2">
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
                                        <textarea x-model="tile.subtitle" rows="2" placeholder="Subtitle" class="rounded border-slate-300 text-xs resize-y"></textarea>
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
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <input type="text" name="footer_brand_name" value="{{ old('footer_brand_name', $settings->get('footer_brand_name', config('app.name', 'Nai Printers'))) }}" class="rounded-xl border-slate-300" placeholder="Footer brand">
            <input type="text" name="footer_copyright_text" value="{{ old('footer_copyright_text', $settings->get('footer_copyright_text', 'All rights reserved.')) }}" class="rounded-xl border-slate-300" placeholder="Copyright text">
            <textarea name="footer_about_text" rows="3" class="rounded-xl border-slate-300 sm:col-span-2" placeholder="Footer about text">{{ old('footer_about_text', $settings->get('footer_about_text', config('app.tagline'))) }}</textarea>
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
</script>
@endpush
