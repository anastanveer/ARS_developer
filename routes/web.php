<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditReportController;
use App\Http\Controllers\Admin\BlockedContactController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\LeadEmailController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;
use App\Http\Controllers\Admin\OperationsController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\BlogCommentController as AdminBlogCommentController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ClientReviewController as AdminClientReviewController;
use App\Http\Controllers\BlogCommentController;
use App\Http\Controllers\BlogPageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServicePageImageController;
use App\Http\Controllers\Admin\SystemLogController;
use App\Http\Controllers\ClientReviewController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\ChatWidgetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeetingBookingController;
use App\Http\Controllers\SeoHubController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\PortfolioPageController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\PublicInvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/chat/bootstrap', [ChatWidgetController::class, 'bootstrap'])->name('chat.bootstrap');
Route::post('/chat/profile', [ChatWidgetController::class, 'profile'])->name('chat.profile');
Route::post('/chat/message', [ChatWidgetController::class, 'message'])->name('chat.message');
Route::get('/chat/conversation/{token}', [ChatWidgetController::class, 'conversation'])->name('chat.conversation');
Route::get('/whatsapp/webhook', [\App\Http\Controllers\WhatsAppWebhookController::class, 'verify'])->name('whatsapp.webhook.verify');
Route::post('/whatsapp/webhook', [\App\Http\Controllers\WhatsAppWebhookController::class, 'receive'])->name('whatsapp.webhook.receive');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemaps/{section}.xml', [SitemapController::class, 'section'])
    ->where('section', 'pages|portfolio|blog')
    ->name('sitemap.section');

