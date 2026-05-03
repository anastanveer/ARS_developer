@php
    $servicePageImage = \App\Support\ServicePageImages::get('search-engine-optimization');
    $page_title = 'Search Engine Optimization';
    $seoOverride = [
        'title' => 'SEO Services UK | Trusted Technical SEO and AI Search Growth',
        'description' => 'UK-based SEO services focused on technical SEO, AI search visibility, scalable solutions, and secure systems that help businesses earn stronger rankings and enquiries.',
        'keywords' => 'seo services uk, ai seo services uk, answer engine optimization uk, aeo services uk, technical seo services uk, search engine optimization uk, local seo uk, search console optimization uk, on page seo uk',
        'related_links' => [
            '/pricing',
            '/contact',
            '/seo-hub',
            '/uk-growth-hub',
            '/blog/uk-seo-growth-system-2026-aeo-geo-eeat-guide',
            '/blog/google-search-console-insights-uk-how-to-find-easy-seo-wins-faster',
            '/blog/answer-engine-optimization-uk-how-service-businesses-structure-content-for-ai-search',
            '/blog/seo-company-stoke-on-trent-for-small-businesses-what-actually-drives-enquiries',
        ],
        'faq_items' => [
            [
                'question' => 'What is usually the fastest SEO or AI search improvement for a UK service website?',
                'answer' => 'The fastest wins usually come from fixing page intent, metadata, internal links, and technical blockers on URLs that are already earning impressions.',
            ],
            [
                'question' => 'Do you improve local and national SEO together?',
                'answer' => 'Yes. We align local-commercial pages, service clusters, and broader topic authority so rankings support real enquiry growth.',
            ],
            [
                'question' => 'Does Search Console data shape the SEO roadmap?',
                'answer' => 'Yes. We prioritise pages and keywords already showing visibility so updates are driven by real query demand rather than guesswork.',
            ],
        ],
    ];
