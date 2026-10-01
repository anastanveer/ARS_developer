<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
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
            'blog' => $this->blogEntries(),
            default => abort(404),
        };

        return response()
            ->view('sitemaps.urlset', ['entries' => $entries], 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function pageEntries(): Collection
    {
        // Real dates, not now().
        //
        // Every entry here used to carry the time the sitemap was fetched, so all
        // nineteen pages claimed to have changed on every request. Google's
        // documented response to a lastmod it finds consistently inaccurate is to
        // stop trusting the field — which is the opposite of useful when one page
        // genuinely has changed and needs recrawling.
        //
        // These are the dates the page's own template last changed. Update the date
        // when you change the page; leaving it stale is better than moving them all
        // to today, because a date that never moves is merely unhelpful while a date
        // that always moves is actively disbelieved.
        $staticPages = collect([
            ['path' => '/', 'changed' => '2026-08-30', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['path' => '/about', 'changed' => '2026-05-14', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/services', 'changed' => '2026-03-26', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/software-development', 'changed' => '2026-06-03', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/web-design-development', 'changed' => '2026-05-13', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/search-engine-optimization', 'changed' => '2026-06-03', 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['path' => '/digital-marketing', 'changed' => '2026-05-13', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/app-development', 'changed' => '2026-05-13', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/design-and-branding', 'changed' => '2026-05-13', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/healthcare', 'changed' => '2026-09-08', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/law-firms', 'changed' => '2026-09-08', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/ecommerce', 'changed' => '2026-09-08', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/sectors/b2b', 'changed' => '2026-09-08', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/pricing', 'changed' => '2026-07-02', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/faq', 'changed' => '2026-05-13', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['path' => '/contact', 'changed' => '2026-07-02', 'changefreq' => 'monthly', 'priority' => '0.9'],
            // The portfolio catalogue was restructured on 2026-09-30: entries naming
            // companies the business had not worked for were removed. This is the one
            // page that most needs recrawling, so its date has to be right.
            ['path' => '/portfolio', 'changed' => '2026-09-30', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/blog', 'changed' => null, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['path' => '/uk-growth-hub', 'changed' => '2026-04-18', 'changefreq' => 'weekly', 'priority' => '0.9'],
            // Programmatic UK service/city landing pages are intentionally omitted
            // during AdSense approval. They remain reachable from internal links,
            // but are noindexed at render time so the sitemap only promotes the
            // strongest human-edited cornerstone, sector, blog, and portfolio URLs.
            // Noindex legal/support pages stay discoverable from the footer, but
            // are not submitted in XML sitemaps.
        ]);

        // The blog index changes whenever a post does, so it takes its date from the
        // posts rather than from a constant somebody has to remember to update.
        $latestPost = $this->latestTimestamp(
            BlogPost::query()->live()->pluck('updated_at')
        );

        return $staticPages->map(function (array $page) use ($latestPost) {
            $changed = $page['path'] === '/blog'
                ? $latestPost
                : ($page['changed'] ? now()->parse($page['changed']) : null);

            return [
                'loc' => url($page['path']),
                'lastmod' => $changed,
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        });
    }

    private function blogEntries(): Collection
    {
        $publishedPosts = BlogPost::query()
            ->live()
            ->where(function ($query) {
                $query->whereNull('meta_robots')
                    ->orWhere('meta_robots', 'not like', '%noindex%');
            })
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
