@php
    $footerBrand = $siteSettings->get('footer_brand_name', config('app.name', 'Nai Printers'));
    $footerAbout = $siteSettings->get('footer_about_text', config('app.tagline'));
    $footerCopyright = $siteSettings->get('footer_copyright_text', 'All rights reserved.');
    $whatsappLabel = $siteSettings->get('whatsapp_label', 'WhatsApp');
    $whatsappLink = $siteSettings->get('whatsapp_link', config('shop.whatsapp_link'));
    $chatLogoUrl = \App\Support\PublicAssetUrl::resolve($siteSettings->get('whatsapp_logo_url', $siteSettings->get('header_logo_url')));
@endphp

<style>
    .np-site-footer {
        background: #061a33;
        border-top: 1px solid #10233f;
        color: #cbd5e1;
    }

    .np-site-footer h3 {
        color: #ffffff;
    }

    .np-site-footer h4 {
        color: #ff4b35;
    }

    .np-site-footer a,
    .np-site-footer p {
        color: #cbd5e1;
    }

    .np-site-footer a:hover {
        color: #ffffff;
    }

    .np-site-footer-bottom {
        border-top: 1px solid #10233f;
        color: #94a3b8;
    }

    .np-whatsapp-widget {
        position: fixed;
        right: 26px;
        bottom: 24px;
        z-index: 50;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
    }

    .np-whatsapp-card {
        position: relative;
        display: none;
        align-items: center;
        gap: 10px;
        width: min(270px, calc(100vw - 28px));
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 12px;
        background: #fff;
        padding: 12px 34px 12px 13px;
        color: #111827;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
    }

    .np-whatsapp-widget.is-visible .np-whatsapp-card {
        display: flex;
    }

    .np-whatsapp-avatar {
        position: relative;
        display: inline-flex;
        width: 40px;
        height: 40px;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        overflow: visible;
        border: 2px solid #f7b500;
        border-radius: 999px;
        background: #e62222;
        color: #fff;
        font-size: 11px;
        font-weight: 900;
        line-height: 0.9;
        text-align: center;
    }

    .np-whatsapp-avatar img {
        width: 100%;
        height: 100%;
        border-radius: inherit;
        object-fit: cover;
    }

    .np-whatsapp-avatar::after {
        content: "";
        position: absolute;
        right: -3px;
        bottom: 4px;
        width: 11px;
        height: 11px;
        border: 2px solid #fff;
        border-radius: 999px;
        background: #22c55e;
    }

    .np-whatsapp-name {
        margin: 0;
        color: #4b587c;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.15;
    }

    .np-whatsapp-message {
        margin: 4px 0 0;
        color: #111827;
        font-size: 17px;
        font-weight: 400;
        line-height: 1.18;
    }

    .np-whatsapp-close {
        position: absolute;
        top: 8px;
        right: 8px;
        display: inline-flex;
        width: 22px;
        height: 22px;
        align-items: center;
        justify-content: center;
        border: 0;
        background: transparent;
        color: #7b8190;
        cursor: pointer;
    }

    .np-whatsapp-close svg {
        width: 18px;
        height: 18px;
    }

    .np-whatsapp-button {
        display: inline-flex;
        width: 56px;
        height: 56px;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #078b19;
        color: #fff;
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.24);
        transition: transform 160ms ease, background 160ms ease;
    }

    .np-whatsapp-button:hover {
        transform: translateY(-2px);
        background: #067a16;
    }

    .np-whatsapp-button svg {
        width: 33px;
        height: 33px;
    }

    .np-whatsapp-widget.is-closed .np-whatsapp-card {
        display: none;
    }

    @media (max-width: 640px) {
        .np-whatsapp-widget {
            right: 16px;
            bottom: 16px;
            gap: 9px;
        }

        .np-whatsapp-card {
            width: min(260px, calc(100vw - 32px));
            padding: 12px 34px 12px 13px;
        }

        .np-whatsapp-name {
            font-size: 13px;
        }

        .np-whatsapp-message {
            font-size: 16px;
        }

        .np-whatsapp-button {
            width: 54px;
            height: 54px;
        }

        .np-whatsapp-button svg {
            width: 31px;
            height: 31px;
        }
    }
</style>

