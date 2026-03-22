<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
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
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 0 ELSE 1 END')
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $seoOverride = [
            'title' => $query !== '' ? ('Search: ' . $querySeo . ' - Blog') : 'AI SEO Blog | UK Software Development, AEO, GEO and Growth Insights',
            'description' => $query !== ''
                ? ('Search results for "' . $query . '" in ARSDeveloper blog articles about AI SEO, software development, CRM, and growth strategy.')
                : 'Practical AI SEO, software development, CRM, answer engine optimization, and growth insights for UK businesses.',
            'keywords' => $query !== ''
                ? ($query . ', ai seo blog uk, software development insights uk, answer engine optimization blog')
                : 'ai seo blog uk, aeo blog uk, geo seo uk, software development blog uk, crm blog uk, answer engine optimization uk',
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
            ->limit(10)
            ->get();
        $currentCategory = trim((string) ($post->category ?: 'UK Growth'));
        $topicGroups = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderByRaw('CASE WHEN category = ? THEN 0 ELSE 1 END', [$currentCategory])
            ->orderBy('category')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy(function (BlogPost $item) {
                return trim((string) ($item->category ?: 'UK Growth'));
            })
            ->map(function ($items, $category) use ($currentCategory) {
                return [
                    'category' => $category,
                    'is_current' => strcasecmp((string) $category, $currentCategory) === 0,
                    'posts' => $items->take(4)->values(),
                ];
            })
            ->sortByDesc(static fn (array $group) => $group['is_current'])
            ->values();

        $relatedPosts = $this->buildStrategicRelatedPosts($post);
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
        $clusterLinks = $this->buildTopicalClusterLinks($post);
        $tableOfContents = $this->extractTableOfContents((string) $post->content);
        $approvedComments = $post->comments()->approved()->latest()->get();
        $authorProfile = [
            'title' => 'About ARS Developer Ltd',
            'role' => 'UK Software, CRM and Search Growth Delivery Partner',
            'image' => asset('assets/images/resources/uk.png'),
            'summary' => 'ARS Developer Ltd helps UK businesses build clearer websites, stronger CRM workflows, better ecommerce journeys, and practical SEO systems that support enquiries, conversions, and long-term growth.',
            'points' => [
                'Founder-led project delivery with clear scope, timelines, and launch planning.',
                'Focused on software builds, business websites, CRM systems, ecommerce flows, and technical SEO.',
                'Built for UK service teams that want qualified leads, cleaner operations, and dependable post-launch support.',
            ],
        ];

        return view('pages.blog-details', compact('post', 'recentPosts', 'topicGroups', 'relatedPosts', 'seoOverride', 'clusterLinks', 'tableOfContents', 'approvedComments', 'authorProfile'));
    }

    public function detailsLegacy(Request $request): RedirectResponse
    {
        $slug = trim((string) $request->query('slug', ''));

        if ($slug !== '') {
            return redirect('/blog/' . $slug, 301);
        }

        $first = BlogPost::query()
            ->where('is_published', true)
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 0 ELSE 1 END')
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
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
        $companyName = trim((string) config('company.legal_name', 'ARS Developer Ltd'));
        $authorNameRaw = trim((string) ($post->author_name ?? ''));
        $authorName = $authorNameRaw !== '' ? $authorNameRaw : $companyName;
        $authorType = $authorNameRaw !== '' ? 'Person' : 'Organization';
        $authorUrl = $authorType === 'Person' ? url('/about') : url('/');

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
            'author' => $authorName,
            'authorType' => $authorType,
            'authorUrl' => $authorUrl,
            'publisher' => $companyName,
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
            'ai search' => ['AI Overviews', 'Answer Engine Optimization', 'Search Intent', 'Topical Authority'],
            'cyber security' => ['Cyber Security', 'Phishing Protection', 'Access Control', 'Business Resilience'],
            'managed it' => ['Managed IT Services', 'Business Continuity', 'Vendor Governance', 'Endpoint Standards'],
            'local seo' => ['Local SEO', 'Software Development', 'Discovery Process', 'Client Proof'],
            'saas' => ['SaaS Development', 'Recurring Billing', 'Product Onboarding', 'User Permissions'],
            'software pricing' => ['Software Pricing', 'Project Discovery', 'Milestone Delivery', 'Integration Scope'],
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
            'Answer Engine Optimization' => '/uk-growth-hub',
            'Search Intent' => '/uk-growth-hub',
            'Topical Authority' => '/uk-growth-hub',
            'Cyber Security' => '/services',
            'Phishing Protection' => '/services',
            'Access Control' => '/services',
            'Business Resilience' => '/services',
            'Managed IT Services' => '/services',
            'Business Continuity' => '/services',
            'Vendor Governance' => '/services',
            'Endpoint Standards' => '/services',
            'Local SEO' => '/contact',
            'Software Development' => '/software-development',
            'Discovery Process' => '/contact',
            'Client Proof' => '/portfolio',
            'SaaS Development' => '/software-development',
            'Recurring Billing' => '/services',
            'Product Onboarding' => '/software-development',
            'User Permissions' => '/software-development',
            'Software Pricing' => '/pricing',
            'Project Discovery' => '/contact',
            'Milestone Delivery' => '/pricing',
            'Integration Scope' => '/services',
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
        } elseif (in_array($category, ['saas', 'software pricing', 'local seo'], true)) {
            $citations[] = url('/software-development');
            $citations[] = url('/contact');
        }

        return array_values(array_unique(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            $citations
        ))));
    }

    private function buildTopicalClusterLinks(BlogPost $post): array
    {
        $category = Str::lower(trim((string) $post->category));

        $clusterByCategory = [
            'seo' => [
                ['label' => 'UK SEO Growth Hub', 'url' => '/uk-growth-hub'],
                ['label' => 'Technical SEO Services', 'url' => '/search-engine-optimization'],
                ['label' => 'UK SEO Pricing', 'url' => '/pricing'],
                ['label' => 'SEO Case Studies', 'url' => '/portfolio'],
                ['label' => 'Book SEO Strategy Call', 'url' => '/contact'],
            ],
            'web development' => [
                ['label' => 'Web Design & Development', 'url' => '/web-design-development'],
                ['label' => 'Software Development Services', 'url' => '/software-development'],
                ['label' => 'Website Project Pricing', 'url' => '/pricing'],
                ['label' => 'Website Portfolio Examples', 'url' => '/portfolio'],
                ['label' => 'Start Website Project', 'url' => '/contact'],
            ],
            'crm' => [
                ['label' => 'CRM Development Services', 'url' => '/software-development'],
                ['label' => 'Custom Web App Development', 'url' => '/app-development'],
                ['label' => 'CRM and Portal Pricing', 'url' => '/pricing'],
                ['label' => 'CRM Project Portfolio', 'url' => '/portfolio'],
                ['label' => 'Book CRM Discovery Call', 'url' => '/contact'],
            ],
            'ecommerce' => [
                ['label' => 'Ecommerce Web Development', 'url' => '/web-design-development'],
                ['label' => 'Ecommerce SEO and CRO', 'url' => '/search-engine-optimization'],
                ['label' => 'Ecommerce Growth Services', 'url' => '/digital-marketing'],
                ['label' => 'Ecommerce Portfolio Cases', 'url' => '/portfolio'],
                ['label' => 'Start Ecommerce Build', 'url' => '/contact'],
            ],
            'digital marketing' => [
                ['label' => 'Digital Marketing Services', 'url' => '/digital-marketing'],
                ['label' => 'SEO and Performance Services', 'url' => '/search-engine-optimization'],
                ['label' => 'Landing Page Development', 'url' => '/web-design-development'],
                ['label' => 'Growth Retainer Pricing', 'url' => '/pricing'],
                ['label' => 'Book Growth Strategy Call', 'url' => '/contact'],
            ],
            'pillar guide' => [
                ['label' => 'UK SEO Growth Hub', 'url' => '/uk-growth-hub'],
                ['label' => 'SEO Service Delivery', 'url' => '/search-engine-optimization'],
                ['label' => 'Full Services Overview', 'url' => '/services'],
                ['label' => 'Proof-Based Portfolio', 'url' => '/portfolio'],
                ['label' => 'Get a Custom SEO Plan', 'url' => '/contact'],
            ],
            'local seo' => [
                ['label' => 'Software Development Services', 'url' => '/software-development'],
                ['label' => 'Project Pricing Guide', 'url' => '/pricing'],
                ['label' => 'Recent Project Portfolio', 'url' => '/portfolio'],
                ['label' => 'Full Services Overview', 'url' => '/services'],
                ['label' => 'Book Discovery Call', 'url' => '/contact'],
            ],
            'saas' => [
                ['label' => 'SaaS and App Development', 'url' => '/software-development'],
                ['label' => 'Custom Build Pricing', 'url' => '/pricing'],
                ['label' => 'Portal and App Portfolio', 'url' => '/portfolio'],
                ['label' => 'Service Delivery Scope', 'url' => '/services'],
                ['label' => 'Start SaaS Discovery', 'url' => '/contact'],
            ],
            'software pricing' => [
                ['label' => 'Software Project Pricing', 'url' => '/pricing'],
                ['label' => 'Custom Software Development', 'url' => '/software-development'],
                ['label' => 'Project Delivery Portfolio', 'url' => '/portfolio'],
                ['label' => 'Implementation Services', 'url' => '/services'],
                ['label' => 'Request a Scoped Estimate', 'url' => '/contact'],
            ],
        ];

        $fallback = [
            ['label' => 'UK SEO Growth Hub', 'url' => '/uk-growth-hub'],
            ['label' => 'Service Solutions', 'url' => '/services'],
            ['label' => 'Case Studies', 'url' => '/portfolio'],
            ['label' => 'Pricing Plans', 'url' => '/pricing'],
            ['label' => 'Book Strategy Call', 'url' => '/contact'],
        ];

        $links = $clusterByCategory[$category] ?? $fallback;

        return collect($links)
            ->filter(static fn (array $link) => !empty($link['label']) && !empty($link['url']))
            ->unique(static fn (array $link) => trim((string) $link['url']))
            ->values()
            ->all();
    }

    private function buildStrategicRelatedPosts(BlogPost $post)
    {
        $map = [
            'uk-seo-growth-system-2026-aeo-geo-eeat-guide' => [
                'ai-overviews-seo-uk-how-service-businesses-win-ai-search-visibility',
                'technical-seo-checklist-for-uk-websites-before-launch',
                'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
            ],
            'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites' => [
                'landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries',
                'uk-seo-growth-system-2026-aeo-geo-eeat-guide',
                'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
            ],
            'wordpress-vs-shopify-for-uk-businesses-which-platform-fits-your-growth-stage' => [
                'technical-seo-checklist-for-uk-websites-before-launch',
                'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
                'landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries',
            ],
            'technical-seo-checklist-for-uk-websites-before-launch' => [
                'uk-seo-growth-system-2026-aeo-geo-eeat-guide',
                'ai-overviews-seo-uk-how-service-businesses-win-ai-search-visibility',
                'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
            ],
            'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm' => [
                'managed-it-services-uk-what-growing-businesses-should-expect-in-2026',
                'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
                'landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries',
            ],
            'landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries' => [
                'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
                'uk-seo-growth-system-2026-aeo-geo-eeat-guide',
                'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
            ],
            'ai-overviews-seo-uk-how-service-businesses-win-ai-search-visibility' => [
                'answer-engine-optimization-uk-how-service-businesses-structure-content-for-ai-search',
                'ai-mode-seo-uk-how-service-brands-create-content-that-earns-clicks',
                'google-search-console-insights-uk-how-to-find-easy-seo-wins-faster',
            ],
            'uk-cyber-security-checklist-for-growing-businesses-in-2026' => [
                'managed-it-services-uk-what-growing-businesses-should-expect-in-2026',
                'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
                'technical-seo-checklist-for-uk-websites-before-launch',
            ],
            'managed-it-services-uk-what-growing-businesses-should-expect-in-2026' => [
                'uk-cyber-security-checklist-for-growing-businesses-in-2026',
                'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
                'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
            ],
            'software-development-company-stoke-on-trent-how-to-choose-the-right-uk-partner' => [
                'custom-software-development-pricing-uk-what-businesses-should-budget-for-in-2026',
                'subscription-software-development-uk-how-saas-products-are-planned-priced-and-built',
                'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
            ],
            'subscription-software-development-uk-how-saas-products-are-planned-priced-and-built' => [
                'custom-software-development-pricing-uk-what-businesses-should-budget-for-in-2026',
                'managed-it-services-uk-what-growing-businesses-should-expect-in-2026',
                'technical-seo-checklist-for-uk-websites-before-launch',
            ],
            'custom-software-development-pricing-uk-what-businesses-should-budget-for-in-2026' => [
                'subscription-software-development-uk-how-saas-products-are-planned-priced-and-built',
                'software-development-company-stoke-on-trent-how-to-choose-the-right-uk-partner',
                'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
            ],
            'answer-engine-optimization-uk-how-service-businesses-structure-content-for-ai-search' => [
                'ai-mode-seo-uk-how-service-brands-create-content-that-earns-clicks',
                'google-search-console-insights-uk-how-to-find-easy-seo-wins-faster',
                'ai-overviews-seo-uk-how-service-businesses-win-ai-search-visibility',
            ],
            'google-search-console-insights-uk-how-to-find-easy-seo-wins-faster' => [
                'answer-engine-optimization-uk-how-service-businesses-structure-content-for-ai-search',
                'ai-mode-seo-uk-how-service-brands-create-content-that-earns-clicks',
                'uk-seo-growth-system-2026-aeo-geo-eeat-guide',
            ],
            'ai-mode-seo-uk-how-service-brands-create-content-that-earns-clicks' => [
                'answer-engine-optimization-uk-how-service-businesses-structure-content-for-ai-search',
                'google-search-console-insights-uk-how-to-find-easy-seo-wins-faster',
                'ai-overviews-seo-uk-how-service-businesses-win-ai-search-visibility',
            ],
            'software-development-company-tunbridge-wells-how-to-choose-the-right-partner' => [
                'software-development-company-stoke-on-trent-how-to-choose-the-right-uk-partner',
                'website-development-company-stoke-on-trent-what-businesses-should-expect',
                'custom-crm-development-cost-uk-what-affects-budget-and-timeline',
            ],
            'website-development-company-stoke-on-trent-what-businesses-should-expect' => [
                'seo-company-stoke-on-trent-for-small-businesses-what-actually-drives-enquiries',
                'software-development-company-stoke-on-trent-how-to-choose-the-right-uk-partner',
                'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
            ],
            'seo-company-stoke-on-trent-for-small-businesses-what-actually-drives-enquiries' => [
                'website-development-company-stoke-on-trent-what-businesses-should-expect',
                'google-search-console-insights-uk-how-to-find-easy-seo-wins-faster',
                'uk-seo-growth-system-2026-aeo-geo-eeat-guide',
            ],
            'saas-mvp-development-uk-what-to-build-first-and-what-to-delay' => [
                'mvp-development-cost-uk-how-founders-budget-for-version-one',
                'subscription-software-development-uk-how-saas-products-are-planned-priced-and-built',
                'custom-crm-development-cost-uk-what-affects-budget-and-timeline',
            ],
            'custom-crm-development-cost-uk-what-affects-budget-and-timeline' => [
                'mvp-development-cost-uk-how-founders-budget-for-version-one',
                'custom-software-development-pricing-uk-what-businesses-should-budget-for-in-2026',
                'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
            ],
            'mvp-development-cost-uk-how-founders-budget-for-version-one' => [
                'saas-mvp-development-uk-what-to-build-first-and-what-to-delay',
                'subscription-software-development-uk-how-saas-products-are-planned-priced-and-built',
                'custom-crm-development-cost-uk-what-affects-budget-and-timeline',
            ],
        ];

        $preferred = $map[$post->slug] ?? [];
        $preferredPosts = BlogPost::query()
            ->where('is_published', true)
            ->whereIn('slug', $preferred)
            ->get()
            ->sortBy(fn (BlogPost $item) => array_search($item->slug, $preferred, true))
            ->values();

        if ($preferredPosts->count() >= 3) {
            return $preferredPosts->take(3);
        }

        $fallback = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->whereNotIn('id', $preferredPosts->pluck('id'))
            ->when($post->category, fn ($q) => $q->where('category', $post->category))
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(3 - $preferredPosts->count())
            ->get();

        return $preferredPosts->concat($fallback)->take(3)->values();
    }

    private function extractTableOfContents(string $content): array
    {
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $content, $matches);
        $headings = collect($matches[1] ?? [])
            ->map(static fn ($heading) => trim(preg_replace('/\s+/', ' ', strip_tags((string) $heading))))
            ->filter()
            ->take(8)
            ->values();

        return $headings->map(static fn (string $heading) => [
            'label' => $heading,
            'id' => Str::slug($heading),
        ])->all();
    }

}
