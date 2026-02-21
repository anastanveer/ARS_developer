<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    /**
     * Submit one or more URLs to IndexNow.
     *
     * @param  array<int, string>  $urls
     */
    public function submitUrls(array $urls, string $reason = ''): bool
    {
        if (!(bool) config('indexnow.enabled', false)) {
            return false;
        }

        if (!(bool) config('indexnow.auto_submit', true)) {
            return false;
        }

        $key = trim((string) config('indexnow.key', ''));
        if ($key === '') {
            Log::warning('IndexNow skipped: key is missing.', ['reason' => $reason]);
            return false;
        }

        $endpoint = trim((string) config('indexnow.endpoint', 'https://api.indexnow.org/indexnow'));
        $host = $this->resolveHost();
        $keyLocation = $this->resolveKeyLocation($host, $key);
        $urlList = $this->normalizeUrls($urls, $host);

        if (count($urlList) === 0) {
            return false;
        }

        $payload = [
            'host' => $host,
            'key' => $key,
            'keyLocation' => $keyLocation,
            'urlList' => $urlList,
        ];

        try {
            $response = Http::timeout((int) config('indexnow.timeout', 8))
                ->acceptJson()
                ->post($endpoint, $payload);

            if ($response->failed()) {
                Log::warning('IndexNow submission failed.', [
                    'reason' => $reason,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url_count' => count($urlList),
                ]);

                return false;
            }

            Log::info('IndexNow submission success.', [
                'reason' => $reason,
                'url_count' => count($urlList),
            ]);

            return true;
        } catch (\Throwable $exception) {
            Log::error('IndexNow submission exception.', [
                'reason' => $reason,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @param  array<int, string>  $urls
     * @return array<int, string>
     */
    private function normalizeUrls(array $urls, string $host): array
    {
        $baseUrl = rtrim((string) config('app.url', ''), '/');
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME) ?: 'https';
        $allowedHost = strtolower($host);

        $normalized = [];
        foreach ($urls as $url) {
            $url = trim((string) $url);
            if ($url === '') {
                continue;
            }

            if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                $url = $baseUrl . '/' . ltrim($url, '/');
            }

            $parts = parse_url($url);
            if (!is_array($parts)) {
                continue;
            }

            $urlHost = strtolower((string) ($parts['host'] ?? ''));
            if ($urlHost !== '' && $urlHost !== $allowedHost) {
                continue;
            }

            $path = (string) ($parts['path'] ?? '/');
            $query = isset($parts['query']) && $parts['query'] !== '' ? ('?' . $parts['query']) : '';
            $normalizedUrl = $scheme . '://' . $allowedHost . $path . $query;
            $normalized[$normalizedUrl] = true;
        }

        return array_keys($normalized);
    }

    private function resolveHost(): string
    {
        $configured = trim((string) config('indexnow.host', ''));
        if ($configured !== '') {
            return strtolower($configured);
        }

        $appUrl = trim((string) config('app.url', ''));
        $host = strtolower((string) parse_url($appUrl, PHP_URL_HOST));

        return $host !== '' ? $host : 'localhost';
    }

    private function resolveKeyLocation(string $host, string $key): string
    {
        $configured = trim((string) config('indexnow.key_location', ''));
        if ($configured !== '') {
            return $configured;
        }

        $appUrl = rtrim((string) config('app.url', ''), '/');
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'https';

        return $scheme . '://' . $host . '/' . $key . '.txt';
    }
}

