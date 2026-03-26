@php
    $servicePageImage = \App\Support\ServicePageImages::get('search-engine-optimization');
    $page_title = 'Search Engine Optimization';
    $seoOverride = [
        'title' => 'SEO Services UK | Technical SEO, AI Search Visibility, AEO and Growth Support',
        'description' => 'SEO services for UK businesses focused on technical SEO, on-page optimisation, AI search visibility, answer engine optimisation, Search Console improvements, and ranking growth that drives qualified enquiries.',
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
                                <h3 class="services-details__title-1">SEO and AI Search Services for UK Businesses That Need More Qualified Organic Leads</h3>
                                <div class="services-details__shape-1"></div>
                                <p class="services-details__text-1">Our SEO service is built for UK businesses that want stronger rankings, better click-through rates, more qualified enquiries, and stronger AI search visibility. We audit technical foundations, improve site structure, and align content with real keyword demand to increase visibility in competitive search results and answer engines.</p>
                                <h3 class="services-details__title-2">SEO Delivery Framework</h3>
                                <p class="services-details__text-2">We combine technical SEO, on-page optimisation, Search Console analysis, answer engine optimisation, AI search entity alignment, and content planning into a clear monthly workflow. Every action is prioritised by impact so your team sees measurable progress in rankings, clicks, AI search visibility, and enquiries.</p>
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
                                                <h3 class="services-details__progress-left-title">SEO Impact Indicators</h3>
                                                <p class="services-details__progress-left-text">Our audits focus on the
                                                    highest-impact fixes first, then move into sustained content and
                                                    authority improvement. This approach helps avoid ranking volatility and
                                                    supports long-term search visibility.</p>
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
                                    <h3 class="services-details__title-2" style="margin-bottom:8px;">What Clients Value Most</h3>
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
                                <h3 class="services-details__title-4">Request an SEO Audit:</h3>
                                <p class="services-details__text-5">Send your website URL and top services. We will
                                    deliver an actionable audit summary covering technical blockers, keyword opportunities,
                                    and a priority roadmap for the next 90 days.</p>
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
                                <h3>Contact Us</h3>
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
                            <a href="/contact">Get Started <span class=" icon-right-arrow-1"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--CTA One End -->
@include('layouts.footer')
