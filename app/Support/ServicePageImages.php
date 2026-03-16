<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class ServicePageImages
{
    public const STORAGE_PATH = 'app/service-page-images.json';

    public static function defaults(): array
    {
        return [
            'software-development' => [
                'label' => 'Software Development',
                'image' => 'assets/images/services/service-software-development.svg',
                'alt' => 'Software development service overview',
            ],
            'web-design-development' => [
                'label' => 'Web Design & Development',
                'image' => 'assets/images/services/service-web-development.svg',
                'alt' => 'Web design and development service overview',
            ],
            'search-engine-optimization' => [
                'label' => 'Search Engine Optimization',
                'image' => 'assets/images/services/service-seo.svg',
                'alt' => 'SEO service overview',
            ],
            'design-and-branding' => [
                'label' => 'Design & Branding',
                'image' => 'assets/images/services/service-branding.svg',
                'alt' => 'Design and branding service overview',
            ],
            'app-development' => [
                'label' => 'App Development',
                'image' => 'assets/images/services/service-app-development.svg',
                'alt' => 'App development service overview',
            ],
            'digital-marketing' => [
                'label' => 'Digital Marketing',
                'image' => 'assets/images/services/service-digital-marketing.svg',
                'alt' => 'Digital marketing service overview',
            ],
        ];
    }

    public static function all(): array
    {
        $defaults = static::defaults();
        $path = storage_path(static::STORAGE_PATH);

        if (!File::exists($path)) {
            return $defaults;
        }

        $decoded = json_decode((string) File::get($path), true);
        if (!is_array($decoded)) {
            return $defaults;
        }

        foreach ($defaults as $slug => $default) {
            $stored = is_array($decoded[$slug] ?? null) ? $decoded[$slug] : [];
            $defaults[$slug] = [
                'label' => (string) ($stored['label'] ?? $default['label']),
                'image' => (string) ($stored['image'] ?? $default['image']),
                'alt' => (string) ($stored['alt'] ?? $default['alt']),
            ];
        }

        return $defaults;
    }

    public static function get(string $slug): array
    {
        $all = static::all();

        return $all[$slug] ?? [
            'label' => $slug,
            'image' => 'assets/images/services/service-software-development.svg',
            'alt' => 'Service overview',
        ];
    }

    public static function save(array $images): void
    {
        $payload = [];

        foreach (static::defaults() as $slug => $default) {
            $item = is_array($images[$slug] ?? null) ? $images[$slug] : [];
            $payload[$slug] = [
                'label' => $default['label'],
                'image' => trim((string) ($item['image'] ?? $default['image'])),
                'alt' => trim((string) ($item['alt'] ?? $default['alt'])),
            ];
        }

        File::ensureDirectoryExists(dirname(storage_path(static::STORAGE_PATH)));
        File::put(
            storage_path(static::STORAGE_PATH),
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
        );
    }

    public static function toUrl(string $path): string
    {
        return preg_match('/^https?:\/\//i', $path) ? $path : asset(ltrim($path, '/'));
    }
}
