<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditReportController;
use App\Http\Controllers\Admin\BlockedContactController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\LeadEmailController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\OperationsController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\BlogPageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\MeetingBookingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\PortfolioPageController;
use App\Http\Controllers\PricingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.index')->name('home');
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
            'highlights' => [
                'Patient-friendly landing pages with trust-first UX',
                'Online appointment request and callback workflows',
                'Location pages, service pages, and clinician profile setup',
                'GDPR-aware form capture and admin notification flow',
            ],
        ],
        'law-firms' => [
            'name' => 'Law Firms',
            'headline' => 'Conversion-Focused Digital Setup for UK Law Firms',
            'summary' => 'Structured legal service pages, consultation enquiry funnels, and credibility-focused design that converts visitors into case enquiries.',
            'highlights' => [
                'Practice-area page structure with SEO intent mapping',
                'Consultation forms and lead qualification workflow',
                'Trust badges, review blocks, and local authority signals',
                'Content architecture for long-term legal SEO growth',
            ],
        ],
        'ecommerce' => [
            'name' => 'Ecommerce Brands',
            'headline' => 'Ecommerce Growth Systems for Shopify and WooCommerce',
            'summary' => 'Store build, catalog optimization, checkout improvements, and performance-first implementation for UK ecommerce brands.',
            'highlights' => [
                'Shopify and WooCommerce setup with conversion UX',
                'Product structure, category flow, and search navigation',
                'Checkout, shipping, and payment journey optimization',
                'Analytics tracking and campaign-ready landing pages',
            ],
        ],
        'b2b' => [
            'name' => 'B2B Service Teams',
            'headline' => 'B2B Websites, CRM Portals and Lead Workflow Automation',
            'summary' => 'Lead capture systems, CRM-aligned operations, and milestone-based project visibility for B2B service companies across the UK.',
            'highlights' => [
                'Pipeline-focused website and landing flow architecture',
                'CRM handoff automation and internal team workflows',
                'Role-based admin views and client communication timeline',
                'Reporting structure for enquiry, conversion, and follow-up',
            ],
        ],
    ];

    if (!isset($sectors[$sector])) {
        abort(404);
    }

    $data = $sectors[$sector];
    $canonicalBase = rtrim((string) (app()->environment('local')
        ? url('/')
        : config('regions.regions.uk.base_url', url('/'))), '/');
    $seoOverride = [
        'title' => $data['name'] . ' Software Services UK',
        'description' => $data['summary'],
        'keywords' => strtolower($data['name']) . ' uk, uk software services, website development uk, crm and seo uk',
        'canonical' => $canonicalBase . '/sectors/' . $sector,
    ];

    return view('pages.sector-landing', [
        'sector' => $data,
        'seoOverride' => $seoOverride,
    ]);
})->name('sectors.show');

Route::get('/portfolio', [PortfolioPageController::class, 'index']);
Route::get('/portfolio-details', [PortfolioPageController::class, 'details'])->name('portfolio.details');
Route::get('/portfolio-details/{slug}', [PortfolioPageController::class, 'details'])->name('portfolio.show');
Route::view('/testimonials', 'pages.testimonials');
Route::view('/testimonial-carousel', 'pages.testimonial-carousel');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::post('/pricing/coupon-preview', [PricingController::class, 'previewCoupon'])->name('pricing.coupon.preview');
Route::view('/gallery', 'pages.gallery');
Route::view('/faq', 'pages.faq');
Route::view('/404', 'pages.404');
Route::view('/coming-soon', 'pages.coming-soon');

Route::get('/blog', [BlogPageController::class, 'index'])->name('blog.index');
Route::get('/blog-list', [BlogPageController::class, 'index']);
Route::get('/blog-details', [BlogPageController::class, 'detailsLegacy']);
Route::get('/blog/{slug}', [BlogPageController::class, 'show'])->name('blog.show');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');
Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::view('/contact', 'pages.contact');
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
        'blog-list.php' => '/blog-list',
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
Route::redirect('/testimonials.php', '/testimonials', 301);
Route::redirect('/testimonial-carousel.php', '/testimonial-carousel', 301);
Route::redirect('/pricing.php', '/pricing', 301);
Route::redirect('/gallery.php', '/gallery', 301);
Route::redirect('/faq.php', '/faq', 301);
Route::redirect('/404.php', '/404', 301);
Route::redirect('/coming-soon.php', '/coming-soon', 301);
Route::redirect('/blog.php', '/blog', 301);
Route::redirect('/blog-list.php', '/blog-list', 301);
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

            Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
            Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
            Route::post('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');
            Route::post('/leads/{lead}/block', [LeadController::class, 'block'])->name('leads.block');
            Route::post('/leads/{lead}/send-email', [LeadEmailController::class, 'send'])->name('leads.send-email');

            Route::resource('/portfolios', PortfolioController::class)->except('show');
            Route::resource('/coupons', CouponController::class)->except('show');
            Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
            Route::post('/analytics/monthly', [AnalyticsController::class, 'storeMonthlyMetric'])->name('analytics.monthly.store');
            Route::post('/analytics/source', [AnalyticsController::class, 'storeSourceMetric'])->name('analytics.source.store');
            Route::resource('/blocked-contacts', BlockedContactController::class)->except('show');
            Route::resource('/clients', ClientController::class)->except('show');
            Route::resource('/projects', ProjectController::class);
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
            Route::post('/projects/{project}/payments', [ProjectController::class, 'storePayment'])->name('projects.payments.store');

            Route::get('/admin-users', [AdminUserController::class, 'index'])->name('admin-users.index');
            Route::post('/admin-users', [AdminUserController::class, 'store'])->name('admin-users.store');
            Route::post('/admin-users/{adminUser}', [AdminUserController::class, 'update'])->name('admin-users.update');
            Route::post('/notifications/mark-all', [AdminNotificationController::class, 'markAll'])->name('notifications.mark-all');
            Route::get('/notifications/open/{type}/{activityId}/{projectId}', [AdminNotificationController::class, 'open'])->name('notifications.open');
        });

        Route::middleware('admin.role:super_admin,advanced_admin,blog_seo_admin')->group(function () {
            Route::resource('/blog-posts', BlogPostController::class)->except('show');
        });
    });
});
