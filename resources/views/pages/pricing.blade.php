@php
    $page_title = 'Pricing';
    $seoOverride = [
        'title' => 'UK Software Pricing: Monthly Support and Requirement-Based Packages',
        'description' => 'Compare UK software pricing packages for websites, CRM systems, ecommerce stores, and SEO support with transparent monthly and one-time options.',
        'keywords' => 'software pricing uk, website package uk, crm project cost uk, ecommerce development cost uk, seo monthly support uk',
    ];
    $liveCoupons = $liveCoupons ?? collect();
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
                        <span class="pricing-one__start-flow-tag">Coupon Support</span>
                        <h3 class="pricing-one__start-flow-title">Apply coupon on selected package</h3>
                        <p class="pricing-one__start-flow-text">Apply your valid offer code to preview your discounted total before you continue.</p>
                        @if($liveCoupons->isNotEmpty())
                            <div class="pricing-live-coupons" id="pricingLiveCoupons">
                                <div class="pricing-live-coupons__head">
                                    <h4 class="pricing-live-coupons__title">Live Offers</h4>
                                    <div class="pricing-live-coupons__nav">
                                        <button type="button" class="pricing-live-coupons__nav-btn" id="pricingLiveCouponPrev" aria-label="Previous offers">
                                            <span class="fas fa-angle-left"></span>
                                        </button>
                                        <button type="button" class="pricing-live-coupons__nav-btn" id="pricingLiveCouponNext" aria-label="Next offers">
                                            <span class="fas fa-angle-right"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="pricing-live-coupons__track" id="pricingLiveCouponTrack">
                                    @foreach($liveCoupons as $offer)
                                        <button type="button" class="pricing-live-coupons__card js-live-coupon-card" data-coupon-select="{{ $offer['code'] }}">
                                            <span class="pricing-live-coupons__badge">{{ $offer['discount_label'] }}</span>
                                            <p class="pricing-live-coupons__card-title">{{ $offer['title'] }}</p>
                                            <p class="pricing-live-coupons__card-meta">Code: <strong>{{ $offer['code'] }}</strong></p>
                                            <p class="pricing-live-coupons__card-meta">Expires: {{ $offer['expires_label'] }}</p>
                                            @if(!is_null($offer['remaining_uses']))
                                                <p class="pricing-live-coupons__card-meta">{{ $offer['remaining_uses'] }} uses left</p>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                                <p class="pricing-live-coupons__hint">Select an offer to auto-fill the coupon code.</p>
                            </div>
                        @endif
                        <div class="row" style="margin-top:8px;">
                            <div class="col-xl-4 col-lg-5 col-md-6">
                                <input type="text" id="pricingCouponCode" class="form-control" placeholder="Enter coupon code (e.g. FIRST20)" style="height:48px;border-radius:10px;border:1px solid #d6deef;padding:0 14px;">
                            </div>
                            <div class="col-xl-4 col-lg-5 col-md-6">
                                <input type="email" id="pricingCouponEmail" class="form-control" placeholder="Enter your email for validation" style="height:48px;border-radius:10px;border:1px solid #d6deef;padding:0 14px;">
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4">
                                <button type="button" id="applyPricingCouponBtn" class="thm-btn thm-btn-two pricing-coupon-btn"><span class="icon-right"></span> Apply Coupon</button>
                            </div>
                        </div>
                        <ul class="list-unstyled pricing-one__start-flow-trust" style="margin-top:10px;">
                            <li><span class="icon-check"></span> One coupon redemption is allowed per client email.</li>
                            <li><span class="icon-check"></span> Discount is finalised when you submit your order enquiry.</li>
                        </ul>
                        <p id="pricingCouponResult" class="pricing-one__start-flow-text" style="margin-top:8px;"></p>
                        <div id="pricingCouponSummary" style="display:none;margin-top:10px;padding:12px 14px;border:1px solid #b5d9c8;background:#f3fff9;border-radius:10px;">
                            <p style="margin:0 0 4px;font-weight:700;color:#0d8051;">Coupon Applied: <span data-coupon-code-view>-</span></p>
                            <p style="margin:0;color:#173153;font-size:14px;">
                                Base: <strong>GBP <span data-coupon-base-view>0.00</span></strong>
                                | Discount: <strong>GBP <span data-coupon-discount-view>0.00</span></strong>
                                | Final: <strong>GBP <span data-coupon-final-view>0.00</span></strong>
                            </p>
                        </div>
                        <p id="pricingCouponNextStep" class="pricing-one__start-flow-text" style="margin-top:8px;color:#173153;"></p>
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

