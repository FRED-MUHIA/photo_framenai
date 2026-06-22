@extends('layouts.storefront')

@section('meta_title', 'Custom Branded Receipts, Invoices & Delivery Books - Nai Printers')

@section('content')
<section class="bg-white py-8">
    <div class="mx-auto grid max-w-[1400px] gap-8 px-4 sm:px-6 lg:grid-cols-[1.15fr_1fr] lg:px-8">
        <article class="overflow-hidden rounded-lg border border-[#e8d3d3] bg-white">
            <img loading="eager" fetchpriority="high" decoding="async"
                src="https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=1600&q=70"
                alt="Custom receipt books"
                class="h-full min-h-[420px] w-full object-cover"
            >
        </article>

        <div>
            <h1 class="text-[32px] font-black leading-tight text-[#111]">Custom Branded Receipts, Invoices & Delivery Books</h1>
            <p class="mt-3 text-[15px] leading-7 text-[#2c2c2c]">
                We use high quality no carbon required paper for business forms including receipts, invoices and delivery books.
                Fill your details below and our team will respond.
            </p>

            <form method="POST" action="{{ route('pages.business-stationery-request') }}" class="mt-5 space-y-4 rounded-lg border border-[#e8d3d3] bg-white p-4 sm:p-5">
                @csrf
                <input type="hidden" name="page_title" value="Custom Branded Receipts, Invoices & Delivery Books">
                <input type="hidden" name="page_slug" value="custom-branded-receipts-invoices-delivery-books">
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

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Select Book Size</label>
                    <select name="book_size" class="h-10 w-full rounded border border-[#bfc7cf] bg-[#eef4f4] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($sizes as $size)
                            <option value="{{ $size['label'] }} ({{ $size['dimensions'] }})">{{ $size['label'] }} - {{ $size['dimensions'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Copies Per Book</label>
                    <select name="copies_per_book" class="h-10 w-full rounded border border-[#bfc7cf] bg-[#eef4f4] px-3 text-[14px] text-[#1f1f1f]">
                        <option value="Duplicate (50 originals, 50 copies)">Duplicate (50 originals, 50 copies)</option>
                        <option value="Triplicate (50 originals, 100 copies)">Triplicate (50 originals, 100 copies)</option>
                        <option value="Quadruplicate (50 originals, 150 copies)">Quadruplicate (50 originals, 150 copies)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Design</label>
                    <select name="design" class="h-10 w-full rounded border border-[#bfc7cf] bg-[#eef4f4] px-3 text-[14px] text-[#1f1f1f]">
                        <option value="Choose an option">Choose an option</option>
                        <option value="I have print-ready artwork">I have print-ready artwork</option>
                        <option value="Need basic design setup">Need basic design setup</option>
                        <option value="Need full branded design">Need full branded design</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Quantity</label>
                    <input type="number" name="quantity" min="1" step="1" value="1" class="h-10 w-full rounded border border-[#bfc7cf] bg-[#eef4f4] px-3 text-[14px] text-[#1f1f1f]">
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="h-10 w-full rounded border border-[#bfc7cf] bg-white px-3 text-[14px] text-[#1f1f1f]">
                    </div>
                    <div>
                        <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="h-10 w-full rounded border border-[#bfc7cf] bg-white px-3 text-[14px] text-[#1f1f1f]">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="h-10 w-full rounded border border-[#bfc7cf] bg-white px-3 text-[14px] text-[#1f1f1f]">
                </div>

                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-full bg-[#b10f1e] px-7 text-[14px] font-bold text-white hover:bg-[#920d19]">
                    Request Quotation
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
