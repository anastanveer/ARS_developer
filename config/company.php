<?php

return [
    'legal_name' => env('COMPANY_LEGAL_NAME', 'ARS Developer Ltd'),
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
];
