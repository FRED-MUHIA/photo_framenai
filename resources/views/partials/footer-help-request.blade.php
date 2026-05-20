@php
    $requestRouteName = optional(request()->route())->getName() ?: 'current-page';
    $requestTitle = trim($__env->yieldContent('meta_title')) ?: config('app.name', 'NaiPrinters');
    $solutionOptions = [
        'Shop board / logo signage',
        'Office or wall branding',
        'Posters / banners',
        'Stickers / labels',
        'Brochures / fliers',
        'Exhibition or custom request',
    ];
@endphp

<section class="bg-[#f4f4f4] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl border border-[#d9d9d9] bg-white">
        <div class="border-b border-[#e5e7eb] px-5 py-5 text-center sm:px-8">
            <h2 class="text-2xl font-extrabold text-[#111]">Didn't find what you were looking for?</h2>
            <p class="mx-auto mt-2 max-w-2xl text-sm leading-6 text-[#5f6368]">
                Tell us what you need and our team will get back to you.
            </p>
        </div>

        <form method="POST" action="{{ route('pages.business-stationery-request') }}" class="px-5 py-6 text-[#111] sm:px-8">
            @csrf
            <input type="hidden" name="page_title" value="Footer help request - {{ $requestTitle }}">
            <input type="hidden" name="page_slug" value="{{ $requestRouteName }}">
            <input type="hidden" name="request_url" value="{{ url()->current() }}">

        @if (session('request_success'))
            <div class="mb-5 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('request_success') }}
            </div>
        @endif

        @error('request')
            <div class="mb-5 border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                {{ $message }}
            </div>
        @enderror

        <fieldset>
            <legend class="text-sm font-bold">I am looking for...</legend>
            <div class="mt-3 grid gap-x-10 gap-y-3 text-sm sm:grid-cols-2">
                @foreach ($solutionOptions as $option)
                    <label class="flex items-start gap-2">
                        <input type="checkbox" name="solutions[]" value="{{ $option }}" class="mt-0.5 h-4 w-4 rounded border-[#9b9b9b] text-[#b10f1e] focus:ring-[#b10f1e]">
                        <span>{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>

        <div class="mt-6">
            <label for="footer_request_message" class="text-sm font-bold">Message</label>
            <textarea id="footer_request_message" name="message" rows="3" placeholder="Tell us what you need." class="mt-2 w-full border border-[#d6d6d6] px-3 py-2 text-sm focus:border-[#b10f1e] focus:ring-[#b10f1e]">{{ old('message') }}</textarea>
        </div>

        <div class="mt-5 grid gap-5 md:grid-cols-2">
            <div>
                <label for="footer_request_name" class="text-sm font-bold">Name <span class="text-red-600">*</span></label>
                <input id="footer_request_name" type="text" name="name_first" value="{{ old('name_first') }}" required class="mt-2 w-full border border-[#d6d6d6] px-3 py-2 text-sm focus:border-[#b10f1e] focus:ring-[#b10f1e]">
            </div>
            <div>
                <label for="footer_request_business" class="text-sm font-bold">Business Name</label>
                <input id="footer_request_business" type="text" name="business_name" value="{{ old('business_name') }}" placeholder="Company Name" class="mt-2 w-full border border-[#d6d6d6] px-3 py-2 text-sm focus:border-[#b10f1e] focus:ring-[#b10f1e]">
            </div>
        </div>

        <input type="hidden" name="name_last" value="">

        <div class="mt-5 grid gap-5 md:grid-cols-2">
            <div>
                <label for="footer_request_phone" class="text-sm font-bold">Phone <span class="text-red-600">*</span></label>
                <div class="mt-2 flex">
                    <span class="inline-flex items-center border border-r-0 border-[#d6d6d6] bg-[#f6f6f6] px-3 text-sm">+254</span>
                    <input id="footer_request_phone" type="tel" name="phone" value="{{ old('phone') }}" required class="w-full border border-[#d6d6d6] px-3 py-2 text-sm focus:border-[#b10f1e] focus:ring-[#b10f1e]">
                </div>
            </div>
            <div>
                <label for="footer_request_email" class="text-sm font-bold">Email <span class="text-red-600">*</span></label>
                <input id="footer_request_email" type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full border border-[#d6d6d6] px-3 py-2 text-sm focus:border-[#b10f1e] focus:ring-[#b10f1e]">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 text-right">
            <button type="submit" class="inline-flex h-10 items-center justify-center rounded-md bg-[#b10f1e] px-6 text-sm font-extrabold text-white transition hover:bg-[#8f0c18]">
                Submit
            </button>
        </div>
        </form>
    </div>
</section>
