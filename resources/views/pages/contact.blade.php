@php
    $page_title = 'Contact';
    $seoOverride = [
        'title' => 'Contact UK Software Agency for Website, CRM and SEO Projects',
        'description' => 'Contact ARSDeveloper to discuss website development, custom CRM, ecommerce, and SEO services for UK businesses.',
        'keywords' => 'contact software agency uk, request website quote uk, crm consultation uk, ecommerce development inquiry uk, seo consultation uk',
    ];
    $flowIntent = strtolower(trim((string) request()->query('intent', '')));
    $flowPlanRaw = trim((string) request()->query('plan', ''));
    $flowPlanRaw = (string) preg_replace('/[^a-z0-9\-\_\s]/i', ' ', $flowPlanRaw);
    $flowPlanLabel = preg_replace('/\s+/', ' ', str_replace(['-', '_'], ' ', $flowPlanRaw));
    $flowPlanLabel = trim((string) ucwords((string) $flowPlanLabel));
    $flowBillingRaw = strtolower(trim((string) request()->query('billing', '')));
    $flowPriceRaw = trim((string) request()->query('price', ''));
    $flowBillingLabel = match ($flowBillingRaw) {
        'subscription' => 'Subscription',
        'one_time' => 'One-Time',
        default => '',
    };
    $flowPlanDescriptor = $flowPlanLabel;
    if ($flowBillingLabel !== '') {
        $flowPlanDescriptor .= $flowPlanDescriptor !== '' ? " ({$flowBillingLabel})" : $flowBillingLabel;
    }
    $flowProjectType = $flowPlanLabel;
    if ($flowBillingLabel !== '') {
        $flowProjectType .= $flowProjectType !== '' ? " ({$flowBillingLabel})" : $flowBillingLabel;
    }
    if (is_numeric($flowPriceRaw) && (float) $flowPriceRaw > 0) {
        $flowPlanDescriptor .= ' - GBP ' . number_format((float) $flowPriceRaw, 2);
    }
    $flowPlanSuffix = $flowPlanDescriptor !== '' ? ' - ' . $flowPlanDescriptor : '';
    $flowPlanText = $flowPlanDescriptor !== '' ? ' for ' . $flowPlanDescriptor : '';
    $flowBasePrice = is_numeric($flowPriceRaw) ? max(0, (float) $flowPriceRaw) : null;
    $flowFinalPrice = null;
    $flowPayableAmount = $flowFinalPrice && $flowFinalPrice > 0 ? $flowFinalPrice : $flowBasePrice;
    $showDirectOrderButton = in_array($flowIntent, ['kickoff_payment', 'order'], true);
    $canDirectOrderCheckout = $showDirectOrderButton && is_numeric($flowPayableAmount) && (float) $flowPayableAmount > 0;
    $prefillActionMode = old('action_mode', $canDirectOrderCheckout ? 'pay' : 'message');
    $showActionSelector = $canDirectOrderCheckout;

    $flowIntents = [
        'requirements' => [
            'kicker' => 'Pricing Start Path',
            'title' => 'Submit Project Requirements',
            'description' => 'Share your requirements once and our team replies with scope, timeline, and recommended execution path.',
            'steps' => [
                'Share pages/features, target timeline, and budget range',
                'Get scope feedback and implementation plan',
                'Receive proposal with delivery milestones',
            ],
            'form_type' => 'pricing_requirements',
            'subject' => 'Requirements Submission',
            'message' => 'I want to submit project requirements' . $flowPlanText . '. Please review and share timeline, scope, and quotation.',
        ],
        'order' => [
            'kicker' => 'Pricing Start Path',
            'title' => 'Start Order and Invoice Setup',
            'description' => 'Use this option when you already decided to proceed. We confirm scope and share your invoice link with milestone terms.',
            'steps' => [
                'Share business details and final scope summary',
                'Confirm timeline and milestone plan',
                'Receive invoice link to start onboarding',
            ],
            'form_type' => 'pricing_order',
            'budget_hint' => 'Kickoff invoice requested',
            'subject' => 'Order Start Request',
            'message' => 'I am ready to start the order' . $flowPlanText . '. Please send onboarding steps and invoice details.',
        ],
        'kickoff_payment' => [
            'kicker' => 'Pricing Start Path',
            'title' => 'Start Order and Kickoff Payment',
            'description' => 'Use this option when you are ready to start now. We confirm final scope and share secure kickoff invoice link.',
            'steps' => [
                'Share final requirements and business billing details',
                'Confirm milestone scope and handover timeline',
                'Receive secure kickoff invoice/payment link',
            ],
            'form_type' => 'pricing_order',
            'budget_hint' => 'Kickoff invoice requested',
            'subject' => 'Kickoff Payment Request',
            'message' => 'I am ready to start and request kickoff invoice/payment link' . $flowPlanText . '. Please share next onboarding steps.',
        ],
        'meeting' => [
            'kicker' => 'Pricing Start Path',
            'title' => 'Book Planning Meeting',
            'description' => 'Use this path to discuss scope first, then finalize package and delivery milestones.',
            'steps' => [
                'Pick preferred meeting time',
                'Discuss requirements and expected outcomes',
                'Finalize scope and start plan',
            ],
            'form_type' => 'pricing_requirements',
            'subject' => 'Planning Meeting Request',
            'message' => 'I want to schedule a planning call before finalizing scope' . $flowPlanText . '.',
        ],
    ];
    $flowContext = $flowIntents[$flowIntent] ?? null;
    $prefillSubject = old('subject', $flowContext['subject'] ?? '');
    $prefillMessage = old('message', $flowContext['message'] ?? '');
    $prefillFormType = $flowContext['form_type'] ?? 'contact';
