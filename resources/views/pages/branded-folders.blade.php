@extends('layouts.storefront')

@section('meta_title', 'Branded Folders - ' . config('app.name'))
@section('meta_description', 'Order custom branded folders with premium finishing, design options, and fast turnaround.')

@section('content')
<section class="bg-white py-8">
    <div class="mx-auto grid max-w-[1400px] gap-8 px-4 sm:px-6 lg:grid-cols-[1.15fr_1fr] lg:px-8">
        <article class="overflow-hidden rounded-lg border border-[#e4cccc] bg-white">
            <img loading="eager" fetchpriority="high" decoding="async"
                src="https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1600&q=70"
                alt="Branded folders"
                class="h-full min-h-[420px] w-full object-cover"
            >
        </article>

        <div>
            <h1 class="text-[32px] font-black leading-tight text-[#111]">Branded Folders</h1>
            <p class="mt-3 text-[15px] leading-7 text-[#2c2c2c]">
                Fill the details below and submit your request. Our team will review and get back to you.
            </p>

            <form method="POST" action="{{ route('pages.business-stationery-request') }}" class="mt-5 space-y-4 rounded-lg border border-[#e4cccc] bg-white p-4 sm:p-5">
                @csrf
                <input type="hidden" name="page_title" value="Branded Folders">
                <input type="hidden" name="page_slug" value="branded-folders">
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

                <p class="border-b border-[#18888e] pb-2 text-[13px] text-[#283233]">Standard delivery, typically within 7 days.</p>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Select Folder Type</label>
                    <select name="folder_type" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($folderTypes as $type)
                            <option value="{{ $type['label'] }}">{{ $type['label'] }}</option>
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
                    <input type="number" name="quantity" min="100" max="5000" step="100" value="100" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
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