<style>
.pricing-live-coupons {
    margin-top: 14px;
    padding: 14px;
    border: 1px solid #d9e7fc;
    border-radius: 12px;
    background: #ffffff;
}

.pricing-live-coupons__head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.pricing-live-coupons__title {
    margin: 0;
    font-size: 18px;
    color: #173153;
}

.pricing-live-coupons__nav {
    display: inline-flex;
    gap: 6px;
}

.pricing-live-coupons__nav-btn {
    width: 34px;
    height: 34px;
    border: 1px solid #bcd3f6;
    border-radius: 50%;
    background: #f6f9ff;
    color: #1d5ea9;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

.pricing-live-coupons__track {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: minmax(210px, 1fr);
    gap: 10px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    padding-bottom: 4px;
}

.pricing-live-coupons__card {
    scroll-snap-align: start;
    text-align: left;
    border: 1px solid #d7e4fb;
    border-radius: 12px;
    background: #f8fbff;
    padding: 11px 12px;
    min-height: 122px;
    transition: all .2s ease;
}

.pricing-live-coupons__card:hover {
    border-color: #1182d8;
    background: #f0f8ff;
}

.pricing-live-coupons__card.is-active {
    border-color: #1182d8;
    background: #eaf6ff;
    box-shadow: 0 0 0 2px rgba(17, 130, 216, .13);
}

.pricing-live-coupons__badge {
    display: inline-block;
    background: #1182d8;
    color: #fff;
    border-radius: 999px;
    padding: 3px 9px;
    font-size: 12px;
    font-weight: 700;
}

.pricing-live-coupons__card-title {
    margin: 9px 0 4px;
    font-size: 15px;
    line-height: 1.35;
    color: #173153;
    font-weight: 700;
}

.pricing-live-coupons__card-meta {
    margin: 0;
    font-size: 12px;
    color: #4a6283;
    line-height: 1.4;
}

.pricing-live-coupons__hint {
    margin: 10px 0 0;
    font-size: 13px;
    color: #4a6283;
}

.pricing-coupon-btn {
    min-height: 48px;
    width: 100%;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    line-height: 1;
    padding: 0 18px;
}

.pricing-coupon-btn .icon-right {
    margin: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 767px) {
    .pricing-live-coupons__track {
        grid-auto-columns: minmax(190px, 1fr);
    }
}
</style>

<script>
(function () {
    var planButtons = document.querySelectorAll('.js-plan-select');
    var selectedPlanNode = document.querySelector('[data-selected-plan]');
    var startLinks = document.querySelectorAll('[data-start-link]');
    var liveCouponCards = document.querySelectorAll('.js-live-coupon-card');
    var liveCouponTrack = document.getElementById('pricingLiveCouponTrack');
    var liveCouponPrevBtn = document.getElementById('pricingLiveCouponPrev');
    var liveCouponNextBtn = document.getElementById('pricingLiveCouponNext');
    var couponInput = document.getElementById('pricingCouponCode');
    var couponEmailInput = document.getElementById('pricingCouponEmail');
    var couponBtn = document.getElementById('applyPricingCouponBtn');
    var couponResult = document.getElementById('pricingCouponResult');
    var couponSummary = document.getElementById('pricingCouponSummary');
    var couponCodeView = document.querySelector('[data-coupon-code-view]');
    var couponBaseView = document.querySelector('[data-coupon-base-view]');
    var couponDiscountView = document.querySelector('[data-coupon-discount-view]');
    var couponFinalView = document.querySelector('[data-coupon-final-view]');
    var couponNextStep = document.getElementById('pricingCouponNextStep');
    var startFlow = document.getElementById('pricing-start-flow');

    var state = {
        plan: '',
        billing: '',
        planPrice: null,
        couponCode: '',
        couponEmail: '',
        discountAmount: 0,
        finalPrice: null,
        couponApplied: false
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

            if (state.couponApplied && state.couponCode) {
                url.searchParams.set('coupon', state.couponCode);
                if (state.couponEmail) {
                    url.searchParams.set('email', state.couponEmail);
                }
                url.searchParams.set('discount', String(state.discountAmount));
                if (state.finalPrice != null) url.searchParams.set('final', String(state.finalPrice));
            } else {
                url.searchParams.delete('coupon');
                url.searchParams.delete('email');
                url.searchParams.delete('discount');
                url.searchParams.delete('final');
            }

            link.setAttribute('href', url.pathname + url.search + url.hash);
        });
    }

    function showCouponSummary(show) {
        if (couponSummary) {
            couponSummary.style.display = show ? 'block' : 'none';
        }
    }

    function setNextStepMessage(message, color) {
        if (couponNextStep) {
            couponNextStep.textContent = message || '';
            couponNextStep.style.color = color || '#173153';
        }
    }

    function clearLiveCouponSelection() {
        liveCouponCards.forEach(function (card) {
            card.classList.remove('is-active');
        });
    }

    function activateLiveCouponCardByCode(code) {
        var normalized = safeValue(code).toUpperCase();
        var matched = false;
        liveCouponCards.forEach(function (card) {
            var cardCode = safeValue(card.getAttribute('data-coupon-select')).toUpperCase();
            var isMatch = normalized !== '' && cardCode === normalized;
            card.classList.toggle('is-active', isMatch);
            if (isMatch) {
                matched = true;
            }
        });

        if (!matched) {
            clearLiveCouponSelection();
        }
    }

    function resetCouponAppliedState() {
        state.couponApplied = false;
        state.couponCode = '';
        state.discountAmount = 0;
        state.finalPrice = state.planPrice;
        showCouponSummary(false);
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
        if (couponResult) {
            couponResult.textContent = 'Please select a package with fixed price before clicking Start Order.';
            couponResult.style.color = '#a66a00';
        }
        showCouponSummary(false);
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
            resetCouponAppliedState();
            state.couponEmail = couponEmailInput ? couponEmailInput.value.trim() : '';
            setNextStepMessage('After applying your coupon, choose "Submit Form" or "Start Order" to continue.', '#173153');

            if (selectedPlanNode) {
                selectedPlanNode.textContent = state.plan + (state.billing ? ' (' + state.billing.replace('_', ' ') + ')' : '');
            }

            if (couponResult) {
                if (state.planPrice == null) {
                    couponResult.textContent = 'Custom package selected. Coupon can be discussed in custom quotation.';
                    couponResult.style.color = '#4a5f82';
                    setNextStepMessage('Use "Book Planning Call" for custom scope and manual offer confirmation.', '#4a5f82');
                } else {
                    var presetCode = couponInput ? couponInput.value.trim().toUpperCase() : '';
                    couponResult.textContent = presetCode !== ''
                        ? ('Selected plan price: GBP ' + state.planPrice.toFixed(2) + '. Coupon ' + presetCode + ' is ready to apply.')
                        : ('Selected plan price: GBP ' + state.planPrice.toFixed(2) + '. You can apply coupon now.');
                    couponResult.style.color = '#4a5f82';
                    setNextStepMessage('Apply your coupon, then continue with Submit Form or Start Order.', '#173153');
                }
            }
            updateStartLinks();
        });
    });

    startLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            handleStartLinkGuard(event, link);
        });
    });

    if (liveCouponPrevBtn && liveCouponTrack) {
        liveCouponPrevBtn.addEventListener('click', function () {
            var delta = Math.max(220, Math.floor(liveCouponTrack.clientWidth * 0.8));
            liveCouponTrack.scrollBy({ left: -delta, behavior: 'smooth' });
        });
    }

    if (liveCouponNextBtn && liveCouponTrack) {
        liveCouponNextBtn.addEventListener('click', function () {
            var delta = Math.max(220, Math.floor(liveCouponTrack.clientWidth * 0.8));
            liveCouponTrack.scrollBy({ left: delta, behavior: 'smooth' });
        });
    }

    liveCouponCards.forEach(function (card) {
        card.addEventListener('click', function () {
            var code = safeValue(card.getAttribute('data-coupon-select')).toUpperCase();
            if (!code) {
                return;
            }

            activateLiveCouponCardByCode(code);
            if (couponInput) {
                couponInput.value = code;
            }
            resetCouponAppliedState();
            state.couponEmail = couponEmailInput ? couponEmailInput.value.trim() : '';

            if (couponResult) {
                couponResult.textContent = 'Offer ' + code + ' selected. Enter your email and click Apply Coupon.';
                couponResult.style.color = '#1d5ea9';
            }

            if (state.planPrice == null) {
                setNextStepMessage('Now select a package with fixed price, then apply this coupon.', '#1d5ea9');
            } else {
                setNextStepMessage('Offer selected. Enter your email and click Apply Coupon to lock the discount.', '#1d5ea9');
            }

            updateStartLinks();
            if (couponEmailInput && couponEmailInput.value.trim() === '') {
                couponEmailInput.focus();
            }
        });
    });

    if (couponBtn) {
        couponBtn.addEventListener('click', function () {
            if (state.planPrice == null) {
                if (couponResult) {
                    couponResult.textContent = 'Please choose a package with fixed price before applying coupon.';
                    couponResult.style.color = '#a66a00';
                }
                showCouponSummary(false);
                return;
            }

            var code = couponInput ? couponInput.value.trim() : '';
            var email = couponEmailInput ? couponEmailInput.value.trim() : '';
            if (!code) {
                if (couponResult) {
                    couponResult.textContent = 'Please enter coupon code first.';
                    couponResult.style.color = '#a66a00';
                }
                showCouponSummary(false);
                setNextStepMessage('Please enter a coupon code and your email address to validate the offer.', '#a66a00');
                return;
            }

            if (!email) {
                if (couponResult) {
                    couponResult.textContent = 'Please enter your email for one-time coupon validation.';
                    couponResult.style.color = '#a66a00';
                }
                showCouponSummary(false);
                setNextStepMessage('Your email is required to validate one-time coupon use.', '#a66a00');
                return;
            }

            couponBtn.disabled = true;
            couponBtn.textContent = 'Applying...';

            fetch('{{ route('pricing.coupon.preview') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    plan_price: state.planPrice,
                    billing: state.billing || '',
                    email: email || ''
                })
            })
            .then(function (res) { return res.json(); })
            .then(function (payload) {
                if (!payload || payload.ok !== true || payload.valid !== true) {
                    state.couponApplied = false;
                    state.couponCode = '';
                    state.discountAmount = 0;
                    state.finalPrice = state.planPrice;
                    if (couponResult) {
                        couponResult.textContent = (payload && payload.message) ? payload.message : 'Coupon could not be applied.';
                        couponResult.style.color = '#a12828';
                    }
                    showCouponSummary(false);
                    setNextStepMessage('Coupon could not be validated. You can still continue with Submit Form or Start Order.', '#a12828');
                    updateStartLinks();
                    return;
                }

                state.couponApplied = true;
                state.couponCode = safeValue(payload.code).toUpperCase();
                state.couponEmail = email;
                state.discountAmount = Number(payload.discount_amount || 0);
                state.finalPrice = Number(payload.final_price || state.planPrice);
                activateLiveCouponCardByCode(state.couponCode);

                if (couponResult) {
                    var capNote = payload.discount_capped === true
                        ? ' Discount adjusted to selected package value.'
                        : '';
                    couponResult.textContent =
                        'Coupon ' + state.couponCode + ' applied. Base: GBP ' + Number(payload.base_price).toFixed(2) +
                        ' | Discount: GBP ' + Number(payload.discount_amount).toFixed(2) +
                        ' | Final: GBP ' + Number(payload.final_price).toFixed(2) +
                        '. ' + safeValue(payload.billing_note) + capNote;
                    couponResult.style.color = '#0d8051';
                }

                if (couponCodeView) couponCodeView.textContent = state.couponCode;
                if (couponBaseView) couponBaseView.textContent = Number(payload.base_price).toFixed(2);
                if (couponDiscountView) couponDiscountView.textContent = Number(payload.discount_amount).toFixed(2);
                if (couponFinalView) couponFinalView.textContent = Number(payload.final_price).toFixed(2);
                showCouponSummary(true);
                setNextStepMessage('Discount is locked for this email. Now choose Submit Form or Start Order to proceed.', '#0d8051');
                updateStartLinks();

                if (startFlow) {
                    startFlow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            })
            .catch(function () {
                if (couponResult) {
                    couponResult.textContent = 'Coupon request failed. Please try again.';
                    couponResult.style.color = '#a12828';
                }
                showCouponSummary(false);
                setNextStepMessage('A technical issue occurred while applying the coupon. Please try again.', '#a12828');
            })
            .finally(function () {
                couponBtn.disabled = false;
                couponBtn.innerHTML = '<span class="icon-right"></span> Apply Coupon';
            });
        });
    }

    setNextStepMessage('Select a package, apply your coupon, then continue with Submit Form or Start Order.', '#173153');
    if (couponInput && couponInput.value.trim() !== '') {
        activateLiveCouponCardByCode(couponInput.value.trim().toUpperCase());
    }
    updateStartLinks();
})();
</script>

@include('layouts.footer')