@endphp
@include('layouts.header')
<style>
    .contact-page__left {
        margin-top: 28px;
    }

    .contact-page__left-head {
        margin: 0 0 20px;
        max-width: 520px;
    }

    .contact-page__left .row {
        --bs-gutter-x: 26px;
        --bs-gutter-y: 26px;
    }

    .contact-page__left-title {
        margin: 0 0 8px;
        color: #0f2f64;
        font-size: 30px;
        line-height: 1.1;
    }

    .contact-page__left-text {
        margin: 0;
        max-width: 430px;
        color: #5b6f8c;
        font-size: 15px;
        line-height: 1.6;
    }

    .contact-page__contact-single {
        min-height: 100%;
        padding: 26px 24px 22px;
        border-color: #dbe7f6;
        border-radius: 22px;
        margin-bottom: 0;
        box-shadow: 0 14px 34px rgba(21, 61, 118, 0.06);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .contact-page__contact-single:hover {
        transform: translateY(-4px);
        border-color: #b9d4f7;
        box-shadow: 0 18px 38px rgba(16, 59, 116, 0.1);
    }

    .contact-page__contact-single-title {
        margin-bottom: 10px;
        color: #103767;
        font-size: 22px;
    }

    .contact-page__contact-single p,
    .contact-page__contact-single p a {
        color: #5e7290;
        font-size: 15px;
        line-height: 1.7;
    }

    .contact-page__contact-icon {
        width: 68px;
        height: 68px;
        border-style: solid;
        border-color: #d5e5fb;
        background: linear-gradient(180deg, #ffffff 0%, #f2f7ff 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.92);
    }

    .contact-page__contact-icon-shape {
        width: 24px;
        height: 24px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #4bb7ff 0%, #1f73d1 100%);
        opacity: 0.9;
    }

    .contact-page__right--start {
        overflow: hidden;
        border-radius: 28px;
        padding: 32px;
        background:
            radial-gradient(circle at top right, rgba(103, 188, 255, 0.18), transparent 28%),
            linear-gradient(135deg, #0d2f63 0%, #15448c 52%, #206dc9 100%);
        box-shadow: 0 28px 56px rgba(13, 42, 91, 0.22);
    }

    .contact-page__contact-form-title-box {
        gap: 12px;
    }

    .contact-page__contact-form-title {
        font-size: 30px;
        line-height: 1.18;
        letter-spacing: 0.01em;
        font-weight: 700;
        color: #f7fbff !important;
        text-wrap: balance;
        text-shadow: 0 1px 10px rgba(5, 19, 46, 0.12);
    }

    .contact-page__contact-form-text {
        max-width: 520px;
        margin-top: 8px;
        margin-bottom: 14px;
        color: rgba(239, 246, 255, 0.88);
        font-size: 14px;
        line-height: 1.5;
    }

    .contact-page__trust-strip {
        margin: 0 0 16px;
        padding: 12px 16px;
        border-radius: 16px;
        border: 1px solid rgba(177, 214, 255, 0.26);
        background: rgba(5, 24, 58, 0.16);
    }

    .contact-page__trust-strip h4 {
        margin: 0;
        color: #ffffff;
        font-size: 14px;
        line-height: 1.4;
    }

    .contact-page__form-shell {
        padding: 24px;
        border: 1px solid rgba(215, 231, 251, 0.86);
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 18px 34px rgba(10, 43, 97, 0.12);
    }

    .contact-page__input-box {
        margin-bottom: 16px;
    }

    .contact-page__btn-box {
        margin-top: 6px;
    }

    @media (max-width: 1199px) {
        .contact-page__left {
            margin-top: 0;
            margin-bottom: 28px;
        }
    }

    @media (max-width: 991px) {
        .contact-page__left-title,
        .contact-page__contact-form-title {
            font-size: 27px;
        }

        .contact-page__right--start {
            padding: 26px;
        }
    }

    @media (max-width: 767px) {
        .contact-page__left-head {
            margin-bottom: 18px;
        }

        .contact-page__left-title,
        .contact-page__contact-form-title {
            font-size: 23px;
            line-height: 1.22;
        }

        .contact-page__right--start {
            padding: 20px 18px;
            border-radius: 22px;
        }

        .contact-page__form-shell {
            padding: 18px;
            border-radius: 18px;
        }

        .contact-page__contact-single {
            padding: 22px 18px 18px;
        }

        .contact-page__left .row {
            --bs-gutter-x: 18px;
            --bs-gutter-y: 18px;
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
                    <h1>Contact <span>Us</span></h1><div class="thm-breadcrumb__inner">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><i class="icon-home"></i><a href="/">Home</a></li>
                            <li><span></span></li>
                            <li>Contact Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--Page Header End-->
        <!--Contact Page Start-->
        <section class="contact-page">
            <div class="container">
                <h2 class="seo-hidden-heading">Contact ARSDeveloper UK for project requirements and support</h2>
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="contact-page__left">
                            <div class="contact-page__left-head">
                                <h3 class="contact-page__left-title">Contact details</h3>
                                <p class="contact-page__left-text">Use the form or contact us directly through the details below.</p>
                            </div>
                            <div class="row">
                                <!--Contact Page Contact Single Start-->
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="contact-page__contact-single">
                                        <div class="contact-page__contact-icon">
                                            <span class="icon-pin"></span>
                                            <div class="contact-page__contact-icon-shape"></div>
                                        </div>
                                        <h3 class="contact-page__contact-single-title">Our Address</h3>
                                        <p>38 Elm Street, ST6 2HN, Stoke-on-Trent, United Kingdom</p>
                                    </div>
                                </div>
                                <!--Contact Page Contact Single End-->
                                <!--Contact Page Contact Single Start-->
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="contact-page__contact-single">
                                        <div class="contact-page__contact-icon">
                                            <span class="icon-user"></span>
                                            <div class="contact-page__contact-icon-shape"></div>
                                        </div>
                                        <h3 class="contact-page__contact-single-title">Contact Info</h3>
                                        <p><a href="tel:+447478034328">+44 7478034328</a></p>
                                        <p><a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a></p>
                                    </div>
                                </div>
                                <!--Contact Page Contact Single End-->
                                <!--Contact Page Contact Single Start-->
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="contact-page__contact-single">
                                        <div class="contact-page__contact-icon">
                                            <span class="icon-live-chat"></span>
                                            <div class="contact-page__contact-icon-shape"></div>
                                        </div>
                                        <h3 class="contact-page__contact-single-title">Live Support</h3>
                                        <p>Use email or direct message to start the conversation. New enquiries are reviewed quickly during business hours.</p>
                                    </div>
                                </div>
                                <!--Contact Page Contact Single End-->
                                <!--Contact Page Contact Single Start-->
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="contact-page__contact-single">
                                        <div class="contact-page__contact-icon">
                                            <span class="icon-time"></span>
                                            <div class="contact-page__contact-icon-shape"></div>
                                        </div>
                                        <h3 class="contact-page__contact-single-title">Working Hour</h3>
                                        <p>10:00 AM - 6:00 PM
                                            <br> Monday - Friday </p>
                                    </div>
                                </div>
                                <!--Contact Page Contact Single End-->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="contact-page__right contact-page__right--start">
                            <div class="contact-page__contact-form-title-box">
                                <div class="contact-page__contact-form-title-icon contact-page__contact-form-title-icon--pill">
                                    <span>Step 1 of 3</span>
                                </div>
                                <h3 class="contact-page__contact-form-title">Start your project enquiry</h3>
                            </div>
                            <p class="contact-page__contact-form-text">Share the basics and we will reply with the next clear step.</p>
                            @if($flowContext)
                                <div class="contact-page__flow-box">
                                    <p class="contact-page__flow-kicker">{{ $flowContext['kicker'] }}</p>
                                    <h4 class="contact-page__flow-title">{{ $flowContext['title'] }}</h4>
                                    <p class="contact-page__flow-text">{{ $flowContext['description'] }}</p>
                                    @if($flowPlanDescriptor !== '')
                                        <p class="contact-page__flow-text"><strong>Selected package:</strong> {{ $flowPlanDescriptor }}</p>
                                    @endif
                                    <ul class="list-unstyled contact-page__flow-list">
                                        @foreach($flowContext['steps'] as $flowStep)
                                            <li><span class="icon-check"></span> {{ $flowStep }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="contact-page__trust-strip">
                                <h4>Clear reply, clear next step, no vague sales follow-up.</h4>
                            </div>
                            <div class="contact-page__form-shell">
                                <form class="contact-form-validated contact-page__form" action="{{ route('contact.submit') }}"
                                    method="post" novalidate="novalidate" data-ga4-form-name="contact_page">
                                    @csrf
                                    <input type="hidden" name="form_type" value="{{ $prefillFormType }}">
                                    <input type="hidden" name="start_order_payment" value="0" data-order-pay-flag>
                                    <input type="hidden" name="payment_intent" value="{{ $flowIntent }}">
                                    <input type="hidden" name="project_type" value="{{ old('project_type', $flowProjectType) }}">
                                    <input type="hidden" name="selected_plan_price" value="{{ old('selected_plan_price', is_numeric($flowBasePrice) ? (float) $flowBasePrice : '') }}">
                                    <input type="hidden" name="budget_range" value="{{ old('budget_range', $flowContext['budget_hint'] ?? '') }}">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="contact-page__input-box">
                                                <div class="contact-page__input-icon">
                                                    <span class="icon-user"></span>
                                                </div>
                                                <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required="">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="contact-page__input-box">
                                                <div class="contact-page__input-icon">
                                                    <span class="icon-envelope"></span>
                                                </div>
                                                <input type="email" name="email" placeholder="Business Email" value="{{ old('email', request()->query('email')) }}" required="">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="contact-page__input-box">
                                                @if($showActionSelector)
                                                    <div class="contact-page__input-icon">
                                                        <span class="icon-credit-card"></span>
                                                    </div>
                                                    <select class="ignore" name="action_mode" data-contact-action data-direct-enabled="1">
                                                        <option value="message" @selected($prefillActionMode === 'message')>Send message only</option>
                                                        <option value="pay" @selected($prefillActionMode === 'pay')>Pay now and start order</option>
                                                    </select>
                                                @else
                                                    <input type="hidden" name="action_mode" value="message">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="contact-page__input-box">
                                                <div class="contact-page__input-icon">
                                                    <span class="icon-resume"></span>
                                                </div>
                                                <input type="text" name="subject" placeholder="Project Type / Subject" value="{{ $prefillSubject }}" required="">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="contact-page__input-box text-message-box">
                                                <div class="contact-page__input-icon">
                                                    <span class="icon-write"></span>
                                                </div>
                                                <textarea name="message" placeholder="Share your website URL, target audience, and goal">{{ $prefillMessage }}</textarea>
                                            </div>
                                            <div class="contact-page__btn-box">
                                                <button type="submit" class="thm-btn contact-page__btn" data-primary-submit><span
                                                        class="icon-right"></span><span data-primary-submit-label>SEND MESSAGE</span></button>
                                                <p style="margin:10px 0 0;font-size:13px;color:#4b6187;" data-contact-action-hint>
                                                    {{ $showActionSelector ? 'Choose one action: send message only, or pay now and start order.' : 'Standard enquiry mode. We will review and respond quickly.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="result"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Contact Page End-->

        <!--Google Map One Start-->
        <section class="google-map-one">
            <div class="container">
                <div class="google-map-one__inner">
                    <iframe
                        src="https://www.google.com/maps?q=38+Elm+Street,+Stoke-on-Trent,+ST6+2HN,+United+Kingdom&output=embed&hl=en&gl=GB"
                        class="google-map__one" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>
        <!--Google Map One End-->

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
                                    <span class="section-title-two__tagline">Contact FAQs</span>
                                </div>
                                <h2 class="section-title-two__title title-animation">Before You <br>
                                    <span>Start a Project</span> </h2>
                            </div>
                            <p class="faq-one__text">Quick answers for new clients about proposal flow, timeline,
                                communication, and project onboarding.</p>
                            <div class="faq-one__btn-box">
                                <a href="/pricing" class="faq-one__btn thm-btn thm-btn-two"><span class="icon-right"></span> View Packages</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6">
                        <div class="faq-one__right">
                            <h3 class="seo-hidden-heading">Contact page frequently asked questions</h3>
                            <div class="accrodion-grp faq-one-accrodion" data-grp-name="contact-faq-accrodion">
                                <div class="accrodion active">
                                    <div class="accrodion-title">
                                        <h4>What details should I share in the contact form?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Share business type, required service, target timeline, current website
                                                status, and budget range. This helps us send an accurate plan faster.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>How soon will I receive a response?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Most business inquiries receive a response within one working day. Priority
                                                project requests are usually reviewed earlier.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>Do you provide a fixed quote or custom proposal?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Both. Standard services can start from package pricing, while custom CRM,
                                                portal, or complex builds are provided through milestone-based proposals.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>Can we schedule a call before finalizing scope?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Yes. We offer consultation calls to define requirements, priorities, and
                                                delivery stages before development starts.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>Will I get progress updates after project starts?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Yes. Updates are shared through milestone reporting and structured
                                                communication so you always know what is completed and what is next.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accrodion">
                                    <div class="accrodion-title">
                                        <h4>Do you support clients outside the UK?</h4>
                                    </div>
                                    <div class="accrodion-content">
                                        <div class="inner">
                                            <p>Yes. We work with clients in UK, USA, Canada, Germany, India, Pakistan and
                                                other regions with country-aware communication and delivery planning.</p>
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
                        <form class="newsletter-two__form newsletter-form-validated" action="{{ route('contact.submit') }}" method="post" data-ga4-form-name="newsletter_contact">
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
                var PRICING_SESSION_KEY = 'ars_selected_pricing_plan_v1';
                var actionSelect = document.querySelector('[data-contact-action]');
                if (!actionSelect) return;

                var form = actionSelect.closest('form');
                if (!form) return;

                var labelNode = form.querySelector('[data-primary-submit-label]');
                var flagInput = form.querySelector('[data-order-pay-flag]');
                var hintNode = form.querySelector('[data-contact-action-hint]');
                var projectTypeInput = form.querySelector('[name="project_type"]');
                var priceInput = form.querySelector('[name="selected_plan_price"]');
                var budgetInput = form.querySelector('[name="budget_range"]');
                var subjectInput = form.querySelector('[name="subject"]');
                var messageInput = form.querySelector('[name="message"]');

                function parseStoredPlan() {
                    try {
                        var raw = window.sessionStorage.getItem(PRICING_SESSION_KEY);
                        if (!raw) return null;
                        var parsed = JSON.parse(raw);
                        if (!parsed || typeof parsed !== 'object') return null;
                        return parsed;
                    } catch (e) {
                        return null;
                    }
                }

                function firstNonEmpty() {
                    for (var i = 0; i < arguments.length; i++) {
                        var value = arguments[i];
                        if (value !== null && value !== undefined && String(value).trim() !== '') {
                            return String(value).trim();
                        }
                    }
                    return '';
                }

                function hydrateFromPricingContext() {
                    var params = new URLSearchParams(window.location.search);
                    var intent = firstNonEmpty(params.get('intent')).toLowerCase();
                    var allowPricingHydration = intent === 'kickoff_payment' || intent === 'order';
                    var stored = parseStoredPlan();

                    var resolvedPlan = firstNonEmpty(
                        params.get('plan'),
                        projectTypeInput ? projectTypeInput.value : '',
                        allowPricingHydration && stored ? stored.plan : ''
                    );
                    var resolvedPrice = firstNonEmpty(
                        params.get('price'),
                        priceInput ? priceInput.value : '',
                        allowPricingHydration && stored ? stored.price : ''
                    );
                    var resolvedBilling = firstNonEmpty(
                        params.get('billing'),
                        allowPricingHydration && stored ? stored.billing : ''
                    );

                    if (projectTypeInput && resolvedPlan !== '') {
                        projectTypeInput.value = resolvedPlan;
                    }
                    if (priceInput && resolvedPrice !== '') {
                        priceInput.value = resolvedPrice;
                    }
                    if (budgetInput && budgetInput.value.trim() === '' && resolvedPrice !== '') {
                        budgetInput.value = 'Selected package: GBP ' + resolvedPrice;
                    }
                    if (subjectInput && subjectInput.value.trim() === '' && resolvedPlan !== '') {
                        subjectInput.value = resolvedPlan + (resolvedBilling ? ' (' + resolvedBilling.replace(/_/g, ' ') + ')' : '');
                    }
                    if (messageInput && messageInput.value.trim() === '' && resolvedPlan !== '') {
                        messageInput.value = 'I am ready to start and request kickoff invoice/payment link for ' + resolvedPlan + '. Please share next onboarding steps.';
                    }
                }

                function hasPayablePrice() {
                    if (!priceInput) return false;
                    var value = String(priceInput.value || '').trim();
                    if (value === '') return false;
                    var amount = parseFloat(value.replace(/[^0-9.\-]/g, ''));
                    return !isNaN(amount) && amount > 0;
                }

                function syncActionState() {
                    var selectedMode = actionSelect.value;
                    if (selectedMode === 'pay' && !hasPayablePrice()) {
                        actionSelect.value = 'message';
                        selectedMode = 'message';
                    }

                    var isPayNow = selectedMode === 'pay' && hasPayablePrice();

                    if (flagInput) {
                        flagInput.value = isPayNow ? '1' : '0';
                    }
                    if (labelNode) {
                        labelNode.textContent = isPayNow ? 'PAY NOW' : 'SEND MESSAGE';
                    }
                    if (hintNode) {
                        if (isPayNow) {
                            hintNode.textContent = 'You will be redirected to secure Stripe checkout after submit.';
                        } else {
                            hintNode.textContent = 'Standard enquiry mode. We will review and respond quickly.';
                        }
                    }
                }

                hydrateFromPricingContext();
                actionSelect.addEventListener('change', syncActionState);

                syncActionState();
            })();
        </script>



@include('layouts.footer')
