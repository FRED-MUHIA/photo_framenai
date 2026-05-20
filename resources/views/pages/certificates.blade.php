@extends('layouts.storefront')

@section('meta_title', 'Certificates - NaiPrinters')
@section('meta_description', 'Order high-quality certificates in A4 or US Letter sizes with paper, finish, orientation, and delivery options.')

@section('content')
<section class="bg-white py-8">
    <div class="mx-auto grid max-w-[1480px] gap-8 px-4 sm:px-6 lg:grid-cols-[1.2fr_1fr] lg:px-8">
        <article>
            <div class="overflow-hidden rounded-lg border border-[#e4cccc] bg-white">
                <img
                    src="https://images.unsplash.com/photo-1516549655669-df2ccf7ed4f5?auto=format&fit=crop&w=1800&q=80"
                    alt="Certificates"
                    class="h-full min-h-[420px] w-full object-cover"
                >
            </div>
            <div class="mt-3 grid grid-cols-3 gap-2">
                <img src="https://images.unsplash.com/photo-1517842645767-c639042777db?auto=format&fit=crop&w=700&q=80" alt="Certificate sample 1" class="h-28 w-full rounded border border-[#d8c6c6] object-cover">
                <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=700&q=80" alt="Certificate sample 2" class="h-28 w-full rounded border border-[#d8c6c6] object-cover">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=700&q=80" alt="Certificate sample 3" class="h-28 w-full rounded border border-[#d8c6c6] object-cover">
            </div>
        </article>

        <div>
            <h1 class="text-[32px] font-black leading-tight text-[#111]">Certificates</h1>
            <p class="mt-3 text-[15px] leading-7 text-[#2c2c2c]">Choose your preferred format and submit your certificate printing request.</p>

            <form method="POST" action="{{ route('pages.business-stationery-request') }}" class="mt-5 space-y-4 rounded-lg border border-[#e4cccc] bg-white p-4 sm:p-5">
                @csrf
                <input type="hidden" name="page_title" value="Certificates">
                <input type="hidden" name="page_slug" value="certificates">
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
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Certificate Size</label>
                    <select name="certificate_size" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($certificateSizes as $size)
                            <option value="{{ $size['label'] }} ({{ $size['dimensions'] }})">{{ $size['label'] }} - {{ $size['dimensions'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Type of Paper</label>
                    <select name="paper_type" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
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

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Orientation</label>
                    <select name="orientation" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($orientationOptions as $orientation)
                            <option value="{{ $orientation['label'] }}">{{ $orientation['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Personalize the Certificate?</label>
                    <select name="personalize_certificate" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($yesNoOptions as $option)
                            <option value="{{ $option['label'] }}">{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Include Envelope?</label>
                    <select name="include_envelope" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($yesNoOptions as $option)
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
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Delivery Urgency</label>
                    <select name="delivery_urgency" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
                        @foreach($deliveryOptions as $delivery)
                            <option value="{{ $delivery['label'] }}">{{ $delivery['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-[13px] font-semibold text-[#242424]">Quantity</label>
                    <input type="number" name="quantity" min="1" max="2000" step="1" value="50" class="h-10 w-full rounded border border-[#8db4b8] bg-[#dbe6e7] px-3 text-[14px] text-[#1f1f1f]">
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
