@php
    $page_title  = $page['title'] ?? $page['nav_label'] ?? 'Service';
    $ukBase      = app()->environment('local')
        ? rtrim(url('/'), '/')
        : rtrim((string) config('regions.regions.uk.base_url', url('/')), '/');
    $geo         = $page['geo'] ?? ['region' => 'GB', 'placename' => 'United Kingdom', 'lat' => '53.0027', 'lng' => '-2.1794'];
    $slug        = $page['slug'] ?? request()->segment(1);
    $pageUrl     = $ukBase . '/' . $slug;
    $navLabel    = $page['nav_label'] ?? $page['title'];
    $aggRating   = $seoOverride['aggregate_rating'] ?? null;  // ['rating' => 4.9, 'count' => 12]
    $cityWikiMap = [
        'London'              => 'https://www.wikidata.org/wiki/Q84',
        'Manchester'          => 'https://www.wikidata.org/wiki/Q18125',
        'Birmingham'          => 'https://www.wikidata.org/wiki/Q2256',
        'Stoke-on-Trent'      => 'https://www.wikidata.org/wiki/Q193266',
        'Leeds'               => 'https://www.wikidata.org/wiki/Q211294',
        'Sheffield'           => 'https://www.wikidata.org/wiki/Q42448',
        'Bristol'             => 'https://www.wikidata.org/wiki/Q23154',
        'Glasgow'             => 'https://www.wikidata.org/wiki/Q4093',
        'Edinburgh'           => 'https://www.wikidata.org/wiki/Q23436',
        'Liverpool'           => 'https://www.wikidata.org/wiki/Q24826',
        'Cardiff'             => 'https://www.wikidata.org/wiki/Q1093',
        'Nottingham'          => 'https://www.wikidata.org/wiki/Q41262',
        'Newcastle upon Tyne' => 'https://www.wikidata.org/wiki/Q1425412',
        'Leicester'           => 'https://www.wikidata.org/wiki/Q44306',
        'Coventry'            => 'https://www.wikidata.org/wiki/Q48759',
        'United Kingdom'      => 'https://www.wikidata.org/wiki/Q145',
    ];
    $placename      = $geo['placename'] ?? 'United Kingdom';
    $cityWikiSameAs = $cityWikiMap[$placename] ?? $cityWikiMap['United Kingdom'];
    $isCityPage     = !in_array($placename, ['United Kingdom'], true);
    $howToSteps = [
        ['name' => 'Book a Free Consultation', 'text' => 'Complete the contact form or book a strategy call. We review your requirements and respond within one business day with next-step recommendations.'],
        ['name' => 'Receive a Scoped Proposal', 'text' => 'We prepare a detailed proposal covering scope, delivery timeline, milestones, and GBP pricing. No open-ended retainers — every project has a clear contract.'],
        ['name' => 'Milestone-Based Delivery', 'text' => 'Work is delivered in structured milestones with review and approval at each stage. You maintain full visibility and sign off before the next phase begins.'],
        ['name' => 'Launch and Post-Launch Support', 'text' => 'We handle launch, post-launch monitoring, and hand over full documentation. Monthly growth and maintenance support available after launch.'],
    ];
@endphp
@include('layouts.header')

