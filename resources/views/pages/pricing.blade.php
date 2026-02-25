@php
    $page_title = 'Pricing';
    $seoOverride = [
        'title' => 'UK Software Pricing: Monthly Support and Requirement-Based Packages',
        'description' => 'Compare UK software pricing packages for websites, CRM systems, ecommerce stores, and SEO support with transparent monthly and one-time options.',
        'keywords' => 'software pricing uk, website package uk, crm project cost uk, ecommerce development cost uk, seo monthly support uk',
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
                    <h1>Our <span>Pricing</span></h1><div class="thm-breadcrumb__inner">
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
                    <h2 class="section-title__title title-animation">Choose from <span>subscription plans</span> or
                        requirement-based delivery.</h2>
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
                    </div>
                    <p class="pricing-one__switch-note">Choose a package first, then pick your start option below:
                        meeting, requirements form, or order kickoff.</p>
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
                                            data-plan="Website Care" data-billing="subscription" data-price="225"><span class="icon-right"></span> Choose Package</a>
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
                                            data-plan="Business Growth" data-billing="subscription" data-price="535"><span class="icon-right"></span> Choose Package</a>
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
                                            data-plan="Ecommerce Scale" data-billing="subscription" data-price="999"><span class="icon-right"></span> Choose Package</a>
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
                                            data-plan="Business Website Build" data-billing="one_time" data-price="1490"><span class="icon-right"></span> Choose Package</a>
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
                                            data-plan="Ecommerce Store Build" data-billing="one_time" data-price="2690"><span class="icon-right"></span> Choose Package</a>
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
                                            data-plan="Software or CRM Build" data-billing="one_time" data-price="4900"><span class="icon-right"></span> Choose Package</a>
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
                </div>

                <div class="pricing-one__start-flow" id="pricing-start-flow">
                    <div class="pricing-one__start-flow-head">
                        <span class="pricing-one__start-flow-tag">How To Start</span>
                        <h3 class="pricing-one__start-flow-title">Pick your next step after package selection</h3>
                        <p class="pricing-one__start-flow-text">Selected package and billing type are forwarded automatically
                            so the team already knows your context.</p>
                        <p class="pricing-one__start-flow-selected">Selected package:
                            <strong data-selected-plan>Not selected yet</strong>
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                            <div class="pricing-one__start-card">
                                <div class="pricing-one__start-card-icon"><span class="far fa-calendar-check"></span></div>
                                <h4 class="pricing-one__start-card-title">Book Meeting First</h4>
                                <p class="pricing-one__start-card-text">Best when you want to discuss scope and timeline
                                    before approving the project start.</p>
                                <a href="/#book-meeting" class="pricing-one__start-card-btn thm-btn thm-btn-two"
                                    data-start-link="meeting" data-base-href="/#book-meeting"><span class="icon-right"></span>
                                    Book Planning Call</a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                            <div class="pricing-one__start-card">
                                <div class="pricing-one__start-card-icon"><span class="far fa-file-alt"></span></div>
                                <h4 class="pricing-one__start-card-title">Submit Requirements</h4>
                                <p class="pricing-one__start-card-text">Share features, goals, and deadline. We reply with
                                    clear plan and timeline in one business day.</p>
                                <a href="/contact?intent=requirements" class="pricing-one__start-card-btn thm-btn thm-btn-two"
                                    data-start-link="requirements" data-base-href="/contact?intent=requirements"><span class="icon-right"></span> Submit Form</a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="500ms">
                            <div class="pricing-one__start-card">
                                <div class="pricing-one__start-card-icon"><span class="far fa-credit-card"></span></div>
                                <h4 class="pricing-one__start-card-title">Start Order</h4>
                                <p class="pricing-one__start-card-text">Ready to proceed now? Request kickoff invoice and
                                    start your order with secure payment flow.</p>
                                <a href="/contact?intent=kickoff_payment" class="pricing-one__start-card-btn thm-btn"
                                    data-start-link="kickoff" data-base-href="/contact?intent=kickoff_payment"><span class="icon-right"></span> Start Order</a>
                            </div>
                        </div>
                    </div>
                    <ul class="list-unstyled pricing-one__start-flow-trust">
                        <li><span class="icon-check"></span> No auto charges: scope, timeline and invoice are confirmed before execution.</li>
                        <li><span class="icon-check"></span> Transparent milestone billing with proper business invoices.</li>
                        <li><span class="icon-check"></span> Privacy-first process aligned with UK business expectations.</li>
                    </ul>
                    <div class="pricing-one__start-flow-head" style="margin-top:16px;">
                        <span class="pricing-one__start-flow-tag">Next Step</span>
                        <h3 class="pricing-one__start-flow-title">Choose one clear action</h3>
                        <p id="pricingStartStepMessage" class="pricing-one__start-flow-text" style="margin-top:8px;color:#173153;"></p>
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
                                <h2 class="section-title-two__title title-animation">Questions About <br>
                                    <span>Packages & Payments</span></h2>
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

        <!--Newsletter Two Start -->
        <section class="newsletter-two">
            <div class="newsletter-two__big-text">Subscribe Newsletter</div>
            <div class="container">
                <div class="newsletter-two__inner">
                    <div class="newsletter-two__left">
                        <h2 class="newsletter-two__title">Subscribe Newsletter</h2>
                        <p class="newsletter-two__text">Get the latest SEO tips and software insights straight to your
                            <br> inbox. Stay informed</p>
                    </div>
                    <div class="newsletter-two__right">
                        <form class="newsletter-two__form newsletter-form-validated" action="{{ route('contact.submit') }}" method="post">
                            @csrf
                            <input type="hidden" name="form_type" value="newsletter">
                            <input type="hidden" name="subject" value="Newsletter Subscription Request">
                            <input type="hidden" name="message" value="Please add me to ARSDeveloper updates.">
                            <div class="newsletter-two__input">
                                <input type="email" name="email" placeholder="Enter Your Email" required>
                            </div>
                            <button type="submit" class="newsletter-two__btn">Subscribe</button>
                        </form>
                        <div class="result"></div>
                    </div>
                </div>
            </div>
        </section>
        <!--Newsletter Two End -->

<script>
(function () {
    var planButtons = document.querySelectorAll('.js-plan-select');
    var selectedPlanNode = document.querySelector('[data-selected-plan]');
    var startLinks = document.querySelectorAll('[data-start-link]');
    var startStepMessage = document.getElementById('pricingStartStepMessage');
    var startFlow = document.getElementById('pricing-start-flow');

    var state = {
        plan: '',
        billing: '',
        planPrice: null
    };

    function safeValue(value) {
        return value == null ? '' : String(value);
    }

    function updateStartLinks() {
        startLinks.forEach(function (link) {
            var baseHref = link.getAttribute('data-base-href') || link.getAttribute('href') || '/contact';
            var url;
            try {
                url = new URL(baseHref, window.location.origin);
            } catch (e) {
                return;
            }

            if (state.plan) url.searchParams.set('plan', state.plan);
            if (state.billing) url.searchParams.set('billing', state.billing);
            if (state.planPrice != null) url.searchParams.set('price', String(state.planPrice));

            link.setAttribute('href', url.pathname + url.search + url.hash);
        });
    }

    function setNextStepMessage(message, color) {
        if (startStepMessage) {
            startStepMessage.textContent = message || '';
            startStepMessage.style.color = color || '#173153';
        }
    }

    function handleStartLinkGuard(event, link) {
        var mode = safeValue(link.getAttribute('data-start-link')).toLowerCase();
        if (mode !== 'kickoff') {
            return;
        }

        var hasFixedPrice = typeof state.planPrice === 'number' && isFinite(state.planPrice) && state.planPrice > 0;
        if (hasFixedPrice) {
            return;
        }

        event.preventDefault();
        setNextStepMessage('For direct payment, choose a package first. For custom scope, use Submit Form or Book Planning Call.', '#a66a00');

        if (startFlow) {
            startFlow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    planButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            state.plan = safeValue(btn.getAttribute('data-plan'));
            state.billing = safeValue(btn.getAttribute('data-billing'));
            var priceRaw = safeValue(btn.getAttribute('data-price')).trim();
            state.planPrice = priceRaw === '' ? null : Number(priceRaw);
            setNextStepMessage('Now choose your next step: Book Planning Call, Submit Form, or Start Order.', '#173153');

            if (selectedPlanNode) {
                selectedPlanNode.textContent = state.plan + (state.billing ? ' (' + state.billing.replace('_', ' ') + ')' : '');
            }
            updateStartLinks();
        });
    });

    startLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            handleStartLinkGuard(event, link);
        });
    });
    setNextStepMessage('Select a package, then continue with Submit Form, Book Planning Call, or Start Order.', '#173153');
    updateStartLinks();
})();
</script>

@include('layouts.footer')
