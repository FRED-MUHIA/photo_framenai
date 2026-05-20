<?php

namespace App\Support;

class AllProductsMegaMenu
{
    public static function defaults(): array
    {
        return [
            [
                'title' => 'Banners',
                'items' => [
                    ['label' => 'Vinyl banner printing', 'url' => route('products.index', ['q' => 'vinyl banner printing'])],
                    ['label' => 'X-Banner Stands printing', 'url' => route('products.index', ['q' => 'x banner stands printing'])],
                    ['label' => 'Roll up Banner printing', 'url' => route('products.index', ['q' => 'roll up banner printing'])],
                    ['label' => 'Door Frame Banner printing', 'url' => route('products.index', ['q' => 'door frame banner printing'])],
                    ['label' => 'Adjustable Backdrop Banner Printing', 'url' => route('products.index', ['q' => 'adjustable backdrop banner printing'])],
                ],
                'view_all_url' => route('products.index', ['category' => 'banners']),
            ],
            [
                'title' => 'Booklet Magazines',
                'items' => [
                    ['label' => 'Booklet Magazines printing', 'url' => route('products.index', ['q' => 'booklet magazines printing'])],
                    ['label' => 'Funeral Programs printing', 'url' => route('products.index', ['q' => 'funeral programs printing'])],
                ],
                'view_all_url' => '',
            ],
            [
                'title' => 'Branded Apparel',
                'items' => [
                    ['label' => 'Reflector Jackets printing', 'url' => route('products.index', ['q' => 'reflector jackets printing'])],
                    ['label' => 'Branded Jerseys for clubs', 'url' => route('products.index', ['q' => 'branded jerseys for clubs'])],
                    ['label' => 'Branded Hoodies', 'url' => route('products.index', ['q' => 'branded hoodies'])],
                    ['label' => 'Branded T-shirt', 'url' => route('products.index', ['q' => 'branded t-shirt'])],
                    ['label' => 'Branded Aprons', 'url' => route('products.index', ['q' => 'branded aprons'])],
                ],
                'view_all_url' => '',
            ],
            [
                'title' => 'Business Cards',
                'items' => [
                    ['label' => 'Business Cards Printing', 'url' => route('products.index', ['q' => 'business cards printing'])],
                    ['label' => 'Postcards printing', 'url' => route('products.index', ['q' => 'postcards printing'])],
                    ['label' => 'Bookmarks printing', 'url' => route('products.index', ['q' => 'bookmarks printing'])],
                    ['label' => 'Spot UV Business Cards', 'url' => route('products.index', ['q' => 'spot uv business cards'])],
                    ['label' => 'Gift Voucher Printing', 'url' => route('products.index', ['q' => 'gift voucher printing'])],
                ],
                'view_all_url' => '',
            ],
            [
                'title' => 'Digital printing',
                'items' => [
                    ['label' => 'Business Cards Printing', 'url' => route('products.index', ['q' => 'business cards printing'])],
                    ['label' => 'Document Printing', 'url' => route('products.index', ['q' => 'document printing'])],
                    ['label' => 'Photo Printing Services', 'url' => route('products.index', ['q' => 'photo printing services'])],
                    ['label' => 'Gift Voucher Printing', 'url' => route('products.index', ['q' => 'gift voucher printing'])],
                ],
                'view_all_url' => '',
            ],
        ];
    }

    public static function normalize(mixed $menu): array
    {
        if (! is_array($menu)) {
            return [];
        }

        return collect($menu)
            ->map(function ($column) {
                if (! is_array($column)) {
                    return null;
                }

                $items = collect($column['items'] ?? [])
                    ->map(function ($item) {
                        if (! is_array($item)) {
                            return null;
                        }

                        $label = trim((string) ($item['label'] ?? ''));
                        $url = trim((string) ($item['url'] ?? ''));

                        if ($label === '' || $url === '') {
                            return null;
                        }

                        return [
                            'label' => $label,
                            'url' => $url,
                        ];
                    })
                    ->filter()
                    ->values()
                    ->all();

                $title = trim((string) ($column['title'] ?? ''));

                if ($title === '' || empty($items)) {
                    return null;
                }

                return [
                    'title' => $title,
                    'items' => $items,
                    'view_all_url' => trim((string) ($column['view_all_url'] ?? '')),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
