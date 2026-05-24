<?php

return [
    'currency' => 'KES',
    'currency_symbol' => 'KSh',
    'shipping_options' => [
        'standard' => [
            'label' => 'Standard (3-5 days)',
            'price' => 400,
            'days' => 3,
        ],
        'express' => [
            'label' => 'Express (1-2 days)',
            'price' => 1200,
            'days' => 1,
        ],
        'pickup' => [
            'label' => 'Rush',
            'price' => 1500,
            'days' => 1,
        ],
    ],
    'allowed_artwork_mimes' => [
        'application/pdf',
        'image/png',
        'image/jpeg',
        'image/webp',
        'application/postscript',
        'application/illustrator',
    ],
    'max_artwork_size_kb' => 20480,
    'min_dpi' => 150,
    'whatsapp_link' => 'https://wa.me/254700000000?text=Hello%20NaiPrinters%2C%20I%20need%20a%20quote.',
];
