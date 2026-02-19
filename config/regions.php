<?php

return [
    'default' => 'uk',

    'regions' => [
        'uk' => [
            'label' => 'United Kingdom',
            'country' => 'UK',
            'domain' => env('APP_UK_DOMAIN', 'arsdeveloper.co.uk'),
            'base_url' => env('APP_UK_URL', 'https://arsdeveloper.co.uk'),
            'currency' => 'GBP',
            'symbol' => 'GBP ',
            'hreflang' => 'en-GB',
            'og_locale' => 'en_GB',
            'rate_from_usd' => 0.79,
            'default_locale' => 'en_uk',
            'locales' => ['en_uk'],
        ],
    ],

    'locale_labels' => [
        'en_uk' => 'British English',
    ],
];

