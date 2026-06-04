<?php

namespace App\Support;

use Illuminate\Support\Str;

class HeaderThemeMegaMenu
{
    public static function defaults(): array
    {
        return [
            [
                'key' => 'brochures',
                'label' => 'Brochures',
                'url' => route('products.index', ['category' => 'flyers-brochures']),
                'show_all_label' => 'SHOW ALL BROCHURES',
                'items' => [
                    ['title' => 'Brochures', 'subtitle' => 'Saddle-stitched', 'image' => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Brochures/Catalogues', 'subtitle' => 'Adhesive-bound', 'image' => 'https://images.unsplash.com/photo-1476357471311-43c0db9fb2b4?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Digitally printed', 'subtitle' => 'Brochures', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Eco paper', 'subtitle' => 'Brochures', 'image' => 'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Books', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
            [
                'key' => 'flyers_leaflets',
                'label' => 'Flyers & Leaflets',
                'url' => route('products.index', ['category' => 'flyers-brochures']),
                'show_all_label' => 'SHOW ALL FLYERS & LEAFLETS',
                'items' => [
                    ['title' => 'Flyers', 'subtitle' => 'A6/A5/A4 options', 'image' => 'https://images.unsplash.com/photo-1590128222329-5fbce59afc1f?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Leaflets', 'subtitle' => 'Folded formats', 'image' => 'https://images.unsplash.com/photo-1586952518485-11b180e92764?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Event Flyers', 'subtitle' => 'Fast turnaround', 'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Promo Leaflets', 'subtitle' => 'Bulk discounts', 'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Folded Menus', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
            [
                'key' => 'posters',
                'label' => 'Posters',
                'url' => route('products.index', ['category' => 'posters']),
                'show_all_label' => 'SHOW ALL POSTERS',
                'items' => [
                    ['title' => 'Indoor Posters', 'subtitle' => 'Matte / Gloss', 'image' => 'https://images.unsplash.com/photo-1523726491678-bf852e717f6a?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Outdoor Posters', 'subtitle' => 'Weather resistant', 'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Photo Posters', 'subtitle' => 'Gallery quality', 'image' => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Large Format', 'subtitle' => 'A2 / A1 / A0', 'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Poster Sets', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
            [
                'key' => 'trade_show',
                'label' => 'Trade show & Advertising',
                'url' => route('products.index', ['category' => 'banners']),
                'show_all_label' => 'SHOW ALL TRADE SHOW & ADVERTISING',
                'items' => [
                    ['title' => 'Roll-up Banners', 'subtitle' => 'Portable displays', 'image' => 'https://images.unsplash.com/photo-1564061170517-d3907caa96ea?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'PVC Banners', 'subtitle' => 'Indoor / Outdoor', 'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Backdrop Stands', 'subtitle' => 'Event ready', 'image' => 'https://images.unsplash.com/photo-1478144592103-25e218a04891?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Display Kits', 'subtitle' => 'Complete packages', 'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Expo Counters', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
            [
                'key' => 'business_cards',
                'label' => 'Business cards & Cards',
                'url' => route('products.index', ['category' => 'business-cards']),
                'show_all_label' => 'SHOW ALL BUSINESS CARDS & CARDS',
                'items' => [
                    ['title' => 'Premium Cards', 'subtitle' => '350-400gsm', 'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Laminated Cards', 'subtitle' => 'Matte / Gloss', 'image' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Spot UV Cards', 'subtitle' => 'Luxury finish', 'image' => 'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Rounded Cards', 'subtitle' => 'Modern shape', 'image' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Square Cards', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
            [
                'key' => 'office_supplies',
                'label' => 'Office supplies',
                'url' => route('products.index', ['category' => 'packaging']),
                'show_all_label' => 'SHOW ALL OFFICE SUPPLIES',
                'items' => [
                    ['title' => 'Letterheads', 'subtitle' => 'Professional docs', 'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Notepads', 'subtitle' => 'Branded pads', 'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Branded Folders', 'subtitle' => 'Presentation kits', 'image' => 'https://images.unsplash.com/photo-1470790376778-a9fbc86d70e2?auto=format&fit=crop&w=900&q=70', 'url' => route('pages.branded-folders')],
                    ['title' => 'Custom Branded Receipts, Invoices & Delivery Books', 'subtitle' => 'NCR business forms', 'image' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=900&q=70', 'url' => route('pages.custom-receipts')],
                    ['title' => 'Certificates', 'subtitle' => 'Award and completion prints', 'image' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=900&q=70', 'url' => route('pages.certificates')],
                    ['title' => 'Envelopes', 'subtitle' => 'DL, C5, C4, B4 and C3 options', 'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=900&q=70', 'url' => route('pages.envelopes')],
                    ['title' => 'Complementary Slips', 'subtitle' => 'Business stationery special', 'image' => 'https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=900&q=70', 'url' => route('pages.token-business')],
                ],
            ],
            [
                'key' => 'stickers',
                'label' => 'Stickers',
                'url' => route('products.index', ['category' => 'apparel']),
                'show_all_label' => 'SHOW ALL STICKERS',
                'items' => [
                    ['title' => 'Die-cut Stickers', 'subtitle' => 'Custom shapes', 'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Round Stickers', 'subtitle' => 'Packaging labels', 'image' => 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Vinyl Stickers', 'subtitle' => 'Waterproof', 'image' => 'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Window Decals', 'subtitle' => 'Storefront use', 'image' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Sheet Stickers', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
            [
                'key' => 'promotional_items',
                'label' => 'Promotional items',
                'url' => route('products.index'),
                'show_all_label' => 'SHOW ALL PROMOTIONAL ITEMS',
                'items' => [
                    ['title' => 'Mugs', 'subtitle' => 'Custom print', 'image' => 'https://images.unsplash.com/photo-1516408299441-2c7772b7f9d4?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'T-Shirts', 'subtitle' => 'Team branding', 'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Pens', 'subtitle' => 'Promo giveaways', 'image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc36b35?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Gift Sets', 'subtitle' => 'Corporate packs', 'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Desk Items', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
            [
                'key' => 'services',
                'label' => 'Services',
                'url' => route('pages.help'),
                'show_all_label' => 'SHOW ALL SERVICES',
                'items' => [
                    ['title' => 'Design Service', 'subtitle' => 'Artwork support', 'image' => 'https://images.unsplash.com/photo-1529333166437-7750a6dd5a70?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Paper Samples', 'subtitle' => 'Touch & feel kits', 'image' => 'https://images.unsplash.com/photo-1586952518485-11b180e92764?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Print Consultation', 'subtitle' => 'Expert guidance', 'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'QuickCheck', 'subtitle' => 'Artwork validation', 'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                    ['title' => 'Account Management', 'subtitle' => '', 'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=900&q=70', 'url' => ''],
                ],
            ],
        ];
    }

    public static function normalize(mixed $menus): array
    {
        if (! is_array($menus)) {
            return [];
        }

        $seenKeys = [];

        return collect($menus)
            ->map(function ($menu, int $index) use (&$seenKeys) {
                if (! is_array($menu)) {
                    return null;
                }

                $label = trim((string) ($menu['label'] ?? ''));
                $url = trim((string) ($menu['url'] ?? ''));

                if ($label === '' || $url === '') {
                    return null;
                }

                $key = trim((string) ($menu['key'] ?? ''));
                $key = $key !== '' ? $key : Str::slug($label, '_');
                $key = preg_replace('/[^A-Za-z0-9_-]/', '_', $key) ?: 'menu_' . ($index + 1);
                $baseKey = $key;
                $suffix = 2;

                while (in_array($key, $seenKeys, true)) {
                    $key = $baseKey . '_' . $suffix;
                    $suffix++;
                }

                $seenKeys[] = $key;

                $items = collect($menu['items'] ?? [])
                    ->map(function ($item) {
                        if (! is_array($item)) {
                            return null;
                        }

                        $title = trim((string) ($item['title'] ?? ''));

                        if ($title === '') {
                            return null;
                        }

                        return [
                            'title' => $title,
                            'subtitle' => trim((string) ($item['subtitle'] ?? '')),
                            'image' => trim((string) ($item['image'] ?? '')),
                            'url' => trim((string) ($item['url'] ?? '')),
                        ];
                    })
                    ->filter()
                    ->values()
                    ->all();

                $showAllLabel = trim((string) ($menu['show_all_label'] ?? ''));

                return [
                    'key' => $key,
                    'label' => $label,
                    'url' => $url,
                    'show_all_label' => $showAllLabel !== '' ? $showAllLabel : 'SHOW ALL ' . Str::upper($label),
                    'items' => $items,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