<style>
    .svc-hero {
        padding: 110px 0 86px;
    }

    .svc-hero__wrap {
        border: 1px solid #d9e7fb;
        border-radius: 28px;
        background:
            radial-gradient(circle at top right, rgba(15, 127, 233, 0.12), transparent 28%),
            linear-gradient(180deg, #fbfdff 0%, #f3f9ff 100%);
        box-shadow: 0 22px 44px rgba(16, 42, 77, 0.08);
        overflow: hidden;
    }

    .svc-hero__main,
    .svc-hero__aside {
        padding: 34px;
    }

    .svc-hero__main {
        border-right: 1px solid #e0ebfb;
    }

    .svc-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(15, 127, 233, 0.1);
        color: #0f63bd;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .svc-hero__title {
        margin: 0 0 14px;
        color: #102a4d;
        font-size: 38px;
        line-height: 1.1;
        max-width: 760px;
    }

    .svc-hero__desc {
        margin: 0 0 22px;
        color: #5a7091;
        font-size: 17px;
        line-height: 1.8;
        max-width: 760px;
    }

    .svc-hero__best-for {
        margin-bottom: 22px;
    }

    .svc-hero__best-for h4 {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #0f63bd;
        margin: 0 0 12px;
    }

    .svc-hero__best-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .svc-hero__chip {
        padding: 7px 14px;
        border-radius: 999px;
        background: #eef5ff;
        color: #123561;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #cddcf7;
    }

    .svc-hero__aside {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .svc-hero__cta,
    .svc-hero__trust {
        padding: 22px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid #dce8fb;
    }

    .svc-hero__cta h3,
    .svc-hero__trust h3 {
        margin: 0 0 10px;
        color: #102a4d;
        font-size: 20px;
    }

    .svc-hero__cta p,
    .svc-hero__trust p,
    .svc-hero__trust li {
        color: #5a7091;
        line-height: 1.75;
        font-size: 14px;
    }

    .svc-hero__trust ul {
        margin: 10px 0 0;
        padding-left: 18px;
    }

    .svc-hero__trust li + li {
        margin-top: 6px;
    }

    .svc-hero__cta-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .svc-hero__link-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: #eef5ff;
        color: #123561;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
    }

    /* ── Problems + Features ──────────────────────────── */
    .svc-section {
        padding: 60px 0;
    }

    .svc-section + .svc-section {
        padding-top: 0;
    }

    .svc-section__title {
        font-size: 26px;
        color: #102a4d;
        margin: 0 0 24px;
        font-weight: 700;
    }

    .svc-problem-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .svc-problem-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 14px;
        background: #fff8f8;
        border: 1px solid #fde8e8;
        color: #5a3a3a;
        font-size: 15px;
        line-height: 1.6;
    }

    .svc-problem-list li::before {
        content: '✗';
        color: #e05252;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .svc-feature-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .svc-feature-card {
        padding: 18px 18px 16px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #dce8fb;
    }

    .svc-feature-card .num {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #117be8 0%, #35a3ff 100%);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .svc-feature-card p {
        margin: 0;
        color: #4f6588;
        line-height: 1.7;
        font-size: 14px;
    }

    /* ── FAQ ─────────────────────────────────────────── */
    .svc-faq {
        padding: 60px 0;
        background: #f8fbff;
    }

    .svc-faq__title {
        font-size: 26px;
        color: #102a4d;
        margin: 0 0 30px;
        font-weight: 700;
    }

    .svc-faq__item {
        border: 1px solid #dce8fb;
        border-radius: 14px;
        background: #fff;
        margin-bottom: 10px;
        overflow: hidden;
    }

    .svc-faq__q {
        width: 100%;
        text-align: left;
        padding: 18px 20px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 15px;
        font-weight: 700;
        color: #102a4d;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .svc-faq__q:hover {
        color: #0f63bd;
    }

    .svc-faq__q .icon {
        flex-shrink: 0;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #eef5ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: #0f63bd;
        transition: transform .2s;
    }

    .svc-faq__item.open .svc-faq__q .icon {
        transform: rotate(45deg);
    }

    .svc-faq__a {
        display: none;
        padding: 0 20px 18px;
        color: #5a7091;
        font-size: 14px;
        line-height: 1.8;
    }

    .svc-faq__item.open .svc-faq__a {
        display: block;
    }

    /* ── Related / CTA footer ─────────────────────────── */
    .svc-related {
        padding: 60px 0;
    }

    .svc-related__title {
        font-size: 26px;
        color: #102a4d;
        margin: 0 0 22px;
        font-weight: 700;
    }

    .svc-related__links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .svc-related__link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 999px;
        background: #eef5ff;
        color: #123561;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        border: 1px solid #cddcf7;
        transition: background .15s;
    }

    .svc-related__link:hover {
        background: #ddeaff;
        color: #0f63bd;
    }

    @media (max-width: 991px) {
        .svc-hero__main {
            border-right: 0;
            border-bottom: 1px solid #e0ebfb;
        }

        .svc-hero__title {
            font-size: 30px;
        }

        .svc-feature-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="page-header">
    <div class="page-header__bg" style="background-image: url({{ asset('assets/images/shapes/page-header-bg-shape.png') }});"></div>
    <div class="page-header__shape-1">
        <img src="{{ asset('assets/images/shapes/page-header-shape-1.png') }}" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>{{ $page['nav_label'] ?? $page['title'] }}</h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="/">Home</a></li>
                    <li><span></span></li>
                    <li>{{ $page['nav_label'] ?? $page['title'] }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ── Hero ──────────────────────────────────────────────────── --}}
