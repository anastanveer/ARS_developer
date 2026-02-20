@php
    $page_title = 'Terms and Conditions';
    $seoOverride = [
        'title' => 'Terms and Conditions for ARSDeveloper UK Projects',
        'description' => 'Read ARSDeveloper terms covering scope, approvals, payments, intellectual property, liability, and dispute handling for UK projects.',
        'keywords' => 'terms and conditions uk software agency, project service terms uk, arsdeveloper contract terms',
        'robots' => 'noindex, follow',
        'type' => 'WebPage',
    ];
    $termsLastUpdated = '17 February 2026';
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
    <div class="page-header__shape-1">
        <img src="assets/images/shapes/page-header-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Terms <span>& Conditions</span></h1><div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Terms and Conditions</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="faq-page" style="padding: 120px 0;">
    <div class="container">
        <div class="row">
            <div class="col-xl-10">
                <h2 style="margin-bottom: 16px;">Terms and Conditions</h2>
                <p><strong>{{ config('company.legal_name') }} (Company No: {{ config('company.company_number') }})</strong><br>{{ config('company.acts_notice') }} Registered in {{ config('company.registered_in') }}.</p>
                <p>These terms apply to website use and to services delivered by {{ config('company.legal_name') }}. By using this website, submitting a request, or proceeding with a proposal, you accept the terms below unless a separate signed agreement states otherwise.</p>

                <h3 style="margin-top: 28px;">1. Scope and contractual documents</h3>
                <p>Project scope, timeline, pricing, and deliverables are defined in written documents such as proposal, statement of work, invoice notes, or signed contract. If there is conflict, the latest signed document takes priority.</p>

                <h3 style="margin-top: 28px;">2. Change requests</h3>
                <p>Requirements outside agreed scope are handled as change requests. This may affect budget, timeline, milestones, or delivery date. Work on additional requests starts after written approval.</p>

                <h3 style="margin-top: 28px;">3. Client responsibilities</h3>
                <p>Clients are responsible for providing accurate content, approvals, access credentials, and timely feedback. Delays in client input may move project deadlines.</p>

                <h3 style="margin-top: 28px;">4. Pricing, invoicing, and payment</h3>
                <p>Custom work is normally billed by milestone. Support plans are billed on agreed schedule. Invoices must be paid within agreed terms. ARSDeveloper may pause or reschedule work where invoices remain unpaid.</p>

                <h3 style="margin-top: 28px;">5. Delivery and acceptance</h3>
                <p>Deliverables are considered accepted when approved in writing, moved to production, or used in business operations. Minor revisions after acceptance may be treated as new scope unless covered in warranty terms.</p>

                <h3 style="margin-top: 28px;">6. Intellectual property and licences</h3>
                <p>Ownership of custom deliverables transfers according to agreed contract terms after cleared payment. Third-party assets (themes, plugins, APIs, fonts, stock media, SaaS tools) remain subject to their original licences and vendor terms.</p>

                <h3 style="margin-top: 28px;">7. Performance and platform disclaimer</h3>
                <p>We apply best-practice development and SEO methods, but external platforms (search engines, ad networks, social platforms, app stores, payment providers) control final ranking, visibility, policy status, and service uptime.</p>

                <h3 style="margin-top: 28px;">8. Service interruption and force majeure</h3>
                <p>We are not liable for delay or failure caused by events beyond reasonable control, including outages, cyber incidents, regulatory changes, or third-party service failures.</p>

                <h3 style="margin-top: 28px;">9. Limitation of liability</h3>
                <p>To the extent allowed by law, ARSDeveloper is not liable for indirect, consequential, or loss-of-profit claims. Maximum liability for direct proven loss is limited to fees paid for the affected service scope.</p>

                <h3 style="margin-top: 28px;">10. Anti-fraud and payment safety</h3>
                <p>Clients must verify payment instructions through official ARS channels before payment. We are not responsible for losses resulting from payments sent to unverified or impersonated accounts.</p>

                <h3 style="margin-top: 28px;">11. Complaints and dispute handling</h3>
                <p>For contract, billing, or delivery concerns, email <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a> with full project reference. We aim to review complaints in good faith and provide a response within a reasonable business timeframe.</p>

                <h3 style="margin-top: 28px;">12. Governing law</h3>
                <p>These terms are governed by the laws applicable in the United Kingdom, unless otherwise specified in a signed agreement.</p>

                <h4 style="margin-top: 28px;">Last updated</h4>
                <p>{{ $termsLastUpdated }}</p>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
