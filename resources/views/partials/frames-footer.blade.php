@php
    $footerColumns = $siteSettings->has('footer_columns') ? json_decode($siteSettings->get('footer_columns', '[]'), true) : null;
    if (! is_array($footerColumns)) {
        $footerColumns = [
            ['heading' => 'About Us', 'links' => [['label' => 'Our story', 'url' => '/about'], ['label' => 'Why Photo Frames', 'url' => '/about'], ['label' => 'Ideas & inspiration', 'url' => '/blog'], ['label' => 'Get in touch', 'url' => '/contact']]],
            ['heading' => 'Support', 'links' => [['label' => 'Contact us', 'url' => '/contact'], ['label' => 'Framing help', 'url' => '/help'], ['label' => 'Shipping & returns', 'url' => '/help'], ['label' => 'FAQs', 'url' => '/help'], ['label' => 'My orders', 'url' => '/login']]],
            ['heading' => 'Find Your Frame', 'links' => [['label' => 'Shop by type', 'url' => '#frame-types'], ['label' => 'Shop by color', 'url' => '#frame-colors'], ['label' => 'Shop by décor', 'url' => '#frame-decor'], ['label' => 'Custom prints', 'url' => '#custom-prints'], ['label' => 'Explore all frames', 'url' => '/products']]],
        ];
    }
    $footerHref = fn ($target) => preg_match('~^(https?://|mailto:|tel:|#)~i', (string) $target) ? $target : url(ltrim((string) $target, '/'));
    $footerBrand = $siteSettings->get('footer_brand_name', $siteSettings->get('site_name', config('app.name', 'Photo Frames')));
@endphp
<footer class="pf-footer">
    <a class="pf-logo pf-footer-logo" href="{{ route('home') }}"><span class="pf-logo-mark" aria-hidden="true"></span>{{ $footerBrand }}</a>
    <div class="pf-footer-grid pf-container">
        @foreach($footerColumns as $column)
            <div><h2>{{ $column['heading'] ?? '' }}</h2>@foreach(($column['links'] ?? []) as $link)<a href="{{ $footerHref($link['url'] ?? '/') }}">{{ $link['label'] ?? '' }}</a>@endforeach</div>
        @endforeach
        <div class="pf-footer-contact"><h2>{{ $siteSettings->get('footer_contact_heading', 'A little framing advice?') }}</h2><p>{{ $siteSettings->get('footer_contact_text') ?: $siteSettings->get('footer_about_text', 'From choosing a finish to finding the right size, let’s make something you’ll love.') }}</p><a class="pf-button" href="{{ route('pages.contact') }}">{{ $siteSettings->get('footer_contact_button', 'Talk to us') }} <span aria-hidden="true">↗</span></a><a href="mailto:{{ $siteSettings->get('footer_contact_email', 'info@photoframes.co.ke') }}">{{ $siteSettings->get('footer_contact_email', 'info@photoframes.co.ke') }}</a><span class="pf-footer-location">{{ $siteSettings->get('footer_location_text', 'Nairobi, Kenya · Nationwide delivery') }}</span></div>
    </div>
    <div class="pf-footer-bottom pf-container"><small>© {{ date('Y') }} {{ $siteSettings->get('footer_brand_name', $siteSettings->get('site_name', 'Photo Frames')) }}. {{ $siteSettings->get('footer_copyright_text', 'All rights reserved.') }}</small><span>{{ $siteSettings->get('footer_bottom_tagline', 'Made for the moments that matter.') }}</span><span class="pf-footer-powered-by">@if($siteSettings->get('footer_powered_by_url'))<a href="{{ $footerHref($siteSettings->get('footer_powered_by_url')) }}">{{ $siteSettings->get('footer_powered_by_label', 'Powered by Nai Prints') }}</a>@else{{ $siteSettings->get('footer_powered_by_label', 'Powered by Nai Prints') }}@endif</span><a href="#homepage">{{ $siteSettings->get('footer_back_to_top_label', 'Back to top ↑') }}</a></div>
</footer>
<div class="pf-whatsapp-widget" data-pf-whatsapp-widget>
    <a class="pf-whatsapp-float" style="width:50px;height:50px" href="{{ $footerHref($siteSettings->get('whatsapp_link', config('shop.whatsapp_link'))) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $siteSettings->get('whatsapp_label', 'Chat with us on WhatsApp') }}" title="{{ $siteSettings->get('whatsapp_label', 'Chat with us on WhatsApp') }}">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.7a8.5 8.5 0 0 1-12.6 7.4L3 20l.9-4.7a8.5 8.5 0 1 1 16.6-3.6Z"/><circle cx="8.5" cy="12" r=".8"/><circle cx="12" cy="12" r=".8"/><circle cx="15.5" cy="12" r=".8"/></svg>
    </a>
</div>