<footer class="np-site-footer mt-10">
    <div class="relative mx-auto max-w-7xl px-6 py-10 sm:px-8 lg:px-10">
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <h3 class="text-3xl font-extrabold">{{ $footerBrand }}</h3>
                <p class="mt-3 max-w-xs text-[15px] leading-7">
                    {{ $footerAbout }}
                </p>
            </div>

            <div>
                <h4 class="text-sm font-extrabold uppercase tracking-[0.12em]">Company</h4>
                <ul class="mt-3 space-y-2 text-[15px]">
                    <li><a href="{{ route('pages.about') }}">About Us</a></li>
                    <li><a href="{{ route('pages.contact') }}">Contact</a></li>
                    <li><a href="{{ route('pages.blog') }}">Blog</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-extrabold uppercase tracking-[0.12em]">Support</h4>
                <ul class="mt-3 space-y-2 text-[15px]">
                    <li><a href="{{ route('pages.help') }}">Help Center</a></li>
                    <li><a href="{{ route('artwork.create') }}">Artwork Upload</a></li>
                    <li><a href="{{ route('checkout.index') }}">Checkout</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-extrabold uppercase tracking-[0.12em]">Trust</h4>
                <div class="mt-3 space-y-2 text-[15px]">
                    <p>ISO-grade print quality</p>
                    <p>Nationwide delivery</p>
                    <p>Eco-friendly paper options</p>
                </div>
            </div>
        </div>
    </div>

    <div class="np-site-footer-bottom py-3 text-center text-[15px]">
        &copy; {{ now()->year }} {{ $footerBrand }}. {{ $footerCopyright }}
    </div>
</footer>

<div class="np-whatsapp-widget" data-whatsapp-widget>
    <div class="np-whatsapp-card">
        <span class="np-whatsapp-avatar" aria-hidden="true">
            @if($chatLogoUrl)
                <img loading="lazy" decoding="async" src="{{ $chatLogoUrl }}" alt="">
            @else
                NAI<br>PRINTS
            @endif
        </span>
        <div>
            <p class="np-whatsapp-name">Nai Prints</p>
            <p class="np-whatsapp-message">Hi, how can we help?</p>
        </div>
        <button type="button" class="np-whatsapp-close" aria-label="Close WhatsApp message" data-whatsapp-close>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M6 6l12 12M18 6 6 18"/>
            </svg>
        </button>
    </div>
    <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" class="np-whatsapp-button" aria-label="{{ $whatsappLabel }}">
        <svg viewBox="0 0 64 64" aria-hidden="true" fill="currentColor">
            <path d="M32 8C18.75 8 8 17.82 8 29.94c0 7.1 3.69 13.42 9.41 17.43L15.8 56l8.71-4.65A26.33 26.33 0 0 0 32 51.88c13.25 0 24-9.82 24-21.94S45.25 8 32 8Z"/>
            <circle cx="23.5" cy="30.5" r="3.2" fill="#078b19"/>
            <circle cx="32" cy="30.5" r="3.2" fill="#078b19"/>
            <circle cx="40.5" cy="30.5" r="3.2" fill="#078b19"/>
        </svg>
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const widget = document.querySelector('[data-whatsapp-widget]');
        const close = document.querySelector('[data-whatsapp-close]');
        let soundPlayed = false;
        let autoHideTimer = null;

        const hideWidgetCard = () => {
            widget?.classList.remove('is-visible');
        };

        const scheduleAutoHide = () => {
            window.clearTimeout(autoHideTimer);
            autoHideTimer = window.setTimeout(hideWidgetCard, 8000);
        };

        const playNotificationSound = () => {
            if (soundPlayed) {
                return;
            }

            soundPlayed = true;

            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;

                if (! AudioContext) {
                    return;
                }

                const audioContext = new AudioContext();
                const now = audioContext.currentTime;
                const gain = audioContext.createGain();

                gain.connect(audioContext.destination);
                gain.gain.setValueAtTime(0.0001, now);
                gain.gain.exponentialRampToValueAtTime(0.12, now + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.55);

                [740, 980].forEach((frequency, index) => {
                    const oscillator = audioContext.createOscillator();
                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(frequency, now + (index * 0.16));
                    oscillator.connect(gain);
                    oscillator.start(now + (index * 0.16));
                    oscillator.stop(now + 0.22 + (index * 0.16));
                });

                setTimeout(() => audioContext.close(), 750);
            } catch (error) {
                // Some browsers block audio before user interaction; the popup still appears.
            }
        };

        if (widget && close) {
            window.setTimeout(() => {
                if (! widget.classList.contains('is-closed')) {
                    widget.classList.add('is-visible');
                    scheduleAutoHide();
                    playNotificationSound();
                }
            }, 10000);

            widget.addEventListener('mouseenter', () => window.clearTimeout(autoHideTimer));
            widget.addEventListener('mouseleave', () => {
                if (widget.classList.contains('is-visible')) {
                    scheduleAutoHide();
                }
            });

            widget.addEventListener('focusin', () => window.clearTimeout(autoHideTimer));
            widget.addEventListener('focusout', () => {
                if (widget.classList.contains('is-visible')) {
                    scheduleAutoHide();
                }
            });

            close.addEventListener('click', () => {
                window.clearTimeout(autoHideTimer);
                widget.classList.add('is-closed');
            });
        }
    });
</script>
