<?php

use App\Services\MeetingReminderService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('meetings:send-reminders', function (MeetingReminderService $service) {
    $result = $service->sendDueReminders();
    $this->info('Meeting reminders sent: 24h=' . ($result['sent_24h'] ?? 0) . ', 2h=' . ($result['sent_2h'] ?? 0));
})->purpose('Send 24-hour and 2-hour meeting reminder emails');

Schedule::command('meetings:send-reminders')->hourly();
