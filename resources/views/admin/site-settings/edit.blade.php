@extends('layouts.admin')

@section('heading', 'Header, Footer & Menu Settings')

@section('content')
@php
    $initialMegaMenu = json_decode(old('all_products_mega_menu_json', $megaMenuJson), true);
    if (! is_array($initialMegaMenu)) {
        $initialMegaMenu = [];
    }
    $headerLogoValue = old('header_logo_url', $settings->get('header_logo_url'));
    $headerLogoPreview = \App\Support\PublicAssetUrl::resolve($headerLogoValue);
    $whatsappLogoValue = old('whatsapp_logo_url', $settings->get('whatsapp_logo_url'));
    $whatsappLogoPreview = \App\Support\PublicAssetUrl::resolve($whatsappLogoValue);
@endphp

<form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="space-y-6 rounded-xl bg-white p-5 shadow-sm">
    @csrf
    @method('PUT')

    <section>
        <h2 class="text-lg font-bold">Header</h2>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <input type="text" name="header_brand_label" value="{{ old('header_brand_label', $settings->get('header_brand_label', 'onlineprinters')) }}" class="rounded-xl border-slate-300" placeholder="Brand label">
            <input type="text" name="header_live_chat_label" value="{{ old('header_live_chat_label', $settings->get('header_live_chat_label', 'Live Chat')) }}" class="rounded-xl border-slate-300" placeholder="Live chat label">
            <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-bold">Header Logo</label>
                @if($headerLogoPreview)
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <img src="{{ $headerLogoPreview }}" alt="Header logo" class="h-12 w-auto max-w-[220px] object-contain">
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

    <section>
        <h2 class="text-lg font-bold">Footer</h2>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <input type="text" name="footer_brand_name" value="{{ old('footer_brand_name', $settings->get('footer_brand_name', 'NaiPrinters')) }}" class="rounded-xl border-slate-300" placeholder="Footer brand">
            <input type="text" name="footer_copyright_text" value="{{ old('footer_copyright_text', $settings->get('footer_copyright_text', 'All rights reserved.')) }}" class="rounded-xl border-slate-300" placeholder="Copyright text">
            <textarea name="footer_about_text" rows="3" class="rounded-xl border-slate-300 sm:col-span-2" placeholder="Footer about text">{{ old('footer_about_text', $settings->get('footer_about_text', 'Custom printing at scale with fast turnaround and design-friendly workflows.')) }}</textarea>
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
                        <img src="{{ $whatsappLogoPreview }}" alt="WhatsApp popup logo" class="h-12 w-12 rounded-full object-cover">
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
</script>
@endpush
