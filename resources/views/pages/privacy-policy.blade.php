@php
    $page_title = 'Privacy Policy';
    $seoOverride = [
        'title' => 'Privacy Policy for ARSDeveloper UK Services',
        'description' => 'Read how ARSDeveloper UK collects, stores, and protects personal data for enquiries, meetings, projects, billing, and support.',
        'keywords' => 'privacy policy uk software agency, gdpr data handling uk, arsdeveloper privacy notice',
        'robots' => 'noindex, follow',
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
            <h1>Privacy <span>Policy</span></h1>
            <div class="seo-heading-ladder" aria-hidden="true">
                <h2 class="seo-hidden-heading">Core page sections</h2>
                <h3 class="seo-hidden-heading">Section details</h3>
                <h4 class="seo-hidden-heading">Supporting information</h4>
                <h5 class="seo-hidden-heading">Additional notes</h5>
                <h6 class="seo-hidden-heading">Reference points</h6>
            </div>
            <div class="thm-breadcrumb__inner">
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

                <h3 style="margin-top: 28px;">10. Contact and complaints</h3>
                <p>For privacy requests, email <a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a> with subject line <strong>Privacy Request</strong>. We aim to acknowledge within 2 business days and resolve within a reasonable period depending on request complexity.</p>

                <h4 style="margin-top: 28px;">Last updated</h4>
                <p>{{ $policyLastUpdated }}</p>
            </div>
        </div>
    </div>
</section>

@include('layouts.footer')
