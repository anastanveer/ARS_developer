<?php

use App\Services\SystemLogService;
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

Artisan::command('logs:daily-digest {--date=}', function (SystemLogService $service) {
    $date = (string) ($this->option('date') ?: now()->subDay()->toDateString());
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $this->error('Invalid date format. Use YYYY-MM-DD.');
        return;
    }

    $digest = $service->generateDigest($date);
    $path = $service->writeDigest($digest);

    $this->info('Daily log digest generated.');
    $this->line('Date: ' . $date);
    $this->line('Total entries: ' . (int) ($digest['total'] ?? 0));
    $this->line('Saved at: ' . $path);
})->purpose('Generate date-wise log digest for admin logs monitor');

Schedule::command('meetings:send-reminders')->hourly();
Schedule::command('logs:daily-digest')->dailyAt('00:10');
