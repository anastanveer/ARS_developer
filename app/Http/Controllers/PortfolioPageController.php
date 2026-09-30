<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PortfolioPageController extends Controller
{
    public function index(): View
    {
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');
        $portfolios = Portfolio::query()
            ->where('is_published', true)
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $seoOverride = [
            'title' => 'Portfolio of UK Web Development, CRM and SEO Projects',
            'description' => 'Explore ARSDeveloper UK portfolio projects across business websites, ecommerce stores, CRM platforms, and growth-focused digital systems.',
            'keywords' => 'uk software portfolio, uk web development portfolio, crm case studies uk, ecommerce website projects uk',
            'canonical' => $canonicalBase . '/portfolio',
            'type' => 'CollectionPage',
            'related_links' => [
                '/services',
                '/pricing',
                '/contact',
                '/uk-growth-hub',
            ],
        ];

        return view('pages.portfolio', compact('portfolios', 'seoOverride'));
    }

    public function details(Request $request, ?string $slug = null): View|RedirectResponse
    {
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');

        // /portfolio-details?tab=…&item=… used to render a second, hardcoded copy of
        // the catalogue: same "Project N" titles, same copy, served whatever the
        // database said. Worse, it declared itself noindex while canonicalising to
        // /portfolio — roughly 22 URLs pointing a noindex at the one portfolio page
        // that has to stay indexed, which is a way to lose it. Nothing links to these
        // and they are not in the sitemap, so they redirect to the listing.
        if ($request->query('tab') !== null || $request->query('item') !== null) {
            return redirect('/portfolio', 301);
        }

        $slug = $slug ?: trim((string) $request->query('slug', ''));

        $baseQuery = Portfolio::query()
            ->where('is_published', true)
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('id');

        $portfolio = $slug !== ''
            ? (clone $baseQuery)->where('slug', $slug)->first()
            : null;

        // An unknown slug used to fall through to firstOrFail(), which returned the
        // first project with a 200. That made /portfolio-details/<anything> a valid
        // page: an unbounded set of URLs all serving the same content, which is a
        // soft 404 to a crawler and an invitation to index duplicates. Send them to
        // the listing instead, where the real projects are.
        if (!$portfolio) {
            // 302, not 301: slugs are created from the admin, so a URL that is
            // unknown today may be a real project tomorrow, and a permanent redirect
            // cached by a browser would keep it unreachable.
            return redirect('/portfolio', 302);
        }

        $ordered = (clone $baseQuery)->get(['id', 'slug']);
        $currentIndex = $ordered->search(fn ($item) => (int) $item->id === (int) $portfolio->id);

        $previousPortfolio = $currentIndex !== false && $currentIndex > 0 ? $ordered[$currentIndex - 1] : null;
        $nextPortfolio = $currentIndex !== false && $currentIndex < $ordered->count() - 1 ? $ordered[$currentIndex + 1] : null;

        $relatedPortfolios = Portfolio::query()
            ->where('is_published', true)
            ->where('id', '!=', $portfolio->id)
            ->when($portfolio->category, fn ($q) => $q->where('category', $portfolio->category))
            ->orderByRaw('CASE WHEN sort_order = 0 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $portfolioUrl = $canonicalBase . '/portfolio-details/' . $portfolio->slug;
        $seoTitle = $portfolio->title;
        $seoDescription = Str::limit(strip_tags((string) ($portfolio->excerpt ?: $portfolio->description ?: 'UK software and web project case study by ARSDeveloper.')), 160, '');

        $seoOverride = [
            'title' => $seoTitle,
            'description' => $seoDescription,
            'keywords' => 'uk project case study, web development project uk, crm implementation uk',
            'canonical' => $portfolioUrl,
            'type' => 'WebPage',
            // Not indexed: see SitemapController::portfolioEntries(). 'follow' is kept
            // so the outbound project links still pass through normally.
            'robots' => 'noindex, follow',
            'preload_image' => $portfolio->image_path ? asset($portfolio->image_path) : null,
            'related_links' => [
                '/portfolio',
                '/services',
                '/pricing',
                '/contact',
                '/uk-growth-hub',
            ],
            'og_title' => $seoTitle,
            'og_description' => $seoDescription,
            'og_image' => $portfolio->image_path ? asset($portfolio->image_path) : null,
            'twitter_title' => $seoTitle,
            'twitter_description' => $seoDescription,
            'twitter_image' => $portfolio->image_path ? asset($portfolio->image_path) : null,
        ];

        $caseNarrative = $this->buildCaseNarrative($portfolio);

        return view('pages.portfolio-details', compact('portfolio', 'previousPortfolio', 'nextPortfolio', 'relatedPortfolios', 'caseNarrative', 'seoOverride'));
    }

    private function buildCaseNarrative(object $portfolio): array
    {
        $category = strtolower(trim((string) ($portfolio->category ?? 'project')));
        $title = (string) ($portfolio->title ?? 'Project');
        $host = preg_replace('/^www\./', '', (string) (parse_url((string) ($portfolio->project_url ?? ''), PHP_URL_HOST) ?: ($portfolio->client_name ?? 'business website')));

        $base = [
            'case_study_intro' => 'This delivery focused on practical business outcomes, technical quality, and a conversion-ready user journey for ' . $host . '.',
            'challenge' => 'The existing digital presence required clearer structure, stronger trust signals, and better conversion flow to support consistent enquiry and growth.',
            'approach' => 'We mapped business goals, rebuilt page hierarchy, improved UX clarity, and implemented a scalable structure for future updates.',
            'result' => 'The final platform delivered clearer messaging, faster user flow, and a more maintainable setup for long-term business operations.',
            'highlights_text' => 'Delivery combined strategy, implementation, and QA to ensure launch readiness without creating operational complexity.',
            'implementation_text' => 'Execution followed a milestone process with technical checks, design validation, and post-launch readiness planning.',
            'highlights' => [
                'Conversion-focused information architecture.',
                'Responsive layout and mobile usability alignment.',
                'Technical setup designed for speed and stability.',
                'Admin-side structure for easier ongoing updates.',
            ],
            'notes_left' => [
                'Scope defined with business-first priorities.',
                'UX decisions aligned with user intent.',
                'Core pages optimized for clarity and action.',
            ],
            'notes_right' => [
                'Structured QA before release.',
                'SEO-ready technical foundation.',
                'Handover-friendly content workflow.',
            ],
        ];

        $byCategory = [
            'wordpress' => [
                'challenge' => 'The previous WordPress setup had weak content hierarchy, mixed messaging, and inconsistent page speed on mobile.',
                'approach' => 'We rebuilt core templates, aligned headings with search intent, improved internal structure, and simplified content management blocks.',
                'result' => 'The WordPress website now has cleaner service presentation, better engagement flow, and stronger readiness for SEO growth campaigns.',
                'highlights' => [
                    'Service pages aligned with local keyword intent.',
                    'Improved Core Web Vitals readiness.',
                    'Structured CTAs for enquiry-driven conversion.',
                    'Editor-friendly backend content layout.',
                ],
                'notes_left' => [
                    'WordPress theme structure optimized for maintainability.',
                    'Plugin overhead reduced for better performance.',
                    'Form and CTA placements tested for conversion clarity.',
                ],
                'notes_right' => [
                    'On-page SEO essentials configured.',
                    'Image delivery optimized for load efficiency.',
                    'Future landing pages can be added without layout drift.',
                ],
            ],
            'shopify' => [
                'challenge' => 'Store navigation and product journey were creating drop-offs before checkout completion.',
                'approach' => 'We optimized collection-to-product flow, improved product detail clarity, and streamlined checkout path visibility.',
                'result' => 'The Shopify store now delivers stronger product discovery and a cleaner checkout route for higher purchase intent.',
                'highlights' => [
                    'Collection structure refined for browsing efficiency.',
                    'Product pages improved with trust and decision cues.',
                    'Checkout experience optimized for lower friction.',
                    'Mobile ecommerce behavior aligned with conversion goals.',
                ],
            ],
            'custom coding' => [
                'challenge' => 'The business needed logic beyond off-the-shelf builders, including custom workflow and advanced data handling.',
                'approach' => 'We designed a custom architecture, implemented targeted modules, and validated flows through staged QA.',
                'result' => 'The platform now supports tailored business operations with scalable technical foundations.',
                'highlights' => [
                    'Custom logic mapped to real business processes.',
                    'Secure backend + structured API handling.',
                    'Modular build for phased future expansion.',
                    'Performance and maintainability considered from start.',
                ],
            ],
            'crm' => [
                'challenge' => 'Lead and client activity required a centralized workflow with visibility across statuses and follow-up actions.',
                'approach' => 'We structured pipeline stages, status logic, and actionable tracking points for practical daily operations.',
                'result' => 'The CRM now provides clearer control over lead movement, task visibility, and client communication flow.',
                'highlights' => [
                    'Pipeline stages aligned to sales operations.',
                    'Status tracking and action flow clarity.',
                    'Operational visibility for team coordination.',
                    'Scalable structure for future automation layers.',
                ],
                'notes_left' => [
                    'Lead lifecycle mapped end-to-end.',
                    'Role-based workflows simplified for daily use.',
                    'Admin visibility improved for decision speed.',
                ],
                'notes_right' => [
                    'Data structure planned for reporting extensions.',
                    'Action states reduced manual follow-up confusion.',
                    'Client process flow became predictable and trackable.',
                ],
            ],
            'fiverr' => [
                'challenge' => 'Prospective clients needed proof of delivery consistency, technical breadth, and long-term reliability.',
                'approach' => 'We presented profile credibility with service depth, project diversity, and full-stack implementation strengths.',
                'result' => 'The case communicates a trust-first freelance profile with clear capability across websites, ecommerce, CRM, and custom systems.',
                'highlights' => [
                    'Active Fiverr delivery history since 2017.',
                    '1000+ client engagements across multiple industries.',
                    'Full-stack web development capability.',
                    'Strong repeat-client trust and project continuity.',
                ],
                'notes_left' => [
                    'Scope handled from discovery to deployment.',
                    'Communication and timeline clarity maintained.',
                    'Projects delivered across varied business models.',
                ],
                'notes_right' => [
                    'Frontend, backend, CMS, and ecommerce experience.',
                    'Practical problem-solving under real client constraints.',
                    'Long-term support and improvement mindset.',
                ],
            ],
        ];

        $specific = $byCategory[$category] ?? [];
        $narrative = array_replace($base, $specific);
        $narrative['case_study_intro'] = $title . ': ' . $narrative['case_study_intro'];
        $narrative['project_host'] = $host;

        return $narrative;
    }

    public function detailsRedirect(string $slug): RedirectResponse
    {
        return redirect('/portfolio-details.php?slug=' . urlencode($slug));
    }
}