<section class="svc-hero">
    <div class="container">
        <div class="svc-hero__wrap">
            <div class="row g-0">
                <div class="col-xl-8 col-lg-7">
                    <div class="svc-hero__main">
                        <span class="svc-hero__eyebrow">
                            <i class="fa fa-map-marker-alt"></i> {{ $page['eyebrow'] ?? 'UK Service' }}
                        </span>
                        <h2 class="svc-hero__title">{{ $page['hero_title'] ?? $page['title'] }}</h2>
                        <p class="svc-hero__desc">{{ $page['hero_desc'] ?? $page['meta_desc'] }}</p>

                        @if (!empty($page['best_for']))
                            <div class="svc-hero__best-for">
                                <h4>Best For</h4>
                                <div class="svc-hero__best-chips">
                                    @foreach ($page['best_for'] as $item)
                                        <span class="svc-hero__chip">{{ $item }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="svc-hero__aside">
                        <div class="svc-hero__cta">
                            <h3>Start a Project</h3>
                            <p>Tell us your current situation and goals. We will recommend the right scope, timeline, and delivery model for your business.</p>
                            <div class="svc-hero__cta-links">
                                <a href="/contact" class="services-details__contact-btn thm-btn">
                                    <i class="icon-right"></i>Book Free Consultation
                                </a>
                                <a href="/pricing" class="svc-hero__link-chip">
                                    <i class="fa fa-pound-sign"></i> View Pricing
                                </a>
                                <a href="/portfolio" class="svc-hero__link-chip">
                                    <i class="fa fa-briefcase"></i> Portfolio
                                </a>
                            </div>
                        </div>
                        <div class="svc-hero__trust">
                            <h3>Why ARS Developer Ltd</h3>
                            <ul>
                                <li>UK Companies House No. 17039150</li>
                                <li>GBP milestone contracts — clear scope</li>
                                <li>UK GDPR compliant delivery</li>
                                <li>UK timezone support, Mon–Fri 09:00–18:00</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Problems + Features ──────────────────────────────────── --}}
@if (!empty($page['problems']) || !empty($page['features']))
<section class="svc-section">
    <div class="container">
        <div class="row g-4">
            @if (!empty($page['problems']))
            <div class="col-lg-5">
                <h2 class="svc-section__title">Problems We Solve</h2>
                <ul class="svc-problem-list">
                    @foreach ($page['problems'] as $problem)
                        <li>{{ $problem }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if (!empty($page['features']))
            <div class="{{ !empty($page['problems']) ? 'col-lg-7' : 'col-12' }}">
                <h2 class="svc-section__title">What's Included</h2>
                <div class="svc-feature-grid">
                    @foreach ($page['features'] as $index => $feature)
                        <div class="svc-feature-card">
                            <span class="num">{{ $index + 1 }}</span>
                            <p>{{ $feature }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ── FAQs ─────────────────────────────────────────────────── --}}
