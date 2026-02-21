<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use stdClass;

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

    public function details(Request $request, ?string $slug = null): View
    {
        $canonicalBase = rtrim((string) (app()->environment('local')
            ? url('/')
            : (config('regions.regions.uk.base_url') ?: url('/'))), '/');

        $staticPortfolio = $this->resolveStaticPortfolio(
            trim((string) $request->query('tab', '')),
            (int) $request->query('item', 0)
        );

        if ($staticPortfolio instanceof stdClass) {
            $seoTitle = $staticPortfolio->title;
            $seoDescription = Str::limit(strip_tags((string) $staticPortfolio->excerpt), 160, '');

            $seoOverride = [
                'title' => $seoTitle,
                'description' => $seoDescription,
                'keywords' => 'uk project case study, web development project uk, crm implementation uk',
                'canonical' => $canonicalBase . '/portfolio',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
                'related_links' => [
                    '/portfolio',
                    '/services',
                    '/pricing',
                    '/contact',
                ],
                'og_title' => $seoTitle,
                'og_description' => $seoDescription,
                'og_image' => asset($staticPortfolio->image_path),
                'twitter_title' => $seoTitle,
                'twitter_description' => $seoDescription,
                'twitter_image' => asset($staticPortfolio->image_path),
            ];

            return view('pages.portfolio-details', [
                'portfolio' => $staticPortfolio,
                'previousPortfolio' => null,
                'nextPortfolio' => null,
                'relatedPortfolios' => collect(),
                'caseNarrative' => $this->buildCaseNarrative($staticPortfolio),
                'seoOverride' => $seoOverride,
            ]);
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

        if (!$portfolio) {
            $portfolio = (clone $baseQuery)->firstOrFail();
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
            'type' => 'Article',
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

    private function resolveStaticPortfolio(string $tab, int $item): ?stdClass
    {
        if ($tab === '' || $item < 1) {
            return null;
        }

        $tabs = [
            'wordpress' => ['label' => 'WordPress', 'folder' => 'WordPress', 'urls' => [
                'https://www.tlcplumbing.com/',
                'https://cardiffpest.co.uk/',
                'https://www.abathhouse.com/',
                'https://allpawsvet.com/',
                'https://beaverbrookah.com/',
                'https://thecolourloungesalon.com/',
                'https://foxmarin.ca/',
                'https://www.halcyonhealth.us/',
                'https://goblueox.com/',
                'https://www.goldmedalservice.com/',
                'https://prestigedetailingco.com/',
                'https://bathpestcontrollers.co.uk/',
            ]],
            'shopify' => ['label' => 'Shopify', 'folder' => 'Shopify', 'urls' => [
                'https://kyliecosmetics.com/en-ae',
                'https://www.gymshark.com/',
                'https://colourpop.com/',
                'https://skincarestore.com.co/',
                'https://graflantz.com/',
                'https://nerdynuts.com/',
                'https://www.allbirds.com/',
            ]],
            'wix' => ['label' => 'Wix', 'folder' => 'Wix', 'urls' => [
                'https://gannonchess.wixsite.com/',
                'https://risdcareers.wixsite.com/design-review/2016?utm_source=chatgpt.com',
                'https://www.canva.com/design/DAGx6qeJGHI/rGeJ3m1PnVCkM-B20-V-dQ/edit',
            ]],
            'webflow' => ['label' => 'Webflow', 'folder' => 'Webflow', 'urls' => [
                'https://imo2017.webflow.io/',
                'https://uwdesign2017.webflow.io/',
            ]],
            'custom-coding' => ['label' => 'Custom Coding', 'folder' => 'Custom', 'urls' => [
                'https://www.getonce.com/#referrer=https%3A%2F%2Fdemo.torontobytes.com%2F&sso_redirect=true',
                'https://www.getthursday.com/',
                'https://www.adaline.ai/',
                'https://finotivefunding.com/',
                'https://4proptrader.com/',
                'https://traivend.com/',
            ]],
            'crm' => ['label' => 'CRM', 'folder' => 'Custom', 'urls' => [
                'https://traivend.com/',
            ]],
            'landing-page' => ['label' => 'Landing Pages', 'folder' => 'Landing', 'urls' => [
                'https://www.loxclubapp.com/',
                'https://www.butter.us/',
            ]],
            'fiverr' => ['label' => 'Fiverr', 'folder' => 'Custom', 'urls' => [
                'https://www.fiverr.com/users/anasjutt244/portfolio/',
            ]],
        ];
        $copyMap = [
            'wordpress' => [
                'excerpt' => 'WordPress website delivery focused on fast loading, local SEO readiness, and conversion-focused service pages.',
                'description' => 'This WordPress case study covers UX structure, performance setup, on-page SEO basics, and admin-friendly content workflows for long-term growth.',
            ],
            'shopify' => [
                'excerpt' => 'Shopify ecommerce build optimized for product discovery, checkout conversion, and mobile buying experience.',
                'description' => 'This Shopify case study explains catalogue structure, product page UX, checkout optimization, and practical store operations for UK ecommerce goals.',
            ],
            'wix' => [
                'excerpt' => 'Wix business website setup aligned with service visibility, trust messaging, and lead generation flow.',
                'description' => 'This Wix case study outlines content architecture, conversion sections, local search intent alignment, and easy owner-side updates.',
            ],
            'webflow' => [
                'excerpt' => 'Webflow project with modern UI system, clear page hierarchy, and conversion-led interactions.',
                'description' => 'This Webflow case study highlights component-based layout, responsive behaviour, brand consistency, and growth-ready landing architecture.',
            ],
            'custom-coding' => [
                'excerpt' => 'Custom-coded platform engineered for business workflows, secure logic, and scalable feature growth.',
                'description' => 'This custom development case study covers requirement mapping, architecture decisions, QA process, and production-ready rollout standards.',
            ],
            'crm' => [
                'excerpt' => 'CRM project focused on lead tracking, team workflow automation, and client pipeline visibility.',
                'description' => 'This CRM case study explains contact lifecycle setup, activity tracking, pipeline controls, and operational reporting for day-to-day management.',
            ],
            'landing-page' => [
                'excerpt' => 'Landing page build designed for campaign traffic, message clarity, and high-intent conversion actions.',
                'description' => 'This landing page case study covers offer positioning, CTA hierarchy, trust blocks, and performance-focused lead capture flow.',
            ],
            'fiverr' => [
                'excerpt' => 'Professional Fiverr portfolio with consistent delivery record, multiple completed projects, and repeat-client trust.',
                'description' => 'Active on Fiverr since 2017, this portfolio highlights practical delivery across business websites, ecommerce builds, CRM implementations, and growth-focused digital projects.',
            ],
        ];

        if (!isset($tabs[$tab])) {
            return null;
        }

        $tabData = $tabs[$tab];
        $url = $tabData['urls'][$item - 1] ?? null;
        if (!$url) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST) ?: $url;
        $title = $tabData['label'] . ' Project ' . $item;
        $image = 'assets/Portfolio/' . $tabData['folder'] . '/' . $item . '.avif';
        $image2 = null;
        $image3 = null;
        if ($tab === 'crm' && $item === 1) {
            $image = 'assets/Portfolio/Crm/continentdispo/crm-main.avif';
            $image2 = 'assets/Portfolio/Crm/continentdispo/crm-1.avif';
            $image3 = 'assets/Portfolio/Crm/continentdispo/crm-2.avif';
        } elseif ($tab === 'fiverr' && $item === 1) {
            $image = 'assets/images/project/portfolio-page-1-1.jpg';
        }

        $copy = $copyMap[$tab] ?? [
            'excerpt' => 'Business-focused digital delivery with measurable commercial outcomes.',
            'description' => 'This project covers structured implementation, practical UX improvements, and conversion-focused delivery standards.',
        ];

        return (object) [
            'id' => null,
            'slug' => null,
            'tab' => $tab,
            'item' => $item,
            'title' => $title,
            'excerpt' => $copy['excerpt'] . ' Live project: ' . preg_replace('/^www\./', '', (string) $host) . '.',
            'description' => $copy['description'],
            'project_url' => $url,
            'image_path' => $image,
            'image_path_2' => $image2,
            'image_path_3' => $image3,
            'client_name' => preg_replace('/^www\./', '', (string) $host),
            'category' => $tabData['label'],
            'created_at' => null,
            'updated_at' => null,
            'is_published' => true,
        ];
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
            'wix' => [
                'challenge' => 'The site needed better service credibility and clearer action paths for first-time visitors.',
                'approach' => 'We redesigned key sections, tightened copy blocks, and optimized layout flow for faster decision-making.',
                'result' => 'The Wix website now communicates value faster and supports a cleaner lead generation experience.',
                'highlights' => [
                    'Service-first section design with clearer flow.',
                    'Improved trust blocks for faster user confidence.',
                    'Mobile responsive tuning for better usability.',
                    'Owner-side editing made simpler and safer.',
                ],
            ],
            'webflow' => [
                'challenge' => 'The project required a premium visual system without sacrificing clarity or conversion performance.',
                'approach' => 'We structured reusable components, refined interaction balance, and optimized content readability across devices.',
                'result' => 'The final Webflow build delivers modern brand presentation with strong usability and growth-ready structure.',
                'highlights' => [
                    'Reusable component system for consistency.',
                    'Interaction effects tuned for clarity, not noise.',
                    'Strong responsive behavior across breakpoints.',
                    'Content hierarchy aligned with conversion goals.',
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
            'landing pages' => [
                'challenge' => 'Campaign traffic needed a focused page structure that converts quickly without content noise.',
                'approach' => 'We built offer-first sections, tightened headline hierarchy, and optimized CTA order for high-intent users.',
                'result' => 'The landing pages now support faster decision making and stronger lead form completion rates.',
                'highlights' => [
                    'Offer clarity above the fold.',
                    'CTA sequencing based on intent progression.',
                    'Trust blocks integrated near conversion points.',
                    'Mobile-first structure for ad traffic behavior.',
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
