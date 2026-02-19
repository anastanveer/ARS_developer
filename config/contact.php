<?php

return [
    'inbox_email' => env('CONTACT_INBOX_EMAIL', 'info@arsdeveloper.co.uk'),
    'auto_reply' => (bool) env('CONTACT_AUTO_REPLY', true),
    'meeting_timezone' => env('CONTACT_MEETING_TIMEZONE', 'Europe/London'),
    'meeting_slots' => [
        '09:00 AM - 10:00 AM',
        '10:00 AM - 11:00 AM',
        '11:00 AM - 12:00 PM',
        '01:00 PM - 02:00 PM',
        '02:00 PM - 03:00 PM',
        '03:00 PM - 04:00 PM',
        '04:00 PM - 05:00 PM',
    ],
    'timezone_options' => [
        'Europe/London',
        'Europe/Dublin',
        'Europe/Paris',
        'Europe/Berlin',
        'Europe/Madrid',
        'Europe/Rome',
        'America/New_York',
        'America/Chicago',
        'America/Los_Angeles',
        'America/Toronto',
        'Asia/Dubai',
        'Asia/Karachi',
        'Asia/Kolkata',
        'Asia/Singapore',
        'Australia/Sydney',
    ],
    'booking_store' => storage_path((string) env('CONTACT_BOOKING_STORE', 'app/meeting-bookings.json')),
    'booked_dates' => array_values(array_filter(array_map(
        fn ($date) => trim((string) $date),
        explode(',', (string) env('CONTACT_BOOKED_DATES', ''))
    ))),
];
