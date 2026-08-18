<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use Carbon\CarbonInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sections = collect([
            [
                'loc' => route('sitemap.section', ['section' => 'pages']),
                'lastmod' => $this->latestTimestamp($this->pageEntries()->pluck('lastmod')->filter()),
            ],
            [
                'loc' => route('sitemap.section', ['section' => 'portfolio']),
                'lastmod' => $this->latestTimestamp($this->portfolioEntries()->pluck('lastmod')->filter()),
            ],
            [
                'loc' => route('sitemap.section', ['section' => 'blog']),
                'lastmod' => $this->latestTimestamp($this->blogEntries()->pluck('lastmod')->filter()),
            ],
        ]);

        return response()
            ->view('sitemaps.index', ['sections' => $sections], 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function section(string $section): Response
    {
        $entries = match ($section) {
            'pages' => $this->pageEntries(),
            'portfolio' => $this->portfolioEntries(),
            'blog' => $this->blogEntries(),
            default => abort(404),
        };

        return response()
            ->view('sitemaps.urlset', ['entries' => $entries], 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function pageEntries(): Collection
    {
        $today = now();
        $staticPages = collect([
            ['path' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['path' => '/about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/services', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/software-development', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/web-design-development', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/search-engine-optimization', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/digital-marketing', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/app-development', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/design-and-branding', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/healthcare', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/law-firms', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/ecommerce', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/b2b', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/pricing', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/faq', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/contact', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/portfolio', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/blog', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/uk-growth-hub', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/testimonials', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['path' => '/gallery', 'changefreq' => 'monthly', 'priority' => '0.5'],
            // UK service / city landing pages
            ['path' => '/laravel-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/wordpress-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/shopify-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/php-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/react-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/crm-development-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/ecommerce-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-stoke-on-trent', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-manchester', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-birmingham', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/laravel-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/wordpress-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/shopify-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-leeds', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-sheffield', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-bristol', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-glasgow', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-edinburgh', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/react-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/php-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/nextjs-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-uk', 'changefreq' => 'monthly', 'priority' => '1.0'],
            ['path' => '/nextjs-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/typescript-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/vue-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/node-developer-uk', 'changefreq' => 'monthly', 'priority' => '0.9'],
            // Additional UK city pages
            ['path' => '/web-developer-liverpool', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-cardiff', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-nottingham', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-newcastle', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-leicester', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/web-developer-coventry', 'changefreq' => 'monthly', 'priority' => '0.9'],
            // London tech stack combos
            ['path' => '/vue-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/node-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/typescript-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/crm-developer-london', 'changefreq' => 'monthly', 'priority' => '0.9'],
            // Legal pages. Low priority — they are here so they are crawlable and
            // discoverable (AdSense and users both expect to find them), not to
            // compete for search traffic.
            ['path' => '/privacy-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/cookie-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/terms-and-conditions', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/refund-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['path' => '/service-disclaimer', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ]);

        return $staticPages->map(function (array $page) use ($today) {
            return [
                'loc' => url($page['path']),
                'lastmod' => $today,
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        });
    }

    private function portfolioEntries(): Collection
    {
        $indexEntry = collect([[
            'loc' => url('/portfolio'),
            'lastmod' => $this->latestTimestamp(
                Portfolio::query()->where('is_published', true)->pluck('updated_at')
            ) ?? now(),
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ]]);

        $portfolioItems = Portfolio::query()
            ->where('is_published', true)
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get()
            ->map(function (Portfolio $portfolio) {
                return [
                    'loc' => url('/portfolio-details/' . $portfolio->slug),
                    'lastmod' => $portfolio->updated_at ?: $portfolio->created_at,
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ];
            });

        return $indexEntry->concat($portfolioItems);
    }

    private function blogEntries(): Collection
    {
        $publishedPosts = BlogPost::query()
            ->live()
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 0 ELSE 1 END')
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $latestPostDate = $this->latestTimestamp(
            $publishedPosts->map(fn (BlogPost $post) => $post->updated_at ?: $post->published_at ?: $post->created_at)
        ) ?? now();

        $indexEntries = collect([
            [
                'loc' => url('/blog'),
                'lastmod' => $latestPostDate,
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'loc' => url('/uk-growth-hub'),
                'lastmod' => $latestPostDate,
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
        ]);

        $postEntries = $publishedPosts->map(function (BlogPost $post) {
            return [
                'loc' => url('/blog/' . $post->slug),
                'lastmod' => $post->updated_at ?: $post->published_at ?: $post->created_at,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        });

        return $indexEntries->concat($postEntries);
    }

    private function latestTimestamp($values): ?CarbonInterface
    {
        $collection = collect($values)
            ->filter()
            ->map(function ($value) {
                if ($value instanceof CarbonInterface) {
                    return $value;
                }

                return $value ? now()->parse($value) : null;
            })
            ->filter();

        return $collection->isEmpty() ? null : $collection->max();
    }
}
