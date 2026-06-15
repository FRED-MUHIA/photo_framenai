<?php

return [
    'currency' => 'KES',
    'currency_symbol' => 'KSh',
    'shipping_options' => [
        'not_necessary' => [
            'label' => 'Not necessary',
            'price' => 0,
            'days' => 0,
        ],
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
        'image/vnd.adobe.photoshop',
        'image/psd',
        'application/photoshop',
        'application/psd',
        'application/x-photoshop',
        'application/postscript',
        'application/illustrator',
    ],
    'max_artwork_size_kb' => 51200,
    'min_dpi' => 150,
    'mpesa_paybill' => env('MPESA_PAYBILL', 'Pending setup'),
    'notification_email' => env('SHOP_NOTIFICATION_EMAIL', 'info@naiprinters.co.ke'),
    'notification_name' => env('SHOP_NOTIFICATION_NAME', 'NaiPrinters'),
    'whatsapp_link' => 'https://wa.me/254700000000?text=Hello%20NaiPrinters%2C%20I%20need%20a%20quote.',
];
