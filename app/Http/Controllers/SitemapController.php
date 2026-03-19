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
            ['path' => '/testimonials', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['path' => '/gallery', 'changefreq' => 'monthly', 'priority' => '0.5'],
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
            ->where('is_published', true)
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
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
