@php
    $servicePageImage = \App\Support\ServicePageImages::get('web-design-development');
    $page_title = 'Web Design Development';
    $seoOverride = [
        'title' => 'Website Design and Development UK | Trusted, Scalable Business Websites',
        'description' => 'UK-based website design and development with scalable solutions, strong trust signals, SEO-ready structure, and secure systems built to support lead generation.',
        'keywords' => 'website design and development uk, ai website development uk, website development company uk, business website design uk, conversion focused website uk, answer engine optimization website uk, web design agency uk, lead generation website uk',
        'related_links' => [
            '/pricing',
            '/portfolio',
            '/seo-hub',
            '/contact',
            '/blog/how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
            '/blog/website-development-company-stoke-on-trent-what-businesses-should-expect',
            '/blog/technical-seo-checklist-for-uk-websites-before-launch',
            '/blog/landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries',
        ],
        'faq_items' => [
            [
                'question' => 'What helps a business website rank and convert better?',
                'answer' => 'Clear page structure, fast mobile experience, strong trust sections, focused calls to action, and SEO-ready content usually improve both visibility and enquiries.',
            ],
            [
                'question' => 'Should web design include technical SEO from the start?',
                'answer' => 'Yes. Heading hierarchy, metadata, internal links, schema-ready structure, answer-first sections, and Core Web Vitals are more effective when planned during the build.',
            ],
            [
                'question' => 'Can website development support local SEO too?',
                'answer' => 'Yes. Location-relevant service pages, internal links, and trust signals help a website perform better for local commercial searches.',
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

    /* --- 2026 Advanced SEO Blocks --- */
    .seo-problem-block { margin:28px 0; padding:26px 28px; background:linear-gradient(135deg,#fff8f8 0%,#fff 100%); border-left:4px solid #e03737; border-radius:0 14px 14px 0; box-shadow:0 4px 18px rgba(224,55,55,0.06); }
    .seo-problem-block__title { color:#1a2940; font-size:19px; font-weight:700; margin:0 0 12px; }
    .seo-problem-block__lead { color:#3a5270; margin-bottom:14px; line-height:1.75; }
    .seo-problem-block__list { padding:0; list-style:none; margin:0; }
    .seo-problem-block__list li { padding:11px 0 11px 22px; position:relative; border-bottom:1px solid #f5e8e8; color:#3a5270; line-height:1.75; }
    .seo-problem-block__list li:last-child { border-bottom:none; }
    .seo-problem-block__list li::before { content:"→"; position:absolute; left:0; color:#e03737; font-weight:700; }
    .seo-process-block { margin:28px 0; padding:26px 28px; background:#f8fbff; border:1px solid #d0e4fb; border-radius:14px; }
    .seo-process-block__title { color:#0f2749; font-size:19px; font-weight:700; margin:0 0 18px; }
    .seo-process-block ol { padding:0; counter-reset:seo-steps; list-style:none; margin:0; }
    .seo-process-block ol li { counter-increment:seo-steps; padding:14px 14px 14px 56px; position:relative; border-bottom:1px solid #e4eefb; color:#3a5270; line-height:1.75; }
    .seo-process-block ol li:last-child { border-bottom:none; }
    .seo-process-block ol li::before { content:counter(seo-steps); position:absolute; left:12px; top:12px; width:30px; height:30px; background:#1d6bf3; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; }
    .seo-process-block ol li strong { color:#0f2749; }
    .seo-audit-cta { margin:28px 0; padding:34px 32px; background:linear-gradient(135deg,#0f2749 0%,#1652b8 100%); border-radius:20px; text-align:center; }
    .seo-audit-cta__eyebrow { display:inline-block; padding:5px 14px; background:rgba(255,255,255,0.15); color:#a8d4ff; font-size:13px; border-radius:20px; margin-bottom:14px; font-weight:600; }
    .seo-audit-cta h3 { color:#fff; font-size:22px; margin:0 0 12px; }
    .seo-audit-cta p { color:rgba(255,255,255,0.82); margin:0 0 22px; line-height:1.75; }
    .seo-audit-cta__list { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin:0 0 22px; padding:0; list-style:none; }
    .seo-audit-cta__list li { padding:6px 14px; background:rgba(255,255,255,0.12); color:#e0f0ff; border-radius:20px; font-size:13px; }
    .seo-audit-cta__btn { display:inline-block; padding:14px 34px; background:#fff; color:#0f2749; font-weight:700; border-radius:8px; text-decoration:none; font-size:15px; transition:background 0.2s,color 0.2s; }
    .seo-audit-cta__btn:hover { background:#d6e8ff; color:#0f2749; text-decoration:none; }
    .seo-audit-cta__note { display:block; margin-top:12px; color:rgba(255,255,255,0.55); font-size:13px; }
    .seo-topical-links { margin:24px 0; padding:22px 24px; background:#f8fbff; border:1px solid #d0e4fb; border-radius:14px; }
    .seo-topical-links__title { color:#0f2749; font-size:15px; font-weight:700; margin:0 0 14px; }
    .seo-topical-links ul { list-style:none; padding:0; margin:0; display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .seo-topical-links ul li a { color:#1d6bf3; text-decoration:none; font-size:14px; display:flex; align-items:flex-start; gap:6px; line-height:1.5; }
    .seo-topical-links ul li a::before { content:"→"; flex-shrink:0; }
    .seo-topical-links ul li a:hover { color:#0a4ab5; text-decoration:underline; }
    @media (max-width:767px) { .seo-topical-links ul { grid-template-columns:1fr; } .seo-audit-cta { padding:24px 18px; } }
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
                    <h1>Web Design & <span>Development</span></h1><div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="/">Home</a></li>
                            <li><span></span></li>
                            <li><a href="/services">Services</a></li>
                            <li><span></span></li>
                            <li>Web Design & Development</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!--Services Details Start-->
        <section class="services-details">
            <div class="container">
                <h2 class="seo-hidden-heading">UK web design and development service overview</h2>
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="services-details__left">
                            <div class="services-details__img">
                                <img src="{{ \App\Support\ServicePageImages::toUrl($servicePageImage['image']) }}" alt="{{ $servicePageImage['alt'] }}">
                            </div>
                            <div class="services-details__content">
                                <h3 class="services-details__title-1">UK-Based Website Design and Development for Businesses That Need More Enquiries</h3>
                                <div class="services-details__shape-1"></div>
                                <p class="services-details__text-1">We design and develop fast, scalable business websites for companies that need stronger enquiry generation, trust, and search visibility. From brochure sites to conversion-focused lead funnels, every build is structured for SEO, usability, and long-term maintainability, giving businesses a web development company UK partner focused on commercial outcomes.</p>
                                <!-- PROBLEM BLOCK -->
                                <div class="seo-problem-block">
                                    <h3 class="seo-problem-block__title">Why UK Business Websites Fail to Generate Enquiries — Even With Good Traffic</h3>
                                    <p class="seo-problem-block__lead">Most UK businesses invest in a new website and see visitors arrive — but qualified enquiries don't follow. The gap between traffic and leads almost always comes from the same four structural problems:</p>
                                    <ul class="seo-problem-block__list">
                                        <li><strong>No conversion architecture:</strong> Pages are designed to look professional, not to guide visitors toward a specific action. Without a clear hierarchy from landing to contact, most visitors leave without enquiring.</li>
                                        <li><strong>Weak trust signals:</strong> UK buyers in 2026 evaluate three things before contacting any agency — social proof, specific delivery evidence, and clear scope communication. Websites that skip these lose enquiries to competitors that include them.</li>
                                        <li><strong>Slow mobile performance:</strong> Google's Core Web Vitals scoring directly affects search rankings. A website that loads slowly on mobile loses both visitors and rankings — especially on commercial service queries where competitors are faster.</li>
                                        <li><strong>SEO built as an afterthought:</strong> When heading structure, metadata, internal links, and schema are added after the build is finished, they are never as effective as when they are planned into the architecture from the start.</li>
                                    </ul>
                                </div>

                                <h3 class="services-details__title-2">Core Website Delivery Capabilities for UK Business Growth</h3>
                                <p class="services-details__text-2">Our process covers strategy, UX wireframing, UI design, frontend development, CMS integration, QA, launch support, and search-ready information architecture. That gives your team a reliable website foundation that is easier to manage, easier to scale, and better aligned with commercial intent, trust, and long-term growth.</p>
                                <div class="services-details__points-box">
                                    <ul class="services-details__points-list list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Custom website architecture and user flow planning</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Responsive UI design for mobile, tablet and desktop</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Clean development with maintainable code standards</p>
                                        </li>
                                    </ul>
                                    <ul class="services-details__points-list list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>CMS setup for easy internal content management</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Technical SEO foundations and schema-ready structure</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Speed optimization and Core Web Vitals improvements</p>
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
                                                <p>Sitemap and Navigation<br> Planning</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-2.png" alt="">
                                                </div>
                                                <p>Design System<br> Implementation</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-3.png" alt="">
                                                </div>
                                                <p>Content Structure<br> and On-page SEO</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-4.png" alt="">
                                                </div>
                                                <p>Launch QA and<br> Handover</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="services-details__progress-box">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="services-details__progress-left">
                                                <h3 class="services-details__progress-left-title">Website Quality Standards That Support Better Business Outcomes</h3>
                                                <p class="services-details__progress-left-text">Our builds balance visual
                                                    quality with performance so pages load quickly, build trust, and convert more effectively.
                                                    We prioritise UX clarity, strong trust signals, and SEO-ready structure to
                                                    support sustainable lead growth for UK businesses.</p>
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
                                                                    <div class="text">Deployment Quality</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="99">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="99">
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
                                                                    <div class="text">UX Satisfaction Benchmark</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="91">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="91">
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
                                                                    <div class="text">Technical SEO Readiness</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="94">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="94">
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
                                <!-- PROCESS BLOCK: 2026 build standards, featured snippet target -->
                                <div class="seo-process-block">
                                    <h3 class="seo-process-block__title">How We Build UK Business Websites That Rank and Convert in 2026</h3>
                                    <ol>
                                        <li><strong>Discovery and Scope Planning:</strong> We map your target audience, buyer journey, service priorities, and conversion goals before any design work starts. This prevents scope drift and ensures every page has a clear commercial purpose.</li>
                                        <li><strong>SEO-First Information Architecture:</strong> Sitemap, URL structure, heading hierarchy, and internal linking are planned with search intent in mind. Pages are structured so Google and AI engines understand what each URL covers and how it connects to the rest of the site.</li>
                                        <li><strong>Conversion-Focused Design:</strong> UI design is built around trust signals, clear calls to action, credibility blocks, and frictionless enquiry paths — not just visual aesthetics. We follow <a href="/sectors/healthcare">healthcare</a>, <a href="/sectors/law-firms">legal</a>, and <a href="/sectors/ecommerce">ecommerce</a> sector conversion patterns specific to UK buyers.</li>
                                        <li><strong>Performance-Optimised Build:</strong> Code quality, image loading, caching, and Core Web Vitals pass rates are built in from development — not patched on after launch. This directly supports both rankings and user experience.</li>
                                        <li><strong>Schema and Structured Data:</strong> Organisation schema, service schema, BreadcrumbList, FAQ schema, and LocalBusiness markup are implemented so Google and AI search tools understand your entity, location, and service coverage.</li>
                                        <li><strong>Launch QA, Handover and Support:</strong> Pre-launch testing covers forms, speed, mobile rendering, analytics setup, and Search Console verification. Post-launch support ensures the site performs from day one without requiring developer dependency for routine updates.</li>
                                    </ol>
                                </div>

                                <h3 class="services-details__title-3">Why Choose ARSDeveloper for Web Development</h3>
                                <p class="services-details__text-3">You receive business-led design decisions, clean code
                                    delivery, and launch support in one engagement. We focus on conversion outcomes, not
                                    just visuals, so your site works as a sales asset from day one.</p>
                                <div class="services-details__points-and-img">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <ul class="services-details__points-1 list-unstyled">
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Design aligned to your offer, audience and conversion path</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Flexible development for future pages, features and integrations</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>SEO-focused page hierarchy with clear H1, H2 and H3 structure</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Fast support for content updates, bug fixes and improvements</p>
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
                                <p class="services-details__text-4">Our web development services suit startups, local UK
                                    businesses, and established brands planning redesigns. We can deliver brochure sites,
                                    service websites, conversion pages, and scalable CMS builds with analytics tracking.</p>
                                <div class="service-trust-panel">
                                    <h3 class="services-details__title-2" style="margin-bottom:8px;">How We Reduce Risk on UK Website Projects</h3>
                                    <p class="services-details__text-2" style="margin-bottom:0;">Visitors trust websites that feel clear and credible. Clients trust agencies that make scope, launch, and support easy to understand.</p>
                                    <div class="service-trust-panel__grid">
                                        <div class="service-trust-panel__item">
                                            <h4>Clear Sitemap Planning</h4>
                                            <p>Page structure, CTA flow, and content priorities are scoped before design decisions.</p>
                                        </div>
                                        <div class="service-trust-panel__item">
                                            <h4>SEO-Ready Build</h4>
                                            <p>Heading hierarchy, metadata logic, and internal links are built into the delivery process.</p>
                                        </div>
                                        <div class="service-trust-panel__item">
                                            <h4>Admin-Friendly Handover</h4>
                                            <p>Your team can manage content updates without depending on developers for every small change.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="services-details__img-two">
                                    <img src="assets/images/services/services-details-img-2.jpg" alt="">
                                </div>

                                <!-- TOPICAL CLUSTER LINKS -->
                                <div class="seo-topical-links">
                                    <p class="seo-topical-links__title">Related Web Development Guides and UK Resources</p>
                                    <ul>
                                        <li><a href="/blog/how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites">How UK Service Businesses Generate More Leads With Better Websites</a></li>
                                        <li><a href="/blog/technical-seo-checklist-for-uk-websites-before-launch">Technical SEO Checklist for UK Websites Before Launch</a></li>
                                        <li><a href="/blog/landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries">7 Landing Page Fixes That Increase UK Enquiries</a></li>
                                        <li><a href="/blog/website-development-company-stoke-on-trent-what-businesses-should-expect">What UK Businesses Should Expect From a Web Development Company</a></li>
                                        <li><a href="/search-engine-optimization">SEO Services — Rank Your New Website in UK Search</a></li>
                                        <li><a href="/portfolio">Web Development Portfolio — UK Project Examples</a></li>
                                        <li><a href="/sectors/ecommerce">Ecommerce Website Development UK — Shopify and WooCommerce</a></li>
                                        <li><a href="/pricing">Website Development Pricing — UK Project Packages</a></li>
                                    </ul>
                                </div>

                                <!-- FREE AUDIT CTA -->
                                <div class="seo-audit-cta">
                                    <span class="seo-audit-cta__eyebrow">Free Website Review — No Obligation</span>
                                    <h3>Get a Free Website Audit for Your UK Business</h3>
                                    <p>Share your current website URL and top 3 business goals. We will review your conversion architecture, technical SEO foundations, page speed, mobile performance, and trust signals — then return a clear action plan covering what to fix, improve, and prioritise before your next launch or redesign.</p>
                                    <ul class="seo-audit-cta__list">
                                        <li>Conversion architecture review</li>
                                        <li>Core Web Vitals check</li>
                                        <li>Mobile performance audit</li>
                                        <li>SEO structure analysis</li>
                                    </ul>
                                    <a href="/contact" class="seo-audit-cta__btn">Request Your Free Website Audit</a>
                                    <span class="seo-audit-cta__note">Delivered within 2–3 business days. No obligation to proceed.</span>
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
                                    <li class="active">
                                        <div class="icon">
                                            <img src="assets/images/icon/services-details-more-services-icon.png"
                                                alt="">
                                        </div>
                                        <p><a href="/web-design-development">Web Design & Development</a></p>
                                    </li>
                                    <li>
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
                                <h3>Plan Your Website Delivery</h3>
                                <span></span>
                                <p class="services-details__contact-text">Need a modern website with strong SEO structure?
                                    Send your requirements and we will suggest the right build plan.</p>
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
                            <a href="/contact">Discuss Your Website Project <span class=" icon-right-arrow-1"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--CTA One End -->
@include('layouts.footer')