@if (!empty($page['faqs']))
<section class="svc-faq">
    <div class="container">
        <h2 class="svc-faq__title">Frequently Asked Questions</h2>
        <div class="row">
            <div class="col-lg-9">
                @foreach ($page['faqs'] as $faq)
                <div class="svc-faq__item">
                    <button class="svc-faq__q" type="button">
                        {{ $faq['q'] }}
                        <span class="icon">+</span>
                    </button>
                    <div class="svc-faq__a">{{ $faq['a'] }}</div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <div class="svc-hero__cta" style="position:sticky;top:100px;">
                    <h3 style="font-size:16px;">Got a question?</h3>
                    <p>Our UK team is available Mon–Fri, 09:00–18:00 GMT.</p>
                    <div class="svc-hero__cta-links">
                        <a href="/contact" class="svc-hero__link-chip" style="background:#117be8;color:#fff;border-color:#117be8;">
                            <i class="fa fa-envelope"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── How We Work ──────────────────────────────────────────── --}}
<section class="svc-section" style="background:#f8fbff;padding:60px 0;">
    <div class="container">
        <h2 class="svc-section__title" style="text-align:center;margin-bottom:36px;">How We Work With UK Businesses</h2>
        <div class="row g-4 justify-content-center">
            @foreach ($howToSteps as $i => $step)
            <div class="col-lg-3 col-md-6">
                <div class="svc-feature-card" style="height:100%;text-align:center;padding:26px 20px;">
                    <span class="num" style="margin:0 auto 14px;">{{ $i + 1 }}</span>
                    <strong style="display:block;color:#102a4d;font-size:15px;margin-bottom:8px;">{{ $step['name'] }}</strong>
                    <p style="font-size:13px;color:#5a7091;">{{ $step['text'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:30px;">
            <a href="/contact" class="services-details__contact-btn thm-btn"><i class="icon-right"></i>Start a Project</a>
        </div>
    </div>
</section>

{{-- ── Related Pages ─────────────────────────────────────────── --}}
@if (!empty($page['related']))
<section class="svc-related">
    <div class="container">
        <h2 class="svc-related__title">Related Services</h2>
        <div class="svc-related__links">
            @foreach ($page['related'] as $rel)
                <a href="{{ $rel['href'] }}" class="svc-related__link">
                    <i class="fa fa-arrow-right"></i> {{ $rel['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── BreadcrumbList ───────────────────────────────────────── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    '@id'             => $pageUrl . '#breadcrumb',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',     'item' => $ukBase . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => $ukBase . '/services'],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $navLabel,  'item' => $pageUrl],
    ],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- ── Service schema (with optional AggregateRating + city LocalBusiness) ── --}}
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'    => 'https://schema.org',
    '@type'       => 'Service',
    '@id'         => $pageUrl . '#service',
    'name'        => $navLabel,
    'description' => $page['meta_desc'] ?? '',
    'serviceType' => $page['eyebrow'] ?? 'Web Development',
    'url'         => $pageUrl,
    'provider'    => ['@id' => $ukBase . '#organization'],
    'areaServed'  => $isCityPage ? [
        '@type'  => 'City',
        'name'   => $placename,
        'sameAs' => $cityWikiSameAs,
    ] : [
        '@type'  => 'Country',
        'name'   => 'United Kingdom',
        'sameAs' => 'https://www.wikidata.org/wiki/Q145',
    ],
    'hasOfferCatalog' => [
        '@type' => 'OfferCatalog',
        'name'  => $navLabel . ' Packages',
        'itemListElement' => [[
            '@type'         => 'Offer',
            'name'          => $navLabel,
            'priceCurrency' => 'GBP',
            'url'           => $pageUrl,
            'seller'        => ['@id' => $ukBase . '#organization'],
        ]],
    ],
    'aggregateRating' => $aggRating ? [
        '@type'       => 'AggregateRating',
        'ratingValue' => (string) $aggRating['rating'],
        'reviewCount' => (string) $aggRating['count'],
        'bestRating'  => '5',
        'worstRating' => '1',
    ] : null,
    'geo' => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => $geo['lat'],
        'longitude' => $geo['lng'],
    ],
]), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- ── City-specific LocalBusiness (city pages only) ─────────── --}}
@if ($isCityPage)
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'    => 'https://schema.org',
    '@type'       => ['LocalBusiness', 'ProfessionalService'],
    '@id'         => $pageUrl . '#localbusiness-city',
    'name'        => 'ARS Developer Ltd — ' . $placename,
    'url'         => $pageUrl,
    'telephone'   => config('company.phone', '+447478034328'),
    'email'       => config('company.email', 'info@arsdeveloper.co.uk'),
    'image'       => url('/assets/images/resources/ars-logo-dark.png'),
    'priceRange'  => 'GBP',
    'address'     => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => config('company.street_address', '38 Elm Street'),
        'postalCode'      => config('company.postal_code', 'ST6 2HN'),
        'addressLocality' => config('company.address_locality', 'Stoke-on-Trent'),
        'addressCountry'  => 'GB',
    ],
    'areaServed' => [
        '@type'  => 'City',
        'name'   => $placename,
        'sameAs' => $cityWikiSameAs,
    ],
    'geo' => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => $geo['lat'],
        'longitude' => $geo['lng'],
    ],
    'openingHoursSpecification' => [[
        '@type'     => 'OpeningHoursSpecification',
        'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday'],
        'opens'     => '09:00',
        'closes'    => '18:00',
    ]],
    'aggregateRating' => $aggRating ? [
        '@type'       => 'AggregateRating',
        'ratingValue' => (string) $aggRating['rating'],
        'reviewCount' => (string) $aggRating['count'],
        'bestRating'  => '5',
        'worstRating' => '1',
    ] : null,
    'parentOrganization' => ['@id' => $ukBase . '#organization'],
]), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif

