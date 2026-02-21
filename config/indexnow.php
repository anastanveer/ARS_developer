<?php

return [
    'enabled' => (bool) env('INDEXNOW_ENABLED', false),
    'auto_submit' => (bool) env('INDEXNOW_AUTO_SUBMIT', true),
    'endpoint' => env('INDEXNOW_ENDPOINT', 'https://api.indexnow.org/indexnow'),
    'key' => trim((string) env('INDEXNOW_KEY', '')),
    'host' => env('INDEXNOW_HOST'),
    'key_location' => env('INDEXNOW_KEY_LOCATION'),
    'timeout' => (int) env('INDEXNOW_TIMEOUT', 8),
];

