<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $designMetaTitle = $cmsPage?->meta_title ?: 'Design Services - ' . config('app.name');
        $designMetaDescription = $cmsPage?->meta_description ?: 'Creative professional design and branding portfolio page.';
        $designCanonicalUrl = rtrim(config('app.url'), '/') . route('pages.design-services', [], false);
        $designFaviconUrl = \App\Support\PublicAssetUrl::resolve($siteSettings->get('favicon_url'))
            ?: rtrim(config('app.url'), '/') . '/favicon.svg';
    @endphp
    <title>{{ $designMetaTitle }}</title>
    <meta name="description" content="{{ $designMetaDescription }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ $designCanonicalUrl }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Nai Printers') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $designMetaTitle }}">
    <meta property="og:description" content="{{ $designMetaDescription }}">
    <meta property="og:url" content="{{ $designCanonicalUrl }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $designMetaTitle }}">
    <meta name="twitter:description" content="{{ $designMetaDescription }}">
    <link rel="icon" href="{{ $designFaviconUrl }}" sizes="any">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link rel="preconnect" href="https://images.pexels.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: #fff;
            color: #050505;
            font-family: 'Inter', Arial, sans-serif;
        }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Inter', sans-serif;
        }
        .page {
            width: 100%;
            margin: 0 auto;
            background: #fff;
            overflow: hidden;
        }
        .growth-hero {
            overflow: hidden;
            background:
                radial-gradient(circle at 56% 45%, rgba(177, 15, 30, .48), transparent 34%),
                linear-gradient(100deg, #070304 0%, #120507 36%, #61101a 63%, #070304 100%);
            color: #fff;
        }
        .growth-hero-inner {
            display: grid;
            min-height: 520px;
            grid-template-columns: 0.82fr 1.18fr;
            gap: 44px;
            align-items: center;
            padding: 54px 0;
        }
        .growth-pill {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            border: 1px solid rgba(255, 102, 116, .9);
            border-radius: 999px;
            padding: 0 14px;
            color: #ffb3ba;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .growth-title {
            max-width: 440px;
            margin: 26px 0 0;
            color: #fff;
            font-size: 54px;
            line-height: .92;
            letter-spacing: -3px;
        }
        .growth-title em {
            display: block;
            font-size: 60px;
            font-weight: 700;
            letter-spacing: -2px;
        }
        .growth-copy {
            max-width: 330px;
            margin: 24px 0 0;
            color: rgba(255,255,255,.82);
            font-size: 13px;
            line-height: 1.75;
        }
        .growth-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 38px;
            margin-top: 28px;
            border: 1px solid #ff6674;
            border-radius: 999px;
            padding: 0 22px;
            color: #fff;
            font-size: 12px;
            font-weight: 900;
            box-shadow: 0 0 18px rgba(177, 15, 30, .35);
        }
        .growth-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 16px;
            align-items: center;
        }
        .growth-card {
            min-height: 150px;
            overflow: hidden;
            border-radius: 22px;
            background: rgba(255,255,255,.9);
            color: #111;
            box-shadow: 0 18px 60px rgba(0,0,0,.22);
        }
        .growth-card.dark {
            display: grid;
            place-items: center;
            background: #0d0506;
            color: #ffb3ba;
            text-align: center;
        }
        .growth-card.stat {
            padding: 22px;
        }
        .growth-card.stat strong {
            display: block;
            margin-top: 6px;
            font-size: 34px;
            line-height: 1;
            letter-spacing: -2px;
        }
        .growth-card.stat span {
            color: #7f5f62;
            font-size: 11px;
            line-height: 1.35;
        }
        .growth-card img {
            width: 100%;
            height: 100%;
            min-height: 150px;
            object-fit: cover;
        }
        .growth-card.offset {
            transform: translateY(-34px);
        }
        .growth-card.lower {
            transform: translateY(30px);
        }
        .wrap {
            width: min(100% - 48px, 1240px);
            margin: 0 auto;
        }
        .hero {
            padding: 18px 0 0;
            border-bottom: 1px solid #d6d6d6;
        }
        .hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 36px;
        }
        .hero-title {
            margin: 0;
            font-size: 112px;
            line-height: 0.78;
            font-weight: 800;
            letter-spacing: 0;
            text-shadow: 0 5px 0 rgba(0,0,0,.16);
        }
        .stars {
            margin-top: 32px;
            font-size: 42px;
            letter-spacing: 18px;
        }
        .hero-meta {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 210px;
            align-items: start;
            padding: 62px 0 54px;
        }
        .contact-line {
            display: block;
            border-bottom: 1px solid #bdbdbd;
            padding: 8px 0;
            font-size: 11px;
            line-height: 1;
        }
        .socials {
            display: flex;
            gap: 12px;
            padding-top: 20px;
        }
        .social {
            display: inline-flex;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            border: 1px solid #bdbdbd;
            border-radius: 999px;
            font-size: 12px;
        }
        .intro p {
            margin: 0;
            max-width: 420px;
            font-size: 11px;
            line-height: 1.45;
        }
        .black-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 94px;
            height: 30px;
            margin-top: 18px;
            border-radius: 8px;
            background: #050505;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
        }
        .hero-gallery {
            position: relative;
            width: 100%;
            padding-bottom: 56px;
        }
        .gallery-track {
            display: grid;
            grid-template-columns: 234px 354px 234px;
            justify-content: center;
            gap: 2px;
        }
        .gallery-card {
            height: 374px;
            overflow: hidden;
            border-radius: 10px;
            background: #f3f3f3;
            display: none;
        }
        .gallery-card.active { display: block; }
        .gallery-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-card.center { background: #eff7b7; }
        .service-categories {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            padding: 0 0 18px;
        }
        .service-category {
            border: 1px solid #d3d3d3;
            border-radius: 999px;
            background: #fff;
            padding: 10px 16px;
            color: #111;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .service-category.active,
        .service-category:hover {
            border-color: #050505;
            background: #050505;
            color: #fff;
        }
        .section {
            padding: 78px 0;
        }
        .section.soft {
            background: #f8f8f8;
            border-top: 1px solid #dedede;
            border-bottom: 1px solid #dedede;
        }
        .section-head {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 72px;
            align-items: end;
            margin-bottom: 36px;
        }
        .eyebrow {
            margin: 0 0 10px;
            font-size: 12px;
            color: #555;
        }
        h2 {
            margin: 0;
            font-size: 38px;
            line-height: 1.02;
            letter-spacing: -2px;
        }
        .section-head p {
            margin: 0;
            font-size: 12px;
            line-height: 1.5;
            color: #555;
        }
        .service-list {
            display: grid;
            gap: 10px;
        }
        .service-card {
            overflow: hidden;
            border: 1px solid #d6d6d6;
            border-radius: 12px;
            background: #fff;
        }
        .service-card.open {
            min-height: 360px;
        }
        .service-row {
            display: grid;
            grid-template-columns: 420px 1fr auto;
            gap: 20px;
            align-items: center;
            padding: 16px;
        }
        .service-num {
            font-size: 18px;
            letter-spacing: -1px;
        }
        .service-num.star::before {
            content: '*';
            display: inline-block;
            margin-right: 14px;
            font-size: 34px;
            line-height: 0;
            vertical-align: middle;
        }
        .service-title {
            font-size: 15px;
            font-weight: 700;
        }
        .small-btn {
            height: 26px;
            padding: 0 16px;
            border: 1px solid #d6d6d6;
            border-radius: 6px;
            background: #fff;
            font-size: 10px;
            font-weight: 700;
        }
        .small-btn.dark {
            border-color: #050505;
            background: #050505;
            color: #fff;
        }
        .service-card:not(.open) .small-btn {
            border-color: #d6d6d6;
            background: #fff;
            color: #050505;
        }
        .service-card.open .small-btn {
            border-color: #050505;
            background: #050505;
            color: #fff;
        }
        .service-detail {
            display: none;
            grid-template-columns: 1fr 1.15fr;
            gap: 32px;
            align-items: start;
            padding: 0 20px 20px;
        }
        .service-card.open .service-detail {
            display: grid;
        }
        .service-image {
            overflow: hidden;
            border-radius: 10px;
            background: #eee;
        }
        .service-image img {
            width: 100%;
            height: 282px;
            object-fit: cover;
        }
        .service-copy {
            margin: 0;
            font-size: 14px;
            line-height: 1.55;
            color: #333;
        }
        .request-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            margin-top: 20px;
            padding: 0 18px;
            border: 0;
            border-radius: 8px;
            background: #050505;
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .request-btn:hover {
            background: #b10f1e;
        }
        .process {
            text-align: center;
        }
        .process h2 {
            max-width: 600px;
            margin: 0 auto;
        }
        .circles {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 48px 0 42px;
        }
        .circle {
            display: grid;
            width: 178px;
            aspect-ratio: 1;
            place-items: center;
            margin-left: -18px;
            border: 1px solid #bdbdbd;
            border-radius: 999px;
            background: #fafafa;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
        }
        .circle:first-child { margin-left: 0; }
        .circle.active {
            position: relative;
            z-index: 1;
            border: 3px solid #050505;
            box-shadow: 0 0 0 12px #fff, 0 14px 36px rgba(0,0,0,.14);
        }
        .process-note {
            margin: 0 auto;
            max-width: 360px;
            font-size: 11px;
            line-height: 1.5;
            color: #555;
        }
        .process-modal {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(0,0,0,.48);
        }
        .process-modal.open {
            display: flex;
        }
        .process-dialog {
            width: min(100%, 560px);
            border: 1px solid #d6d6d6;
            border-radius: 16px;
            background: #fff;
            padding: 24px;
            box-shadow: 0 24px 80px rgba(0,0,0,.28);
        }
        .process-dialog-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }
        .process-dialog-title {
            margin: 0;
            font-size: 26px;
            line-height: 1.1;
            letter-spacing: -1px;
        }
        .process-dialog-copy {
            margin: 16px 0 0;
            font-size: 14px;
            line-height: 1.65;
            color: #444;
        }
        .process-close {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border: 1px solid #d6d6d6;
            border-radius: 999px;
            background: #fff;
            color: #050505;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
        }
        .request-modal {
            position: fixed;
            inset: 0;
            z-index: 60;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(0,0,0,.52);
        }
        .request-modal.open { display: flex; }
        .request-dialog {
            width: min(100%, 620px);
            border: 1px solid #d6d6d6;
            border-radius: 16px;
            background: #fff;
            padding: 24px;
            box-shadow: 0 24px 80px rgba(0,0,0,.3);
        }
        .request-dialog-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }
        .request-dialog-title {
            margin: 0;
            font-size: 26px;
            line-height: 1.1;
            letter-spacing: -1px;
        }
        .request-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .request-form-grid input,
        .request-form-grid select,
        .request-form-grid textarea {
            width: 100%;
            border: 1px solid #cfcfcf;
            border-radius: 8px;
            padding: 11px 12px;
            font: inherit;
            font-size: 13px;
        }
        .request-form-grid textarea {
            min-height: 110px;
            resize: vertical;
        }
        .request-form-grid .full { grid-column: 1 / -1; }
        .contact-section {
            border-top: 1px solid #dedede;
            background: #f8f8f8;
            padding: 64px 0;
        }
        .experience-band {
            position: relative;
            overflow: hidden;
            background: #151515;
            color: #f5f5f5;
            padding: 72px 0 54px;
        }
        .experience-band h2 {
            max-width: 620px;
            font-size: 58px;
            line-height: 1.12;
            letter-spacing: -4px;
            color: #f4f4f4;
        }
        .experience-logos {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 28px;
            margin-top: 62px;
        }
        .experience-logo {
            display: grid;
            width: min(100%, 180px);
            aspect-ratio: 1;
            place-items: center;
            justify-self: center;
            border-radius: 999px;
            background: #202020;
            color: #8c8c8c;
            font-size: 30px;
            font-weight: 900;
            letter-spacing: -1.5px;
        }
        .experience-side {
            position: absolute;
            top: 34px;
            right: 28px;
            writing-mode: vertical-rl;
            color: #d7d7d7;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .contact-panel {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            gap: 36px;
            align-items: start;
            border: 1px solid #d6d6d6;
            border-radius: 14px;
            background: #fff;
            padding: 28px;
        }
        .work-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .arrows {
            display: flex;
            gap: 10px;
        }
        .arrow {
            display: grid;
            width: 28px;
            height: 28px;
            place-items: center;
            border: 0;
            border-radius: 999px;
            background: #f5f5ef;
            font-weight: 800;
        }
        .projects {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .project {
            border: 1px solid #d6d6d6;
            border-radius: 10px;
            background: #fff;
            padding: 8px;
        }
        .project img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            background: #eee;
        }
        .project-meta {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            padding: 18px 8px 8px;
        }
        .project h3 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .project p {
            margin: 12px 0 0;
            font-size: 11px;
            line-height: 1.45;
            color: #555;
        }
        .project-year {
            font-size: 12px;
            color: #333;
        }
        .project-slide { display: none; }
        .project-slide.active { display: block; }
        @media (max-width: 900px) {
            .wrap { width: min(100% - 32px, 852px); }
            .growth-hero-inner {
                grid-template-columns: 1fr;
            }
            .growth-title {
                font-size: 42px;
                letter-spacing: -2px;
            }
            .growth-title em {
                font-size: 48px;
            }
            .growth-cards {
                grid-template-columns: 1fr 1fr;
            }
            .growth-card.offset,
            .growth-card.lower {
                transform: none;
            }
            .hero-title { font-size: 78px; letter-spacing: -4px; }
            .hero-meta,
            .section-head,
            .service-detail,
            .projects {
                grid-template-columns: 1fr;
            }
            .hero-gallery {
                width: 100%;
                margin-left: 0;
            }
            .gallery-track {
                grid-template-columns: 1fr;
                gap: 2px;
            }
            .gallery-card { height: 320px; }
            .service-row {
                grid-template-columns: 1fr;
            }
            .contact-panel {
                grid-template-columns: 1fr;
            }
            .experience-band h2 {
                font-size: 38px;
                letter-spacing: -2px;
            }
            .experience-logos {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }
            .experience-logo {
                font-size: 22px;
            }
            .circles {
                flex-wrap: wrap;
                gap: 12px;
            }
            .circle { width: 132px; margin-left: 0; }
        }
    </style>
