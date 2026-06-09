<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowSubmit extends Command
{
    protected $signature = 'indexnow:submit
                            {--dry-run : Print URLs without submitting}
                            {--batch=100 : How many URLs per API request (max 10000)}';

    protected $description = 'Submit all UK service page URLs to IndexNow for instant search engine discovery';

    public function handle(): int
    {
        $key = (string) (config('indexnow.key') ?: env('INDEXNOW_KEY', ''));
        if ($key === '' || $key === 'replace_with_indexnow_key') {
            $this->error('INDEXNOW_KEY is not set in .env. Add: INDEXNOW_KEY=your32charkey');
            return self::FAILURE;
        }

        $host = (string) (
            config('indexnow.host')
            ?: (parse_url((string) config('app.url', ''), PHP_URL_HOST) ?: '')
        );
        if ($host === '') {
            $this->error('Cannot determine host. Set APP_URL in .env or INDEXNOW_HOST=arsdeveloper.co.uk.');
            return self::FAILURE;
        }

        $base  = rtrim((string) config('app.url', 'https://' . $host), '/');
        $urls  = $this->buildUrlList($base);
        $total = count($urls);

        if ($this->option('dry-run')) {
            $this->info("Dry-run mode — would submit $total URLs to IndexNow:");
            foreach ($urls as $url) {
                $this->line("  $url");
            }
            return self::SUCCESS;
        }

        $this->info("Submitting $total URLs to IndexNow (host: $host) …");

        $batchSize = max(1, min(10000, (int) $this->option('batch')));
        $batches   = array_chunk($urls, $batchSize);
        $failed    = 0;

        foreach ($batches as $batchIndex => $batch) {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(15)
                ->post('https://api.indexnow.org/indexnow', [
                    'host'    => $host,
                    'key'     => $key,
                    'urlList' => $batch,
                ]);

            $status = $response->status();
            if (in_array($status, [200, 202], true)) {
                $this->line("  Batch " . ($batchIndex + 1) . "/" . count($batches) . ": {$status} OK (" . count($batch) . " URLs)");
            } else {
                $this->warn("  Batch " . ($batchIndex + 1) . ": HTTP {$status} — " . $response->body());
                $failed += count($batch);
                Log::warning('IndexNow batch submit failed', [
                    'status' => $status,
                    'body'   => $response->body(),
                    'batch'  => $batchIndex + 1,
                ]);
            }
        }

        if ($failed > 0) {
            $this->error("$failed URLs failed to submit. Check logs for details.");
            return self::FAILURE;
        }

        $this->info("IndexNow submission complete — $total URLs submitted.");
        return self::SUCCESS;
    }

    /** @return list<string> */
    private function buildUrlList(string $base): array
    {
        $servicePageSlugs = array_keys(config('seo_service_pages', []));

        $staticPaths = [
            '/',
            '/about',
            '/services',
            '/software-development',
            '/web-design-development',
            '/search-engine-optimization',
            '/digital-marketing',
            '/app-development',
            '/design-and-branding',
            '/sectors/healthcare',
            '/sectors/law-firms',
            '/sectors/ecommerce',
            '/sectors/b2b',
            '/pricing',
            '/faq',
            '/contact',
            '/portfolio',
            '/blog',
            '/uk-growth-hub',
        ];

        $urls = array_map(
            static fn (string $path) => $base . $path,
            $staticPaths
        );

        foreach ($servicePageSlugs as $slug) {
            $urls[] = $base . '/' . $slug;
        }

        $blogSlugs = \App\Models\BlogPost::query()
            ->live()
            ->orderByDesc('published_at')
            ->pluck('slug')
            ->all();

        foreach ($blogSlugs as $slug) {
            $urls[] = $base . '/blog/' . $slug;
        }

        return array_values(array_unique($urls));
    }
}
