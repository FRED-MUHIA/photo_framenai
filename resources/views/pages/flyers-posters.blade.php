@extends('layouts.storefront')

@section('meta_title', 'Flyers & Posters - Nai Printers')
@section('meta_description', 'Configure flyer, leaflet, and poster printing with paper, quantity, finishing, delivery, and artwork options.')

@section('content')
<section class="bg-[#f5f5f5] py-8">
    <div class="mx-auto grid max-w-[1280px] gap-6 px-4 sm:px-6 lg:grid-cols-[1fr_340px] lg:px-8">
        <form method="POST" action="{{ route('pages.business-stationery-request') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="page_title" value="Flyers & Posters">
            <input type="hidden" name="page_slug" value="flyers-posters">
            <input type="hidden" name="request_url" value="{{ url()->current() }}">

            <div class="rounded-lg border border-[#d7d7d7] bg-white p-4 sm:p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h1 class="text-[24px] font-black leading-tight text-[#111] sm:text-[30px]">Flyers & Leaflets, A8, printed on one side</h1>
                        <p class="mt-2 max-w-3xl text-[14px] leading-6 text-[#5a5a5a]">Choose paper, quantity, finishing, delivery date, and how you want to supply artwork.</p>
                    </div>
                    <div class="flex gap-2 text-[#777]">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-[#dadada]">&nearr;</span>
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-[#dadada]">PDF</span>
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-[#dadada]">*</span>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span class="rounded-full bg-[#f2f2f2] px-3 py-1.5 text-[12px] font-bold text-[#111]">Artwork information</span>
                    <span class="rounded-full bg-[#f2f2f2] px-3 py-1.5 text-[12px] font-bold text-[#111]">Product details</span>
                    <span class="rounded-full bg-[#f2f2f2] px-3 py-1.5 text-[12px] font-bold text-[#111]">Safety and manufacturer details</span>
                </div>

                <div class="mt-8 divide-y divide-[#d7d7d7] border-y border-[#d7d7d7]">
                    <label class="grid gap-2 py-4 sm:grid-cols-[210px_1fr] sm:items-center">
                        <span class="text-[13px] font-bold text-[#111]">Product</span>
                        <select name="product_format" class="h-10 w-full rounded border border-transparent bg-white px-0 text-[14px] font-semibold text-[#111] focus:border-[#b10f1e] focus:ring-[#b10f1e]">
                            @foreach($productOptions['product_formats'] as $option)
                                <option value="{{ $option['label'] }}">{{ $option['label'] }}@if($option['extra'] > 0) (+ KSh {{ number_format($option['extra']) }})@endif</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="grid gap-2 py-4 sm:grid-cols-[210px_1fr] sm:items-center">
                        <span class="text-[13px] font-bold text-[#111]">Paper <span class="font-normal text-[#888]">(i)</span></span>
                        <select name="paper" class="h-10 w-full rounded border border-transparent bg-white px-0 text-[14px] font-semibold text-[#111] focus:border-[#b10f1e] focus:ring-[#b10f1e]">
                            @foreach($productOptions['paper_types'] as $option)
                                <option value="{{ $option['label'] }}">{{ $option['label'] }} {{ $option['badge'] ? '(' . $option['badge'] . ')' : '' }}@if($option['extra'] > 0) (+ KSh {{ number_format($option['extra']) }})@endif</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="grid gap-2 py-4 sm:grid-cols-[210px_1fr] sm:items-center">
                        <span class="text-[13px] font-bold text-[#111]">Quantity</span>
                        <select name="quantity" class="h-10 w-full rounded border border-transparent bg-white px-0 text-[14px] font-semibold text-[#111] focus:border-[#b10f1e] focus:ring-[#b10f1e]">
                            @foreach($productOptions['quantities'] as $option)
                                <option value="{{ $option['label'] }}">{{ $option['label'] }}@if($option['extra'] > 0) (+ KSh {{ number_format($option['extra']) }})@endif</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-[#d7d7d7] bg-white p-4 sm:p-5">
                <div class="mb-3 flex items-baseline justify-between gap-3">
                    <h2 class="text-[20px] font-bold text-[#111]">Other product options</h2>
                    <span class="text-[12px] text-[#555]">net</span>
                </div>

                <div class="divide-y divide-[#d7d7d7] border-y border-[#d7d7d7]">
                    @foreach([
                        'paper_finish' => ['label' => 'Paper finish', 'items' => $productOptions['paper_finishes']],
                        'finishing' => ['label' => 'Finishing', 'items' => $productOptions['finishings']],
                        'corner_rounding' => ['label' => 'Corner rounding', 'items' => $productOptions['corner_rounding']],
                        'printing_method' => ['label' => 'Printing methods', 'items' => $productOptions['printing_methods']],
                        'data_check' => ['label' => 'Data check', 'items' => $productOptions['data_checks']],
                    ] as $name => $group)
                        <label class="grid gap-2 py-4 sm:grid-cols-[210px_1fr] sm:items-center">
                            <span class="text-[13px] font-bold text-[#111]">{{ $group['label'] }} <span class="font-normal text-[#888]">(i)</span></span>
                            <select name="{{ $name }}" class="h-10 w-full rounded border border-transparent bg-white px-0 text-[14px] font-semibold text-[#111] focus:border-[#b10f1e] focus:ring-[#b10f1e]">
                                @foreach($group['items'] as $option)
                                    <option value="{{ $option['label'] }}">
                                        {{ $option['label'] }}
                                        @if(! empty($option['note'])) - {{ $option['note'] }} @endif
                                        @if($option['extra'] > 0) (+ KSh {{ number_format($option['extra']) }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg border border-[#d7d7d7] bg-white p-4 sm:p-5">
                <h2 class="text-[20px] font-bold text-[#111]">Delivery date approx.</h2>
                <p class="mt-2 text-[12px] leading-5 text-[#333]">Upload artwork by today 12:00. Shipping provider can be adjusted during checkout.</p>

                <div class="mt-4 grid gap-3">
                    @foreach($productOptions['delivery_options'] as $index => $option)
                        <label class="flex min-h-[48px] cursor-pointer items-center justify-between rounded border px-4 text-[14px] {{ $index === 0 ? 'border-[#f28a00] bg-white' : 'border-[#d6d6d6] bg-[#f7f7f7]' }}">
                            <span class="flex items-center gap-3">
                                <input type="radio" name="delivery_date" value="{{ $option['label'] }}" class="text-[#b10f1e] focus:ring-[#b10f1e]" @checked($index === 0)>
                                <span class="font-bold">{{ $option['label'] }}</span>
                            </span>
                            @if($option['extra'] > 0)
                                <span class="font-bold">+ KSh {{ number_format($option['extra']) }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg border border-[#d7d7d7] bg-white p-4 sm:p-5">
                <h2 class="text-[20px] font-bold text-[#111]">Print Files</h2>
                <p class="mt-2 text-[12px] leading-5 text-[#333]">The data processing agreement and artwork requirements apply to artwork processing.</p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label class="flex min-h-[190px] cursor-pointer flex-col justify-between rounded border-2 border-[#f28a00] p-4 text-center">
                        <input type="radio" name="artwork_option" value="Custom artwork" class="mx-auto text-[#b10f1e] focus:ring-[#b10f1e]" checked>
                        <span class="mx-auto mt-1 text-[42px] leading-none">PDF</span>
                        <span class="mt-2 text-[15px] font-bold">Custom artwork</span>
                        <span class="mt-2 text-[12px] leading-5 text-[#333]">You can upload your artwork before or after purchase.</span>
                        <a href="{{ route('artwork.create') }}" class="mt-4 inline-flex h-10 items-center justify-center rounded bg-[#2e67c4] px-4 text-[13px] font-bold text-white hover:bg-[#2455a5]">Upload now</a>
                    </label>

                    <label class="flex min-h-[190px] cursor-pointer flex-col justify-between rounded border border-[#bfbfbf] p-4 text-center">
                        <input type="radio" name="artwork_option" value="Design online" class="mx-auto text-[#b10f1e] focus:ring-[#b10f1e]">
                        <span class="mx-auto mt-1 text-[42px] leading-none">DES</span>
                        <span class="mt-2 text-[15px] font-bold">Design online</span>
                        <span class="mt-2 text-[12px] leading-5 text-[#333]">Our team can help create a clean design from your brief.</span>
                        <button type="submit" name="design_request" value="Design online" class="mt-4 inline-flex h-10 items-center justify-center rounded border border-[#111] px-4 text-[13px] font-bold text-[#111] hover:bg-[#f2f2f2]">Design now</button>
                    </label>
                </div>
            </div>

            <div class="rounded-lg border border-[#d7d7d7] bg-white p-4 sm:p-5">
                @if (session('request_success'))
                    <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-3 py-2 text-[13px] text-emerald-800">
                        {{ session('request_success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded border border-red-300 bg-red-50 px-3 py-2 text-[13px] text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <h2 class="text-[20px] font-bold text-[#111]">Contact details</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Full name" class="h-11 rounded border border-[#c9c9c9] px-3 text-[14px]">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" class="h-11 rounded border border-[#c9c9c9] px-3 text-[14px]">
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number" class="h-11 rounded border border-[#c9c9c9] px-3 text-[14px]">
                </div>
                <button type="submit" class="mt-4 inline-flex h-11 items-center justify-center rounded bg-[#b10f1e] px-6 text-[14px] font-bold text-white hover:bg-[#920d19]">Request quotation</button>
            </div>
        </form>

        <aside class="h-fit rounded-lg border border-[#d7d7d7] bg-white p-5 lg:sticky lg:top-5">
            <p class="text-[13px] font-bold uppercase tracking-wide text-[#6b6b6b]">Base price</p>
            <div class="mt-2 flex items-end justify-between gap-3">
                <span class="text-[34px] font-black leading-none text-[#111]">KSh 2,045</span>
                <span class="text-[12px] text-[#555]">net</span>
            </div>
            <p class="mt-3 text-[13px] leading-6 text-[#444]">Pricing changes with size, quantity, paper, finishing, artwork check, and delivery speed. Submit the selected options and our team will confirm the final quote.</p>

            <div class="mt-5 border-t border-[#d7d7d7] pt-4">
                <h2 class="text-[18px] font-bold text-[#111]">Included options</h2>
                <ul class="mt-3 space-y-2 text-[13px] leading-5 text-[#333]">
                    <li>One-sided print setup</li>
                    <li>Artwork upload or design support</li>
                    <li>Selectable paper and finishing</li>
                    <li>Approximate delivery choices</li>
                </ul>
            </div>
        </aside>
    </div>
</section>
@endsection
