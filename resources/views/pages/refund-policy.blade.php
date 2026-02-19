@php
    $page_title = 'Refund Policy';
    $seoOverride = [
        'title' => 'Refund Policy for ARSDeveloper UK Services',
        'description' => 'Read ARSDeveloper refund policy for project milestones, subscriptions, cancellations, third-party costs, and chargeback handling.',
        'keywords' => 'refund policy uk software agency, web development refund terms uk, arsdeveloper refunds',
        'robots' => 'noindex, follow',
        'type' => 'WebPage',
    ];
    $refundLastUpdated = '17 February 2026';
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
    <div class="page-header__shape-1">
        <img src="assets/images/shapes/page-header-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Refund <span>Policy</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Refund Policy</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="faq-page" style="padding: 120px 0;">
    <div class="container">
        <div class="row">
            <div class="col-xl-10">
                <h2 style="margin-bottom: 16px;">Refund and cancellation terms</h2>
                <p><strong>{{ config('company.legal_name') }} (Company No: {{ config('company.company_number') }})</strong> is a {{ strtolower(config('company.company_type')) }} registered in {{ config('company.registered_in') }}.</p>
                <p>This policy explains how ARSDeveloper handles refund requests for UK web development, CRM, SEO, support plans, and related services.</p>

                <h3 style="margin-top: 28px;">1. General principle</h3>
                <p>Refund decisions are based on work completed, delivery status, reserved resources, and third-party costs already committed for your project.</p>

                <h3 style="margin-top: 28px;">2. Discovery, planning, and consultation services</h3>
                <p>Fees for completed discovery, consulting, architecture planning, or strategy sessions are generally non-refundable once delivered.</p>

                <h3 style="margin-top: 28px;">3. Milestone-based project work</h3>
                <p>For custom projects, each paid milestone covers allocated team time and delivered output. Completed or partially completed milestones are not fully refundable. Any approved but not-started scope may be reviewed for partial refund at our discretion.</p>

                <h3 style="margin-top: 28px;">4. Monthly support or subscription services</h3>
                <p>Subscription charges are typically non-refundable for the active billing period once service has started. Cancellation usually applies to future billing cycles unless agreed otherwise in writing.</p>

                <h3 style="margin-top: 28px;">5. Third-party and pass-through costs</h3>
                <p>Domain, hosting, plugins, paid APIs, paid themes, ads spend, and other third-party costs are controlled by external providers and are not refundable by ARSDeveloper once incurred.</p>

                <h3 style="margin-top: 28px;">6. How to request a refund review</h3>
                <p>Send your request to <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a> with:</p>
                <ul style="margin: 10px 0 0 18px;">
                    <li>Project reference or invoice number</li>
                    <li>Reason for request</li>
                    <li>Supporting timeline/details</li>
                </ul>
                <p style="margin-top:10px;">We aim to acknowledge within 2 business days and issue a decision after reviewing delivery records and contractual scope.</p>

                <h3 style="margin-top: 28px;">7. Chargebacks and payment disputes</h3>
                <p>If a chargeback is opened without prior support contact, we may suspend related services while the dispute is reviewed. We always recommend contacting us first for an audit trail and faster resolution.</p>

                <h3 style="margin-top: 28px;">8. Anti-scam payment protection</h3>
                <p>Please verify any payment instruction against official ARS communication channels before transfer. ARSDeveloper is not liable for funds sent to impersonated or unverified recipients.</p>

                <h3 style="margin-top: 28px;">9. Contact for legal or billing support</h3>
                <p>Email: <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a><br>Phone: <a href="tel:+44747803428">+44 747803428</a><br>Registered Office: {{ config('company.registered_office') }}</p>

                <h4 style="margin-top: 28px;">Last updated</h4>
                <p>{{ $refundLastUpdated }}</p>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
