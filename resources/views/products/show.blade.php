@extends('layouts.storefront')

@section('meta_title', data_get($product->metadata, 'meta_title') ?: $product->name . ' - Configure Print Specs')
@section('meta_description', data_get($product->metadata, 'meta_description') ?: ($product->short_description ?: 'Configure and order this print product online.'))

@push('head')
<style>
    .product-detail-shell {
        display: grid;
        grid-template-columns: minmax(0, 672px) minmax(0, 1fr);
        gap: 32px 50px;
        align-items: start;
        max-width: 1424px;
        margin: 0 auto;
        padding: 32px 0;
    }

    .product-detail-image-column {
        grid-column: 1;
        min-width: 0;
    }

    .product-detail-form-column {
        grid-column: 2;
        grid-row: 1 / span 2;
        min-width: 0;
        align-self: start;
    }

    .product-detail-description {
        grid-column: 1;
        grid-row: 2;
        min-width: 0;
    }

    @media (max-width: 1472px) {
        .product-detail-shell {
            padding-left: 24px;
            padding-right: 24px;
        }
    }

    @media (max-width: 900px) {
        .product-detail-shell {
            grid-template-columns: minmax(0, 1fr);
            padding: 24px 16px;
        }

        .product-detail-image-column,
        .product-detail-form-column,
        .product-detail-description {
            grid-column: 1;
            grid-row: auto;
        }

    }
</style>
@endpush

@section('content')
@php
    $mainProductImage = \App\Support\PublicAssetUrl::resolve($product->thumbnail) ?: $product->mediaItems->first()?->url;
    $minQuantity = max(1, (int) $product->min_quantity);
    $quantitySuggestions = collect($quantitySuggestions ?? [])
        ->map(fn ($quantity) => (int) $quantity)
        ->filter(fn ($quantity) => $quantity >= $minQuantity)
        ->unique()
        ->values();
    $usdRate = (float) config('shop.usd_exchange_rate', 126);
    $whatsappLink = config('shop.whatsapp_link');
    $description = trim(strip_tags($product->description ?: $product->short_description ?: ''));
    $uploadEnabled = (bool) data_get($product->metadata, 'upload_enabled', true);
    $uploadLabel = data_get($product->metadata, 'upload_label') ?: 'Upload Design (Optional)';
    $uploadHelp = data_get($product->metadata, 'upload_help') ?: 'PDF, AI, PSD, JPG, PNG (max 50MB)';
@endphp

