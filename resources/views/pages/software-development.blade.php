@php
    $servicePageImage = \App\Support\ServicePageImages::get('software-development');
    $page_title = 'Software Development';
    $seoOverride = [
        'title' => 'Custom Software Development UK | Scalable CRM, Portals and Secure Systems',
        'description' => 'UK-based custom software development for businesses needing scalable solutions, secure systems, custom CRM UK builds, portals, automation, and reliable delivery support.',
        'keywords' => 'custom software development uk, software development company uk, crm development uk, client portal development uk, web app development uk, workflow automation uk, ai software development uk',
        'related_links' => [
            '/pricing',
            '/portfolio',
            '/seo-hub',
            '/contact',
            '/blog/custom-software-development-pricing-uk-what-businesses-should-budget-for-in-2026',
            '/blog/subscription-software-development-uk-how-saas-products-are-planned-priced-and-built',
            '/blog/custom-crm-development-cost-uk-what-affects-budget-and-timeline',
            '/blog/saas-mvp-development-uk-what-to-build-first-and-what-to-delay',
        ],
        'faq_items' => [
            [
                'question' => 'What custom software projects do UK businesses usually start with first?',
                'answer' => 'Most UK businesses start with CRM systems, client portals, internal dashboards, or workflow automation that reduces manual operations, improves lead handling, and prepares teams for AI-assisted workflows.',
            ],
            [
                'question' => 'Can software development work be split into milestones?',
                'answer' => 'Yes. Milestone delivery is usually safer because discovery, design, build, QA, and launch can be reviewed in stages with clearer budget control.',
            ],
            [
                'question' => 'Can custom software development support SEO and conversion goals too?',
                'answer' => 'Yes. We align software delivery with lead flow, search visibility, and business process outcomes instead of treating development as an isolated technical task.',
            ],
        ],
    ];
