@php
    $page_title = 'Privacy Policy';
    $seoOverride = [
        'title' => 'Privacy Policy for ARSDeveloper UK Services',
        'description' => 'Read how ARSDeveloper UK collects, stores, and protects personal data for enquiries, meetings, projects, billing, and support.',
        'keywords' => 'privacy policy uk software agency, gdpr data handling uk, arsdeveloper privacy notice',
        'robots' => 'index, follow',
        'type' => 'WebPage',
    ];
    $policyLastUpdated = '17 February 2026';
@endphp
@include('layouts.header')

<section class="page-header">
    <div class="page-header__bg" style="background-image: url(assets/images/shapes/page-header-bg-shape.png);"></div>
    <div class="page-header__shape-1">
        <img src="assets/images/shapes/page-header-shape-1.png" alt="">
    </div>
    <div class="container">
        <div class="page-header__inner">
            <h1>Privacy <span>Policy</span></h1><div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><i class="icon-home"></i><a href="{{ url('/') }}">Home</a></li>
                    <li><span></span></li>
                    <li>Privacy Policy</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="faq-page" style="padding: 120px 0;">
    <div class="container">
        <div class="row">
            <div class="col-xl-10">
                <h2 style="margin-bottom: 16px;">Privacy Notice for {{ config('company.legal_name') }}</h2>
                <p>This notice explains how {{ config('company.legal_name') }} handles personal data when you visit our website, submit an enquiry, book a meeting, or become a project client. We process data in line with UK GDPR principles and only where there is a clear business or legal basis.</p>

                <h3 style="margin-top: 28px;">1. Who controls your data</h3>
                <p>Business name: <strong>{{ config('company.legal_name') }}</strong><br>Company No: <strong>{{ config('company.company_number') }}</strong><br>Registered in: <strong>{{ config('company.registered_in') }}</strong><br>Company type: <strong>{{ config('company.company_type') }}</strong><br>Date of incorporation: <strong>{{ config('company.incorporation_date') }}</strong><br>Website: <strong>arsdeveloper.co.uk</strong><br>Email: <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a><br>Registered Office: {{ config('company.registered_office') }}</p>

                <h3 style="margin-top: 28px;">2. Data we collect</h3>
                <p>Depending on your interaction, we may collect:</p>
                <ul style="margin: 10px 0 0 18px;">
                    <li>Contact details (name, email, phone, company)</li>
                    <li>Project details (requirements, budget range, timeline, meeting preferences)</li>
                    <li>Operational records (support requests, invoice references, portal actions)</li>
                    <li>Technical data (IP address, browser type, pages visited, basic device metadata)</li>
                </ul>

                <h3 style="margin-top: 28px;">3. Why we process data</h3>
                <p>We use personal data to:</p>
                <ul style="margin: 10px 0 0 18px;">
                    <li>Respond to enquiries and provide quotations</li>
                    <li>Schedule and manage meetings</li>
                    <li>Deliver web, CRM, SEO, and support services</li>
                    <li>Issue invoices and keep delivery records</li>
                    <li>Protect website security and prevent abuse/fraud</li>
                </ul>

                <h3 style="margin-top: 28px;">4. Lawful basis for processing</h3>
                <p>Our lawful basis may include consent, contract performance, legitimate interests, and legal obligations (for example, finance/tax record requirements).</p>

                <h3 style="margin-top: 28px;">5. Data sharing and processors</h3>
                <p>We do not sell personal data. Data may be shared only with necessary service providers such as hosting, email delivery, analytics, payment, or cloud tools under appropriate safeguards and confidentiality controls.</p>

                <h3 style="margin-top: 28px;">6. Data retention</h3>
                <p>We retain data only as long as required for delivery, support, compliance, and dispute handling. Retention periods may differ by data type and contractual/legal obligations.</p>

                <h3 style="margin-top: 28px;">7. Your data rights</h3>
                <p>You can request access, correction, deletion, restriction, objection, or data portability where applicable. We may require identity verification before processing rights requests.</p>

                <h3 style="margin-top: 28px;">8. Security controls</h3>
                <p>We apply proportionate administrative and technical controls including access restriction, secure service configuration, activity monitoring, and controlled data handling procedures.</p>

                <h3 style="margin-top: 28px;">9. Anti-fraud communication notice</h3>
                <p>For your protection:</p>
                <ul style="margin: 10px 0 0 18px;">
                    <li>Official communication is issued via verified ARS channels.</li>
                    <li>If any suspicious payment request appears, confirm via <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a> before acting.</li>
                    <li>We recommend clients verify invoice references and portal links before payment.</li>
                </ul>

                <h3 style="margin-top: 28px;">10. Analytics</h3>
                <p>We use Google Analytics to understand how the site is used &mdash; which pages get read, how visitors arrive, and where they drop off. It sets cookies that record a randomised identifier for your browser, not your identity, and IP addresses are anonymised. We use this to decide what to write and what to fix, nothing else.</p>
                <p>If you would rather not be measured at all, Google publishes a browser add-on that blocks it everywhere: <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">tools.google.com/dlpage/gaoptout</a>.</p>

                <h3 style="margin-top: 28px;">11. Advertising</h3>
                <p>This site may display advertising served through Google AdSense. A few things follow from that, and you should know all of them.</p>
                <ul style="margin: 10px 0 0 18px;">
                    <li>Google uses cookies &mdash; including the DoubleClick DART cookie &mdash; to serve ads based on your previous visits to this site and other sites on the internet.</li>
                    <li>Third-party vendors and ad networks other than Google may also serve ads here, and may set their own cookies to measure how those ads perform.</li>
                    <li>We do not control those cookies, cannot read them, and do not receive the personal data behind them. We see aggregate performance only.</li>
                </ul>
                <p style="margin-top: 12px;">You can switch personalised advertising off without going through us:</p>
                <ul style="margin: 10px 0 0 18px;">
                    <li>Google&rsquo;s own controls: <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">google.com/settings/ads</a></li>
                    <li>Many vendors at once: <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">aboutads.info/choices</a> or <a href="https://www.youronlinechoices.eu/" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">youronlinechoices.eu</a></li>
                    <li>The full vendor list: <a href="https://policies.google.com/technologies/partner-sites" target="_blank" rel="noopener noreferrer" style="text-decoration: underline;">Google&rsquo;s partner sites page</a></li>
                </ul>
                <p style="margin-top: 12px;">Visitors in the UK, EEA and Switzerland are shown a consent message before advertising cookies are set, and can change that choice at any time.</p>

                <h3 style="margin-top: 28px;">12. Contact and complaints</h3>
                <p>For privacy requests, email <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a> with subject line <strong>Privacy Request</strong>. We aim to acknowledge within 2 business days and resolve within a reasonable period depending on request complexity.</p>

                <h4 style="margin-top: 28px;">Last updated</h4>
                <p>{{ $policyLastUpdated }}</p>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
