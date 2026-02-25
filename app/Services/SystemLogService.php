<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class SystemLogService
{
    public function getLogFiles(): array
    {
        $dir = storage_path('logs');
        if (!File::isDirectory($dir)) {
            return [];
        }

        $files = collect(File::files($dir))
            ->filter(fn ($file) => str_ends_with(strtolower($file->getFilename()), '.log'))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values()
            ->all();

        return $files;
    }

    public function generateDigest(string $date): array
    {
        $summary = [
            'date' => $date,
            'total' => 0,
            'by_level' => [],
            'by_channel' => [],
            'by_type' => [],
            'first_seen' => null,
            'last_seen' => null,
        ];

        foreach ($this->getLogFiles() as $file) {
            $lines = @file($file->getPathname(), FILE_IGNORE_NEW_LINES) ?: [];
            foreach ($lines as $line) {
                $parsed = $this->parseLine($line);
                if (!$parsed || $parsed['date'] !== $date) {
                    continue;
                }

                $summary['total']++;
                $level = $parsed['level'];
                $channel = $parsed['channel'];
                $type = $this->detectType($parsed['message']);

                $summary['by_level'][$level] = ($summary['by_level'][$level] ?? 0) + 1;
                $summary['by_channel'][$channel] = ($summary['by_channel'][$channel] ?? 0) + 1;
                $summary['by_type'][$type] = ($summary['by_type'][$type] ?? 0) + 1;

                $timestamp = $parsed['timestamp'] ?? ($parsed['date'] . ' 00:00:00');
                if (!$summary['first_seen'] || strcmp($timestamp, $summary['first_seen']) < 0) {
                    $summary['first_seen'] = $timestamp;
                }
                if (!$summary['last_seen'] || strcmp($timestamp, $summary['last_seen']) > 0) {
                    $summary['last_seen'] = $timestamp;
                }
            }
        }

        arsort($summary['by_level']);
        arsort($summary['by_channel']);
        arsort($summary['by_type']);

        return $summary;
    }

    public function writeDigest(array $digest): string
    {
        $dir = storage_path('app/log-digests');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $filename = 'daily-' . ($digest['date'] ?? Carbon::today()->toDateString()) . '.json';
        $path = $dir . DIRECTORY_SEPARATOR . $filename;
        File::put($path, json_encode($digest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $path;
    }

    public function readDigest(string $date): ?array
    {
        $path = storage_path('app/log-digests/daily-' . $date . '.json');
        if (!File::exists($path)) {
            return null;
        }

        $json = json_decode((string) File::get($path), true);
        return is_array($json) ? $json : null;
    }

    public function collectEntriesByDate(string $date, int $limit = 250): array
    {
        $entries = [];
        foreach ($this->getLogFiles() as $file) {
            $lines = @file($file->getPathname(), FILE_IGNORE_NEW_LINES) ?: [];
            foreach ($lines as $line) {
                $parsed = $this->parseLine($line);
                if (!$parsed || $parsed['date'] !== $date) {
                    continue;
                }

                $parsed['source_file'] = $file->getFilename();
                $entries[] = $parsed;
            }
        }

        usort($entries, function (array $a, array $b) {
            return strcmp(($b['timestamp'] ?? ''), ($a['timestamp'] ?? ''));
        });

        return array_slice($entries, 0, $limit);
    }

    public function collectAvailableDates(int $days = 14): array
    {
        $dates = [];
        foreach ($this->getLogFiles() as $file) {
            $lines = @file($file->getPathname(), FILE_IGNORE_NEW_LINES) ?: [];
            foreach ($lines as $line) {
                $parsed = $this->parseLine($line);
                if (!$parsed) {
                    continue;
                }
                $dates[$parsed['date']] = true;
            }
        }

        $result = array_keys($dates);
        rsort($result);

        if (count($result) < $days) {
            for ($i = 0; $i < $days; $i++) {
                $fallbackDate = now()->subDays($i)->toDateString();
                if (!in_array($fallbackDate, $result, true)) {
                    $result[] = $fallbackDate;
                }
            }
            rsort($result);
        }

        return array_slice($result, 0, $days);
    }

    public function parseLine(string $line): ?array
    {
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2})\s([^\]]+)\]\s([a-zA-Z0-9_.-]+)\.([A-Z]+):\s?(.*)$/', $line, $m)) {
            return [
                'date' => $m[1],
                'time' => trim($m[2]),
                'timestamp' => $m[1] . ' ' . trim($m[2]),
                'channel' => $m[3],
                'level' => strtoupper($m[4]),
                'message' => trim($m[5]),
            ];
        }

        if (preg_match('/^\[(\d{2})-([A-Za-z]{3})-(\d{4})\s([0-9: ]+UTC)\]\s(PHP\s[a-zA-Z]+):\s?(.*)$/', $line, $m)) {
            try {
                $date = Carbon::createFromFormat('d-M-Y', $m[1] . '-' . $m[2] . '-' . $m[3])->toDateString();
            } catch (\Throwable $e) {
                return null;
            }

            return [
                'date' => $date,
                'time' => trim($m[4]),
                'timestamp' => $date . ' ' . trim($m[4]),
                'channel' => 'php',
                'level' => strtoupper(str_replace('PHP ', '', $m[5])),
                'message' => trim($m[6]),
            ];
        }

        return null;
    }

    private function detectType(string $message): string
    {
        $msg = strtolower($message);

        return match (true) {
            str_contains($msg, 'sqlstate') || str_contains($msg, 'queryexception') => 'database',
            str_contains($msg, 'mail') || str_contains($msg, 'smtp') => 'mail',
            str_contains($msg, 'stripe') || str_contains($msg, 'payment') || str_contains($msg, 'invoice') => 'payment',
            str_contains($msg, 'route') || str_contains($msg, '404') || str_contains($msg, 'not found') => 'routing',
            str_contains($msg, 'css') || str_contains($msg, 'js') || str_contains($msg, 'view') || str_contains($msg, 'blade') => 'ui',
            str_contains($msg, 'queue') || str_contains($msg, 'schedule') || str_contains($msg, 'cron') => 'cron',
            default => 'application',
        };
    }
}

