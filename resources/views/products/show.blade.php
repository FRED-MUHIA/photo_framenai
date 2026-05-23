@extends('layouts.storefront')

@section('meta_title', data_get($product->metadata, 'meta_title') ?: $product->name . ' - Configure Print Specs')
@section('meta_description', data_get($product->metadata, 'meta_description') ?: ($product->short_description ?: 'Configure and order this print product online.'))

@section('content')
<section class="bg-white" x-data="productConfigurator()" x-init="init()">
<div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-sm font-semibold text-teal-700">&larr; Back to products</a>
        <h1 class="np-type-page-title mt-2">{{ $product->name }}</h1>
        <p class="mt-2 text-slate-600">{{ $product->description ?: $product->short_description }}</p>
    </div>

    @php
        $mainProductImage = \App\Support\PublicAssetUrl::resolve($product->thumbnail) ?: $product->mediaItems->first()?->url;
    @endphp

    @if($mainProductImage || $product->mediaItems->isNotEmpty())
        <div class="mb-6 grid gap-4 lg:grid-cols-[minmax(0,520px)_1fr] lg:items-start">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                <img
                    src="{{ $mainProductImage }}"
                    alt="{{ $product->name }}"
                    class="h-[360px] w-full object-cover"
                >
            </div>

            @if($product->mediaItems->isNotEmpty())
                <div class="grid gap-3 sm:grid-cols-3">
            @foreach($product->mediaItems as $media)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                    <img src="{{ $media->url }}" alt="{{ $media->alt_text ?: $product->name }}" class="h-36 w-full object-cover">
                </div>
            @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-extrabold">Dynamic Configurator</h2>
            <p class="mt-1 text-sm text-slate-600">{{ data_get($product->metadata, 'configurator_intro') ?: 'Choose size, paper, quantity and finishing to calculate your live quote.' }}</p>

            <form method="POST" action="{{ route('cart.store') }}" class="mt-6 space-y-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                @foreach($product->optionGroups as $group)
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">{{ $group->name }}</label>
                        <select name="selected_options[{{ $group->code }}]" x-model="selectedOptions.{{ $group->code }}" @change="refreshQuote" class="w-full rounded-xl border-slate-300">
                            @foreach($group->values as $value)
                                <option value="{{ $value->id }}">{{ $value->label }} @if((float)$value->price_modifier > 0) (+{{ config('shop.currency_symbol') }} {{ number_format($value->price_modifier, 2) }}) @endif</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Quantity</label>
                    <select name="quantity" x-model="quantity" @change="refreshQuote" class="w-full rounded-xl border-slate-300">
                        @foreach($product->quantityPrices as $priceRow)
                            <option value="{{ $priceRow->quantity }}">{{ number_format($priceRow->quantity) }} copies</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Attach Artwork (Optional)</label>
                    <select name="artwork_id" class="w-full rounded-xl border-slate-300">
                        <option value="">No artwork selected</option>
                        @foreach($artworks as $artwork)
                            <option value="{{ $artwork->id }}">#{{ $artwork->id }} - {{ $artwork->original_name }} ({{ ucfirst($artwork->status) }})</option>
                        @endforeach
                    </select>
                    <a href="{{ route('artwork.create') }}" class="mt-2 inline-block text-xs font-semibold text-teal-700">Upload new artwork</a>
                </div>

                <button class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 py-3 text-sm font-bold text-white hover:bg-slate-800">Add to Cart</button>
            </form>
        </div>

        <aside class="rounded-2xl border border-slate-200 bg-slate-900 p-6 text-white shadow-sm">
            <h3 class="text-lg font-bold">Live Price Calculator</h3>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between"><span>Quantity</span><span x-text="quote.quantity"></span></div>
                <div class="flex justify-between"><span>Unit Price</span><span>{{ config('shop.currency_symbol') }} <span x-text="formatMoney(quote.unit_price)"></span></span></div>
                <div class="flex justify-between"><span>Total</span><span class="text-xl font-extrabold">{{ config('shop.currency_symbol') }} <span x-text="formatMoney(quote.line_total)"></span></span></div>
                <div class="rounded-xl bg-white/10 p-3 text-xs">Estimated delivery: <span x-text="quote.delivery_days"></span> days</div>
            </div>
            <hr class="my-5 border-white/20">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-300">Selected Specs</h4>
            <ul class="mt-3 space-y-2 text-sm text-slate-200">
                <template x-for="spec in quote.selected_values" :key="spec.id">
                    <li class="flex justify-between gap-3"><span x-text="spec.group_code"></span><span x-text="spec.label"></span></li>
                </template>
            </ul>
        </aside>
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
            quantity: {{ $defaultQuantity }},
            selectedOptions: @json($defaultSelections),
            quote: @json($defaultQuote),
            init() {
                this.refreshQuote();
            },
            async refreshQuote() {
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
                        }),
                    });

                    if (!response.ok) {
                        return;
                    }

                    const payload = await response.json();
                    this.quote = payload.quote;
                } catch (error) {
                    console.error(error);
                }
            },
            formatMoney(value) {
                const number = Number(value || 0);
                return number.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
        };
    }
</script>
@endpush
