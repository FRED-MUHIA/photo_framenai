@extends('layouts.storefront')

@section('meta_title', data_get($product->metadata, 'meta_title') ?: $product->name . ' - Configure Print Specs')
@section('meta_description', data_get($product->metadata, 'meta_description') ?: ($product->short_description ?: 'Configure and order this print product online.'))

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
    $shippingOptions = config('shop.shipping_options', []);
    $whatsappLink = config('shop.whatsapp_link');
    $description = trim(strip_tags($product->description ?: $product->short_description ?: ''));
@endphp

<section class="bg-white" x-data="productConfigurator()" x-init="init()">
    <div class="mx-auto grid max-w-7xl items-start gap-8 px-4 py-8 sm:px-6 md:grid-cols-[minmax(0,48%)_minmax(0,52%)] lg:grid-cols-[minmax(0,560px)_minmax(0,1fr)] lg:px-8">
        <div class="order-1 min-w-0">
            <div class="overflow-hidden rounded-lg bg-[#eeeeee]">
                @if($mainProductImage)
                    <img src="{{ $mainProductImage }}" alt="{{ $product->name }}" class="aspect-square w-full object-contain p-8">
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

            <div class="mt-8">
                <p class="mb-5 text-sm font-bold text-slate-900">Product Details</p>

                <div class="border-b border-slate-200">
                    <details class="group border-t border-slate-200 py-4" open>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-bold text-slate-900">
                            Overview
                            <span class="text-rose-500 group-open:rotate-180">^</span>
                        </summary>
                        <div class="mt-4">
                            <h2 class="text-xl font-extrabold leading-tight text-slate-950">{{ $product->name }}</h2>
                            <p class="mt-4 text-sm leading-7 text-slate-700">
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
                            <p class="mt-3 text-sm leading-6 text-slate-600">
                                Select your options on the order panel and upload print-ready artwork after checkout, or send files to our team for review.
                            </p>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('cart.store') }}" class="order-2 min-w-0 md:sticky md:top-4 md:self-start" @submit="isAdding = true">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="shipping_option" :value="selectedShipping">

            <div class="space-y-5">
                <div>
                    <h1 class="text-[22px] font-extrabold leading-tight text-slate-950">{{ $product->name }}</h1>
                    <div class="mt-2 flex flex-wrap items-baseline gap-2">
                        <span class="text-lg font-extrabold text-[#ff0037]">KES <span x-text="formatWhole(quote.line_total)"></span></span>
                        <span class="text-xs text-slate-500">($<span x-text="formatUsd(quote.line_total)"></span>)</span>
                        <span class="text-xs text-slate-500">starting price</span>
                    </div>
                </div>

                @foreach($product->optionGroups as $group)
                    @continue($group->values->isEmpty())
                    <fieldset>
                        <legend class="mb-2 text-xs font-bold text-slate-900">{{ $loop->first ? 'Select Print Option' : $group->name }}</legend>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach($group->values as $value)
                                @php
                                    $defaultValueId = data_get($defaultSelections, $group->code);
                                    $defaultValue = $group->values->firstWhere('id', $defaultValueId);
                                    $defaultModifier = (float) ($defaultValue?->price_modifier ?? 0);
                                    $optionPreviewPrice = (float) data_get($defaultQuote, 'line_total', 0)
                                        - ($defaultModifier * $defaultQuantity)
                                        + ((float) $value->price_modifier * $defaultQuantity);
                                @endphp
                                <label
                                    class="cursor-pointer rounded-lg border p-3 text-center transition"
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
                                    <span class="block text-xs font-extrabold text-slate-900">{{ $value->label }}</span>
                                    <span class="mt-1 block text-sm font-bold text-[#ff0037]">KES {{ number_format(max(0, $optionPreviewPrice), 0) }}</span>
                                    <span class="mt-1 block text-[10px] text-slate-500">${{ number_format(max(0, $optionPreviewPrice) / max($usdRate, 1), 2) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endforeach

                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-900">Order Quantity</label>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="decrementQuantity" class="h-10 w-10 rounded-lg border border-slate-200 text-lg font-bold text-slate-500">-</button>
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
                            class="h-10 w-20 rounded-lg border-slate-300 text-center text-sm font-bold"
                            required
                        >
                        <button type="button" @click="incrementQuantity" class="h-10 w-10 rounded-lg border border-slate-200 text-lg font-bold text-slate-500">+</button>
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

                <div class="rounded-lg border border-slate-200">
                    <button type="button" class="flex w-full items-center justify-between px-4 py-3 text-left text-xs font-bold text-slate-900">
                        <span class="inline-flex items-center gap-2">
                            <span class="text-[#ff0037]">&gt;</span>
                            Delivery Zone & Price
                        </span>
                        <span class="text-slate-400">v</span>
                    </button>
                </div>

                <fieldset>
                    <legend class="mb-2 text-xs font-bold text-slate-900">Turnaround Time</legend>
                    <div class="grid gap-2 sm:grid-cols-3">
                        @foreach($shippingOptions as $key => $option)
                            <label
                                class="cursor-pointer rounded-lg border p-3 transition"
                                :class="selectedShipping === @js($key) ? 'border-[#ff0037] bg-rose-50' : 'border-slate-200 bg-white hover:border-slate-300'"
                            >
                                <input type="radio" name="shipping_choice" value="{{ $key }}" x-model="selectedShipping" @change="refreshQuote" class="sr-only">
                                <span class="block text-xs font-extrabold text-slate-900">{{ $option['label'] }}</span>
                                <span class="mt-1 block text-xs text-slate-600">{{ $option['days'] }} days</span>
                                <span class="mt-1 block text-xs font-bold text-[#ff0037]">
                                    {{ (float) $option['price'] > 0 ? '+KES ' . number_format($option['price'], 0) : 'Free' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="inline-flex items-center gap-2 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700">
                    <span>OK</span>
                    Get this item by: <span x-text="deliveryDateLabel()"></span>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-bold text-slate-900">Upload Design (Optional)</label>
                    <a href="{{ route('artwork.create') }}" class="flex min-h-[92px] items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white p-4 text-center text-xs font-semibold text-slate-500 hover:border-[#ff0037] hover:text-[#ff0037]">
                        <span>
                            <span class="block text-xl">Upload</span>
                            Click to upload<br>
                            <span class="font-normal">PDF, AI, PSD, JPG, PNG (max 50MB)</span>
                        </span>
                    </a>
                    @if($artworks->isNotEmpty())
                        <select name="artwork_id" class="mt-2 w-full rounded-lg border-slate-300 text-xs">
                            <option value="">No artwork selected</option>
                            @foreach($artworks as $artwork)
                                <option value="{{ $artwork->id }}">#{{ $artwork->id }} - {{ $artwork->original_name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-xs">
                    <span class="font-bold text-amber-400">5 stars</span>
                    <span class="font-bold text-slate-700">5.0</span>
                    <span class="text-slate-500">verified ratings</span>
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
                    class="inline-flex w-full items-center justify-center rounded-xl bg-[#ff0037] py-3 text-sm font-extrabold text-white hover:bg-[#e60032] disabled:cursor-not-allowed disabled:opacity-60"
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

                <div>
                    <p class="mb-2 text-xs font-bold text-slate-900">Download Templates</p>
                    <div class="flex gap-2">
                        @foreach(['PSD', 'AI', 'INDD'] as $template)
                            <span class="rounded-md border border-slate-200 bg-white px-3 py-2 text-xs font-extrabold text-slate-700">{{ $template }}</span>
                        @endforeach
                    </div>
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
            init() {
                this.normalizeQuantity();
                this.refreshQuote();
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
                } catch (error) {
                    console.error(error);
                    this.quoteError = 'Unable to update the quote. Please try again.';
                }
            },
            deliveryDateLabel() {
                const days = Number(this.quote.delivery_days || 4);
                const date = new Date();
                date.setDate(date.getDate() + days);
                return date.toLocaleDateString(undefined, { weekday: 'short', month: 'short', day: 'numeric' });
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
