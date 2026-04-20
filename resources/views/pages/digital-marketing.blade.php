@php
    $servicePageImage = \App\Support\ServicePageImages::get('digital-marketing');
    $page_title = 'Digital Marketing';
    $seoOverride = [
        'title' => 'Digital Agency UK | SEO, Paid Growth and Conversion-Focused Marketing',
        'description' => 'UK-based digital agency services combining SEO, paid campaigns, CRO, scalable solutions, and trusted reporting to help businesses generate better-qualified leads.',
        'keywords' => 'ai marketing services uk, ai seo services uk, digital marketing services uk, ppc agency uk, answer engine optimisation uk, conversion rate optimisation uk, lead generation agency uk',
        'related_links' => [
            '/search-engine-optimization',
            '/services',
            '/seo-hub',
            '/pricing',
            '/contact',
        ],
        'faq_items' => [
            [
                'question' => 'Do you combine SEO and paid media in one UK marketing plan?',
                'answer' => 'Yes. We combine SEO, paid campaigns, CRO, and reporting so your traffic, lead quality, and budget decisions stay aligned.',
            ],
            [
                'question' => 'Can AI workflows improve campaign execution without making the content sound robotic?',
                'answer' => 'Yes. We use AI-assisted workflows for research, content operations, and reporting support while keeping strategy, messaging, and quality control human-led.',
            ],
        ],
    ];
@endphp
@include('layouts.header')



        <!--Page Header Start-->
        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);">
            </div>
            <div class="page-header__shape-1">
                <img src="assets/images/shapes/page-header-shape-1.png" alt="">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <h1>Digital <span>Marketing</span></h1><div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="/">Home</a></li>
                            <li><span></span></li>
                            <li><a href="/services">Services</a></li>
                            <li><span></span></li>
                            <li>Digital Marketing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!--Services Details Start-->
        <section class="services-details">
            <div class="container">
                <h2 class="seo-hidden-heading">UK digital marketing service overview</h2>
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="services-details__left">
                            <div class="services-details__img">
                                <img src="{{ \App\Support\ServicePageImages::toUrl($servicePageImage['image']) }}" alt="{{ $servicePageImage['alt'] }}">
                            </div>
                            <div class="services-details__content">
                                <h3 class="services-details__title-1">UK Digital Marketing Services for Lead Generation,
                                    Search Visibility and Measurable ROI</h3>
                                <div class="services-details__shape-1"></div>
                                <p class="services-details__text-1">We build digital marketing campaigns that bring
                                    qualified traffic, improve conversion rates, and increase recurring revenue. From UK
                                    local campaigns to wider growth funnels, our digital agency UK team combines SEO, paid ads,
                                    retargeting, AI-assisted content workflows, and creative strategy into one performance system.</p>
                                <h3 class="services-details__title-2">Core Digital Marketing Delivery Areas for UK Growth</h3>
                                <p class="services-details__text-2">Every campaign starts with keyword intent mapping, audience segmentation, answer engine search visibility checks, AI-assisted content workflow planning, and conversion tracking setup. We then execute channel-specific
                                    campaigns with weekly optimisation so budget is focused on what actually drives leads,
                                    sales, and stronger commercial outcomes.</p>
                                <div class="services-details__points-box">
                                    <ul class="services-details__points-list list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Google Ads and paid social campaign strategy</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Landing page conversion optimization</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Retargeting and funnel recovery journeys</p>
                                        </li>
                                    </ul>
                                    <ul class="services-details__points-list list-unstyled">
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Search intent keyword targeting and SEO alignment</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Performance dashboards with CPL, ROAS and AI-assisted reporting</p>
                                        </li>
                                        <li>
                                            <div class="icon">
                                                <span class="icon-check"></span>
                                            </div>
                                            <p>Creative testing for ad copy, offers and visuals</p>
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
                                                <p>Keyword Gap<br> Analysis</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-2.png" alt="">
                                                </div>
                                                <p>Competitor<br> Campaign Benchmarks </p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-3.png" alt="">
                                                </div>
                                                <p>Audience Intent<br> and Offer Mapping</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-lg-6 col-md-6">
                                            <div class="services-details__single-service">
                                                <div class="services-details__single-icon">
                                                    <img src="assets/images/icon/services-details-icon-4.png" alt="">
                                                </div>
                                                <p>Multichannel Growth<br> Execution</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="services-details__progress-box">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="services-details__progress-left">
                                                <h3 class="services-details__progress-left-title">Performance Snapshot</h3>
                                                <p class="services-details__progress-left-text">Our digital campaigns are
                                                    focused on lead quality, not vanity clicks. We continuously tune
                                                    targeting, messaging, and user journeys to reduce cost per lead and
                                                    improve sales-qualified opportunities.</p>
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
                                                                    <div class="text">Campaign Setup Accuracy</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="98">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="98">
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
                                                                    <div class="text">Avg. Lead Quality Improvement</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="86">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="86">
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
                                                                    <div class="text">Conversion Lift Potential</div>
                                                                    <div class="bar">
                                                                        <div class="bar-innner">
                                                                            <div class="skill-percent">
                                                                                <span class="count-text"
                                                                                    data-speed="3000"
                                                                                    data-stop="72">0</span>
                                                                                <span class="percent">%</span>
                                                                            </div>
                                                                            <div class="bar-fill" data-percent="72">
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
                                <h3 class="services-details__title-3">Why UK Businesses Choose Our Digital Marketing Team</h3>
                                <p class="services-details__text-3">You get strategy, execution, and reporting in one
                                    place. We remove guesswork with clear campaign milestones, transparent analytics, and
                                    practical actions your team can understand and scale.</p>
                                <div class="services-details__points-and-img">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <ul class="services-details__points-1 list-unstyled">
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Custom channel mix based on your service margins and goals</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Daily bid and budget optimization to protect ad spend</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>SEO and paid campaigns aligned to the same high-value keywords</p>
                                                </li>
                                                <li>
                                                    <div class="icon">
                                                        <span class="icon-check"></span>
                                                    </div>
                                                    <p>Monthly growth review with next-step action plan and priorities</p>
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
                                <p class="services-details__text-4">We support B2B, eCommerce, SaaS and local service
                                    brands with campaign systems built for predictable growth. Whether your objective is
                                    booked calls, qualified enquiries, or online sales, we map a plan that scales with
                                    your delivery capacity.</p>
                                <div class="services-details__img-two">
                                    <img src="assets/images/services/services-details-img-2.jpg" alt="">
                                </div>
                                <h3 class="services-details__title-4">Start with a Revenue-Focused Growth Audit:</h3>
                                <p class="services-details__text-5">Share your current channels, budget range and target
                                    market. We will return a practical 90-day roadmap covering campaign setup, expected
                                    milestones, and the KPIs we will track from day one.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="services-details__right">
                            <div class="services-details__more-services">
                                <h3>More Services</h3>
                                <span></span>
                                <ul class="services-details__more-services-list list-unstyled">
                                    <li class="active">
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
                                <h3>Talk Through Your Growth Targets</h3>
                                <span></span>
                                <p class="services-details__contact-text">Need a campaign plan for your market? Send your
                                    goals and we will recommend the right SEO + ads mix.</p>
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
                            <a href="/contact">Book a Growth Planning Call <span class=" icon-right-arrow-1"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--CTA One End -->
@include('layouts.footer')