@endphp
@include('layouts.header')
<style>
    .service-trust-panel {
        margin: 26px 0 28px;
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
</style>

        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
            <div class="page-header__shape-1">
                <img src="assets/images/shapes/page-header-shape-1.png" alt="">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <h1>Software <span>Development</span></h1><div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="/">Home</a></li>
                            <li><span></span></li>
                            <li><a href="/services">Services</a></li>
                            <li><span></span></li>
                            <li>Software Development</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="services-details">
            <div class="container">
                <h2 class="seo-hidden-heading">UK software development service overview</h2>
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="services-details__left">
                            <div class="services-details__img">
                                <img src="{{ \App\Support\ServicePageImages::toUrl($servicePageImage['image']) }}" alt="{{ $servicePageImage['alt'] }}">
                            </div>
                            <div class="services-details__content">
                                <h3 class="services-details__title-1">Custom Software Development UK for Businesses That Need Scalable, Better-Connected Systems</h3>
                                <div class="services-details__shape-1"></div>
                                <p class="services-details__text-1">
                                    We deliver custom software development in the UK for businesses that need cleaner operations, stronger lead handling, better delivery visibility, and scalable solutions built for long-term growth.
                                    That includes custom CRM UK platforms, client portals, internal dashboards, workflow automation, and secure systems delivered under one accountable plan.
                                </p>

                                <h3 class="services-details__title-2">What Our UK-Based Software Delivery Process Includes</h3>
                                <p class="services-details__text-2">
                                    Every project starts with requirements mapping and delivery planning, then moves through interface design, development, QA, deployment, and post-launch support.
                                    This gives UK businesses a practical delivery path, clearer commercial control, and scalable solutions that are easier to manage as operations grow.
                                </p>

                                <div class="services-details__points-box">
                                    <ul class="services-details__points-list list-unstyled">
                                        <li><div class="icon"><span class="icon-check"></span></div><p>Business websites, ecommerce stores, and landing pages</p></li>
                                        <li><div class="icon"><span class="icon-check"></span></div><p>Custom CRM systems and internal operations dashboards</p></li>
                                        <li><div class="icon"><span class="icon-check"></span></div><p>Workflow automation and API integrations</p></li>
                                    </ul>
                                    <ul class="services-details__points-list list-unstyled">
                                        <li><div class="icon"><span class="icon-check"></span></div><p>UI/UX design systems and conversion-focused flows</p></li>
                                        <li><div class="icon"><span class="icon-check"></span></div><p>Technical SEO structure, performance and Core Web Vitals</p></li>
                                        <li><div class="icon"><span class="icon-check"></span></div><p>QA testing, launch support, and ongoing maintenance</p></li>
                                    </ul>
                                </div>

                                <div class="services-details__single-service-box">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon"><img src="assets/images/icon/services-details-icon-1.png" alt=""></div>
                                                <p>Discovery<br> & Scope</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon"><img src="assets/images/icon/services-details-icon-2.png" alt=""></div>
                                                <p>UX/UI<br> Design</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon"><img src="assets/images/icon/services-details-icon-3.png" alt=""></div>
                                                <p>Build &<br> Integrate</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon"><img src="assets/images/icon/services-details-icon-4.png" alt=""></div>
                                                <p>QA, Launch<br> & Support</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="services-details__title-2">Best Fit for UK Businesses</h3>
                                <p class="services-details__text-5">
                                    Ideal for UK service businesses, ecommerce brands, and operations-heavy teams that need a trusted software development UK partner without managing multiple vendors.
                                    We align technical decisions with revenue, lead quality, internal efficiency, and secure systems that support day-to-day operations.
                                </p>
                                <div class="service-trust-panel">
                                    <h3 class="services-details__title-2" style="margin-bottom:8px;">Why UK Businesses Trust Our Software Delivery</h3>
                                    <p class="services-details__text-2" style="margin-bottom:0;">We reduce project risk before build starts by clarifying scope, milestones, ownership, and the commercial outcome the system needs to support, so UK businesses get scalable solutions with clearer delivery accountability.</p>
                                    <div class="service-trust-panel__grid">
                                        <div class="service-trust-panel__item">
                                            <h4>Discovery Before Build</h4>
                                            <p>We map users, workflow, and business goals first so scope stays commercially useful.</p>
                                        </div>
                                        <div class="service-trust-panel__item">
                                            <h4>Milestone Visibility</h4>
                                            <p>Your team gets clear delivery phases, review points, and next-step decisions.</p>
                                        </div>
                                        <div class="service-trust-panel__item">
                                            <h4>Post-Launch Support</h4>
                                            <p>Launch is not the finish line. We support fixes, updates, and measured improvement.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="services-details__right">
                            <div class="services-details__more-services">
                                <h3>Related Services</h3>
                                <span></span>
                                <ul class="services-details__more-services-list list-unstyled">
                                    <li><div class="icon"><img src="assets/images/icon/services-details-more-services-icon.png" alt=""></div><p><a href="/web-design-development">Web App Development</a></p></li>
                                    <li><div class="icon"><img src="assets/images/icon/services-details-more-services-icon.png" alt=""></div><p><a href="/app-development">Mobile App Development</a></p></li>
                                    <li><div class="icon"><img src="assets/images/icon/services-details-more-services-icon.png" alt=""></div><p><a href="/design-and-branding">UX/UI Design</a></p></li>
                                    <li><div class="icon"><img src="assets/images/icon/services-details-more-services-icon.png" alt=""></div><p><a href="/search-engine-optimization">SEO Services</a></p></li>
                                    <li><div class="icon"><img src="assets/images/icon/services-details-more-services-icon.png" alt=""></div><p><a href="/digital-marketing">Digital Marketing</a></p></li>
                                </ul>
                            </div>
                            <div class="services-details__contact-box">
                                <h3>Ready to Scope Your Project?</h3>
                                <span></span>
                                <p class="services-details__contact-text">
                                    Share your requirements once and get a clear delivery recommendation, realistic timeline, and the right build path for your commercial goals.
                                </p>
                                <div class="services-details__contact-btn-box">
                                    <a href="/contact" class="services-details__contact-btn thm-btn"><i class="icon-right"></i>Request a Delivery Plan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-one cta-two">
            <div class="container">
                <div class="cta-one__inner">
                    <div class="cta-one__img">
                        <img src="assets/images/resources/cta-one-img-1.png" alt="">
                    </div>
                    <div class="cta-one__inner-content">
                        <div class="cta-one__shape-bg" style="background-image: url(assets/images/shapes/cta-one-shape-bg.png);"></div>
                        <h3 class="cta-one__title">Start with a delivery team focused on <br> outcomes, support, and long-term value.</h3>
                        <div class="cta-one__btn">
                            <a href="/contact">Book a Project Planning Call <span class="icon-right-arrow-1"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@include('layouts.footer')