<section class="bg-white" x-data="productConfigurator()" x-init="init()">
    <div class="product-detail-shell">
        <div class="product-detail-image-column">
            <div class="overflow-hidden rounded-[10px] bg-[#eeeeee]">
                @if($mainProductImage)
                    <img src="{{ $mainProductImage }}" alt="{{ $product->name }}" class="aspect-square w-full object-contain p-10 lg:p-12">
                @else
                    <div class="flex aspect-square items-center justify-center p-8 text-sm font-semibold text-slate-500">{{ $product->name }}</div>
                @endif
            </div>

            @if($product->mediaItems->isNotEmpty())
                <div class="mt-4 grid grid-cols-4 gap-3">
                    @foreach($product->mediaItems->take(4) as $media)
                        <div class="overflow-hidden rounded-lg border border-slate-200 bg-[#f5f5f5]">
                            <img src="{{ $media->url }}" alt="{{ $media->alt_text ?: $product->name }}" class="aspect-square w-full object-contain p-3">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('cart.store') }}" enctype="multipart/form-data" class="product-detail-form-column" @submit="isAdding = $el.checkValidity()">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="shipping_option" :value="selectedShipping">

            <div class="space-y-6">
                <div>
                    <h1 class="text-[28px] font-extrabold leading-tight text-slate-950">{{ $product->name }}</h1>
                    <div class="mt-4 flex flex-wrap items-baseline gap-3">
                        <span class="text-[22px] font-extrabold leading-none text-[#ff0037]">KES {{ number_format((float) $product->base_price, 0) }}</span>
                    </div>
                </div>

                @foreach($product->optionGroups as $group)
                    @continue($group->values->isEmpty())
                    <fieldset>
                        <legend class="mb-4 text-[13px] font-bold text-slate-900">{{ $loop->first ? 'Select Print Option' : $group->name }}</legend>
                        @if($group->input_type === 'select')
                            <select name="selected_options[{{ $group->code }}]" x-model="selectedOptions[@js($group->code)]" @change="refreshQuote" class="w-full rounded-xl border-slate-300">
                                @foreach($group->values as $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->label }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                        <div
                            class="{{ $group->input_type === 'image_tabs' ? 'grid gap-3 sm:grid-cols-2' : 'flex flex-wrap gap-2' }}"
                            style="{{ $group->input_type === 'image_tabs' ? '' : 'display: flex; flex-wrap: wrap; gap: 8px;' }}"
                        >
                            @foreach($group->values as $value)
                                @php
                                    $optionImageUrl = \App\Support\PublicAssetUrl::resolve(data_get($value->metadata, 'image_url'));
                                @endphp
                                <label
                                    class="flex cursor-pointer flex-col items-center justify-center rounded-lg border px-3 text-center transition {{ $group->input_type === 'image_tabs' ? 'min-h-[86px] py-3' : 'h-[34px] w-[180px] py-1' }}"
                                    style="{{ $group->input_type === 'image_tabs' ? 'min-height: 86px;' : 'width: 180px; height: 34px; min-height: 34px;' }}"
                                    :class="String(selectedOptions[@js($group->code)] || '') === @js((string) $value->id) ? 'border-[#ff0037] bg-rose-50' : 'border-slate-200 bg-white hover:border-slate-300'"
                                >
                                    <input
                                        type="radio"
                                        name="selected_options[{{ $group->code }}]"
                                        value="{{ $value->id }}"
                                        x-model="selectedOptions[@js($group->code)]"
                                        @change="refreshQuote"
                                        class="sr-only"
                                    >
                                    @if($group->input_type === 'image_tabs' && $optionImageUrl)
                                        <img src="{{ $optionImageUrl }}" alt="{{ $value->label }}" class="mb-3 h-16 w-full rounded bg-white object-contain">
                                    @endif
                                    <span class="block text-[12px] font-extrabold leading-none text-slate-900">{{ $value->label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @endif
                    </fieldset>
                @endforeach

                <div>
                    <label class="mb-3 block text-[13px] font-bold text-slate-900">Order Quantity</label>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="decrementQuantity" class="h-[46px] w-[46px] rounded-lg border border-slate-200 text-xl font-normal text-slate-500">-</button>
                        <input
                            type="number"
                            name="quantity"
                            min="{{ $minQuantity }}"
                            step="1"
                            inputmode="numeric"
                            list="product-quantity-options"
                            x-model.number="quantity"
                            @input.debounce.350ms="refreshQuote"
                            @blur="normalizeQuantity"
                            class="h-[46px] w-[84px] rounded-lg border-slate-300 text-center text-base font-bold"
                            required
                        >
                        <button type="button" @click="incrementQuantity" class="h-[46px] w-[46px] rounded-lg border border-slate-200 text-xl font-normal text-slate-950">+</button>
                    </div>
                    @if($quantitySuggestions->isNotEmpty())
                        <datalist id="product-quantity-options">
                            @foreach($quantitySuggestions as $quantity)
                                <option value="{{ $quantity }}">{{ number_format($quantity) }}</option>
                            @endforeach
                        </datalist>
                    @endif
                    <p x-show="quoteError" x-text="quoteError" class="mt-2 text-xs font-semibold text-rose-600"></p>
                </div>

                <div>
                    <label for="delivery-date" class="mb-2 block text-[13px] font-bold text-slate-900">Select Delivery Date</label>
                    <input
                        id="delivery-date"
                        type="date"
                        name="delivery_date"
                        x-model="selectedDeliveryDate"
                        :min="minimumDeliveryDate()"
                        @change="ensureDeliveryDate"
                        class="h-[44px] w-full rounded-lg border-emerald-200 bg-emerald-50 px-3 text-sm font-bold text-emerald-700"
                        required
                    >
                    <p class="mt-2 text-xs font-semibold text-emerald-700">
                        Earliest available: <span x-text="deliveryDateLabel()"></span>
                    </p>
                </div>

                @if($uploadEnabled)
                <div>
                    <label class="mb-3 block text-[13px] font-bold text-slate-900">{{ $uploadLabel }}</label>
                    <label for="product-artwork-upload" class="flex min-h-[92px] cursor-pointer items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white p-4 text-center text-xs font-semibold text-slate-500 hover:border-[#ff0037] hover:text-[#ff0037]">
                        <input
                            id="product-artwork-upload"
                            type="file"
                            name="artwork"
                            accept=".pdf,.ai,.psd,.jpg,.jpeg,.png,.webp"
                            class="sr-only"
                            @change="selectedArtworkName = $event.target.files[0]?.name || ''"
                        >
                        <span>
                            <span class="block text-lg text-slate-600" x-text="selectedArtworkName || 'Upload'"></span>
                            <span class="block text-[11px]" x-text="selectedArtworkName ? 'Selected file' : 'Click to upload'"></span>
                            <span class="font-normal" x-show="! selectedArtworkName">{{ $uploadHelp }}</span>
                        </span>
                    </label>
                    @error('artwork')
                        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                    @if($artworks->isNotEmpty())
                        <select name="artwork_id" class="mt-2 w-full rounded-lg border-slate-300 text-xs">
                            <option value="">No artwork selected</option>
                            @foreach($artworks as $artwork)
                                <option value="{{ $artwork->id }}">#{{ $artwork->id }} - {{ $artwork->original_name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                @endif

                <div class="flex items-center gap-2 text-xs">
                    <span class="tracking-[1px] text-[15px] leading-none text-amber-400">★★★★★</span>
                    <span class="font-semibold text-slate-800">5.0 (7)</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                        <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5 11 14.5 15.5 9.5"/>
                            <circle cx="12" cy="12" r="8.5"/>
                        </svg>
                        Verified Ratings
                    </span>
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>1 x <span x-text="quantity"></span></span>
                        <span>KES <span x-text="formatMoney(quote.line_total)"></span></span>
                    </div>
                    <div class="mt-3 flex justify-between text-xs text-slate-600">
                        <span>Total</span>
                        <span class="text-xl font-extrabold text-[#ff0037]">KES <span x-text="formatMoney(quote.line_total)"></span></span>
                    </div>
                    <div class="text-right text-xs text-slate-500">$<span x-text="formatUsd(quote.line_total)"></span></div>
                </div>

                <button
                    class="inline-flex w-full items-center justify-center rounded-xl bg-black py-3 text-sm font-extrabold text-white hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="isAdding || Boolean(quoteError)"
                >
                    <span x-text="isAdding ? 'Adding...' : 'Add to Cart'"></span>
                </button>

                <a href="{{ $whatsappLink }}" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-500 py-3 text-sm font-extrabold text-white hover:bg-emerald-600">
                    WhatsApp Order
                </a>

                <div class="flex items-center justify-center gap-8 border-b border-slate-200 pb-4 text-xs font-semibold text-slate-600">
                    <span>Free delivery 10k+</span>
                    <span>High Quality Prints</span>
                </div>

                <div class="rounded-xl border border-rose-200 bg-rose-50">
                    <div class="border-b border-rose-200 px-4 py-3 text-xs font-extrabold text-slate-900">Frequently Asked Questions</div>
                    @foreach([
                        'What file formats do you accept?',
                        'How long does delivery take?',
                        'Can I get a proof before printing?',
                        'What is your minimum order quantity?',
                    ] as $question)
                        <details class="group border-b border-rose-100 px-4 py-3 last:border-b-0">
                            <summary class="flex cursor-pointer list-none justify-between gap-4 text-xs font-bold text-slate-800">
                                {{ $question }}
                                <span class="text-rose-500 group-open:rotate-180">v</span>
                            </summary>
                            <p class="mt-2 text-xs leading-5 text-slate-600">We accept PDF, AI, PSD, JPG and PNG files. For best results, submit print-ready files with clear bleed and high resolution.</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </form>

        <div class="product-detail-description">
            <p class="mb-5 text-sm font-bold text-slate-900">Product Details</p>

            <div class="border-b border-slate-200">
                <details class="group border-t border-slate-200 py-4" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900">
                        Overview
                        <span class="text-rose-500 group-open:rotate-180">^</span>
                    </summary>
                    <div class="mt-4 max-w-[1080px]">
                        <h2 class="text-xl font-extrabold leading-tight text-slate-950">{{ $product->name }}</h2>
                        <p class="mt-4 text-justify text-sm leading-7 text-slate-700">
                            {{ $description ?: 'Configure this product with your preferred print options, quantity, delivery timeline, and artwork.' }}
                        </p>
                    </div>
                </details>

                @foreach([
                    'What is included with this product?',
                    'Available sizes and print options',
                    'Print quality and material',
                    'Ordering and delivery',
                    'Need a larger display or custom finish?',
                    'How artwork should be supplied',
                ] as $detailTitle)
                    <details class="group border-t border-slate-200 py-4">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900">
                            {{ $detailTitle }}
                            <span class="text-slate-500 group-open:rotate-180">v</span>
                        </summary>
                        <p class="mt-3 max-w-[1080px] text-justify text-sm leading-6 text-slate-600">
                            Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.
                        </p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function productConfigurator() {
        return {
            productId: {{ $product->id }},
            quoteUrl: '{{ route('api.quote') }}',
            minQuantity: {{ $minQuantity }},
            quantity: {{ $defaultQuantity }},
            selectedOptions: @json($defaultSelections),
            selectedShipping: 'standard',
            usdRate: {{ $usdRate ?: 126 }},
            quote: @json($defaultQuote),
            quoteError: '',
            isAdding: false,
            selectedArtworkName: '',
            selectedDeliveryDate: @js(old('delivery_date', '')),
            init() {
                this.normalizeQuantity();
                this.refreshQuote();
                this.ensureDeliveryDate();
            },
            incrementQuantity() {
                this.quantity = Number(this.quantity || this.minQuantity) + 1;
                this.refreshQuote();
            },
            decrementQuantity() {
                this.quantity = Math.max(this.minQuantity, Number(this.quantity || this.minQuantity) - 1);
                this.refreshQuote();
            },
            normalizeQuantity() {
                const quantity = parseInt(this.quantity, 10);
                this.quantity = Number.isFinite(quantity) ? Math.max(this.minQuantity, quantity) : this.minQuantity;
            },
            async refreshQuote() {
                this.normalizeQuantity();
                this.quoteError = '';

                try {
                    const response = await fetch(this.quoteUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: this.productId,
                            quantity: Number(this.quantity),
                            selected_options: this.selectedOptions,
                            shipping_option: this.selectedShipping,
                        }),
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (! response.ok) {
                        this.quoteError = payload.message || 'Unable to calculate this quantity.';
                        return;
                    }

                    this.quote = payload.quote;
                    this.ensureDeliveryDate();
                } catch (error) {
                    console.error(error);
                    this.quoteError = 'Unable to update the quote. Please try again.';
                }
            },
            minimumDeliveryDate() {
                const days = Number(this.quote.delivery_days || 4);
                const date = new Date();
                date.setDate(date.getDate() + days);
                return this.formatDateForInput(date);
            },
            ensureDeliveryDate() {
                const minimumDate = this.minimumDeliveryDate();
                if (! this.selectedDeliveryDate || this.selectedDeliveryDate < minimumDate) {
                    this.selectedDeliveryDate = minimumDate;
                }
            },
            deliveryDateLabel() {
                const date = new Date(`${this.minimumDeliveryDate()}T00:00:00`);
                return date.toLocaleDateString(undefined, { weekday: 'short', month: 'short', day: 'numeric' });
            },
            formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            },
            formatWhole(value) {
                return Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
            },
            formatMoney(value) {
                return Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            formatUsd(value) {
                return (Number(value || 0) / Math.max(Number(this.usdRate || 1), 1)).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
        };
    }
</script>
@endpush