{{-- ── HowTo schema ─────────────────────────────────────────── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'  => 'https://schema.org',
    '@type'     => 'HowTo',
    '@id'       => $pageUrl . '#howto',
    'name'      => 'How to hire ' . $navLabel . ' for your UK business',
    'description' => 'A step-by-step guide to commissioning ' . $navLabel . ' services from ARS Developer Ltd.',
    'totalTime' => 'PT5D',
    'step'      => array_map(static fn (array $s, int $i) => [
        '@type'    => 'HowToStep',
        'position' => $i + 1,
        'name'     => $s['name'],
        'text'     => $s['text'],
        'url'      => $pageUrl . '#step-' . ($i + 1),
    ], $howToSteps, array_keys($howToSteps)),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- ── FAQPage schema ───────────────────────────────────────── --}}
@if (!empty($page['faqs']))
<script type="application/ld+json">
{!! json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    '@id'        => $pageUrl . '#faq',
    'mainEntity' => array_map(static fn (array $f) => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ], $page['faqs']),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif

{{-- ── Speakable (AEO — AI Overview + Google Assistant citation) ── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'WebPage',
    '@id'           => $pageUrl . '#webpage',
    'speakable'     => [
        '@type'     => 'SpeakableSpecification',
        'cssSelector' => ['.svc-hero__title', '.svc-hero__desc'],
    ],
    'significantLink' => array_map(
        static fn (array $r) => $ukBase . '/' . ltrim($r['href'], '/'),
        $page['related'] ?? []
    ),
    'breadcrumb'    => ['@id' => $pageUrl . '#breadcrumb'],
    'mainEntity'    => ['@id' => $pageUrl . '#service'],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

<script>
document.querySelectorAll('.svc-faq__q').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var item = this.closest('.svc-faq__item');
        var wasOpen = item.classList.contains('open');
        document.querySelectorAll('.svc-faq__item.open').forEach(function (el) {
            el.classList.remove('open');
        });
        if (!wasOpen) {
            item.classList.add('open');
        }
    });
});
</script>

@include('layouts.footer')
