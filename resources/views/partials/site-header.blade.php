@php
    $businessStationerySubmenu = [
        ['label' => 'Branded Folders', 'url' => route('pages.branded-folders')],
        ['label' => 'Certificates', 'url' => route('pages.certificates')],
        ['label' => 'Complimentary Slips', 'url' => route('pages.token-business')],
        ['label' => 'Custom Branded Receipts, Invoices & Delivery Books', 'url' => route('pages.custom-receipts')],
        ['label' => 'Document Printing', 'url' => route('products.index', ['q' => 'document printing'])],
        ['label' => 'Envelopes, Custom/Branded', 'url' => route('pages.envelopes')],
        ['label' => 'Letterhead Printing', 'url' => route('products.index', ['q' => 'letterhead printing'])],
    ];

    $designServicesSubmenu = [
        ['label' => 'Custom Logo Design', 'url' => route('pages.design-services') . '#custom-logo-design'],
        ['label' => 'Website Design Services', 'url' => route('pages.design-services') . '#website-design-services'],
        ['label' => 'Custom Graphic Design', 'url' => route('pages.design-services') . '#custom-graphic-design'],
        ['label' => 'Brochure & Magazine Design', 'url' => route('pages.design-services') . '#brochure-magazine-design'],
    ];

    $whatWePrintItems = [
        ['label' => 'Business Stationary', 'url' => route('products.index', ['category' => 'business-cards']), 'children' => $businessStationerySubmenu],
        ['label' => 'Design Services', 'url' => route('pages.design-services'), 'children' => $designServicesSubmenu],
        ['label' => 'Marketing Collateral & Publications', 'url' => route('products.index', ['category' => 'flyers-brochures'])],
        ['label' => 'Promotional Marketing', 'url' => route('products.index', ['category' => 'apparel'])],
        ['label' => 'Outdoor Marketing', 'url' => route('products.index', ['category' => 'banners'])],
        ['label' => 'Other Printing Services', 'url' => route('pages.about')],
    ];

    $themeNavItems = [
        ['key' => 'brochures', 'label' => 'Brochures', 'url' => route('products.index', ['category' => 'flyers-brochures'])],
        ['key' => 'flyers_leaflets', 'label' => 'Flyers & Leaflets', 'url' => route('products.index', ['category' => 'flyers-brochures'])],
        ['key' => 'posters', 'label' => 'Posters', 'url' => route('products.index', ['category' => 'posters'])],
        ['key' => 'trade_show', 'label' => 'Trade show & Advertising', 'url' => route('products.index', ['category' => 'banners'])],
        ['key' => 'business_cards', 'label' => 'Business cards & Cards', 'url' => route('products.index', ['category' => 'business-cards'])],
        ['key' => 'office_supplies', 'label' => 'Office supplies', 'url' => route('products.index', ['category' => 'packaging'])],
        ['key' => 'stickers', 'label' => 'Stickers', 'url' => route('products.index', ['category' => 'apparel'])],
        ['key' => 'promotional_items', 'label' => 'Promotional items', 'url' => route('products.index')],
        ['key' => 'services', 'label' => 'Services', 'url' => route('pages.help')],
    ];

    $configuredAllProductsMegaMenu = json_decode($siteSettings->get('all_products_mega_menu', ''), true);
    $allProductsMegaMenu = \App\Support\AllProductsMegaMenu::normalize($configuredAllProductsMegaMenu);

    if (! $siteSettings->has('all_products_mega_menu')) {
        $allProductsMegaMenu = \App\Support\AllProductsMegaMenu::defaults();
    }

    $themeMegaMenus = [
        'brochures' => [
            ['title' => 'Brochures', 'subtitle' => 'Saddle-stitched', 'image' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Brochures/Catalogues', 'subtitle' => 'Adhesive-bound', 'image' => 'https://images.unsplash.com/photo-1476357471311-43c0db9fb2b4?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Digitally printed', 'subtitle' => 'Brochures', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Eco paper', 'subtitle' => 'Brochures', 'image' => 'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Books', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=80'],
        ],
        'flyers_leaflets' => [
            ['title' => 'Flyers', 'subtitle' => 'A6/A5/A4 options', 'image' => 'https://images.unsplash.com/photo-1590128222329-5fbce59afc1f?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Leaflets', 'subtitle' => 'Folded formats', 'image' => 'https://images.unsplash.com/photo-1586952518485-11b180e92764?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Event Flyers', 'subtitle' => 'Fast turnaround', 'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Promo Leaflets', 'subtitle' => 'Bulk discounts', 'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Folded Menus', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=80'],
        ],
        'posters' => [
            ['title' => 'Indoor Posters', 'subtitle' => 'Matte / Gloss', 'image' => 'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Outdoor Posters', 'subtitle' => 'Weather resistant', 'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Photo Posters', 'subtitle' => 'Gallery quality', 'image' => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Large Format', 'subtitle' => 'A2 / A1 / A0', 'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Poster Sets', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=900&q=80'],
        ],
        'trade_show' => [
            ['title' => 'Roll-up Banners', 'subtitle' => 'Portable displays', 'image' => 'https://images.unsplash.com/photo-1564061170517-d3907caa96ea?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'PVC Banners', 'subtitle' => 'Indoor / Outdoor', 'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Backdrop Stands', 'subtitle' => 'Event ready', 'image' => 'https://images.unsplash.com/photo-1478144592103-25e218a04891?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Display Kits', 'subtitle' => 'Complete packages', 'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Expo Counters', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=80'],
        ],
        'business_cards' => [
            ['title' => 'Premium Cards', 'subtitle' => '350-400gsm', 'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Laminated Cards', 'subtitle' => 'Matte / Gloss', 'image' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Spot UV Cards', 'subtitle' => 'Luxury finish', 'image' => 'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Rounded Cards', 'subtitle' => 'Modern shape', 'image' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Square Cards', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=80'],
        ],
        'office_supplies' => [
            ['title' => 'Letterheads', 'subtitle' => 'Professional docs', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Notepads', 'subtitle' => 'Branded pads', 'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Branded Folders', 'subtitle' => 'Presentation kits', 'image' => 'https://images.unsplash.com/photo-1470790376778-a9fbc86d70e2?auto=format&fit=crop&w=900&q=80', 'url' => route('pages.branded-folders')],
            ['title' => 'Custom Branded Receipts, Invoices & Delivery Books', 'subtitle' => 'NCR business forms', 'image' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=900&q=80', 'url' => route('pages.custom-receipts')],
            ['title' => 'Certificates', 'subtitle' => 'Award and completion prints', 'image' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=900&q=80', 'url' => route('pages.certificates')],
            ['title' => 'Envelopes', 'subtitle' => 'DL, C5, C4, B4 and C3 options', 'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=900&q=80', 'url' => route('pages.envelopes')],
            ['title' => 'Complementary Slips', 'subtitle' => 'Business stationery special', 'image' => 'https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=900&q=80', 'url' => route('pages.token-business')],
        ],
        'stickers' => [
            ['title' => 'Die-cut Stickers', 'subtitle' => 'Custom shapes', 'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Round Stickers', 'subtitle' => 'Packaging labels', 'image' => 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Vinyl Stickers', 'subtitle' => 'Waterproof', 'image' => 'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Window Decals', 'subtitle' => 'Storefront use', 'image' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Sheet Stickers', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80'],
        ],
        'promotional_items' => [
            ['title' => 'Mugs', 'subtitle' => 'Custom print', 'image' => 'https://images.unsplash.com/photo-1516408299441-2c7772b7f9d4?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'T-Shirts', 'subtitle' => 'Team branding', 'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Pens', 'subtitle' => 'Promo giveaways', 'image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc36b35?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Gift Sets', 'subtitle' => 'Corporate packs', 'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Desk Items', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?auto=format&fit=crop&w=900&q=80'],
        ],
        'services' => [
            ['title' => 'Design Service', 'subtitle' => 'Artwork support', 'image' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Paper Samples', 'subtitle' => 'Touch & feel kits', 'image' => 'https://images.unsplash.com/photo-1586952518485-11b180e92764?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Print Consultation', 'subtitle' => 'Expert guidance', 'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'QuickCheck', 'subtitle' => 'Artwork validation', 'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80'],
            ['title' => 'Account Management', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=900&q=80'],
        ],
    ];

    $categoryNav = collect($navCategories ?? [])->filter(fn ($category) => ! empty($category->slug))->values();

    if ($categoryNav->isNotEmpty()) {
        $categoryWhatWePrint = $categoryNav->map(fn ($category) => [
            'label' => $category->name,
            'url' => route('products.index', ['category' => $category->slug]),
        ])->values();

        $businessIndex = $categoryWhatWePrint->search(function (array $item) {
            return str_contains(strtolower($item['label']), 'business');
        });

        if ($businessIndex !== false) {
            $businessEntry = $categoryWhatWePrint->get($businessIndex);
            $businessEntry['label'] = 'Business Stationary';
            $businessEntry['children'] = $businessStationerySubmenu;
            $categoryWhatWePrint->put($businessIndex, $businessEntry);
        } else {
            $categoryWhatWePrint->prepend([
                'label' => 'Business Stationary',
                'url' => route('products.index', ['category' => 'business-cards']),
                'children' => $businessStationerySubmenu,
            ]);
        }

        $categoryWhatWePrint->splice(1, 0, [[
            'label' => 'Design Services',
            'url' => route('pages.design-services'),
            'children' => $designServicesSubmenu,
        ]]);

        $whatWePrintItems = collect($whatWePrintItems)
            ->concat($categoryWhatWePrint)
            ->unique('label')
            ->take(10)
            ->values()
            ->all();

        $themeNavItems = collect($themeNavItems)
            ->concat(
                $categoryNav
                    ->take(8)
                    ->values()
                    ->map(fn ($category) => [
                        'key' => 'category_' . $category->id,
                        'label' => $category->name,
                        'url' => route('products.index', ['category' => $category->slug]),
                    ])
            )
            ->unique('label')
            ->values()
            ->all();

        $fallbackImages = [
            'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1590128222329-5fbce59afc1f?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1564061170517-d3907caa96ea?auto=format&fit=crop&w=900&q=80',
        ];

        $dynamicThemeMegaMenus = [];
        foreach ($themeNavItems as $index => $item) {
            if (! str_starts_with($item['key'], 'category_')) {
                continue;
            }

            $entries = [[
                'title' => $item['label'],
                'subtitle' => 'Browse category',
                'image' => $fallbackImages[$index % count($fallbackImages)],
            ]];

            if (str_contains(strtolower($item['label']), 'business')) {
                $entries[] = [
                    'title' => 'Branded Folders',
                    'subtitle' => 'Presentation kits',
                    'image' => 'https://images.unsplash.com/photo-1470790376778-a9fbc86d70e2?auto=format&fit=crop&w=900&q=80',
                    'url' => route('pages.branded-folders'),
                ];
                $entries[] = [
                    'title' => 'Custom Branded Receipts, Invoices & Delivery Books',
                    'subtitle' => 'NCR business forms',
                    'image' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=900&q=80',
                    'url' => route('pages.custom-receipts'),
                ];
                $entries[] = [
                    'title' => 'Certificates',
                    'subtitle' => 'Award and completion prints',
                    'image' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=900&q=80',
                    'url' => route('pages.certificates'),
                ];
                $entries[] = [
                    'title' => 'Envelopes',
                    'subtitle' => 'DL, C5, C4, B4 and C3 options',
                    'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=900&q=80',
                    'url' => route('pages.envelopes'),
                ];
                $entries[] = [
                    'title' => 'Complementary Slips',
                    'subtitle' => 'Business stationery special',
                    'image' => 'https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=900&q=80',
                    'url' => route('pages.token-business'),
                ];
            }

            $dynamicThemeMegaMenus[$item['key']] = $entries;
        }

        $themeMegaMenus = array_merge($themeMegaMenus, $dynamicThemeMegaMenus);
    }

    $headerShippingText = $siteSettings->get('header_shipping_text', 'Free standard shipping');
    $headerExperienceText = $siteSettings->get('header_experience_text', '40 years of experience');
    $headerProductionText = $siteSettings->get('header_production_text', 'Our own production');
    $headerLiveChatLabel = $siteSettings->get('header_live_chat_label', 'Live Chat');
    $headerBrandLabel = $siteSettings->get('header_brand_label', 'onlineprinters');
    $headerLogoUrl = \App\Support\PublicAssetUrl::resolve($siteSettings->get('header_logo_url'));
    $whatsappLink = $siteSettings->get('whatsapp_link', config('shop.whatsapp_link'));
@endphp

<style>
    :root {
        --np-bg: #f4f4f4;
        --np-line: #d9d9d9;
        --np-red: #b10f1e;
        --np-black: #111111;
    }

    [x-cloak] { display: none !important; }

    .np-wrap {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .np-header-top {
        border-bottom: 1px solid #111;
        background:
            linear-gradient(90deg, rgba(0, 0, 0, 0.34), rgba(0, 0, 0, 0.18)),
            linear-gradient(90deg, #00aeef 0%, #ec008c 36%, #fff200 68%, #231f20 100%);
        color: #fff;
    }

    .np-header-top a,
    .np-header-top span {
        color: #fff;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.55);
    }

    .np-header-top-inner {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 3px;
        padding-bottom: 3px;
        font-size: 12px;
        line-height: 1.1;
    }

    .np-header-perks {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 24px;
    }

    .np-top-item {
        display: inline-flex;
        min-width: 0;
        align-items: center;
        gap: 8px;
        line-height: 1.1;
    }

    .np-top-item svg {
        flex: 0 0 auto;
        height: 14px;
        width: 14px;
    }

    .np-header-main {
        display: grid;
        grid-template-columns: 300px 230px minmax(320px, 1fr) auto;
        align-items: center;
        gap: 18px;
        padding: 8px 0;
    }

    .np-brand {
        display: flex;
        min-width: 260px;
        align-items: center;
        gap: 8px;
        color: var(--np-red);
        text-decoration: none;
    }

    .np-brand-badge {
        display: inline-flex;
        height: 44px;
        width: 44px;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        border: 2px solid var(--np-red);
        font-size: 24px;
        font-weight: 900;
    }

    .np-brand-logo {
        display: block;
        max-width: 270px;
        height: 50px;
        object-fit: contain;
    }

    .np-brand-name {
        font-size: 24px;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -0.01em;
    }

    .np-what-wrap {
        position: relative;
        min-width: 180px;
    }

    .np-what-trigger {
        width: 100%;
        height: 38px;
        border: 1px solid rgba(255, 255, 255, 0.75);
        border-radius: 8px;
        background: #f2ecdd;
        color: var(--np-red);
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 0 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
    }

    .np-what-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 320px;
        background: #f4f4f6;
        border: 1px solid #dfdfdf;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.16);
        z-index: 80;
    }

    .np-what-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
        padding: 18px 14px;
        border-bottom: 1px solid #eadcf2;
        color: var(--np-black);
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        line-height: 1.3;
    }

    .np-what-item:hover {
        color: var(--np-red);
        background: #efeff2;
    }

    .np-what-group {
        position: relative;
        border-bottom: 1px solid #eadcf2;
    }

    .np-what-group .np-what-item {
        border-bottom: 0;
    }

    .np-what-submenu {
        position: absolute;
        top: -1px;
        left: calc(100% - 1px);
        width: 320px;
        background: #f4f4f6;
        border: 1px solid #dfdfdf;
        box-shadow: 0 12px 26px rgba(0, 0, 0, 0.16);
        z-index: 90;
    }

    .np-what-subitem {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
        padding: 18px 14px;
        border-bottom: 1px solid #eadcf2;
        color: var(--np-black);
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        line-height: 1.3;
    }

    .np-what-subitem:hover {
        color: var(--np-red);
        background: #efeff2;
    }

    .np-header-actions {
        display: flex;
        align-items: center;
        gap: 18px;
        color: var(--np-red);
        font-size: 12px;
    }

    .np-icon-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        line-height: 1;
        color: var(--np-red);
        text-decoration: none;
    }

    .np-icon-action svg {
        margin-bottom: 4px;
        height: 28px;
        width: 28px;
    }

    .np-quick-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        padding: 0 14px;
        border-radius: 6px;
        border: 1px solid var(--np-red);
        color: var(--np-red);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .np-quick-btn:hover {
        background: var(--np-red);
        color: #fff;
    }

    .np-header-nav {
        border-bottom: 1px solid var(--np-line);
        background: #f7f8fa;
        position: relative;
        z-index: 40;
    }

    .np-nav-row {
        display: flex;
        align-items: center;
        gap: 20px;
        min-height: 44px;
        white-space: nowrap;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .np-nav-row::-webkit-scrollbar {
        display: none;
    }

    .np-nav-link {
        color: #111;
        text-decoration: none;
        font-size: 15px;
    }

    .np-nav-first {
        border-right: 1px solid #cfcfcf;
        padding-right: 24px;
    }

    .np-nav-first-trigger {
        border-right: 1px solid #cfcfcf;
        padding-right: 24px;
    }

    .np-nav-trigger {
        border: 0;
        background: transparent;
        padding: 0;
        margin: 0;
        color: #111;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
    }

    .np-nav-trigger.active {
        font-weight: 700;
    }

    .np-nav-trigger:hover {
        font-weight: 700;
    }

    .np-stroke-chevron {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.9;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .np-overlay {
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        background: var(--np-bg);
        border-top: 1px solid #d8d8d8;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        padding: 14px 0 18px;
    }

    .np-all-products-panel {
        padding: 28px 0 30px;
        background: #fff;
    }

    .np-all-products-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 44px;
    }

    .np-all-products-heading {
        margin-bottom: 20px;
        color: #111;
        font-family: inherit;
        font-size: 16px;
        line-height: 1.25;
        font-weight: 800;
    }

    .np-all-products-list {
        display: grid;
        gap: 16px;
    }

    .np-all-products-link {
        color: #4d4d4d;
        font-family: inherit;
        text-decoration: none;
        font-size: 16px;
        line-height: 1.35;
        white-space: normal;
    }

    .np-all-products-link:hover {
        color: #ff0038;
    }

    .np-all-products-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 18px;
        color: #111;
        font-family: inherit;
        text-decoration: none;
        font-size: 16px;
        font-weight: 800;
    }

    .np-all-products-view:hover {
        color: var(--np-red);
    }

    .np-overlay-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 14px;
    }

    .np-overlay-card {
        display: block;
        color: #111;
        text-decoration: none;
    }

    .np-overlay-media {
        background: #d6d0c3;
        border-radius: 6px;
        overflow: hidden;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .np-overlay-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .np-overlay-title {
        margin-top: 10px;
        font-size: 18px;
        line-height: 1.2;
        font-weight: 700;
    }

    .np-overlay-subtitle {
        margin-top: 4px;
        font-size: 15px;
        color: #1f1f1f;
    }

    .np-overlay-showall {
        border-radius: 6px;
        background: #d6d0c3;
        color: var(--np-red);
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        line-height: 1.2;
        font-weight: 800;
        text-align: center;
        padding: 0 8px;
    }

    @media (max-width: 1200px) {
        .np-header-main {
            grid-template-columns: 260px 200px minmax(280px, 1fr) auto;
            gap: 12px;
        }
    }

    @media (max-width: 1024px) {
        .np-header-main {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .np-what-wrap {
            min-width: 0;
        }

        .np-what-dropdown {
            position: static;
            width: 100%;
            margin-top: 8px;
        }

        .np-what-submenu {
            position: static;
            width: 100%;
            box-shadow: none;
            border: 0;
            border-top: 1px solid #dfdfdf;
        }

        .np-header-actions {
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 12px;
        }

        .np-overlay-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .np-all-products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 28px;
        }
    }

    @media (max-width: 640px) {
        .np-header-top {
            display: none;
        }

        .np-overlay-grid {
            grid-template-columns: 1fr;
        }

        .np-all-products-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }
</style>

<div class="np-header-top">
    <div class="np-wrap np-header-top-inner">
        <div class="np-header-perks">
            <span class="np-top-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h14l2 4v6H5a2 2 0 0 1-2-2V7Zm16 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM7 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/></svg>
                {{ $headerShippingText }}
            </span>
            <span class="np-top-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 3 7l9 4 9-4-9-4Zm0 8v10m-4-2h8"/></svg>
                {{ $headerExperienceText }}
            </span>
            <span class="np-top-item">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 11h18M5 11V7l7-4 7 4v4M7 11v7m10-7v7M4 18h16"/></svg>
                {{ $headerProductionText }}
            </span>
        </div>
        <a href="{{ $whatsappLink }}" class="np-top-item font-semibold text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/></svg>
            {{ $headerLiveChatLabel }}
        </a>
    </div>
</div>

<header style="background:#fff;color:var(--np-red);border-bottom:1px solid var(--np-line);">
    <div class="np-wrap np-header-main">
        <a href="{{ route('home') }}" class="np-brand">
            @if($headerLogoUrl)
                <img src="{{ $headerLogoUrl }}" alt="{{ $headerBrandLabel }}" class="np-brand-logo">
            @else
                <span class="np-brand-badge">P</span>
                <span class="np-brand-name">{{ $headerBrandLabel }}</span>
            @endif
        </a>

        <div
            class="np-what-wrap"
            x-data="{ openWhatPrint: false, openSubMenu: null }"
            @mouseenter="openWhatPrint = true"
            @mouseleave="openWhatPrint = false; openSubMenu = null"
            @keydown.escape.window="openWhatPrint = false; openSubMenu = null"
        >
            <button
                type="button"
                class="np-what-trigger"
                @click="openWhatPrint = !openWhatPrint; openSubMenu = openWhatPrint ? 0 : null"
                :aria-expanded="openWhatPrint.toString()"
            >
                <span>What We Print</span>
                <svg viewBox="0 0 24 24" class="np-stroke-chevron" :style="openWhatPrint ? 'transform: rotate(180deg);' : ''" style="transition: transform .18s ease;">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </button>
            <div class="np-what-dropdown" x-show="openWhatPrint" x-cloak x-transition.opacity.duration.150ms @click.outside="openWhatPrint = false; openSubMenu = null">
                @foreach($whatWePrintItems as $index => $entry)
                    @if(! empty($entry['children']))
                        <div class="np-what-group" @mouseenter="openSubMenu = {{ $index }}">
                            <button
                                type="button"
                                class="np-what-item"
                                :style="openSubMenu === {{ $index }} ? 'color: var(--np-red); background: #efeff2;' : ''"
                                @click="openSubMenu = openSubMenu === {{ $index }} ? null : {{ $index }}"
                            >
                                <span>{{ $entry['label'] }}</span>
                                <svg viewBox="0 0 24 24" class="np-stroke-chevron" :style="openSubMenu === {{ $index }} ? 'transform: rotate(180deg);' : ''" style="transition: transform .18s ease;">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>
                            <div class="np-what-submenu" x-show="openSubMenu === {{ $index }}" x-cloak x-transition.opacity.duration.120ms @mouseenter="openSubMenu = {{ $index }}">
                                @foreach($entry['children'] as $child)
                                    <a href="{{ $child['url'] }}" class="np-what-subitem">
                                        <span>{{ $child['label'] }}</span>
                                        <svg viewBox="0 0 24 24" class="np-stroke-chevron">
                                            <path d="m9 6 6 6-6 6"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $entry['url'] }}" class="np-what-item">
                            <span>{{ $entry['label'] }}</span>
                            <svg viewBox="0 0 24 24" class="np-stroke-chevron">
                                <path d="m9 6 6 6-6 6"/>
                            </svg>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        <form action="{{ route('products.index') }}" method="GET" class="w-full">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." class="h-12 w-full rounded-md border-0 bg-white px-4 pr-12 text-[16px] text-[#222]">
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#5e5e5e]" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6 16.65a7.5 7.5 0 0 0 10.65 0Z" /></svg>
                </button>
            </div>
        </form>

        <div class="np-header-actions">
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="np-icon-action">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mb-1 h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5.25A2.25 2.25 0 0 1 6.25 3h11.5A2.25 2.25 0 0 1 20 5.25v13.5A2.25 2.25 0 0 1 17.75 21H6.25A2.25 2.25 0 0 1 4 18.75V5.25ZM8 8h8M8 12h8M8 16h6"/></svg>
                    {{ auth()->user()->isAdmin() ? 'Admin Panel' : 'Dashboard' }}
                </a>
            @else
                <a href="{{ route('login') }}" class="np-icon-action">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mb-1 h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg>
                    Login
                </a>
            @endauth
            <a href="{{ route('products.index') }}" class="np-quick-btn">Quick Quote</a>
            <a href="{{ route('cart.index') }}" class="np-icon-action">
                <svg xmlns="http://www.w3.org/2000/svg" class="mb-1 h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 3h1.386a1.5 1.5 0 0 1 1.45 1.12L5.74 7.5m0 0h13.01l-1.5 6H7.24m-1.5-6 1.5 6m0 0-1.12 2.24A1.5 1.5 0 0 0 7.5 18h9m-9 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm9 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z"/></svg>
                Shopping Cart
            </a>
        </div>
    </div>
</header>

<nav class="np-header-nav" x-data="{ openMenu: null }" @mouseleave="openMenu = null">
    <div class="np-wrap py-1">
        <div class="np-nav-row">
            <button
                type="button"
                class="np-nav-trigger np-nav-first-trigger"
                :class="{ 'active': openMenu === 'all_products' }"
                @mouseenter="openMenu = 'all_products'"
                @click="openMenu = openMenu === 'all_products' ? null : 'all_products'"
            >
                All products
                <svg
                    viewBox="0 0 24 24"
                    class="np-stroke-chevron"
                    :style="openMenu === 'all_products' ? 'transform: rotate(180deg);' : ''"
                    style="transition: transform .18s ease;"
                >
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </button>
            @foreach($themeNavItems as $item)
                <button
                    type="button"
                    class="np-nav-trigger"
                    :class="{ 'active': openMenu === '{{ $item['key'] }}' }"
                    @mouseenter="openMenu = '{{ $item['key'] }}'"
                    @click="openMenu = openMenu === '{{ $item['key'] }}' ? null : '{{ $item['key'] }}'"
                >
                    {{ $item['label'] }}
                    <svg
                        viewBox="0 0 24 24"
                        class="np-stroke-chevron"
                        :style="openMenu === '{{ $item['key'] }}' ? 'transform: rotate(180deg);' : ''"
                        style="transition: transform .18s ease;"
                    >
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>
            @endforeach
        </div>
    </div>

    <div
        class="np-overlay np-all-products-panel"
        x-show="openMenu === 'all_products'"
        x-cloak
        x-transition.opacity.duration.150ms
        @mouseenter="openMenu = 'all_products'"
    >
        <div class="np-wrap">
            <div class="np-all-products-grid">
                @foreach($allProductsMegaMenu as $column)
                    <div>
                        <h3 class="np-all-products-heading">{{ $column['title'] }}</h3>
                        <div class="np-all-products-list">
                            @foreach($column['items'] as $item)
                                <a href="{{ $item['url'] }}" class="np-all-products-link">{{ $item['label'] }}</a>
                            @endforeach
                        </div>
                        @if(! empty($column['view_all_url']))
                            <a href="{{ $column['view_all_url'] }}" class="np-all-products-view">View all <span aria-hidden="true">-&gt;</span></a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @foreach($themeNavItems as $menu)
        <div
            class="np-overlay"
            x-show="openMenu === '{{ $menu['key'] }}'"
            x-cloak
            x-transition.opacity.duration.150ms
            @mouseenter="openMenu = '{{ $menu['key'] }}'"
        >
            <div class="np-wrap">
                <div class="np-overlay-grid">
                    @foreach($themeMegaMenus[$menu['key']] as $entry)
                        <a href="{{ data_get($entry, 'url', $menu['url']) }}" class="np-overlay-card">
                            <div class="np-overlay-media">
                                <img src="{{ $entry['image'] }}" alt="{{ $entry['title'] }}">
                            </div>
                            <div class="np-overlay-title">{{ $entry['title'] }}</div>
                            @if($entry['subtitle'])
                                <div class="np-overlay-subtitle">{{ $entry['subtitle'] }}</div>
                            @endif
                        </a>
                    @endforeach

                    <a href="{{ $menu['url'] }}" class="np-overlay-card">
                        <div class="np-overlay-showall">
                            SHOW ALL {{ strtoupper($menu['label']) }}
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</nav>