</head>
<body>
    @include('partials.site-header')
    @php
        $sectionMap = collect($designSections ?? \App\Support\DesignServicesContentDefaults::sections())->keyBy('component');
        $heroSection = $sectionMap->get('design_hero', []);
        $heroItems = collect(data_get($heroSection, 'items', []))->values();
        $gallerySection = $sectionMap->get('design_gallery', []);
        $galleryItems = collect(data_get($gallerySection, 'items', []))->filter(fn ($item) => is_array($item) && data_get($item, 'image_url'))->values();
        $servicesSection = $sectionMap->get('design_services', []);
        $serviceItems = collect(data_get($servicesSection, 'items', []))->filter(fn ($item) => is_array($item) && data_get($item, 'title'))->values();
        $serviceCategories = $serviceItems->map(fn ($item) => [
            'id' => data_get($item, 'subtitle') ?: data_get($item, 'slug') ?: str(data_get($item, 'title'))->slug()->toString(),
            'title' => data_get($item, 'title'),
        ])->values();
        $processSection = $sectionMap->get('design_process', []);
        $processItems = collect(data_get($processSection, 'items', []))->filter(fn ($item) => is_array($item) && data_get($item, 'title'))->values();
        $projectsSection = $sectionMap->get('design_projects', []);
        $projectItems = collect($projects ?? \App\Support\DesignServicesContentDefaults::projects($designSections ?? null))->values();
        $experienceSection = $sectionMap->get('design_experience', []);
        $experienceItems = collect(data_get($experienceSection, 'items', []))->filter(fn ($item) => is_array($item) && data_get($item, 'title'))->values();
        $contactSection = $sectionMap->get('design_contact', []);
        $defaultServiceTitle = data_get($serviceItems->first(), 'title', 'Design Service');
    @endphp

    <main class="page">
        <section class="growth-hero">
            <div class="wrap growth-hero-inner">
                <div>
                    <h1 class="growth-title">{{ data_get($heroSection, 'title', 'Print Designs That') }} <em>{{ data_get($heroSection, 'subtitle', 'Look Ready') }}</em></h1>
                    <p class="growth-copy">{{ data_get($heroSection, 'content') }}</p>
                    <a href="{{ data_get($heroSection, 'button_url', '#design-request') }}" class="growth-button">{{ data_get($heroSection, 'button_label', 'Get Started') }}</a>
                </div>

                <div class="growth-cards" aria-hidden="true">
                    @foreach($heroItems as $heroItemIndex => $heroItem)
                        @php
                            $cardType = data_get($heroItem, 'content', 'image');
                            $cardClasses = trim(implode(' ', [
                                'growth-card',
                                in_array($cardType, ['stat', 'dark'], true) ? $cardType : '',
                                in_array($heroItemIndex, [0, 1], true) ? 'offset' : '',
                                $heroItemIndex >= 4 ? 'lower' : '',
                            ]));
                        @endphp
                        <div class="{{ $cardClasses }}">
                            @if($cardType === 'stat')
                                <span>{{ data_get($heroItem, 'title') }}</span>
                                <strong>{{ data_get($heroItem, 'subtitle') }}</strong>
                            @elseif($cardType === 'dark')
                                <div>
                                    <div style="font-size:42px; line-height:1;">◎</div>
                                    <div style="margin-top:8px; font-size:11px; font-weight:900; text-transform:uppercase;">{{ data_get($heroItem, 'title') }}</div>
                                </div>
                            @elseif(data_get($heroItem, 'image_url'))
                                <img loading="{{ $heroItemIndex < 3 ? 'eager' : 'lazy' }}" @if($heroItemIndex < 3) fetchpriority="high" @endif decoding="async" src="{{ data_get($heroItem, 'image_url') }}" alt="">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="hero">
            <div class="wrap">
                <div class="section-head" style="margin-bottom: 34px;">
                    <div>
                        <p class="eyebrow">{{ data_get($gallerySection, 'subtitle', 'Service') }}</p>
                        <h2>{{ data_get($gallerySection, 'title', 'Unleashing Your Brand’s Potential') }}</h2>
                    </div>
                </div>
                <div class="hero-gallery" data-slideshow="hero">
                    <div class="service-categories" aria-label="Service categories">
                        @foreach($serviceCategories as $categoryIndex => $category)
                            <button type="button" class="service-category {{ $categoryIndex === 0 ? 'active' : '' }}" data-service-category="{{ $category['id'] }}">{{ $category['title'] }}</button>
                        @endforeach
                    </div>
                    <div class="gallery-track">
                        @foreach($galleryItems as $galleryItem)
                            @php
                                $galleryCategory = data_get($galleryItem, 'subtitle') ?: data_get($serviceCategories->first(), 'id', 'design');
                                $isCenter = str_contains((string) data_get($galleryItem, 'content'), 'center');
                            @endphp
                            <figure class="gallery-card {{ $isCenter ? 'center' : '' }}" data-slide data-gallery-category="{{ $galleryCategory }}">
                                <img loading="lazy" decoding="async" src="{{ data_get($galleryItem, 'image_url') }}" alt="{{ data_get($galleryItem, 'title', 'Design service mockup') }}">
                            </figure>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="wrap">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">{{ data_get($servicesSection, 'subtitle', 'Service') }}</p>
                        <h2>{{ data_get($servicesSection, 'title', 'Unleashing Your Brand’s Potential') }}</h2>
                    </div>
                    <p>{{ data_get($servicesSection, 'content') }}</p>
                </div>

                <div class="service-list">
                    @foreach($serviceItems as $serviceIndex => $service)
                        @php
                            $serviceId = data_get($service, 'subtitle') ?: data_get($service, 'slug') ?: str(data_get($service, 'title'))->slug()->toString();
                        @endphp
                        <article id="{{ $serviceId }}" class="service-card {{ $serviceIndex === 0 ? 'open' : '' }}" data-service-card>
                            <div class="service-row">
                                <div class="service-num {{ $serviceIndex === 0 ? 'star' : '' }}">{{ str_pad((string) ($serviceIndex + 1), 2, '0', STR_PAD_LEFT) }}</div>
                                <div class="service-title">{{ data_get($service, 'title') }}</div>
                                <button type="button" class="small-btn {{ $serviceIndex === 0 ? 'dark' : '' }}" data-service-toggle>See Detail</button>
                            </div>
                            <div class="service-detail">
                                <div>
                                    <p class="service-copy">{{ data_get($service, 'content') }}</p>
                                    <button type="button" class="request-btn" data-request-service="{{ data_get($service, 'title') }}">Book / Request</button>
                                </div>
                                <div>
                                    <div class="service-image">
                                        <img loading="lazy" decoding="async" src="{{ data_get($service, 'image_url') }}" alt="{{ data_get($service, 'title') }} mockup">
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section soft process">
            <div class="wrap">
                <p class="eyebrow">{{ data_get($processSection, 'subtitle', 'How We Work') }}</p>
                <h2>{{ data_get($processSection, 'title', 'A Methodical Process For Maximum Results.') }}</h2>
                <div class="circles">
                    @foreach($processItems as $processIndex => $processItem)
                        <button type="button" class="circle {{ $processIndex === 2 ? 'active' : '' }}" data-process-title="{{ data_get($processItem, 'title') }}" data-process-copy="{{ data_get($processItem, 'content') }}">{{ data_get($processItem, 'title') }}</button>
                    @endforeach
                </div>
                <p class="process-note">{{ data_get($processSection, 'content') }}</p>
            </div>
        </section>

        <div class="process-modal" data-process-modal aria-hidden="true">
            <div class="process-dialog" role="dialog" aria-modal="true" aria-labelledby="process-modal-title">
                <div class="process-dialog-head">
                    <h3 id="process-modal-title" class="process-dialog-title" data-process-modal-title>{{ data_get($processItems->get(2), 'title', data_get($processItems->first(), 'title', 'Design')) }}</h3>
                    <button type="button" class="process-close" data-process-close aria-label="Close process details">×</button>
                </div>
                <p class="process-dialog-copy" data-process-modal-copy>{{ data_get($processItems->get(2), 'content', data_get($processItems->first(), 'content', '')) }}</p>
            </div>
        </div>

        <div class="request-modal" data-request-modal aria-hidden="true">
            <div class="request-dialog" role="dialog" aria-modal="true" aria-labelledby="request-modal-title">
                <div class="request-dialog-head">
                    <h3 id="request-modal-title" class="request-dialog-title">Book / Request Service</h3>
                    <button type="button" class="process-close" data-request-close aria-label="Close request form">×</button>
                </div>
                <form method="POST" action="{{ route('pages.business-stationery-request') }}">
                    @csrf
                    <input type="hidden" name="page_title" value="Design Services">
                    <input type="hidden" name="page_slug" value="design-services">
                    <input type="hidden" name="request_url" value="{{ url()->current() }}">

                    <div class="request-form-grid">
                        <input type="text" name="full_name" placeholder="Full name">
                        <input type="text" name="phone" placeholder="Phone number">
                        <input class="full" type="email" name="email" placeholder="Email address">
                        <select class="full" name="design_service" data-request-service-input>
                            @foreach($serviceItems as $service)
                                <option value="{{ data_get($service, 'title') }}">{{ data_get($service, 'title') }}</option>
                            @endforeach
                        </select>
                        <textarea class="full" name="design_brief" placeholder="Tell us what you want designed, preferred style, quantity, timeline, or any reference you have."></textarea>
                    </div>

                    <button type="submit" class="request-btn">Send request</button>
                </form>
            </div>
        </div>

        <section id="work" class="section">
            <div class="wrap">
                <div class="work-head">
                    <div>
                        <p class="eyebrow">{{ data_get($projectsSection, 'subtitle', 'Work') }}</p>
                        <h2>{!! nl2br(e(data_get($projectsSection, 'title', 'Showcasing My Most Recent Projects'))) !!}</h2>
                    </div>
                    <div class="arrows" data-project-controls>
                        <button class="arrow" type="button" data-prev>↶</button>
                        <button class="arrow" type="button" data-next>↷</button>
                    </div>
                </div>

                <div class="projects" data-slideshow="projects">
                    @foreach($projectItems as $projectIndex => $project)
                        <a href="{{ route('pages.design-projects.show', $project['slug']) }}" class="project project-slide {{ $projectIndex < 2 ? 'active' : '' }}" data-slide>
                            <img loading="lazy" decoding="async" src="{{ $project['images'][0] ?? asset('favicon.svg') }}" alt="{{ $project['title'] }} mockup">
                            <div class="project-meta">
                                <div>
                                    <h3>{{ $project['title'] }}</h3>
                                    <p>{{ $project['summary'] }}</p>
                                </div>
                                <span class="project-year">{{ $project['year'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="experience-band" aria-label="Experience and brands">
            <div class="wrap">
                <h2>{{ data_get($experienceSection, 'title', '8 years of experience and have worked with') }}</h2>
                <div class="experience-logos">
                    @foreach($experienceItems as $experienceItem)
                        <div class="experience-logo">{{ data_get($experienceItem, 'title') }}</div>
                    @endforeach
                </div>
            </div>
            <div class="experience-side">{{ data_get($experienceSection, 'subtitle', '07') }}</div>
        </section>

        <section id="design-request" class="contact-section">
            <div class="wrap">
                <div class="contact-panel">
                    <div>
                        <p class="eyebrow">{{ data_get($contactSection, 'subtitle', 'Contact') }}</p>
                        <h2>{{ data_get($contactSection, 'title', 'Let’s Start Your Design Project') }}</h2>
                        <p class="process-note" style="margin:18px 0 0; text-align:left;">
                            {{ data_get($contactSection, 'content') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('pages.business-stationery-request') }}">
                        @csrf
                        <input type="hidden" name="page_title" value="Design Services">
                        <input type="hidden" name="page_slug" value="design-services">
                        <input type="hidden" name="request_url" value="{{ url()->current() }}">

                        <div class="request-form-grid">
                            <input type="text" name="full_name" placeholder="Full name">
                            <input type="text" name="phone" placeholder="Phone number">
                            <input class="full" type="email" name="email" placeholder="Email address">
                            <select class="full" name="design_service">
                                @foreach($serviceItems as $service)
                                    <option value="{{ data_get($service, 'title') }}">{{ data_get($service, 'title') }}</option>
                                @endforeach
                            </select>
                            <textarea class="full" name="design_brief" placeholder="Tell us what you want designed, preferred style, quantity, timeline, or any reference you have."></textarea>
                        </div>

                        <button type="submit" class="request-btn">Send request</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script>
        const activateWindow = (slides, start, visibleCount) => {
            slides.forEach((slide, index) => {
                const normalized = (index - start + slides.length) % slides.length;
                slide.classList.toggle('active', normalized < visibleCount);
            });
        };

        const heroSlides = Array.from(document.querySelectorAll('[data-slideshow="hero"] [data-slide]'));
        let heroIndex = 0;
        let activeGalleryCategory = @js(data_get($serviceCategories->first(), 'id', 'design'));
        const renderCategoryImages = (category) => {
            activeGalleryCategory = category;
            const categorySlides = heroSlides.filter((slide) => slide.dataset.galleryCategory === category);
            heroSlides.forEach((slide) => slide.classList.remove('active'));
            categorySlides.slice(0, 3).forEach((slide) => slide.classList.add('active'));
        };
        renderCategoryImages(activeGalleryCategory);

        const projectSlides = Array.from(document.querySelectorAll('[data-slideshow="projects"] [data-slide]'));
        let projectIndex = 0;
        const renderProjects = () => activateWindow(projectSlides, projectIndex, 2);
        const moveProjects = (direction) => {
            projectIndex = (projectIndex + direction + projectSlides.length) % projectSlides.length;
            renderProjects();
        };

        document.querySelector('[data-prev]')?.addEventListener('click', () => moveProjects(-2));
        document.querySelector('[data-next]')?.addEventListener('click', () => moveProjects(2));

        if (projectSlides.length > 2) {
            setInterval(() => moveProjects(2), 5000);
        }

        const serviceCards = Array.from(document.querySelectorAll('[data-service-card]'));
        const serviceCategoryButtons = Array.from(document.querySelectorAll('[data-service-category]'));
        const setActiveService = (serviceId, shouldScroll = false) => {
            serviceCards.forEach((item) => item.classList.toggle('open', item.id === serviceId));
            serviceCategoryButtons.forEach((item) => item.classList.toggle('active', item.dataset.serviceCategory === serviceId));

            renderCategoryImages(serviceId);

            if (shouldScroll) {
                document.getElementById(serviceId)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        serviceCards.forEach((card) => {
            card.querySelector('[data-service-toggle]')?.addEventListener('click', () => {
                setActiveService(card.id);
            });
        });
        serviceCategoryButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setActiveService(button.dataset.serviceCategory, false);
            });
        });

        const processModal = document.querySelector('[data-process-modal]');
        const processModalTitle = document.querySelector('[data-process-modal-title]');
        const processModalCopy = document.querySelector('[data-process-modal-copy]');
        const processCircles = Array.from(document.querySelectorAll('[data-process-title]'));
        const closeProcessModal = () => {
            processModal?.classList.remove('open');
            processModal?.setAttribute('aria-hidden', 'true');
        };

        processCircles.forEach((circle) => {
            circle.addEventListener('click', () => {
                processCircles.forEach((item) => item.classList.toggle('active', item === circle));
                processModalTitle.textContent = circle.dataset.processTitle || '';
                processModalCopy.textContent = circle.dataset.processCopy || '';
                processModal?.classList.add('open');
                processModal?.setAttribute('aria-hidden', 'false');
            });
        });

        document.querySelector('[data-process-close]')?.addEventListener('click', closeProcessModal);
        processModal?.addEventListener('click', (event) => {
            if (event.target === processModal) {
                closeProcessModal();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeProcessModal();
                closeRequestModal();
            }
        });

        const requestModal = document.querySelector('[data-request-modal]');
        const requestTitle = document.querySelector('#request-modal-title');
        const requestServiceInput = document.querySelector('[data-request-service-input]');
        const closeRequestModal = () => {
            requestModal?.classList.remove('open');
            requestModal?.setAttribute('aria-hidden', 'true');
        };

        document.querySelectorAll('[data-request-service]').forEach((button) => {
            button.addEventListener('click', () => {
                const service = button.dataset.requestService || 'Design Service';
                requestTitle.textContent = `Book / Request: ${service}`;
                requestServiceInput.value = service;
                requestModal?.classList.add('open');
                requestModal?.setAttribute('aria-hidden', 'false');
            });
        });

        document.querySelector('[data-request-close]')?.addEventListener('click', closeRequestModal);
        requestModal?.addEventListener('click', (event) => {
            if (event.target === requestModal) {
                closeRequestModal();
            }
        });
    </script>
</body>
</html>
