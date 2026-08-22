<?php

return [
    'legal_name' => env('COMPANY_LEGAL_NAME', 'ARS Developer Ltd'),
    'brand_name' => env('COMPANY_BRAND_NAME', 'ARSDeveloper'),
    'website' => env('COMPANY_WEBSITE', env('APP_URL', 'https://arsdeveloper.co.uk')),
    'email' => env('COMPANY_EMAIL', 'info@arsdeveloper.co.uk'),
    'phone' => env('COMPANY_PHONE', '+447478034328'),
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
    'opening_hours' => env('COMPANY_OPENING_HOURS', 'Mo-Fr 09:00-18:00'),

    // Bank transfer details shown on invoices, keyed by currency.
    //
    // Wise issues separate local account details per currency on the same account,
    // and they are not interchangeable: a UK client paying a GBP invoice into the
    // CAD account pays conversion on the way in and cannot use a domestic transfer.
    // So the invoice looks up the block matching its own currency and shows nothing
    // if that currency has no details — better an absent block than instructions
    // that quietly cost the client money.
    //
    // Values live in .env, never in the repo. 'fields' is an ordered label => value
    // map so each currency can carry the identifiers its own banking system uses
    // (sort code in the UK, institution and transit numbers in Canada).
    'bank' => [
        'GBP' => [
            'account_name' => env('BANK_GBP_ACCOUNT_NAME', ''),
            'bank'         => env('BANK_GBP_BANK', ''),
            'fields'       => array_filter([
                'Sort code'      => env('BANK_GBP_SORT_CODE', ''),
                'Account number' => env('BANK_GBP_ACCOUNT_NUMBER', ''),
                'IBAN'           => env('BANK_GBP_IBAN', ''),
                'SWIFT / BIC'    => env('BANK_GBP_SWIFT', ''),
            ]),
        ],
        'CAD' => [
            'account_name' => env('BANK_CAD_ACCOUNT_NAME', ''),
            'bank'         => env('BANK_CAD_BANK', ''),
            'fields'       => array_filter([
                'Account number'      => env('BANK_CAD_ACCOUNT_NUMBER', ''),
                'Institution number'  => env('BANK_CAD_INSTITUTION', ''),
                'Transit number'      => env('BANK_CAD_TRANSIT', ''),
                'SWIFT / BIC'         => env('BANK_CAD_SWIFT', ''),
            ]),
        ],
        'EUR' => [
            'account_name' => env('BANK_EUR_ACCOUNT_NAME', ''),
            'bank'         => env('BANK_EUR_BANK', ''),
            'fields'       => array_filter([
                'IBAN'        => env('BANK_EUR_IBAN', ''),
                'SWIFT / BIC' => env('BANK_EUR_SWIFT', ''),
            ]),
        ],
    ],

    // Interac e-Transfer — Canadian clients only, so it is shown beside the CAD
    // details rather than on every invoice.
    'interac_email' => env('BANK_INTERAC_EMAIL', ''),
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