@endphp
@include('layouts.header')
<style>
    .service-trust-panel {
        margin: 28px 0;
        border: 1px solid #d9e7fb;
        border-radius: 20px;
        background: linear-gradient(180deg, #fbfdff 0%, #f3f8ff 100%);
        padding: 24px;
        box-shadow: 0 16px 30px rgba(16, 42, 77, 0.06);
    }

    .service-trust-panel__grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-top: 16px;
    }

    .service-trust-panel__item {
        padding: 18px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #dce8fb;
    }

    .service-trust-panel__item h4 {
        margin: 0 0 8px;
        color: #123561;
        font-size: 18px;
    }

    .service-trust-panel__item p {
        margin: 0;
        color: #5a7091;
        line-height: 1.7;
    }

    @media (max-width: 991px) {
        .service-trust-panel__grid {
            grid-template-columns: 1fr;
        }
    }

    /* --- 2026 Advanced SEO Content Blocks --- */
    .seo-problem-block {
        margin: 28px 0;
        padding: 26px 28px;
        background: linear-gradient(135deg, #fff8f8 0%, #fff 100%);
        border-left: 4px solid #e03737;
        border-radius: 0 14px 14px 0;
        box-shadow: 0 4px 18px rgba(224,55,55,0.06);
    }
    .seo-problem-block__title {
        color: #1a2940;
        font-size: 19px;
        font-weight: 700;
        margin: 0 0 12px;
    }
    .seo-problem-block__lead { color: #3a5270; margin-bottom: 14px; line-height: 1.75; }
    .seo-problem-block__list { padding: 0; list-style: none; margin: 0; }
    .seo-problem-block__list li {
        padding: 11px 0 11px 22px;
        position: relative;
        border-bottom: 1px solid #f5e8e8;
        color: #3a5270;
        line-height: 1.75;
    }
    .seo-problem-block__list li:last-child { border-bottom: none; }
    .seo-problem-block__list li::before { content: "→"; position: absolute; left: 0; color: #e03737; font-weight: 700; }
    .seo-aeo-block {
        margin: 28px 0;
        padding: 26px 28px;
        background: linear-gradient(135deg, #f0f7ff 0%, #fff 100%);
        border-left: 4px solid #1d6bf3;
        border-radius: 0 14px 14px 0;
        box-shadow: 0 4px 18px rgba(29,107,243,0.06);
    }
    .seo-aeo-block__title { color: #0f2749; font-size: 19px; font-weight: 700; margin: 0 0 12px; }
    .seo-aeo-block__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 16px; }
    .seo-aeo-block__item { padding: 16px; background: #fff; border: 1px solid #d0e4fb; border-radius: 10px; }
    .seo-aeo-block__item strong { display: block; color: #0f2749; margin-bottom: 6px; font-size: 15px; }
    .seo-aeo-block__item p { margin: 0; color: #4a6585; font-size: 14px; line-height: 1.65; }
    .seo-process-block {
        margin: 28px 0;
        padding: 26px 28px;
        background: #f8fbff;
        border: 1px solid #d0e4fb;
        border-radius: 14px;
    }
    .seo-process-block__title { color: #0f2749; font-size: 19px; font-weight: 700; margin: 0 0 18px; }
    .seo-process-block ol { padding: 0; counter-reset: seo-steps; list-style: none; margin: 0; }
    .seo-process-block ol li {
        counter-increment: seo-steps;
        padding: 14px 14px 14px 56px;
        position: relative;
        border-bottom: 1px solid #e4eefb;
        color: #3a5270;
        line-height: 1.75;
    }
    .seo-process-block ol li:last-child { border-bottom: none; }
    .seo-process-block ol li::before {
        content: counter(seo-steps);
        position: absolute; left: 12px; top: 12px;
        width: 30px; height: 30px;
        background: #1d6bf3; color: #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700;
    }
    .seo-process-block ol li strong { color: #0f2749; }
    .seo-audit-cta {
        margin: 28px 0;
        padding: 34px 32px;
        background: linear-gradient(135deg, #0f2749 0%, #1652b8 100%);
        border-radius: 20px;
        text-align: center;
    }
    .seo-audit-cta__eyebrow {
        display: inline-block; padding: 5px 14px;
        background: rgba(255,255,255,0.15);
        color: #a8d4ff; font-size: 13px;
        border-radius: 20px; margin-bottom: 14px; font-weight: 600;
    }
    .seo-audit-cta h3 { color: #fff; font-size: 22px; margin: 0 0 12px; }
    .seo-audit-cta p { color: rgba(255,255,255,0.82); margin: 0 0 22px; line-height: 1.75; }
    .seo-audit-cta__list {
        display: flex; flex-wrap: wrap; gap: 10px;
        justify-content: center; margin: 0 0 22px; padding: 0; list-style: none;
    }
    .seo-audit-cta__list li {
        padding: 6px 14px;
        background: rgba(255,255,255,0.12);
        color: #e0f0ff;
        border-radius: 20px; font-size: 13px;
    }
    .seo-audit-cta__btn {
        display: inline-block; padding: 14px 34px;
        background: #fff; color: #0f2749;
        font-weight: 700; border-radius: 8px;
        text-decoration: none; font-size: 15px;
        transition: background 0.2s, color 0.2s;
        margin-right: 12px;
    }
    .seo-audit-cta__btn:hover { background: #d6e8ff; color: #0f2749; text-decoration: none; }
    .seo-audit-cta__note { display: block; margin-top: 12px; color: rgba(255,255,255,0.55); font-size: 13px; }
    .seo-topical-links {
        margin: 24px 0;
        padding: 22px 24px;
        background: #f8fbff;
        border: 1px solid #d0e4fb;
        border-radius: 14px;
    }
    .seo-topical-links__title { color: #0f2749; font-size: 15px; font-weight: 700; margin: 0 0 14px; }
    .seo-topical-links ul {
        list-style: none; padding: 0; margin: 0;
        display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    }
    .seo-topical-links ul li a {
        color: #1d6bf3; text-decoration: none; font-size: 14px;
        display: flex; align-items: flex-start; gap: 6px; line-height: 1.5;
    }
    .seo-topical-links ul li a::before { content: "→"; flex-shrink: 0; }
    .seo-topical-links ul li a:hover { color: #0a4ab5; text-decoration: underline; }
    @media (max-width: 767px) {
        .seo-aeo-block__grid,
        .seo-topical-links ul { grid-template-columns: 1fr; }
        .seo-audit-cta { padding: 24px 18px; }
    }
</style>



        <!--Page Header Start-->
        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);">
            </div>
            <div class="page-header__shape-1">
                <img src="assets/images/shapes/page-header-shape-1.png" alt="">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <h1>Search Engine <span>Optimization</span></h1><div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="/">Home</a></li>
                            <li><span></span></li>
                            <li><a href="/services">Services</a></li>
                            <li><span></span></li>
                            <li>Search Engine Optimization</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!--Services Details Start-->
        <section class="services-details">
            <div class="container">
                <h2 class="seo-hidden-heading">UK search engine optimization service overview</h2>
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="services-details__left">
                            <div class="services-details__img">
                                <img src="{{ \App\Support\ServicePageImages::toUrl($servicePageImage['image']) }}" alt="{{ $servicePageImage['alt'] }}">
                            </div>
                            <div class="services-details__content">
                                <h3 class="services-details__title-1">UK-Based SEO and AI Search Services for Businesses That Need More Qualified Organic Leads</h3>
                                <div class="services-details__shape-1"></div>
                                <p class="services-details__text-1">Our SEO service is built for UK businesses that want stronger rankings, better click-through rates, more qualified enquiries, and stronger AI search visibility. We audit technical foundations, improve site structure, and align content with real keyword demand to increase visibility in competitive search results while supporting trusted, scalable growth.</p>
                                <!-- PROBLEM BLOCK: AEO passage-indexable, featured snippet target -->
                                <div class="seo-problem-block">
                                    <h3 class="seo-problem-block__title">Why Most UK Service Websites Earn Impressions But Not Enquiries</h3>
                                    <p class="seo-problem-block__lead">Most UK service businesses see Google impressions rising in Search Console but qualified enquiries staying flat. The gap between visibility and revenue usually comes from three specific, fixable problems:</p>
                                    <ul class="seo-problem-block__list">
                                        <li><strong>Intent mismatch:</strong> Service pages target keywords, not the real questions buyers type. Google and AI engines (Perplexity, ChatGPT, Gemini, AI Overviews) now prioritise pages that directly answer buyer questions — not pages that repeat the service name across headings.</li>
                                        <li><strong>Technical blockers:</strong> Crawl errors, poor Core Web Vitals, weak canonicalization, and orphaned service pages quietly prevent Google from understanding your site structure. These rarely show error messages but consistently suppress rankings on your most commercial URLs.</li>
                                        <li><strong>Thin authority signals:</strong> Google's 2026 quality standards use EEAT — Experience, Expertise, Authoritativeness, Trustworthiness — to compare competing pages. Without proof signals (specific project outcomes, credentials, entity coverage), service pages rank below agencies that demonstrate real delivery evidence.</li>
                                        <li><strong>Poor internal linking:</strong> When your most valuable service pages are not linked contextually from relevant content, Google crawls them infrequently and assigns lower importance — even when the content quality is strong.</li>
                                    </ul>
                                </div>

                                <h3 class="services-details__title-2">Our SEO Delivery Framework for UK Ranking Growth</h3>
                                <p class="services-details__text-2">We combine technical SEO, on-page optimisation, Search Console analysis, answer engine optimisation, AI search entity alignment, and content planning into a clear monthly workflow. Every action is prioritised by business impact so UK businesses see measurable progress in rankings, clicks, AI search visibility, and qualified enquiries.</p>
                                <div class="services-details__points-box">
                                    <ul class="services-details__points-list list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Technical SEO audit and issue prioritization</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Core Web Vitals and page speed optimization</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Search intent mapping for service keywords</p>
                                        </li>
                                    </ul>
                                    <ul class="services-details__points-list list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>On-page SEO with optimized headings and metadata</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Internal linking and crawl-depth improvements</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Monthly reporting with traffic and ranking movement</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="services-details__single-service-box">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-1.png" alt="">
                                                </div>
                                                <p>Keyword Cluster<br> Strategy</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-2.png" alt="">
                                                </div>
                                                <p>Competitor SEO<br> Gap Analysis </p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-3.png" alt="">
                                                </div>
                                                <p>Search Console<br> Optimization</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-4.png" alt="">
                                                </div>
                                                <p>Schema and Indexing<br> Control</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="services-details__progress-box">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="services-details__progress-left">
                                                <h3 class="services-details__progress-left-title">SEO Performance Indicators That Support Commercial Growth</h3>
                                                <p class="services-details__progress-left-text">Our audits focus on the
                                                    highest-impact fixes first, then move into sustained content,
                                                    entity authority, and internal linking improvement. This approach helps reduce ranking volatility and
                                                    supports long-term search visibility for UK-based businesses.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="services-details__progress-right">
                                                <ul class="services-details__progress-list list-unstyled">
                                                    <li>
                                                        <div class="progress-levels">
                                                            <!--Skill Box-->
                                                            <div class="progress-box">
                                                                <div class="inner count-box">
                                                                    <div class="text">Technical Fix Completion</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="96">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="96">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="progress-levels">
                                                            <!--Skill Box-->
                                                            <div class="progress-box">
                                                                <div class="inner count-box">
                                                                    <div class="text">Crawl Efficiency Gain</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="84">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="84">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="progress-levels">
                                                            <!--Skill Box-->
                                                            <div class="progress-box">
                                                                <div class="inner count-box">
                                                                    <div class="text">Ranking and AEO Coverage</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="88">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="88">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- AEO / GEO BLOCK: AI search optimization, entity-rich, passage-rankable -->
                                <div class="seo-aeo-block">
                                    <h3 class="seo-aeo-block__title">What AI Search Engines Check in 2026 (AEO and GEO)</h3>
                                    <p style="color:#3a5270;line-height:1.75;margin-bottom:14px;">Answer Engine Optimization (AEO) and Generative Engine Optimization (GEO) are the two disciplines that now sit alongside traditional Google SEO. AEO structures content so Google AI Overviews, Bing Copilot, and AI answer engines cite your website as the authoritative source. GEO ensures that when ChatGPT, Perplexity, Claude, or Gemini answer UK business queries, your agency appears as a credible referenced source.</p>
                                    <div class="seo-aeo-block__grid">
                                        <div class="seo-aeo-block__item">
                                            <strong>AEO — Answer Engine Optimization</strong>
                                            <p>Structure content with direct answer paragraphs, FAQ schema, and entity-rich headings so Google AI Overviews extract and cite your pages as the definitive answer for UK service queries.</p>
                                        </div>
                                        <div class="seo-aeo-block__item">
                                            <strong>GEO — Generative Engine Optimization</strong>
                                            <p>Build brand entity signals, authoritative citations, and consistent structured data so LLMs (ChatGPT, Gemini, Perplexity) recognise and reference ARSDeveloper as a trusted UK software and SEO agency.</p>
                                        </div>
                                        <div class="seo-aeo-block__item">
                                            <strong>EEAT Authority Signals</strong>
                                            <p>Experience and Expertise signals — specific delivery outcomes, industry credentials, team expertise, and verifiable proof — that elevate page quality scores above generic competitor content.</p>
                                        </div>
                                        <div class="seo-aeo-block__item">
                                            <strong>Topical Authority Clusters</strong>
                                            <p>Pillar pages connected to supporting blog posts, sector pages, and internal resources so Google understands your agency holds genuine depth of knowledge across your service areas.</p>
                                        </div>
                                    </div>
                                    <p style="margin:14px 0 0;color:#4a6585;font-size:14px;">We implement AEO and GEO alongside traditional technical SEO so your visibility expands across both classic search and AI-assisted discovery. Read the full guide on our <a href="/uk-growth-hub" style="color:#1d6bf3;">UK SEO Growth Hub</a>.</p>
                                </div>

                                <!-- PROCESS BLOCK: Numbered steps = featured snippet target -->
                                <div class="seo-process-block">
                                    <h3 class="seo-process-block__title">How We Improve UK SEO Rankings — 6-Step Delivery Process</h3>
                                    <ol>
                                        <li><strong>Technical Audit:</strong> Full crawl to identify indexation blockers, Core Web Vitals failures, canonical errors, duplicate content issues, and internal link gaps that silently limit rankings.</li>
                                        <li><strong>Search Console Analysis:</strong> Identify pages already earning impressions but not converting to clicks — these have the highest quick-win potential and get prioritised first.</li>
                                        <li><strong>Buyer Intent Mapping:</strong> Map every service page to the real questions UK buyers type into Google, AI search tools, and voice assistants — not just keyword volume data.</li>
                                        <li><strong>On-Page and AEO Upgrades:</strong> Rewrite titles, meta descriptions, headings, and body content to match search intent, answer-first structure, and AI citation patterns. Add FAQ schema, structured data, and entity coverage.</li>
                                        <li><strong>Internal Linking and Authority Architecture:</strong> Connect service pages, blog posts, sector pages, and the <a href="/uk-growth-hub">UK Growth Hub</a> with contextual anchors so topical authority builds across the whole site.</li>
                                        <li><strong>Monthly Reporting and Iteration:</strong> Track ranking movement, click-through rate improvement, and qualified enquiry growth. Every month produces a prioritised action plan based on real Search Console movement.</li>
                                    </ol>
                                </div>

                                <h3 class="services-details__title-3">Why Our SEO Strategy Works</h3>
                                <p class="services-details__text-3">We treat SEO as a business growth system, not only a
                                    rankings exercise. Technical foundations, content architecture, and conversion intent
                                    are handled together, so traffic quality improves with volume.</p>
                                <div class="services-details__points-and-img">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <ul class="services-details__points-1 list-unstyled">
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Keyword strategy tailored to your service profitability</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Clear priority roadmap with transparent monthly updates</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>On-page updates built around user intent and SERP behavior</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Practical recommendations your team can implement quickly</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="services-details__points-img">
                                                <img src="assets/images/services/services-details-points-img-1.jpg"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="services-details__text-4">Our SEO service supports local SEO, national
                                    visibility, and international keyword targeting. Whether you are targeting UK, USA,
                                    Germany, Canada, India, or Pakistan segments, we build content and technical paths
                                    that match each market.</p>
                                <div class="service-trust-panel">
                                    <h3 class="services-details__title-2" style="margin-bottom:8px;">What UK Businesses Value Most in SEO Delivery</h3>
                                    <p class="services-details__text-2" style="margin-bottom:0;">We focus on pages and fixes that improve commercial visibility first, so SEO work stays tied to enquiries and not just reporting noise.</p>
                                    <div class="service-trust-panel__grid">
                                        <div class="service-trust-panel__item">
                                            <h4>Search Console Led</h4>
                                            <p>We prioritise opportunities already showing impressions and movement.</p>
                                        </div>
                                        <div class="service-trust-panel__item">
                                            <h4>Commercial Intent Focus</h4>
                                            <p>Service pages, metadata, and internal links are aligned to lead generation goals.</p>
                                        </div>
                                        <div class="service-trust-panel__item">
                                            <h4>Technical Stability</h4>
                                            <p>Canonical, schema, crawl, and performance hygiene stay part of the strategy.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="services-details__img-two">
                                    <img src="assets/images/services/services-details-img-2.jpg" alt="">
                                </div>

                                <!-- TOPICAL CLUSTER LINKS: Contextual internal links for authority signals -->
                                <div class="seo-topical-links">
                                    <p class="seo-topical-links__title">Related SEO Guides and UK Growth Resources</p>
                                    <ul>
                                        <li><a href="/uk-growth-hub">UK SEO Growth System 2026 — AEO, GEO and EEAT Full Playbook</a></li>
                                        <li><a href="/blog/answer-engine-optimization-uk-how-service-businesses-structure-content-for-ai-search">How UK Businesses Structure Content for AI Search (AEO Guide)</a></li>
                                        <li><a href="/blog/google-search-console-insights-uk-how-to-find-easy-seo-wins-faster">Find Easy SEO Wins Faster Using Search Console Data</a></li>
                                        <li><a href="/blog/seo-company-stoke-on-trent-for-small-businesses-what-actually-drives-enquiries">What Actually Drives SEO Enquiries for UK Small Businesses</a></li>
                                        <li><a href="/sectors/healthcare">Healthcare Website SEO — Clinics and Private Practices UK</a></li>
                                        <li><a href="/sectors/law-firms">Law Firm SEO — Solicitors and Legal Services UK</a></li>
                                        <li><a href="/sectors/ecommerce">Ecommerce SEO — Shopify and WooCommerce UK</a></li>
                                        <li><a href="/pricing">SEO Service Pricing — UK Packages and Delivery Tiers</a></li>
                                    </ul>
                                </div>

                                <!-- FREE AUDIT CTA: High-conversion, specific offer -->
                                <div class="seo-audit-cta">
                                    <span class="seo-audit-cta__eyebrow">Free Website Audit — No Obligation</span>
                                    <h3>Get a Prioritised SEO Action Plan for Your UK Website</h3>
                                    <p>Send your website URL and top 3 services. We will analyse your technical foundations, Search Console data, keyword positions, content gaps, and AI search visibility — then return a prioritised action plan covering what to fix first, what to improve, and what will move rankings fastest.</p>
                                    <ul class="seo-audit-cta__list">
                                        <li>Technical SEO audit</li>
                                        <li>Search Console gap analysis</li>
                                        <li>Content intent review</li>
                                        <li>90-day priority roadmap</li>
                                    </ul>
                                    <a href="/contact" class="seo-audit-cta__btn">Request Your Free SEO Audit</a>
                                    <span class="seo-audit-cta__note">Delivered within 2–3 business days. No sales calls required.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="services-details__right">
                            <div class="services-details__more-services">
                                <h3>More Services</h3>
                                <span></span>
                                <ul class="services-details__more-services-list list-unstyled">
                                    <li>
                                        <div class="icon">
                                            <img src="assets/images/icon/services-details-more-services-icon.png"
                                                alt="">
                                        </div>
                                        <p><a href="/digital-marketing">Digital Marketing </a></p>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <img src="assets/images/icon/services-details-more-services-icon.png"
                                                alt="">
                                        </div>
                                        <p><a href="/web-design-development">Web Design & Development</a></p>
                                    </li>
                                    <li class="active">
                                        <div class="icon">
                                            <img src="assets/images/icon/services-details-more-services-icon.png"
                                                alt="">
                                        </div>
                                        <p><a href="/search-engine-optimization">Search Engine Optimization</a></p>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <img src="assets/images/icon/services-details-more-services-icon.png"
                                                alt="">
                                        </div>
                                        <p><a href="/design-and-branding">Design & Branding</a></p>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <img src="assets/images/icon/services-details-more-services-icon.png"
                                                alt="">
                                        </div>
                                        <p><a href="/app-development">App Development</a></p>
                                    </li>
                                </ul>
                            </div>
                            <div class="services-details__contact-box">
                                <h3>Plan Your SEO Growth Strategy</h3>
                                <span></span>
                                <p class="services-details__contact-text">Need a technical SEO and speed audit for your
                                    website? Message us and get a clear action plan.</p>
                                <div class="services-details__contact-btn-box">
                                    <a href="/contact" class="services-details__contact-btn thm-btn"><i
                                            class="icon-right"></i>Send
                                        Message</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Services Details End-->



        <!--CTA One Start -->
        <section class="cta-one cta-two">
            <div class="container">
                <div class="cta-one__inner">
                    <div class="cta-one__img">
                        <img src="assets/images/resources/cta-one-img-1.png" alt="">
                    </div>
                    <div class="cta-one__inner-content">
                        <div class="cta-one__shape-bg"
                            style="background-image: url(assets/images/shapes/cta-one-shape-bg.png);"></div>
                        <h3 class="cta-one__title">Start your journey with our <br> exceptional services.</h3>
                        <div class="cta-one__btn">
                            <a href="/contact">Book an SEO Strategy Call <span class=" icon-right-arrow-1"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--CTA One End -->
@include('layouts.footer')
