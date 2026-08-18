@php
    $page_title = 'Cookie Policy';
    $seoOverride = [
        'title' => 'Cookie Policy for ARSDeveloper UK Website',
        'description' => 'Learn how ARSDeveloper uses essential and analytics cookies, and how visitors can manage browser-level cookie controls.',
        'keywords' => 'cookie policy uk website, analytics cookies policy uk, arsdeveloper cookie notice',
        'robots' => 'index, follow',
        'type' => 'WebPage',
    ];
    $cookieLastUpdated = '17 February 2026';
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
    <div class="page-header__shape-1">
        <img src="assets/images/shapes/page-header-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Cookie <span>Policy</span></h1><div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Cookie Policy</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="faq-page" style="padding: 120px 0;">
    <div class="container">
        <div class="row">
            <div class="col-xl-10">
                <h2 style="margin-bottom: 16px;">Cookie Policy</h2>
                <p><strong>{{ config('company.legal_name') }} (Company No: {{ config('company.company_number') }})</strong> is a {{ strtolower(config('company.company_type')) }} registered in {{ config('company.registered_in') }}.</p>
                <p>This policy explains how cookies and similar technologies may be used on ARSDeveloper pages to keep core features working and to improve site performance.</p>

                <h3 style="margin-top: 28px;">1. What cookies are</h3>
                <p>Cookies are small text files placed on your device when you browse a website. They can help remember session data, settings, and website interactions.</p>

                <h3 style="margin-top: 28px;">2. Cookie categories we may use</h3>
                <h4 style="margin-top: 16px;">Essential cookies</h4>
                <p>Required for core functionality such as navigation integrity, form handling, and session continuity.</p>
                <h4 style="margin-top: 16px;">Performance or analytics cookies</h4>
                <p>Used to measure traffic trends and page behaviour so we can improve website speed, structure, and usability.</p>
                <h4 style="margin-top: 16px;">Preference cookies</h4>
                <p>May remember selected preferences (such as display settings) where applicable.</p>

                <h3 style="margin-top: 28px;">3. Third-party technologies</h3>
                <p>Some features may rely on third-party tools (for example analytics or embedded services). These providers may set their own cookies under their own privacy terms.</p>

                <h3 style="margin-top: 28px;">4. Managing cookies</h3>
                <p>You can control or remove cookies via your browser settings. Blocking essential cookies may affect parts of site functionality, including forms or portal-related actions.</p>

                <h3 style="margin-top: 28px;">5. Security and fraud awareness</h3>
                <p>Cookies are not used by us to request payment credentials by email or chat. If you receive suspicious messages claiming to be ARSDeveloper, verify through <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a>.</p>

                <h3 style="margin-top: 28px;">6. Analytics and advertising cookies</h3>
                <p><strong>Analytics.</strong> Google Analytics sets cookies that count visits and record which pages are read. They identify a browser, not a person, and IP addresses are anonymised.</p>
                <p><strong>Advertising.</strong> This site may display ads served through Google AdSense. Google uses cookies &mdash; including the DoubleClick DART cookie &mdash; to serve ads based on your previous visits to this and other sites. Third-party vendors and ad networks other than Google may also serve ads here and set their own cookies to measure performance. We cannot read those cookies and do not receive the data behind them.</p>
                <p>Only essential cookies are genuinely necessary. Blocking the analytics and advertising ones costs you nothing on this site &mdash; you will simply see less relevant ads. You can opt out at <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">google.com/settings/ads</a>, <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">aboutads.info/choices</a> or <a href="https://www.youronlinechoices.eu/" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">youronlinechoices.eu</a>, or block cookies in your browser settings.</p>
                <p>Visitors in the UK, EEA and Switzerland are asked for consent before any advertising cookie is set, and that choice can be changed at any time.</p>

                <h3 style="margin-top: 28px;">7. Policy updates</h3>
                <p>This policy may be updated when technology, regulations, or integrations change. The latest version is always shown on this page.</p>

                <h4 style="margin-top: 28px;">Last updated</h4>
                <p>{{ $cookieLastUpdated }}</p>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
