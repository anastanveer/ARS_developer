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
                                <h3 class="services-details__title-4">Start Your Web Project:</h3>
                                <p class="services-details__text-5">Share your goals, target audience, and preferred
                                    timeline. We will propose the right scope, sitemap, and build phases so your launch is
                                    clear, fast, and low risk.</p>
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
