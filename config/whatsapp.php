<?php

return [
    // Keep the access token in .env only. Temporary tokens will expire; move to a permanent system-user token for production.
    'access_token' => env('WHATSAPP_ACCESS_TOKEN', ''),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', '957772007429514'),
    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID', '913278158245902'),
    'verify_token' => env('WHATSAPP_VERIFY_TOKEN', 'arsdeveloper_whatsapp_verify_2026'),
    'graph_version' => env('WHATSAPP_GRAPH_VERSION', 'v22.0'),
    'default_recipient' => env('WHATSAPP_DEFAULT_RECIPIENT', '971542435418'),
    'admin_recipient' => env('WHATSAPP_ADMIN_RECIPIENT', env('WHATSAPP_DEFAULT_RECIPIENT', '971542435418')),
    'timeout' => (int) env('WHATSAPP_TIMEOUT', 15),
    // Webhook URL must be public HTTPS. Local testing will need ngrok or another tunnel.
    'enabled' => env('WHATSAPP_ENABLED', true),
];
