@extends('layouts.storefront')

@section('meta_title', 'Complementary Slips - NaiPrinters')
@section('meta_description', 'Configure paper, finish, colour and quantity options for complementary slips.')

@section('content')
<section class="bg-white py-8">
    <div class="mx-auto grid max-w-[1480px] gap-8 px-4 sm:px-6 lg:grid-cols-[1.2fr_1fr] lg:px-8">
        <article>
            <div class="overflow-hidden rounded-lg border border-[#d8c6c6] bg-white">
                <img loading="eager" fetchpriority="high" decoding="async"
                    src="https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=1800&q=70"
                    alt="Complementary Slips"
                    class="h-full min-h-[420px] w-full object-cover"
                >
            </div>
            <div class="mt-3 grid grid-cols-3 gap-2">
                <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1586892477838-2b96e85e0f96?auto=format&fit=crop&w=700&q=70" alt="Sample 1" class="h-28 w-full rounded border border-[#d8c6c6] object-cover">
                <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=700&q=70" alt="Sample 2" class="h-28 w-full rounded border border-[#d8c6c6] object-cover">
                <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=700&q=70" alt="Sample 3" class="h-28 w-full rounded border border-[#d8c6c6] object-cover">
            </div>
        </article>

        <div>
            <h1 class="text-[30px] font-black leading-tight text-[#111]">Complementary Slips</h1>
            <p class="mt-3 text-[15px] leading-7 text-[#2c2c2c]">
                Select the options below and submit your request.
            </p>

            <form method="POST" action="{{ route('pages.business-stationery-request') }}" class="mt-5 space-y-4 rounded-lg border border-[#d8c6c6] bg-white p-4 sm:p-5">
                @csrf
                <input type="hidden" name="page_title" value="Complementary Slips">
                <input type="hidden" name="page_slug" value="complementary-slips">
                <input type="hidden" name="request_url" value="{{ url()->current() }}">

                @if (session('request_success'))
                    <div class="rounded border border-emerald-300 bg-emerald-50 px-3 py-2 text-[13px] text-emerald-800">
                        {{ session('request_success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="rounded border border-red-300 bg-red-50 px-3 py-2 text-[13px] text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Type of Paper</label>
                        <select name="type_of_paper" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                            @foreach($paperTypes as $paper)
                                <option value="{{ $paper['label'] }}">{{ $paper['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Finishing Look</label>
                        <select name="finishing_look" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                            @foreach($finishes as $finish)
                                <option value="{{ $finish['label'] }}">{{ $finish['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Complimentary Slip Colour</label>
                    <select name="complimentary_slip_colour" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($slipColours as $colour)
                            <option value="{{ $colour['label'] }}">{{ $colour['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Complimentary Slips Style</label>
                    <select name="complimentary_slip_style" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($slipStyles as $style)
                            <option value="{{ $style['label'] }}">{{ $style['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Include Envelopes?</label>
                    <select name="include_envelopes" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($includeEnvelope as $option)
                            <option value="{{ $option['label'] }}">{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Design</label>
                    <select name="design" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($designOptions as $design)
                            <option value="{{ $design['label'] }}">{{ $design['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Quantity</label>
                    <input type="number" name="quantity" min="100" max="20000" step="100" value="200" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="h-10 w-full rounded border border-[#8db4b8] bg-[#f5fbfb] px-3 text-[14px] text-[#1f1f1f]">
                    </div>
                    <div>
                        <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="h-10 w-full rounded border border-[#8db4b8] bg-[#f5fbfb] px-3 text-[14px] text-[#1f1f1f]">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="h-10 w-full rounded border border-[#8db4b8] bg-[#f5fbfb] px-3 text-[14px] text-[#1f1f1f]">
                </div>

                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-full bg-[#b10f1e] px-7 text-[14px] font-bold text-white hover:bg-[#920d19]">
                    Request Quotation
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
