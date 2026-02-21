<?php

return [
    'legal_name' => env('COMPANY_LEGAL_NAME', 'ARS Developer Ltd'),
    'brand_name' => env('COMPANY_BRAND_NAME', 'ARSDeveloper'),
    'website' => env('COMPANY_WEBSITE', env('APP_URL', 'https://arsdeveloper.co.uk')),
    'email' => env('COMPANY_EMAIL', 'info@arsdeveloper.co.uk'),
    'phone' => env('COMPANY_PHONE', '+44747803428'),
    'street_address' => env('COMPANY_STREET_ADDRESS', '38 Elm Street'),
    'postal_code' => env('COMPANY_POSTAL_CODE', 'ST6 2HN'),
    'address_locality' => env('COMPANY_ADDRESS_LOCALITY', 'Stoke-on-Trent'),
    'address_country' => env('COMPANY_ADDRESS_COUNTRY', 'GB'),
    'country_name' => env('COMPANY_COUNTRY_NAME', 'United Kingdom'),
    'company_number' => env('COMPANY_NUMBER', '17039150'),
    'company_type' => env('COMPANY_TYPE', 'Private Limited Company'),
    'registered_in' => env('COMPANY_REGISTERED_IN', 'England & Wales'),
    'incorporation_date' => env('COMPANY_INCORPORATION_DATE', '17 February 2026'),
    'registered_office' => env(
        'COMPANY_REGISTERED_OFFICE',
        '38 Elm Street, ST6 2HN, Stoke-on-Trent, United Kingdom'
    ),
    'acts_notice' => env(
        'COMPANY_ACTS_NOTICE',
        'A Private Limited Company incorporated under the Companies Act 2006.'
    ),
    'opening_hours' => env('COMPANY_OPENING_HOURS', 'Mo-Fr 09:00-17:00'),
    'same_as' => array_values(array_filter(array_map(
        static fn ($value) => trim((string) $value),
        explode(',', (string) env(
            'COMPANY_SAME_AS',
            'https://www.facebook.com/arsdeveloperuk,https://www.linkedin.com/company/arsdeveloperuk,https://www.instagram.com/arsdeveloperuk/'
        ))
    ))),
    'founder' => [
        'name' => env('COMPANY_FOUNDER_NAME', 'Anas Tanveer'),
        'job_title' => env('COMPANY_FOUNDER_JOB_TITLE', 'Founder & Technical Lead'),
        'description' => env(
            'COMPANY_FOUNDER_DESCRIPTION',
            'Founder-led UK software delivery focused on web engineering, CRM architecture, and search-driven growth systems.'
        ),
        'same_as' => array_values(array_filter(array_map(
            static fn ($value) => trim((string) $value),
            explode(',', (string) env('COMPANY_FOUNDER_SAME_AS', 'https://github.com/anastanveer'))
        ))),
    ],
    'entity_topics' => array_values(array_filter(array_map(
        static fn ($value) => trim((string) $value),
        explode(',', (string) env(
            'COMPANY_ENTITY_TOPICS',
            'Web Development,Custom CRM Development,WordPress Development,Technical SEO,Digital Marketing,Conversion Rate Optimization'
        ))
    ))),
];
