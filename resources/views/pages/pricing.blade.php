@php
    $page_title = 'Pricing';
    $seoOverride = [
        'title' => 'Software Development Pricing UK | AI Websites, CRM, Ecommerce and SEO Costs',
        'description' => 'Compare UK software development pricing for AI-ready business websites, CRM systems, ecommerce projects, workflow automation, and SEO support with clear monthly and one-time options.',
        'keywords' => 'software development pricing uk, ai website development cost uk, crm development cost uk, workflow automation pricing uk, ecommerce development cost uk, seo monthly support uk',
        'related_links' => [
            '/services',
            '/software-development',
            '/web-design-development',
            '/portfolio',
            '/contact',
        ],
        'faq_items' => [
            [
                'question' => 'How much does a business website cost in the UK?',
                'answer' => 'Business website costs vary by scope, but most builds are priced based on page count, integrations, content needs, and launch support.',
            ],
            [
                'question' => 'How is custom CRM pricing usually calculated?',
                'answer' => 'CRM and automation pricing is usually based on workflow complexity, user roles, integrations, reporting requirements, AI features, and deployment phases.',
            ],
            [
                'question' => 'Do you offer monthly support after launch?',
                'answer' => 'Yes. We offer monthly support plans for updates, technical SEO, conversion improvements, bug fixes, and delivery continuity.',
            ],
            [
                'question' => 'Can I start with a call before choosing a package?',
                'answer' => 'Yes. You can book a planning call first, then confirm scope, timeline, and delivery path before paying.',
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
                    <h1>Project <span>Pricing</span></h1><div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="/">Home</a></li>
                            <li><span></span></li>
                            <li>Our Pricing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--Page Header End-->

        <!-- Pricing One Start -->
        <section class="pricing-one pricing-page">
            <div class="pricing-one__shape-3 float-bob-y">
                <img src="assets/images/shapes/pricing-one-shape-3.png" alt="">
            </div>
            <div class="pricing-one__shape-4 float-bob-x">
                <img src="assets/images/shapes/pricing-one-shape-4.png" alt="">
            </div>
            <div class="container">
                <div class="section-title text-center sec-title-animation animation-style1">
                    <div class="section-title__tagline-box justify-content-center">
                        <div class="section-title__tagline-icon-box">
                            <div class="section-title__tagline-icon-1"></div>
                            <div class="section-title__tagline-icon-2"></div>
                        </div>
                        <span class="section-title__tagline">Pricing & Plan</span>
                    </div>
                    <h2 class="section-title__title title-animation">Choose from <span>monthly support</span> or
                        requirement-based project delivery.</h2>
                </div>

                <div class="pricing-one__switch-toggle">
                    <div class="pricing-one__tab-buttons" id="switch-toggle-tab" role="tablist" aria-label="Pricing plans">
                        <button type="button" class="pricing-one__tab-btn active" id="pricing-tab-subscription"
                            role="tab" aria-selected="true" aria-controls="month" data-pricing-target="month">
                            Subscription Plans
                        </button>
                        <button type="button" class="pricing-one__tab-btn" id="pricing-tab-requirement" role="tab"
                            aria-selected="false" aria-controls="year" data-pricing-target="year">
                            Requirement-Based Delivery
                        </button>
                        <button type="button" class="pricing-one__tab-btn" id="pricing-tab-hourly" role="tab"
                            aria-selected="false" aria-controls="hourly" data-pricing-target="hourly">
                            Hourly Rate (UK)
                        </button>
                    </div>
                    <p class="pricing-one__switch-note">Pick a package and continue with one action: call, form, or
                        direct order.</p>
                </div>

                <div class="tabed-content">
                    <div id="month" class="pricing-one__tab-panel" role="tabpanel" aria-labelledby="pricing-tab-subscription">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">WEBSITE CARE</p>
                                        <h3 class="pricing-one__price-box">GBP 225 <span>/Per Month</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Best for local business websites</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Up to 8 support hours every month</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Core website updates and bug fixes</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Security checks, backups, and uptime review</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>One content or landing page refresh</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Basic SEO health checks</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Email support and monthly report</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Website Care" data-billing="subscription" data-price="225"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">BUSINESS GROWTH</p>
                                        <h3 class="pricing-one__price-box">GBP 535 <span>/Per Month</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Best for lead-focused UK teams</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Up to 24 support hours every month</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Conversion improvements on key pages</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Technical SEO and Core Web Vitals checks</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Two campaign or landing pages per month</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Priority turnaround for fixes</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Monthly strategy call + email support</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Business Growth" data-billing="subscription" data-price="535"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">ECOMMERCE SCALE</p>
                                        <h3 class="pricing-one__price-box">GBP 999 <span>/Per Month</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Best for Shopify or WooCommerce stores</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Up to 45 support hours every month</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Checkout and conversion optimization</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Store speed and technical SEO support</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Feature rollout + CRO experiment support</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Dedicated success manager</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Weekly updates on call and email</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Ecommerce Scale" data-billing="subscription" data-price="999"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                                <div class="pricing-one__single-last">
                                    <div class="pricing-one__custom-pricing-box">
                                        <div class="pricing-one__custom-pricing-icon">
                                            <img src="assets/images/icon/pricing-one-custom-pricing-icon-1.png" alt="">
                                        </div>
                                        <p class="pricing-one__custom-pricing-title">Need Full Combo Package?</p>
                                        <p class="pricing-one__custom-pricing-text">Website + SEO + content + design in one
                                            monthly stack for teams that want one accountable partner.</p>
                                        <div class="pricing-one__btn-box-two">
                                            <a href="#pricing-start-flow" class="pricing-one__btn-two thm-btn js-plan-select"
                                                data-plan="Full Combo Monthly Package" data-billing="subscription" data-price=""><span class="icon-right"></span> Choose Custom</a>
                                        </div>
                                    </div>
                                    <div class="pricing-one__custom-pricing-img">
                                        <img src="assets/images/resources/pricing-one-custom-pricing-img-1.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="year" class="pricing-one__tab-panel" role="tabpanel" aria-labelledby="pricing-tab-requirement">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">BUSINESS WEBSITE</p>
                                        <h3 class="pricing-one__price-box">From GBP 1490 <span>/One Time</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Best for service businesses</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Up to 8 core pages with modern UI</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>WordPress implementation and CMS setup</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>On-page SEO, speed and analytics setup</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Contact forms and lead routing ready</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>14-day launch support window</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Business Website Build" data-billing="one_time" data-price="1490"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">ECOMMERCE STORE</p>
                                        <h3 class="pricing-one__price-box">From GBP 2690 <span>/One Time</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Best for online product selling</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Shopify or WooCommerce setup</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Product, payment and shipping configuration</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Category, filter and conversion UX setup</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>GA4 + Meta/Google tracking setup</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>30-day post-launch support</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Ecommerce Store Build" data-billing="one_time" data-price="2690"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">SOFTWARE / CRM</p>
                                        <h3 class="pricing-one__price-box">From GBP 4900 <span>/One Time</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Best for internal workflow automation</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Discovery workshop and process mapping</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Role-based dashboards and team permissions</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Integrations, automations and notifications</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>QA, security hardening and deployment</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Admin handover and training support</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Software or CRM Build" data-billing="one_time" data-price="4900"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                                <div class="pricing-one__single-last">
                                    <div class="pricing-one__custom-pricing-box">
                                        <div class="pricing-one__custom-pricing-icon">
                                            <img src="assets/images/icon/pricing-one-custom-pricing-icon-1.png" alt="">
                                        </div>
                                        <p class="pricing-one__custom-pricing-title">Enterprise Scope + Multi Team</p>
                                        <p class="pricing-one__custom-pricing-text">If your project has multiple teams,
                                            departments, or phases, we prepare a phased roadmap with clear invoice
                                            milestones.</p>
                                        <div class="pricing-one__btn-box-two">
                                            <a href="#pricing-start-flow" class="pricing-one__btn-two thm-btn js-plan-select"
                                                data-plan="Enterprise Custom Scope" data-billing="one_time" data-price=""><span class="icon-right"></span> Choose Custom</a>
                                        </div>
                                    </div>
                                    <div class="pricing-one__custom-pricing-img">
                                        <img src="assets/images/resources/pricing-one-custom-pricing-img-1.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="hourly" class="pricing-one__tab-panel" role="tabpanel" aria-labelledby="pricing-tab-hourly" style="display:none;" aria-hidden="true">
                        <div class="row">
                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">HOURLY ESSENTIAL</p>
                                        <h3 class="pricing-one__price-box">GBP 65 <span>/Hour</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Minimum 10-hour block</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Bug fixes, minor features, and content updates</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Progress report and hours summary</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Best for small ongoing tasks</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Hourly Essential (10h block)" data-billing="hourly" data-price="650"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">HOURLY GROWTH</p>
                                        <h3 class="pricing-one__price-box">GBP 75 <span>/Hour</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Minimum 20-hour block</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Landing page, SEO fixes, and conversion updates</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Weekly status and priority turnaround</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Best for growth teams with recurring tasks</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Hourly Growth (20h block)" data-billing="hourly" data-price="1500"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="500ms">
                                <div class="pricing-one__single">
                                    <div class="pricing-one__title-box">
                                        <p class="pricing-one__title">HOURLY SCALE</p>
                                        <h3 class="pricing-one__price-box">GBP 85 <span>/Hour</span> </h3>
                                        <div class="pricing-one__border"></div>
                                    </div>
                                    <div class="pricing-one__feature-list-box">
                                        <h4 class="pricing-one__feature-title">Minimum 40-hour block</h4>
                                        <ul class="list-unstyled pricing-one__feature-list">
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Development sprint, integrations, and QA tasks</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Milestone-based delivery planning</p></div></li>
                                            <li><div class="icon"><span class="fas fa-check"></span></div><div class="text"><p>Best for teams needing high monthly throughput</p></div></li>
                                        </ul>
                                    </div>
                                    <div class="pricing-one__btn-box">
                                        <a href="#pricing-start-flow" class="pricing-one__btn thm-btn js-plan-select"
                                            data-plan="Hourly Scale (40h block)" data-billing="hourly" data-price="3400"><span class="icon-right"></span> Request This Package</a>
                                    </div>
                                    <div class="pricing-one__shape-1"></div>
                                    <div class="pricing-one__shape-2"></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                                <div class="pricing-one__single-last">
                                    <div class="pricing-one__custom-pricing-box">
                                        <div class="pricing-one__custom-pricing-icon">
                                            <img src="assets/images/icon/pricing-one-custom-pricing-icon-1.png" alt="">
                                        </div>
                                        <p class="pricing-one__custom-pricing-title">Custom Hour Bank</p>
                                        <p class="pricing-one__custom-pricing-text">Need a custom hour bank? We can set
                                            monthly or quarterly hour allocation with clear reporting.</p>
                                        <div class="pricing-one__btn-box-two">
                                            <a href="#pricing-start-flow" class="pricing-one__btn-two thm-btn js-plan-select"
                                                data-plan="Custom Hour Bank" data-billing="hourly" data-price=""><span class="icon-right"></span> Choose Custom</a>
                                        </div>
                                    </div>
                                    <div class="pricing-one__custom-pricing-img">
                                        <img src="assets/images/resources/pricing-one-custom-pricing-img-1.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                #pof-name::placeholder,
                #pof-email::placeholder,
                #pof-phone::placeholder,
                #pof-message::placeholder {
                    color: #b8c4d0;
                    opacity: 1;
                }
                </style>

                <!-- Order Form -->
                <div id="pricing-start-flow" style="margin-top:72px;padding:0 0 8px;">

                    {{-- Section header --}}
                    <div style="text-align:center;margin-bottom:44px;">
                        <div style="display:inline-flex;align-items:center;gap:8px;background:#eef5ff;border:1px solid #bfdbfe;border-radius:100px;padding:7px 20px;margin-bottom:18px;">
                            <i class="fas fa-file-invoice" style="color:#1d93ff;font-size:12px;"></i>
                            <span style="font-size:12px;font-weight:700;color:#1d4ed8;letter-spacing:.8px;text-transform:uppercase;font-family:Arial,sans-serif;">Request Your Invoice</span>
                        </div>
                        <div style="font-size:26px;font-weight:700;color:#0f1e35;margin:0 0 10px;line-height:1.35;font-family:Arial,sans-serif;text-transform:none;letter-spacing:normal;">
                            Selected: <span id="pof-plan-display" style="color:#1d93ff;">choose a package above ↑</span>
                        </div>
                        <p style="font-size:15px;color:#64748b;margin:0;max-width:460px;margin-left:auto;margin-right:auto;font-family:Arial,sans-serif;">
                            Fill in your details — we'll send your invoice within <strong style="color:#0f1e35;">1 business day</strong>. No payment until you approve.
                        </p>
                    </div>

                    <div style="max-width:660px;margin:0 auto;">

                        {{-- Success state --}}
                        <div id="pof-success" style="display:none;text-align:center;padding:56px 32px;background:linear-gradient(135deg,#f0fdf4 0%,#dcfce7 100%);border-radius:20px;border:1px solid #86efac;box-shadow:0 4px 24px rgba(34,197,94,.12);">
                            <div style="width:76px;height:76px;background:linear-gradient(135deg,#16a34a,#22c55e);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 22px;box-shadow:0 8px 24px rgba(34,197,94,.3);">
                                <i class="fas fa-check" style="color:#fff;font-size:30px;"></i>
                            </div>
                            <p style="color:#14532d;margin:0 0 8px;font-size:22px;font-weight:700;font-family:Arial,sans-serif;">Request sent successfully!</p>
                            <p style="color:#166534;margin:0 0 6px;font-size:15px;font-family:Arial,sans-serif;">Check your inbox — your order confirmation is on its way.</p>
                            <p style="color:#166534;margin:0;font-size:13px;opacity:.8;font-family:Arial,sans-serif;">Invoice within 1 business day. Questions? <a href="mailto:info@arsdeveloper.co.uk" style="color:#166534;font-weight:700;">info@arsdeveloper.co.uk</a></p>
                        </div>

                        {{-- Form card --}}
                        <div id="pof-form-wrap" style="background:#fff;border-radius:20px;box-shadow:0 12px 56px rgba(15,30,53,.12),0 2px 12px rgba(15,30,53,.06);border:1px solid #e8edf5;overflow:hidden;">

                            {{-- Gradient top bar --}}
                            <div style="height:4px;background:linear-gradient(90deg,#173153 0%,#1d6faf 40%,#1d93ff 70%,#38bdf8 100%);"></div>

                            <div style="padding:36px 40px 40px;">

                                {{-- Selected plan badge (hidden until plan chosen) --}}
                                <div id="pof-summary" style="display:none;border-radius:12px;padding:14px 18px;margin-bottom:28px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border:1px solid #bfdbfe;">
                                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                                        <div style="width:38px;height:38px;background:linear-gradient(135deg,#1d6faf,#1d93ff);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 3px 10px rgba(29,147,255,.3);">
                                            <i class="fas fa-tag" style="color:#fff;font-size:13px;"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <p style="margin:0 0 2px;font-size:10px;color:#3b82f6;font-weight:700;text-transform:uppercase;letter-spacing:.7px;font-family:Arial,sans-serif;">Package Selected</p>
                                            <p style="margin:0;font-size:15px;color:#1e3a5f;font-weight:700;font-family:Arial,sans-serif;" id="pof-summary-text"></p>
                                        </div>
                                        <span style="background:#173153;color:#fff;border-radius:100px;font-size:10px;font-weight:700;padding:4px 12px;letter-spacing:.5px;white-space:nowrap;font-family:Arial,sans-serif;">READY ✓</span>
                                    </div>
                                </div>

                                <form id="pricing-order-form" novalidate>
                                    @csrf
                                    <input type="hidden" id="pof-plan" name="plan" value="">
                                    <input type="hidden" id="pof-billing" name="billing" value="">
                                    <input type="hidden" id="pof-price" name="price" value="">

                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px;">
                                        <div>
                                            <label style="display:block;font-size:11px;color:#475569;margin-bottom:7px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;font-family:Arial,sans-serif;">Full Name <span style="color:#ef4444;">*</span></label>
                                            <input type="text" name="name" id="pof-name" required placeholder="John Smith"
                                                style="width:100%;padding:13px 15px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#0f1e35;background:#f8fafc;outline:none;box-sizing:border-box;font-family:Arial,sans-serif;"
                                                onfocus="this.style.borderColor='#1d93ff';this.style.background='#fff';this.style.boxShadow='0 0 0 3px rgba(29,147,255,.1)';"
                                                onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.boxShadow='none';">
                                        </div>
                                        <div>
                                            <label style="display:block;font-size:11px;color:#475569;margin-bottom:7px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;font-family:Arial,sans-serif;">Email Address <span style="color:#ef4444;">*</span></label>
                                            <input type="email" name="email" id="pof-email" required placeholder="you@company.com"
                                                style="width:100%;padding:13px 15px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#0f1e35;background:#f8fafc;outline:none;box-sizing:border-box;font-family:Arial,sans-serif;"
                                                onfocus="this.style.borderColor='#1d93ff';this.style.background='#fff';this.style.boxShadow='0 0 0 3px rgba(29,147,255,.1)';"
                                                onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.boxShadow='none';">
                                        </div>
                                    </div>

                                    <div style="margin-bottom:18px;">
                                        <label style="display:block;font-size:11px;color:#475569;margin-bottom:7px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;font-family:Arial,sans-serif;">Phone &nbsp;<span style="color:#94a3b8;font-weight:500;text-transform:none;letter-spacing:0;font-size:11px;">(optional)</span></label>
                                        <input type="tel" name="phone" id="pof-phone" placeholder="+44 7700 000000"
                                            style="width:100%;padding:13px 15px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#0f1e35;background:#f8fafc;outline:none;box-sizing:border-box;font-family:Arial,sans-serif;"
                                            onfocus="this.style.borderColor='#1d93ff';this.style.background='#fff';this.style.boxShadow='0 0 0 3px rgba(29,147,255,.1)';"
                                            onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.boxShadow='none';">
                                    </div>

                                    <div style="margin-bottom:24px;">
                                        <label style="display:block;font-size:11px;color:#475569;margin-bottom:7px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;font-family:Arial,sans-serif;">Project Notes &nbsp;<span style="color:#94a3b8;font-weight:500;text-transform:none;letter-spacing:0;font-size:11px;">(optional)</span></label>
                                        <textarea name="message" id="pof-message" rows="3" placeholder="Brief description of your project, timeline, or any questions..."
                                            style="width:100%;padding:13px 15px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#0f1e35;background:#f8fafc;outline:none;resize:vertical;box-sizing:border-box;font-family:Arial,sans-serif;"
                                            onfocus="this.style.borderColor='#1d93ff';this.style.background='#fff';this.style.boxShadow='0 0 0 3px rgba(29,147,255,.1)';"
                                            onblur="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.boxShadow='none';"></textarea>
                                    </div>

                                    {{-- Error box: starts hidden, JS sets display:flex when needed --}}
                                    <div id="pof-error" style="display:none;align-items:center;gap:10px;background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;padding:13px 16px;color:#b91c1c;font-size:13px;margin-bottom:18px;font-family:Arial,sans-serif;">
                                        <i class="fas fa-exclamation-circle" style="color:#ef4444;flex-shrink:0;font-size:15px;"></i>
                                        <span id="pof-error-text"></span>
                                    </div>

                                    <button type="submit" id="pof-submit"
                                        style="width:100%;background:linear-gradient(135deg,#173153 0%,#1a3d6e 100%);color:#fff;border:none;padding:16px 32px;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;letter-spacing:.3px;box-shadow:0 6px 24px rgba(23,49,83,.3);display:flex;align-items:center;justify-content:center;gap:10px;font-family:Arial,sans-serif;">
                                        <i class="fas fa-paper-plane" style="font-size:13px;" id="pof-icon"></i>
                                        <span id="pof-submit-text">Request Invoice by Email</span>
                                    </button>
                                </form>

                                {{-- Trust signals --}}
                                <div style="display:flex;gap:24px;margin-top:22px;padding-top:22px;border-top:1px solid #f1f5f9;flex-wrap:wrap;justify-content:center;">
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <i class="fas fa-shield-alt" style="color:#22c55e;font-size:12px;"></i>
                                        <span style="font-size:12px;color:#64748b;font-weight:500;font-family:Arial,sans-serif;">No payment until you approve</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <i class="fas fa-clock" style="color:#3b82f6;font-size:12px;"></i>
                                        <span style="font-size:12px;color:#64748b;font-weight:500;font-family:Arial,sans-serif;">Invoice within 1 business day</span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <i class="fas fa-lock" style="color:#8b5cf6;font-size:12px;"></i>
                                        <span style="font-size:12px;color:#64748b;font-weight:500;font-family:Arial,sans-serif;">Secure &amp; confidential</span>
                                    </div>
                                </div>

                                <p style="text-align:center;margin:16px 0 0;font-size:13px;color:#94a3b8;font-family:Arial,sans-serif;">
                                    Prefer to talk first?
                                    <a href="/contact" style="color:#1d93ff;font-weight:600;text-decoration:none;">Book a free call →</a>
                                </p>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- Pricing One End -->

        <!--Faq One Start -->
        <section class="faq-one">
            <div class="faq-one__shape-bg" style="background-image: url(assets/images/shapes/faq-shape-bg.png);"></div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6">
                        <div class="faq-one__left">
                            <div class="section-title-two text-left sec-title-animation animation-style2">
                                <div class="section-title-two__tagline-box">
                                    <div class="section-title-two__tagline-icon-box">
                                        <div class="section-title-two__tagline-icon-1"></div>
                                        <div class="section-title-two__tagline-icon-2"></div>
                                    </div>
                                    <span class="section-title-two__tagline">Pricing FAQs</span>
                                </div>
                                <h2 class="section-title-two__title title-animation">Questions clients ask about
                                    <span>packages and payments</span></h2>
                            </div>
                            <p class="faq-one__text">Clear answers on subscription plans, one-time builds, scope control,
                                and billing process so business decisions stay simple.</p>
                            <div class="faq-one__btn-box">
                                <a href="/contact" class="faq-one__btn thm-btn thm-btn-two"><span class="icon-right"></span> Get Custom Quote</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6">
                        <div class="faq-one__right">
                            <h3 class="seo-hidden-heading">Pricing frequently asked questions</h3>
                            <div class="accrodion-grp faq-one-accrodion" data-grp-name="pricing-faq-accrodion">
                                <div class="accrodion active">
                                    <div class="accrodion-title">
                                        <h4>What is the difference between monthly and requirement-based plans?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Monthly plans are for ongoing support and continuous improvements.
                                                Requirement-based plans are one-time project deliveries with defined
                                                milestones and scope.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>What is the best way to start: meeting, requirements, or direct order?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>All three options are available on each package card. Meeting is best for
                                                guidance, requirements form is best for clear scope submission, and start
                                                order is best when you already want invoice onboarding.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>Are payment gateways and third-party tool costs included?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Package pricing covers development scope. Third-party subscriptions, paid
                                                plugins, and platform fees are usually billed separately unless included in
                                                your custom proposal.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>How are payments scheduled for custom projects?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Custom projects follow milestone-based invoicing. Each stage is started
                                                after previous phase confirmation and payment as agreed in the roadmap.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>Can you provide invoices for company accounting?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Yes. We generate proper invoices for each payment stage and share billing
                                                records for your accounting and finance workflow.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>Do you offer region-based pricing visibility?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Yes. Pricing display can adapt by selected country/currency while project
                                                invoices are finalized in the agreed billing currency.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Faq One End -->
<script>
(function () {
    var planButtons   = document.querySelectorAll('.js-plan-select');
    var planDisplay   = document.getElementById('pof-plan-display');
    var inpPlan       = document.getElementById('pof-plan');
    var inpBilling    = document.getElementById('pof-billing');
    var inpPrice      = document.getElementById('pof-price');
    var summary       = document.getElementById('pof-summary');
    var summaryText   = document.getElementById('pof-summary-text');
    var form          = document.getElementById('pricing-order-form');
    var submitBtn     = document.getElementById('pof-submit');
    var submitTxt     = document.getElementById('pof-submit-text');
    var errorBox      = document.getElementById('pof-error');
    var errorText     = document.getElementById('pof-error-text');
    var successBox    = document.getElementById('pof-success');
    var formWrap      = document.getElementById('pof-form-wrap');
    var startFlow     = document.getElementById('pricing-start-flow');

    function val(el) {
        return el ? el.value.trim() : '';
    }

    function showError(msg) {
        if (!errorBox) return;
        if (errorText) errorText.textContent = msg;
        errorBox.style.display = 'flex';
        errorBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideError() {
        if (errorBox) { errorBox.style.display = 'none'; }
        if (errorText) errorText.textContent = '';
    }

    function setLoading(loading) {
        if (!submitBtn || !submitTxt) return;
        submitBtn.disabled = loading;
        submitTxt.textContent = loading ? 'Sending…' : 'Request Invoice by Email';
        submitBtn.style.opacity = loading ? '0.7' : '1';
    }

    /* Plan button click: populate hidden fields + update UI */
    planButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            var plan    = btn.getAttribute('data-plan')    || '';
            var billing = btn.getAttribute('data-billing') || '';
            var price   = btn.getAttribute('data-price')   || '';

            if (inpPlan)    inpPlan.value    = plan;
            if (inpBilling) inpBilling.value = billing;
            if (inpPrice)   inpPrice.value   = price;

            if (planDisplay) {
                planDisplay.textContent = plan + (billing ? ' — ' + billing.replace(/_/g, ' ') : '');
            }

            if (summary && summaryText) {
                var label = plan;
                if (billing) label += ' (' + billing.replace(/_/g, ' ') + ')';
                if (price && Number(price) > 0) label += ' — GBP ' + Number(price).toLocaleString('en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                summaryText.textContent = label;
                summary.style.display = 'block';
            }

            hideError();

            if (startFlow) {
                startFlow.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* Form submit: AJAX POST */
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            hideError();

            var name  = val(document.getElementById('pof-name'));
            var email = val(document.getElementById('pof-email'));
            var plan  = val(inpPlan);

            if (!name)  { showError('Please enter your full name.'); return; }
            if (!email) { showError('Please enter a valid email address.'); return; }
            if (!plan)  { showError('Please select a package above before submitting.'); return; }

            var fd = new FormData(form);
            var csrf = document.querySelector('input[name="_token"]');
            var headers = { 'Accept': 'application/json' };
            if (csrf) headers['X-CSRF-TOKEN'] = csrf.value;

            setLoading(true);

            fetch('/pricing/order', {
                method: 'POST',
                headers: headers,
                body: fd
            })
            .then(function (res) {
                return res.json().then(function (data) {
                    return { status: res.status, data: data };
                });
            })
            .then(function (result) {
                setLoading(false);
                if (result.data && result.data.ok) {
                    if (formWrap) formWrap.style.display = 'none';
                    if (successBox) {
                        successBox.style.display = 'block';
                        successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    var msg = (result.data && result.data.message)
                        ? result.data.message
                        : 'Something went wrong. Please try again or email us directly at info@arsdeveloper.co.uk';
                    showError(msg);
                }
            })
            .catch(function () {
                setLoading(false);
                showError('Network error. Please check your connection and try again, or contact us directly.');
            });
        });
    }
})();
</script>

@include('layouts.footer')
