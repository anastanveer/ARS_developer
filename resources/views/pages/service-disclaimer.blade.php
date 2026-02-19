@php
    $page_title = 'Service Disclaimer';
    $seoOverride = [
        'title' => 'Service Disclaimer for ARSDeveloper UK',
        'description' => 'Important service disclaimer on third-party platforms, expected outcomes, and communication safety for ARSDeveloper clients.',
        'keywords' => 'service disclaimer uk software agency, arsdeveloper disclaimer, website service limitations uk',
        'robots' => 'noindex, follow',
        'type' => 'WebPage',
    ];
    $disclaimerLastUpdated = '17 February 2026';
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
    <div class="page-header__shape-1">
        <img src="assets/images/shapes/page-header-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Service <span>Disclaimer</span></h1>
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Service Disclaimer</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="faq-page" style="padding: 120px 0;">
    <div class="container">
        <div class="row">
            <div class="col-xl-10">
                <h2 style="margin-bottom: 16px;">Important disclaimer for website and project services</h2>
                <p><strong>{{ config('company.legal_name') }} (Company No: {{ config('company.company_number') }})</strong> is a {{ strtolower(config('company.company_type')) }} registered in {{ config('company.registered_in') }}.</p>
                <p>This page clarifies service boundaries so clients can make informed decisions with realistic expectations.</p>

                <h3 style="margin-top: 28px;">1. Informational content</h3>
                <p>Website pages, audit summaries, and proposal notes are for business guidance only and are not legal, tax, or regulated financial advice.</p>

                <h3 style="margin-top: 28px;">2. Third-party platform dependency</h3>
                <p>Search rankings, ad account status, payment gateway approval, plugin behaviour, hosting uptime, and API availability are controlled by third parties. ARSDeveloper cannot guarantee outcomes controlled by external vendors.</p>

                <h3 style="margin-top: 28px;">3. Estimates and timelines</h3>
                <p>Delivery estimates are based on known scope at the time of agreement. Changes, delayed approvals, or third-party blockers can affect schedule and cost.</p>

                <h3 style="margin-top: 28px;">4. Security and abuse risk</h3>
                <p>We apply professional security practices, but no online system can be guaranteed as risk-free. Clients should maintain strong passwords, access controls, and internal security procedures.</p>

                <h3 style="margin-top: 28px;">5. Communication and payment safety</h3>
                <p>We never ask clients to trust unverified payment requests. Always confirm suspicious instructions via <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a> before paying any invoice.</p>

                <h3 style="margin-top: 28px;">6. Limitation note</h3>
                <p>Where legally permitted, ARSDeveloper is not liable for indirect loss or third-party service interruption beyond our direct control.</p>

                <h3 style="margin-top: 28px;">7. Contact</h3>
                <p>For clarification on any project or policy point, contact <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a>.</p>

                <h4 style="margin-top: 28px;">Last updated</h4>
                <p>{{ $disclaimerLastUpdated }}</p>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
