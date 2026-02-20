<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPageController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));
        $querySeo = Str::limit($query, 32, '');
        $page = max(1, (int) $request->query('page', 1));
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');

        $posts = BlogPost::query()
            ->where('is_published', true)
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($nested) use ($query) {
                    $nested->where('title', 'like', '%' . $query . '%')
                        ->orWhere('excerpt', 'like', '%' . $query . '%')
                        ->orWhere('content', 'like', '%' . $query . '%')
                        ->orWhere('meta_keywords', 'like', '%' . $query . '%')
                        ->orWhere('category', 'like', '%' . $query . '%');
                });
            })
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $seoOverride = [
            'title' => $query !== '' ? ('Search: ' . $querySeo . ' - Blog') : 'Blog - UK Web Development and SEO Insights',
            'description' => $query !== ''
                ? ('Search results for "' . $query . '" in ARSDeveloper blog articles about web development, CRM, and SEO.')
                : 'Practical insights on software development, WordPress, SEO, CRM systems, and digital growth in the UK.',
            'keywords' => $query !== ''
                ? ($query . ', uk software blog, web development insights, seo tips')
                : 'web development blog UK, SEO blog UK, CRM blog UK, WordPress tips UK',
            'type' => 'Blog',
            'robots' => $query !== '' ? 'noindex, follow' : 'index, follow',
            'canonical' => $query === '' && $page > 1
                ? ($canonicalBase . '/blog?page=' . $page)
                : ($canonicalBase . '/blog'),
            'allow_query_canonical' => $query === '' && $page > 1,
            'related_links' => [
                '/uk-growth-hub',
                '/services',
                '/pricing',
                '/portfolio',
                '/contact',
            ],
        ];

        return view('pages.blog', compact('posts', 'query', 'seoOverride'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::query()
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        $recentPosts = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $relatedPosts = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->when($post->category, fn ($q) => $q->where('category', $post->category))
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');

        $metaDescription = Str::limit(
            strip_tags((string) ($post->meta_description ?: $post->excerpt ?: $post->content)),
            155,
            ''
        );
        $metaTitle = $this->normalizeSeoTitle($post->meta_title ?: $post->title);
        $faqItems = $this->extractFaqItems((string) $post->content);
        $postCanonical = $post->canonical_url ?: ($canonicalBase . '/blog/' . $post->slug);

        $seoOverride = [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $post->meta_keywords ?: 'uk software blog, web development insights, seo tips',
            'type' => 'Article',
            'robots' => $post->meta_robots ?: 'index, follow',
            'canonical' => $postCanonical,
            'og_title' => $post->og_title ?: $metaTitle,
            'og_description' => $post->og_description ?: $metaDescription,
            'og_image' => $post->og_image ?: ($post->featured_image ? url('/' . ltrim($post->featured_image, '/')) : null),
            'twitter_title' => $post->twitter_title ?: $metaTitle,
            'twitter_description' => $post->twitter_description ?: $metaDescription,
            'twitter_image' => $post->twitter_image ?: ($post->featured_image ? url('/' . ltrim($post->featured_image, '/')) : null),
            'article' => $this->buildArticleSchema($post, $postCanonical),
            'faq_items' => $faqItems,
            'related_links' => [
                '/uk-growth-hub',
                '/services',
                '/portfolio',
                '/pricing',
                '/contact',
            ],
        ];

        return view('pages.blog-details', compact('post', 'recentPosts', 'relatedPosts', 'seoOverride'));
    }

    public function detailsLegacy(Request $request): RedirectResponse
    {
        $slug = trim((string) $request->query('slug', ''));

        if ($slug !== '') {
            return redirect('/blog/' . $slug, 301);
        }

        $first = BlogPost::query()
            ->where('is_published', true)
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->first();

        if ($first) {
            return redirect('/blog/' . $first->slug, 301);
        }

        return redirect('/blog', 301);
    }

    private function normalizeSeoTitle(string $title): string
    {
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags($title)));
        // Keep room for " | ARSDeveloper" suffix added in global header.
        return Str::limit($clean, 44, '');
    }

    private function buildArticleSchema(BlogPost $post, string $canonical): array
    {
        $description = Str::limit(
            strip_tags((string) ($post->meta_description ?: $post->excerpt ?: $post->content)),
            155,
            ''
        );
        $plainContent = trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $post->content)));
        $wordCount = str_word_count($plainContent);
        $keywords = array_values(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            explode(',', (string) ($post->meta_keywords ?? ''))
        )));
        $entityCoverage = $this->buildEntityCoverage($post);
        $citations = $this->buildCitations($post, $canonical);

        return [
            'headline' => trim((string) ($post->meta_title ?: $post->title)),
            'description' => $description,
            'image' => $post->featured_image ? url('/' . ltrim($post->featured_image, '/')) : url('/assets/images/resources/ars-logo-dark.png'),
            'datePublished' => optional($post->published_at ?: $post->created_at)?->toIso8601String(),
            'dateModified' => optional($post->updated_at ?: $post->published_at ?: $post->created_at)?->toIso8601String(),
            'author' => $post->author_name ?: 'ARS Developer Editorial Team',
            'articleSection' => $post->category ?: 'UK Digital Growth',
            'wordCount' => $wordCount > 0 ? $wordCount : null,
            'keywords' => $keywords,
            'about' => $entityCoverage['about'],
            'mentions' => $entityCoverage['mentions'],
            'citation' => $citations,
            'isAccessibleForFree' => true,
            'canonical' => $canonical,
            'speakable' => [
                '/html/body//h1[1]',
                '/html/body//article//p[1]',
            ],
        ];
    }

    private function buildEntityCoverage(BlogPost $post): array
    {
        $category = Str::lower(trim((string) $post->category));

        $categoryEntities = [
            'pillar guide' => ['AEO', 'GEO', 'EEAT', 'Entity SEO', 'Core Web Vitals', 'Technical SEO'],
            'web development' => ['Web Development', 'Conversion Rate Optimization', 'Service Page UX', 'Lead Generation'],
            'ecommerce' => ['Shopify', 'WooCommerce', 'Ecommerce SEO', 'Checkout Optimization'],
            'seo' => ['Technical SEO', 'Structured Data', 'Crawl Efficiency', 'AI Overviews'],
            'crm' => ['CRM Development', 'Sales Pipeline', 'Workflow Automation', 'Lead Management'],
            'digital marketing' => ['Paid Campaigns', 'Landing Page CRO', 'Attribution Tracking', 'Conversion Reporting'],
        ];

        $entities = $categoryEntities[$category] ?? ['Web Development', 'Technical SEO', 'CRM Systems', 'Conversion Strategy'];

        $entityLinks = [
            'AEO' => '/uk-growth-hub',
            'GEO' => '/uk-growth-hub',
            'EEAT' => '/uk-growth-hub',
            'Entity SEO' => '/uk-growth-hub',
            'Core Web Vitals' => '/search-engine-optimization',
            'Technical SEO' => '/search-engine-optimization',
            'Web Development' => '/web-design-development',
            'Conversion Rate Optimization' => '/digital-marketing',
            'Service Page UX' => '/web-design-development',
            'Lead Generation' => '/digital-marketing',
            'Shopify' => '/services',
            'WooCommerce' => '/services',
            'Ecommerce SEO' => '/search-engine-optimization',
            'Checkout Optimization' => '/digital-marketing',
            'Structured Data' => '/uk-growth-hub',
            'Crawl Efficiency' => '/uk-growth-hub',
            'AI Overviews' => '/uk-growth-hub',
            'CRM Development' => '/services',
            'Sales Pipeline' => '/services',
            'Workflow Automation' => '/software-development',
            'Lead Management' => '/software-development',
            'Paid Campaigns' => '/digital-marketing',
            'Landing Page CRO' => '/digital-marketing',
            'Attribution Tracking' => '/digital-marketing',
            'Conversion Reporting' => '/pricing',
            'CRM Systems' => '/services',
            'Conversion Strategy' => '/pricing',
        ];

        $about = collect($entities)->map(static fn ($entity) => [
            '@type' => 'Thing',
            'name' => $entity,
        ])->values()->all();

        $mentions = collect($entities)->map(function ($entity) use ($entityLinks) {
            $node = [
                '@type' => 'Thing',
                'name' => $entity,
            ];

            if (!empty($entityLinks[$entity])) {
                $node['url'] = url($entityLinks[$entity]);
            }

            return $node;
        })->values()->all();

        return [
            'about' => $about,
            'mentions' => $mentions,
        ];
    }

    private function extractFaqItems(string $content, int $limit = 4): array
    {
        $items = [];
        if (trim($content) === '') {
            return $items;
        }

        preg_match_all('/<(h2|h3)[^>]*>(.*?)<\/\1>/i', $content, $matches, PREG_OFFSET_CAPTURE);
        if (empty($matches[0])) {
            return $items;
        }

        foreach ($matches[0] as $index => $headingMatch) {
            $headingHtml = (string) ($matches[2][$index][0] ?? '');
            $question = trim(preg_replace('/\s+/', ' ', strip_tags($headingHtml)));
            if ($question === '' || !str_contains($question, '?')) {
                continue;
            }

            $offset = (int) $headingMatch[1] + strlen((string) $headingMatch[0]);
            $remaining = substr($content, $offset) ?: '';
            if (!preg_match('/<p[^>]*>(.*?)<\/p>/is', $remaining, $paragraphMatch)) {
                continue;
            }

            $answer = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($paragraphMatch[1] ?? ''))));
            if ($answer === '') {
                continue;
            }

            $items[] = [
                'question' => $question,
                'answer' => Str::limit($answer, 220, ''),
            ];

            if (count($items) >= $limit) {
                break;
            }
        }

        return $items;
    }

    private function buildCitations(BlogPost $post, string $canonical): array
    {
        $citations = [
            $canonical,
            url('/uk-growth-hub'),
            url('/services'),
            url('/pricing'),
            url('/portfolio'),
        ];

        $category = Str::lower(trim((string) $post->category));
        if ($category === 'seo') {
            $citations[] = url('/search-engine-optimization');
        } elseif ($category === 'web development') {
            $citations[] = url('/web-design-development');
        } elseif ($category === 'crm') {
            $citations[] = url('/software-development');
        } elseif ($category === 'digital marketing') {
            $citations[] = url('/digital-marketing');
        }

        return array_values(array_unique(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            $citations
        ))));
    }
}