Route::get('/{indexNowKey}.txt', function (string $indexNowKey) {
    $configuredKey = trim((string) config('indexnow.key', ''));
    if ($configuredKey === '' || !hash_equals($configuredKey, $indexNowKey)) {
        abort(404);
    }

    return response($configuredKey, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->where('indexNowKey', '[A-Za-z0-9\-]{8,128}');

Route::view('/about', 'pages.about');
Route::view('/services', 'pages.services');
Route::view('/software-development', 'pages.software-development');
Route::view('/digital-marketing', 'pages.digital-marketing');
Route::view('/web-design-development', 'pages.web-design-development');
Route::view('/search-engine-optimization', 'pages.search-engine-optimization');
Route::view('/design-and-branding', 'pages.design-and-branding');
Route::view('/app-development', 'pages.app-development');
Route::get('/sectors/{sector}', function (string $sector) {
    $sectors = [
        'healthcare' => [
            'name' => 'Healthcare Clinics',
            'headline' => 'Healthcare Website and Booking Systems for UK Clinics',
            'summary' => 'Appointment-ready websites, patient enquiry flows, and secure lead handling for private clinics and healthcare practices in the UK.',
            'keywords' => 'healthcare website development uk, clinic website design uk, private clinic seo uk, medical lead generation website uk',
            'highlights' => [
                'Patient-friendly landing pages with trust-first UX',
                'Online appointment request and callback workflows',
                'Location pages, service pages, and clinician profile setup',
                'GDPR-aware form capture and admin notification flow',
            ],
            'body' => [
                'Private clinics lose enquiries in the gap between someone deciding they need an appointment and actually asking for one. A patient arriving from a Google search is usually comparing two or three practices, on a phone, and is deciding on a small number of things: whether the clinic treats their specific problem, who the clinician is, where the clinic actually is, and how quickly they can be seen. A site that buries any of those behind a menu loses the enquiry to whichever competitor answered faster.',
                'The build starts with the service pages, because that is what people search for — a condition or a treatment, not the clinic\'s name. Each treatment gets its own page written in the words patients use rather than clinical terminology, with the practical questions answered on the page: what the appointment involves, how long it takes, what it costs or what the consultation costs, and what happens next. Clinician profiles carry qualifications and registration numbers, because in healthcare the person matters as much as the practice.',
                'Enquiry handling is where most clinic sites are weakest. A contact form that emails an inbox nobody watches on a Friday afternoon is not a booking system. We build appointment-request and callback flows that capture what the clinic needs to triage — treatment, preferred times, whether the patient is new or returning — notify the right person immediately, and confirm to the patient that the request arrived. Form data is handled with GDPR in mind: no unnecessary fields, clear consent wording, and no patient detail sitting in a system that does not need it.',
                'For clinics with more than one site, location pages carry the details Google\'s local results depend on — address, opening hours, parking, nearest transport — kept consistent with the Google Business Profile, since a mismatch between the two is one of the most common reasons a clinic ranks below a competitor in map results.',
            ],
            'faq' => [
                ['q' => 'How long does a clinic website take to build?', 'a' => 'A single-location clinic site with treatment pages, clinician profiles and an enquiry workflow is typically four to six weeks, most of which is content: writing treatment pages that answer real patient questions takes longer than building them.'],
                ['q' => 'Can patients book directly, or only request an appointment?', 'a' => 'Both are possible. Direct booking needs to connect to whatever system the clinic already runs its diary in, so it depends on that system\'s API. Where it does not exist or is closed, a structured appointment request that reaches reception with everything needed to book is faster to deliver and creates less risk of double-booking.'],
                ['q' => 'Is the site GDPR compliant?', 'a' => 'The forms are built to collect only what is needed to respond, with explicit consent wording and no health detail requested in a public form. Full compliance also depends on how the clinic stores and handles data after it arrives, which we advise on but do not control.'],
            ],
            'related_links' => ['/services', '/web-design-development', '/search-engine-optimization', '/portfolio', '/pricing', '/contact'],
        ],
        'law-firms' => [
            'name' => 'Law Firms',
            'headline' => 'Conversion-Focused Digital Setup for UK Law Firms',
            'summary' => 'Structured legal service pages, consultation enquiry funnels, and credibility-focused design that converts visitors into case enquiries.',
            'keywords' => 'law firm website design uk, solicitor seo services uk, legal lead generation website uk, law practice marketing website uk',
            'highlights' => [
                'Practice-area page structure with SEO intent mapping',
                'Consultation forms and lead qualification workflow',
                'Trust badges, review blocks, and local authority signals',
                'Content architecture for long-term legal SEO growth',
            ],
            'body' => [
                'Legal enquiries are researched, not impulsive. Someone with a dispute, an injury claim or a property matter reads several firms before contacting any of them, and the reading is genuine — they are trying to work out whether the firm handles their exact situation and whether they can afford to ask. A site organised around the firm\'s internal departments rather than the client\'s problem makes that harder than it needs to be.',
                'The structure that works is one page per practice area, each written for the person with that problem rather than for other solicitors. That means explaining the process in order, saying what the first conversation covers, being clear about how fees work — fixed fee, hourly, conditional — and answering the question every prospective client has and few firm sites address: roughly how long this takes. Firms that answer the fee question openly get fewer enquiries and better ones.',
                'Credibility signals do heavy work in this sector. SRA registration, individual solicitor profiles with their qualifications and practice areas, genuine client reviews attached to the matter type they relate to, and case outcomes described within what professional conduct rules allow. These matter more than design polish; a plain page from a firm that clearly does this work beats a handsome page that could belong to anyone.',
                'On the enquiry side, the form is a qualification step rather than a contact box. Capturing the matter type, rough timeline and whether the person has instructed anyone else lets the firm route the enquiry to the right fee earner and reply with something useful instead of a generic acknowledgement. Conflict-check questions can be built into that first step where the firm wants them.',
            ],
            'faq' => [
                ['q' => 'Do you write the practice-area content?', 'a' => 'The structure and the client-facing explanation, yes. Anything that states legal position or advice is drafted with the firm and signed off by a fee earner — publishing legal content that has not been reviewed by the firm is not something we do.'],
                ['q' => 'Can the enquiry form run a conflict check?', 'a' => 'It can capture the information a conflict check needs — other parties, matter type, whether the person has instructed another firm — and route it before anyone responds. The check itself remains a decision for the firm.'],
                ['q' => 'Will this help us rank for our practice areas?', 'a' => 'Practice-area pages are the foundation, but for most firms the competitive factor locally is the Google Business Profile and review volume alongside the site. We build the site to support that rather than pretending pages alone will do it.'],
            ],
            'related_links' => ['/services', '/search-engine-optimization', '/digital-marketing', '/portfolio', '/pricing', '/contact'],
        ],
        'ecommerce' => [
            'name' => 'Ecommerce Brands',
            'headline' => 'Ecommerce Growth Systems for Shopify and WooCommerce',
            'summary' => 'Store build, catalog optimization, checkout improvements, and performance-first implementation for UK ecommerce brands.',
            'keywords' => 'shopify development uk, woocommerce development uk, ecommerce seo uk, ecommerce conversion optimization uk',
            'highlights' => [
                'Shopify and WooCommerce setup with conversion UX',
                'Product structure, category flow, and search navigation',
                'Checkout, shipping, and payment journey optimization',
                'Analytics tracking and campaign-ready landing pages',
            ],
            'body' => [
                'Most UK ecommerce stores do not have a traffic problem, they have a checkout problem. Traffic arrives, products get viewed, carts get built, and then a measurable share of buyers stop — at shipping cost, at an unexpected account requirement, at a payment method they do not use, or at a product page that failed to answer whether the item would fit. Fixing that is usually worth more than any increase in visitors.',
                'So the work starts with the product page, because that is where the decision is made. Variants and sizing have to be unambiguous, the images have to show the thing at the scale people actually care about, delivery cost and timing belong on the page rather than three steps later, and returns terms belong where the hesitation happens. On stores with sizing complexity this alone moves conversion more than a redesign.',
                'Catalogue structure comes next. Collections and filters should follow how customers shop rather than how the warehouse is organised, with category pages that can rank on their own instead of existing only as a grid. Search matters more than most stores allow for: a shopper who uses site search is far closer to buying, and a search that returns nothing for a product you stock is a sale handed to someone else.',
                'Then speed, which on Shopify usually means the theme rather than the platform — unused apps still loading their scripts, images shipped at desktop size to phones, third-party tags accumulated over years that nobody has audited. And finally the tracking, because a store that cannot see where in the funnel people leave cannot fix it. Analytics and conversion events are set up so the next decision is based on measurement rather than opinion.',
            ],
            'faq' => [
                ['q' => 'Shopify or WooCommerce?', 'a' => 'Shopify for most brands that want to sell rather than maintain infrastructure — hosting, security and payments are handled, at the cost of monthly fees and less freedom deep in checkout. WooCommerce where the store needs unusual logic, complex B2B pricing or tight integration with an existing system, and where someone will own the maintenance. The right answer depends on the catalogue and the team, not on which is generally better.'],
                ['q' => 'Can you improve our current store without rebuilding it?', 'a' => 'Usually yes, and usually that is the better first step. An audit of the product page, checkout and speed typically finds enough to work on for months. A rebuild makes sense when the theme is so heavily modified that changes are risky, not merely because the store looks dated.'],
                ['q' => 'Will a redesign increase sales?', 'a' => 'Not on its own, and anyone promising that is guessing. Removing specific friction — sizing confusion, hidden delivery cost, a slow product page — has a measurable effect. We identify which of those apply to your store before recommending work.'],
            ],
            'related_links' => ['/services', '/web-design-development', '/digital-marketing', '/search-engine-optimization', '/portfolio', '/pricing'],
        ],
        'b2b' => [
            'name' => 'B2B Service Teams',
            'headline' => 'B2B Websites, CRM Portals and Lead Workflow Automation',
            'summary' => 'Lead capture systems, CRM-aligned operations, and milestone-based project visibility for B2B service companies across the UK.',
            'keywords' => 'b2b website development uk, crm development uk, b2b lead generation website uk, workflow automation software uk',
            'highlights' => [
                'Pipeline-focused website and landing flow architecture',
                'CRM handoff automation and internal team workflows',
                'Role-based admin views and client communication timeline',
                'Reporting structure for enquiry, conversion, and follow-up',
            ],
            'body' => [
                'B2B service companies rarely lose deals on the website; they lose them in what happens after the form is submitted. An enquiry lands in a shared inbox, sits over a weekend, gets picked up by whoever notices, and is answered with a generic reply while the prospect has already spoken to two competitors. The site and the sales process have to be one system rather than two.',
                'That starts with the enquiry itself capturing enough to qualify. What the prospect is trying to achieve, rough budget band, timescale, and who else is involved in the decision — asked in a way that does not feel like an interrogation. That information routes the enquiry to the right person and lets the first reply be specific, which is the single biggest factor in whether a B2B lead progresses.',
                'From there it is CRM alignment. The enquiry should create the record automatically rather than being retyped, with the source, campaign and page it came from attached, so the business can eventually see which service pages produce clients rather than merely traffic. Follow-up sequences, ownership and stage changes live in the CRM; the site\'s job is to feed it clean data and stop the manual re-entry that causes leads to be lost.',
                'For firms running delivery as well as sales, client portals are where the real operational gain sits — a logged-in area showing project stage, what is outstanding, who is responsible and what happens next. Most of the status-chasing email a service business generates exists only because the client has no other way to find out. Role-based views keep each side seeing what is relevant, and the reporting layer turns enquiry, conversion and delivery data into something the leadership team can act on.',
            ],
            'faq' => [
                ['q' => 'Do we need a CRM before this is worth doing?', 'a' => 'Not necessarily. If enquiries are currently handled in an inbox and a spreadsheet, the first useful step is often structured capture and routing, which works without a CRM and makes choosing one easier later because you will know what you actually need.'],
                ['q' => 'Which CRMs do you integrate with?', 'a' => 'Anything with a usable API — HubSpot, Pipedrive, Zoho and Salesforce are the common ones. Where a business runs a custom or closed system, the integration is scoped separately once we have seen what it can accept.'],
                ['q' => 'What does a client portal typically cost?', 'a' => 'It depends almost entirely on how much of the delivery process it needs to reflect. A portal showing project stage, files and messages is a much smaller build than one that runs approvals, billing and resourcing. We scope it from the workflow rather than quoting a portal as a fixed product.'],
            ],
            'related_links' => ['/services', '/software-development', '/app-development', '/portfolio', '/pricing', '/contact'],
        ],
    ];

    if (!isset($sectors[$sector])) {
        abort(404);
    }

    $data = $sectors[$sector];
    $canonicalBase = rtrim((string) (app()->environment('local')
        ? url('/')
        : config('regions.regions.uk.base_url', url('/'))), '/');
    $sectorFaq = [
        [
            'question' => 'Can you deliver this sector in phased milestones?',
            'answer' => 'Yes. We split delivery into planning, build, review, and launch phases so your team can approve each stage clearly.',
        ],
        [
            'question' => 'Will this setup be SEO and speed ready?',
            'answer' => 'Yes. We implement technical SEO structure, conversion-focused content flow, and performance optimization from the initial build.',
        ],
        [
            'question' => 'Can internal teams manage updates after launch?',
            'answer' => 'Yes. We provide admin-friendly content structure and handover support so your team can manage daily updates smoothly.',
        ],
    ];
    $seoOverride = [
        'title' => $data['name'] . ' Software Services UK',
        'description' => $data['summary'],
        'keywords' => $data['keywords'] . ', uk software services, website development uk, crm and seo uk',
        'canonical' => $canonicalBase . '/sectors/' . $sector,
        'related_links' => $data['related_links'] ?? ['/services', '/portfolio', '/pricing', '/contact'],
        'faq_items' => $sectorFaq,
        'type' => 'Service',
    ];

    return view('pages.sector-landing', [
        'sector' => $data,
        'seoOverride' => $seoOverride,
    ]);
})->name('sectors.show');

Route::get('/{slug}', function (string $slug) {
    $pages = config('seo_service_pages');
    if (!isset($pages[$slug])) {
        abort(404);
    }
    $page = $pages[$slug];
    $canonicalBase = rtrim((string) (app()->environment('local')
        ? url('/')
        : config('regions.regions.uk.base_url', url('/'))), '/');

    // AggregateRating from approved reviews
    $approvedReviews = \App\Models\ClientReview::query()->where('is_approved', true)->get();
    $reviewCount     = $approvedReviews->count();
    $avgRating       = $reviewCount > 0
        ? round((float) $approvedReviews->avg('rating'), 1)
        : null;

    $seoOverride = [
        'title'            => $page['meta_title'],
        'description'      => $page['meta_desc'],
        'keywords'         => $page['keywords'],
        'canonical'        => $canonicalBase . '/' . $slug,
        'type'             => $page['type'] ?? 'Service',
        'faq_items'        => array_map(
            static fn (array $f) => ['question' => $f['q'], 'answer' => $f['a']],
            $page['faqs'] ?? []
        ),
        'related_links'    => array_map(
            static fn (array $r) => $r['href'],
            $page['related'] ?? []
        ),
        'aggregate_rating' => $avgRating !== null ? [
            'rating' => $avgRating,
            'count'  => $reviewCount,
        ] : null,
    ];
    return view('pages.seo-service-page', compact('page', 'seoOverride'));
})->where('slug', implode('|', array_keys(config('seo_service_pages', []))))->name('seo.service.page');

Route::get('/portfolio', [PortfolioPageController::class, 'index']);
Route::get('/portfolio-details', [PortfolioPageController::class, 'details'])->name('portfolio.details');
Route::get('/portfolio-details/{slug}', [PortfolioPageController::class, 'details'])->name('portfolio.show');
// Both testimonial pages carried the purchased theme's placeholder reviews — two
// different names above word-for-word identical text, and one praising "fast
// delivery" and being a "returning customer", which is ecommerce boilerplate, not
// something a software client writes. Publishing invented reviews is a Google
// policy problem and, in the UK, a DMCC Act 2024 problem. There are no real
// reviews on this site to put in their place, so the pages go rather than being
// refilled with better-written fakes. /portfolio is the honest proof of work.
Route::redirect('/testimonials', '/portfolio', 301);
Route::redirect('/testimonial-carousel', '/portfolio', 301);
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::post('/pricing/coupon-preview', [PricingController::class, 'previewCoupon'])->name('pricing.coupon.preview');
Route::post('/pricing/order', [PricingController::class, 'submitOrder'])->name('pricing.order');
// /gallery was still the purchased theme's demo page — ten 7-13 KB stock images,
// every alt empty, no text at all — yet it was indexed and carrying ad code, which
// is exactly the 'low value content' shape AdSense rejects a site for. The real
// gallery is /portfolio (34 project images, ~985 words), so this consolidates there.
Route::redirect('/gallery', '/portfolio', 301);
Route::view('/faq', 'pages.faq');
Route::view('/404', 'pages.404');
Route::view('/coming-soon', 'pages.coming-soon');

Route::get('/blog', [BlogPageController::class, 'index'])->name('blog.index');
Route::redirect('/blog-list', '/blog', 301);
Route::get('/blog-details', [BlogPageController::class, 'detailsLegacy']);
Route::get('/blog/{slug}', [BlogPageController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comments', [BlogCommentController::class, 'store'])->name('blog.comments.store');
Route::get('/uk-growth-hub', [SeoHubController::class, 'index'])->name('seo.hub');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::view('/contact', 'pages.contact');
Route::view('/book', 'pages.book')->name('book.call');
Route::view('/client-portal-access', 'pages.client-portal-access')->name('client.portal.access');
Route::post('/contact-submit', ContactFormController::class)->name('contact.submit');
Route::get('/meeting-availability', [ContactFormController::class, 'availability'])->name('meeting.availability');
Route::get('/meeting/confirmation/{token}', [MeetingBookingController::class, 'confirmation'])->name('meeting.confirmation');
Route::get('/meeting/manage/{token}', [MeetingBookingController::class, 'manage'])->name('meeting.manage');
Route::post('/meeting/manage/{token}/reschedule', [MeetingBookingController::class, 'reschedule'])->name('meeting.reschedule');
Route::get('/meeting/cancel/{token}', [MeetingBookingController::class, 'cancel'])->name('meeting.cancel');
Route::view('/privacy-policy', 'pages.privacy-policy');
Route::view('/terms-and-conditions', 'pages.terms-and-conditions');
Route::view('/cookie-policy', 'pages.cookie-policy');
Route::view('/refund-policy', 'pages.refund-policy');
Route::view('/service-disclaimer', 'pages.service-disclaimer');

// Legacy URL redirects to clean URLs (SEO-safe migration)
Route::redirect('/index.php', '/', 301);
Route::get('/index.php/{any}', function (string $any) {
    $path = trim($any, '/');
    if ($path === '') {
        return redirect('/', 301);
    }

    $legacyMap = [
        'about.php' => '/about',
        'services.php' => '/services',
        'digital-marketing.php' => '/digital-marketing',
        'web-design-development.php' => '/web-design-development',
        'search-engine-optimization.php' => '/search-engine-optimization',
        'design-and-branding.php' => '/design-and-branding',
        'app-development.php' => '/app-development',
        'portfolio.php' => '/portfolio',
        'testimonials.php' => '/testimonials',
        'testimonial-carousel.php' => '/testimonial-carousel',
        'pricing.php' => '/pricing',
        'gallery.php' => '/gallery',
        'faq.php' => '/faq',
        '404.php' => '/404',
        'coming-soon.php' => '/coming-soon',
        'blog.php' => '/blog',
        'uk-growth-hub.php' => '/uk-growth-hub',
        'blog-list.php' => '/blog',
        'blog-details.php' => '/blog-details',
        'search.php' => '/search',
        'contact.php' => '/contact',
        'client-portal-access.php' => '/client-portal-access',
        'privacy-policy.php' => '/privacy-policy',
        'terms-and-conditions.php' => '/terms-and-conditions',
        'cookie-policy.php' => '/cookie-policy',
        'refund-policy.php' => '/refund-policy',
        'service-disclaimer.php' => '/service-disclaimer',
    ];

    if (isset($legacyMap[$path])) {
        return redirect($legacyMap[$path], 301);
    }

    if (str_starts_with($path, 'portfolio-details.php')) {
        parse_str((string) parse_url($path, PHP_URL_QUERY), $query);
        $slug = trim((string) ($query['slug'] ?? ''));
        return $slug !== '' ? redirect('/portfolio-details/' . urlencode($slug), 301) : redirect('/portfolio-details', 301);
    }

    $cleanPath = '/' . ltrim(preg_replace('/\.php$/', '', $path) ?: $path, '/');
    return redirect($cleanPath, 301);
})->where('any', '.*');
Route::redirect('/about.php', '/about', 301);
Route::redirect('/services.php', '/services', 301);
Route::redirect('/digital-marketing.php', '/digital-marketing', 301);
Route::redirect('/web-design-development.php', '/web-design-development', 301);
Route::redirect('/search-engine-optimization.php', '/search-engine-optimization', 301);
Route::redirect('/design-and-branding.php', '/design-and-branding', 301);
Route::redirect('/app-development.php', '/app-development', 301);
Route::redirect('/portfolio.php', '/portfolio', 301);
Route::get('/portfolio-details.php', function (Request $request) {
    $slug = trim((string) $request->query('slug', ''));
    if ($slug !== '') {
        return redirect('/portfolio-details/' . urlencode($slug), 301);
    }
    return redirect('/portfolio-details', 301);
});
Route::redirect('/testimonials.php', '/portfolio', 301);
Route::redirect('/testimonial-carousel.php', '/portfolio', 301);
Route::redirect('/pricing.php', '/pricing', 301);
Route::redirect('/gallery.php', '/gallery', 301);
Route::redirect('/faq.php', '/faq', 301);
Route::redirect('/404.php', '/404', 301);
Route::redirect('/coming-soon.php', '/coming-soon', 301);
Route::redirect('/blog.php', '/blog', 301);
Route::redirect('/uk-growth-hub.php', '/uk-growth-hub', 301);
Route::redirect('/blog-list.php', '/blog', 301);
Route::get('/blog-details.php', [BlogPageController::class, 'detailsLegacy']);
Route::redirect('/search.php', '/search', 301);
Route::redirect('/contact.php', '/contact', 301);
Route::redirect('/client-portal-access.php', '/client-portal-access', 301);
Route::redirect('/privacy-policy.php', '/privacy-policy', 301);
Route::redirect('/terms-and-conditions.php', '/terms-and-conditions', 301);
Route::redirect('/cookie-policy.php', '/cookie-policy', 301);
Route::redirect('/refund-policy.php', '/refund-policy', 301);
Route::redirect('/service-disclaimer.php', '/service-disclaimer', 301);
Route::get('/client-portal/{token}', [ClientPortalController::class, 'show'])->name('client.portal');
Route::post('/client-portal/{token}/requirements', [ClientPortalController::class, 'addRequirement'])->name('client.portal.requirement');
Route::post('/client-portal/{token}/pay', [ClientPortalController::class, 'payInvoice'])->name('client.portal.pay');
Route::get('/client-portal/{token}/pay/success', [ClientPortalController::class, 'handleStripeSuccess'])->name('client.portal.pay.success');
Route::post('/stripe/webhook', [ClientPortalController::class, 'stripeWebhook'])->name('stripe.webhook');
Route::get('/invoice/{token}', [PublicInvoiceController::class, 'show'])->name('invoice.public.show');
Route::get('/invoice/{token}/pay-now', [PublicInvoiceController::class, 'quickPay'])->name('invoice.public.pay-now');
Route::post('/invoice/{token}/pay', [PublicInvoiceController::class, 'pay'])->name('invoice.public.pay');
Route::get('/invoice/{token}/pay/success', [PublicInvoiceController::class, 'success'])->name('invoice.public.pay.success');
Route::get('/review/{token}', [ClientReviewController::class, 'show'])->name('review.show');
Route::post('/review/{token}', [ClientReviewController::class, 'submit'])->name('review.submit');
Route::get('/client-portal-demo', function () {
    abort_unless(app()->environment('local'), 404);

    $client = \App\Models\Client::query()->updateOrCreate(
        ['email' => 'demo.client@arsdeveloper.co.uk'],
        [
            'name' => 'Demo Client',
            'phone' => '+44 20 7000 1000',
            'company' => 'Demo UK Retail Ltd',
            'country' => 'United Kingdom',
            'notes' => 'Local testing profile for client portal demo.',
        ]
    );

    $project = \App\Models\Project::query()->updateOrCreate(
        ['portal_token' => 'demo-client-portal-access-token-2026'],
        [
            'client_id' => $client->id,
            'title' => 'Demo Ecommerce Revamp',
            'type' => 'Ecommerce Website',
            'status' => 'in_progress',
            'start_date' => now()->subDays(18)->toDateString(),
            'delivery_date' => now()->addDays(42)->toDateString(),
            'delivery_months' => 2,
            'budget_total' => 6500,
            'paid_total' => 1500,
            'currency' => 'GBP',
            'description' => 'Demo scope for portal UX, checkout optimization, and SEO improvements.',
        ]
    );

    $project->milestones()->updateOrCreate(
        ['title' => 'Discovery and UX Audit'],
        [
            'details' => 'Audit complete. User journey and conversion bottlenecks identified.',
            'due_date' => now()->subDays(7)->toDateString(),
            'status' => 'completed',
            'sort_order' => 1,
        ]
    );

    $project->milestones()->updateOrCreate(
        ['title' => 'UI Design and Content Flow'],
        [
            'details' => 'Design system, homepage layout, and category page structure in progress.',
            'due_date' => now()->addDays(10)->toDateString(),
            'status' => 'in_progress',
            'sort_order' => 2,
        ]
    );

    $project->milestones()->updateOrCreate(
        ['title' => 'Checkout and Analytics Setup'],
        [
            'details' => 'Payment and event tracking setup planned after UI approval.',
            'due_date' => now()->addDays(28)->toDateString(),
            'status' => 'pending',
            'sort_order' => 3,
        ]
    );

    $project->requirements()->updateOrCreate(
        ['title' => 'Homepage banner with seasonal offer'],
        [
            'description' => 'Add hero section for spring campaign and CTA to featured collection.',
            'source' => 'client',
            'status' => 'open',
        ]
    );

    $project->requirements()->updateOrCreate(
        ['title' => 'Improve mobile checkout speed'],
        [
            'description' => 'Reduce checkout friction and improve performance on 4G devices.',
            'source' => 'admin',
            'status' => 'in_review',
        ]
    );

    $invoice = \App\Models\Invoice::query()->updateOrCreate(
        ['invoice_number' => 'DEMO-INV-2026-001'],
        [
            'project_id' => $project->id,
            'client_invoice_number' => 'CL-' . $project->client_id . '-2026-0001',
            'invoice_date' => now()->subDays(2)->toDateString(),
            'due_date' => now()->addDays(12)->toDateString(),
            'amount' => 2000,
            'paid_amount' => 500,
            'status' => 'partially_paid',
            'notes' => 'Second milestone invoice.',
        ]
    );

    \App\Models\Payment::query()->updateOrCreate(
        ['project_id' => $project->id, 'invoice_id' => $invoice->id, 'reference' => 'DEMO-TX-8841'],
        [
            'amount' => 500,
            'payment_date' => now()->subDays(1)->toDateString(),
            'method' => 'Bank Transfer',
            'notes' => 'Demo payment for portal testing.',
        ]
    );

    $project->paid_total = (float) $project->payments()->sum('amount');
    $project->save();

    return redirect()->route('client.portal', ['token' => $project->portal_token]);
})->name('client.portal.demo');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::middleware('admin.role:super_admin,advanced_admin')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
            Route::post('/chat/{conversation}/reply', [AdminChatController::class, 'reply'])->name('chat.reply');
            Route::post('/chat/{conversation}/status', [AdminChatController::class, 'status'])->name('chat.status');
            Route::post('/chat/{conversation}/typing', [AdminChatController::class, 'typing'])->name('chat.typing');

            Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
            Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
            Route::post('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');
            Route::post('/leads/{lead}/block', [LeadController::class, 'block'])->name('leads.block');
            Route::post('/leads/{lead}/send-email', [LeadEmailController::class, 'send'])->name('leads.send-email');

            Route::resource('/portfolios', PortfolioController::class)->except('show');
            Route::get('/service-page-images', [ServicePageImageController::class, 'index'])->name('service-page-images.index');
            Route::post('/service-page-images', [ServicePageImageController::class, 'update'])->name('service-page-images.update');
            Route::resource('/coupons', CouponController::class)->except('show');
            Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
            Route::post('/analytics/monthly', [AnalyticsController::class, 'storeMonthlyMetric'])->name('analytics.monthly.store');
            Route::post('/analytics/source', [AnalyticsController::class, 'storeSourceMetric'])->name('analytics.source.store');
            Route::resource('/blocked-contacts', BlockedContactController::class)->except('show');
            Route::resource('/clients', ClientController::class)->except('show');
            Route::resource('/projects', ProjectController::class);
            Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoices.index');
            Route::post('/invoices/direct-payment-link', [AdminInvoiceController::class, 'storeDirectPaymentLink'])->name('invoices.direct-payment-link');
            Route::get('/reviews', [AdminClientReviewController::class, 'index'])->name('reviews.index');
            Route::post('/reviews/{review}/approve', [AdminClientReviewController::class, 'approve'])->name('reviews.approve');
            Route::post('/reviews/{review}/unapprove', [AdminClientReviewController::class, 'unapprove'])->name('reviews.unapprove');
            Route::post('/reviews/{review}/delete', [AdminClientReviewController::class, 'destroy'])->name('reviews.delete');
            Route::get('/operations', [OperationsController::class, 'index'])->name('operations.index');
            Route::post('/operations/expenses', [OperationsController::class, 'storeExpense'])->name('operations.expenses.store');
            Route::post('/operations/expenses/{expense}/delete', [OperationsController::class, 'destroyExpense'])->name('operations.expenses.delete');
            Route::post('/operations/team-hires', [OperationsController::class, 'storeTeamHire'])->name('operations.team-hires.store');
            Route::post('/operations/team-hires/{teamHire}/status', [OperationsController::class, 'updateTeamHireStatus'])->name('operations.team-hires.status');
            Route::post('/operations/team-hires/{teamHire}/delete', [OperationsController::class, 'destroyTeamHire'])->name('operations.team-hires.delete');
            Route::post('/operations/audit-pdf', [OperationsController::class, 'downloadUkAuditPdf'])->name('operations.audit-pdf');
            Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
            Route::post('/finance/expense', [FinanceController::class, 'storeExpense'])->name('finance.expense.store');
            Route::post('/finance/budget', [FinanceController::class, 'storeBudget'])->name('finance.budget.store');
            Route::get('/finance/export-csv', [FinanceController::class, 'exportCsv'])->name('finance.export');
            Route::get('/audits', [AuditReportController::class, 'index'])->name('audits.index');
            Route::get('/audits/create', [AuditReportController::class, 'create'])->name('audits.create');
            Route::post('/audits', [AuditReportController::class, 'store'])->name('audits.store');
            Route::post('/audits/live-scan', [AuditReportController::class, 'liveScan'])->name('audits.live-scan');
            Route::post('/audits/deep-scan', [AuditReportController::class, 'deepScan'])->name('audits.deep-scan');
            Route::post('/audits/benchmark', [AuditReportController::class, 'benchmark'])->name('audits.benchmark');
            Route::post('/audits/targets', [AuditReportController::class, 'storeTarget'])->name('audits.targets.store');
            Route::post('/audits/targets/{target}/run', [AuditReportController::class, 'runTargetNow'])->name('audits.targets.run');
            Route::post('/audits/actions/{action}/status', [AuditReportController::class, 'updateActionStatus'])->name('audits.actions.status');
            Route::get('/audits/trends', [AuditReportController::class, 'trendData'])->name('audits.trends');
            Route::get('/audits/{audit}', [AuditReportController::class, 'show'])->name('audits.show');
            Route::get('/audits/{audit}/pdf', [AuditReportController::class, 'downloadPdf'])->name('audits.pdf');
            Route::post('/projects/{project}/milestones', [ProjectController::class, 'storeMilestone'])->name('projects.milestones.store');
            Route::post('/projects/{project}/milestones/{milestone}/status', [ProjectController::class, 'updateMilestone'])->name('projects.milestones.status');
            Route::post('/projects/{project}/requirements', [ProjectController::class, 'storeRequirement'])->name('projects.requirements.store');
            Route::post('/projects/{project}/requirements/{requirement}/status', [ProjectController::class, 'updateRequirement'])->name('projects.requirements.status');
            Route::post('/projects/{project}/invoices', [ProjectController::class, 'storeInvoice'])->name('projects.invoices.store');
            Route::get('/projects/{project}/invoices/{invoice}/studio', [ProjectController::class, 'editInvoiceStudio'])->name('projects.invoices.studio');
            Route::post('/projects/{project}/invoices/{invoice}/studio', [ProjectController::class, 'saveInvoiceStudio'])->name('projects.invoices.studio.save');
            Route::post('/projects/{project}/invoices/{invoice}/send-link', [ProjectController::class, 'sendInvoiceLink'])->name('projects.invoices.send-link');
            Route::post('/projects/{project}/invoices/{invoice}/status', [ProjectController::class, 'updateInvoiceStatus'])->name('projects.invoices.status');
            Route::post('/projects/{project}/payments', [ProjectController::class, 'storePayment'])->name('projects.payments.store');

            Route::get('/admin-users', [AdminUserController::class, 'index'])->name('admin-users.index');
            Route::post('/admin-users', [AdminUserController::class, 'store'])->name('admin-users.store');
            Route::post('/admin-users/{adminUser}', [AdminUserController::class, 'update'])->name('admin-users.update');
            Route::post('/notifications/mark-all', [AdminNotificationController::class, 'markAll'])->name('notifications.mark-all');
            Route::get('/notifications/open/{type}/{activityId}/{projectId}', [AdminNotificationController::class, 'open'])->name('notifications.open');
        });

        Route::middleware('admin.role:super_admin,advanced_admin,blog_seo_admin')->group(function () {
            Route::resource('/blog-posts', BlogPostController::class)->except('show');
            Route::post('/blog-posts/{blogPost}/quick-status', [BlogPostController::class, 'quickStatus'])->name('blog-posts.quick-status');
            Route::post('/blog-posts-respace', [BlogPostController::class, 'respace'])->name('blog-posts.respace');
            Route::get('/blog-comments', [AdminBlogCommentController::class, 'index'])->name('blog-comments.index');
            Route::post('/blog-comments/{comment}/approve', [AdminBlogCommentController::class, 'approve'])->name('blog-comments.approve');
            Route::post('/blog-comments/{comment}/unapprove', [AdminBlogCommentController::class, 'unapprove'])->name('blog-comments.unapprove');
            Route::delete('/blog-comments/{comment}', [AdminBlogCommentController::class, 'destroy'])->name('blog-comments.destroy');
            Route::get('/logs', [SystemLogController::class, 'index'])->name('logs.index');
        });
    });
});
