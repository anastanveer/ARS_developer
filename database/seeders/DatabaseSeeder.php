<?php

namespace Database\Seeders;

use App\Models\BlockedContact;
use App\Models\Client;
use App\Models\ClientReview;
use App\Models\Coupon;
use App\Models\Lead;
use App\Models\MonthlyMetric;
use App\Models\MonthlySourceMetric;
use App\Models\Payment;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectRequirement;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BlogPostSeeder::class);
        $this->call(AdminUserSeeder::class);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        $clientA = Client::firstOrCreate(
            ['email' => 'hello@northstar-retail.co.uk'],
            [
                'name' => 'Emma Carter',
                'phone' => '+44 20 7123 4567',
                'company' => 'Northstar Retail',
                'country' => 'United Kingdom',
                'notes' => 'Priority ecommerce support client.',
            ]
        );

        $clientB = Client::firstOrCreate(
            ['email' => 'ops@sterlingclinics.co.uk'],
            [
                'name' => 'Daniel Brooks',
                'phone' => '+44 161 555 9001',
                'company' => 'Sterling Clinics',
                'country' => 'United Kingdom',
                'notes' => 'CRM portal and automation project.',
            ]
        );

        $projectA = Project::firstOrCreate(
            ['title' => 'Northstar Ecommerce Growth Stack'],
            [
                'client_id' => $clientA->id,
                'type' => 'Shopify + SEO',
                'status' => 'in_progress',
                'start_date' => now()->subMonth()->toDateString(),
                'delivery_date' => now()->addMonths(2)->toDateString(),
                'delivery_months' => 3,
                'budget_total' => 18000,
                'paid_total' => 7500,
                'currency' => 'GBP',
                'portal_token' => Str::random(48),
                'description' => 'Store redesign, tracking setup, and conversion optimization.',
            ]
        );

        $projectB = Project::firstOrCreate(
            ['title' => 'Sterling Clinics Patient CRM'],
            [
                'client_id' => $clientB->id,
                'type' => 'Custom CRM',
                'status' => 'planning',
                'start_date' => now()->toDateString(),
                'delivery_date' => now()->addMonths(3)->toDateString(),
                'delivery_months' => 3,
                'budget_total' => 42000,
                'paid_total' => 10000,
                'currency' => 'GBP',
                'portal_token' => Str::random(48),
                'description' => 'Role-based dashboard, workflow automation, and reporting.',
            ]
        );

        foreach ([
            [$projectA, 'Discovery & Audit', 'done', now()->subWeeks(2)],
            [$projectA, 'UX + Checkout Revamp', 'in_progress', now()->addWeeks(2)],
            [$projectA, 'SEO + Campaign Launch', 'pending', now()->addMonth()],
            [$projectB, 'Process Mapping', 'in_progress', now()->addWeek()],
            [$projectB, 'Core Module Build', 'pending', now()->addMonths(2)],
        ] as [$project, $title, $status, $dueDate]) {
            ProjectMilestone::firstOrCreate(
                ['project_id' => $project->id, 'title' => $title],
                [
                    'details' => $title . ' milestone details.',
                    'status' => $status,
                    'due_date' => $dueDate->toDateString(),
                    'sort_order' => 0,
                ]
            );
        }

        foreach ([
            [$projectA, 'Homepage hero conversion test', 'admin', 'in_progress'],
            [$projectA, 'Add Klarna payment method', 'client', 'open'],
            [$projectB, 'Patient onboarding workflow', 'admin', 'open'],
            [$projectB, 'Export reports in CSV', 'client', 'open'],
        ] as [$project, $title, $source, $status]) {
            ProjectRequirement::firstOrCreate(
                ['project_id' => $project->id, 'title' => $title],
                [
                    'description' => $title . ' requirement notes.',
                    'source' => $source,
                    'status' => $status,
                ]
            );
        }

        $invoiceA1 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-2026-1001'],
            [
                'project_id' => $projectA->id,
                'client_invoice_number' => 'CL-' . $projectA->client_id . '-2026-0001',
                'invoice_date' => now()->subDays(20)->toDateString(),
                'due_date' => now()->subDays(5)->toDateString(),
                'amount' => 9000,
                'paid_amount' => 7500,
                'status' => 'partially_paid',
            ]
        );

        $invoiceB1 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-2026-1002'],
            [
                'project_id' => $projectB->id,
                'client_invoice_number' => 'CL-' . $projectB->client_id . '-2026-0001',
                'invoice_date' => now()->subDays(5)->toDateString(),
                'due_date' => now()->addDays(15)->toDateString(),
                'amount' => 10000,
                'paid_amount' => 10000,
                'status' => 'paid',
            ]
        );

        Invoice::firstOrCreate(
            ['invoice_number' => 'INV-2026-1003'],
            [
                'project_id' => $projectB->id,
                'client_invoice_number' => 'CL-' . $projectB->client_id . '-2026-0002',
                'invoice_date' => now()->toDateString(),
                'due_date' => now()->addDays(21)->toDateString(),
                'amount' => 8000,
                'paid_amount' => 0,
                'status' => 'unpaid',
                'notes' => 'Milestone 2 delivery payment.',
            ]
        );

        Payment::firstOrCreate(
            ['project_id' => $projectA->id, 'reference' => 'NST-DEP-01'],
            [
                'invoice_id' => $invoiceA1->id,
                'amount' => 7500,
                'payment_date' => now()->subDays(10)->toDateString(),
                'method' => 'bank_transfer',
            ]
        );

        Payment::firstOrCreate(
            ['project_id' => $projectB->id, 'reference' => 'STC-DEP-01'],
            [
                'invoice_id' => $invoiceB1->id,
                'amount' => 10000,
                'payment_date' => now()->subDays(2)->toDateString(),
                'method' => 'bank_transfer',
            ]
        );

        ClientReview::updateOrCreate(
            ['invoice_id' => $invoiceA1->id],
            [
                'client_id' => $projectA->client_id,
                'project_id' => $projectA->id,
                'review_token' => 'demo-review-token-northstar-2026',
                'reviewer_name' => 'Emma Carter',
                'reviewer_email' => 'hello@northstar-retail.co.uk',
                'company_name' => 'Northstar Retail',
                'rating' => 5,
                'review_title' => 'Excellent delivery and communication',
                'review_text' => 'The ARSDeveloper team handled the ecommerce build and SEO implementation with great clarity. Delivery milestones were on time and support remained strong after launch.',
                'result_summary' => 'Higher conversion rate and faster page speed after launch.',
                'submitted_at' => now()->subDays(8),
                'is_approved' => true,
                'approved_at' => now()->subDays(6),
            ]
        );

        ClientReview::updateOrCreate(
            ['invoice_id' => $invoiceB1->id],
            [
                'client_id' => $projectB->client_id,
                'project_id' => $projectB->id,
                'review_token' => 'demo-review-token-sterling-2026',
                'reviewer_name' => 'Daniel Brooks',
                'reviewer_email' => 'ops@sterlingclinics.co.uk',
                'company_name' => 'Sterling Clinics',
                'rating' => 5,
                'review_title' => 'Structured process and reliable outcomes',
                'review_text' => 'From discovery through implementation, everything was managed in a clear structure. Our team had visibility on milestones, payments, and requirements throughout the project.',
                'result_summary' => 'Improved enquiry workflow and admin visibility.',
                'submitted_at' => now()->subDays(5),
                'is_approved' => true,
                'approved_at' => now()->subDays(4),
            ]
        );

        Lead::firstOrCreate(
            ['email' => 'sarah.williams@example.com', 'subject' => 'Need CRM for service team'],
            [
                'type' => 'contact',
                'name' => 'Sarah Williams',
                'phone' => '+44 7700 900123',
                'company' => 'BluePeak Logistics',
                'message' => 'We need CRM + workflow automation for our support desk.',
                'status' => 'new',
                'ip' => '86.11.20.4',
                'country' => 'GB',
            ]
        );

        Lead::firstOrCreate(
            ['email' => 'booking@horizonbrands.co.uk', 'meeting_date' => now()->addDays(3)->toDateString()],
            [
                'type' => 'meeting',
                'name' => 'Horizon Brands',
                'phone' => '+44 7900 120099',
                'subject' => 'Meeting Booking Request',
                'message' => 'Need website + PPC support',
                'meeting_slot' => '11:00 AM - 11:30 AM',
                'project_type' => 'Website + Marketing',
                'budget_range' => 'GBP 5k - 10k',
                'status' => 'contacted',
                'ip' => '81.2.69.142',
                'country' => 'GB',
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'title' => 'Welcome offer (first project)',
                'discount_type' => 'percent',
                'discount_value' => 10,
                'currency' => 'GBP',
                'is_active' => true,
                'notes' => 'Baseline first-project offer.',
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'FIRST20'],
            [
                'title' => 'First client launch offer',
                'discount_type' => 'percent',
                'discount_value' => 20,
                'currency' => 'GBP',
                'is_active' => true,
                'notes' => 'High-conversion first-time offer across selected packages.',
            ]
        );

        Coupon::query()
            ->where('code', 'GROWTH500')
            ->update([
                'is_active' => false,
                'notes' => 'Deprecated due to over-discounting on low-price packages.',
            ]);

        $portfolioSeeds = [
            [
                'slug' => 'small-business-website-seo-launch-uk',
                'title' => 'Small Business Website + Local SEO Launch (UK)',
                'category' => 'Business Website',
                'client_name' => 'Trent Heating Services',
                'excerpt' => 'Budget: GBP 3,900 | 5-page lead-gen website, booking form, GBP-focused local SEO setup, Google Business Profile alignment, and call tracking.',
                'description' => 'Built a conversion-first brochure website for a local UK trade business. Delivered service pages, quote funnel, trust signals, and speed optimization. Added local schema, map consistency, and conversion events to improve phone-call enquiries.',
                'image_path' => 'assets/images/project/portfolio-page-1-1.jpg',
                'sort_order' => 1,
            ],
            [
                'slug' => 'shopify-fashion-store-growth-uk',
                'title' => 'Shopify Fashion Store Build + Growth Stack',
                'category' => 'Shopify Ecommerce',
                'client_name' => 'Northstar Retail',
                'excerpt' => 'Budget: GBP 9,800 | Shopify setup, product/collection architecture, payment/shipping flows, Klaviyo automations, and CRO improvements.',
                'description' => 'Delivered end-to-end Shopify store build with UK checkout optimization, product filters, and campaign-ready landing pages. Implemented analytics events, abandoned cart recovery, and merchandising rules to improve conversion and average order value.',
                'image_path' => 'assets/images/project/portfolio-page-1-2.jpg',
                'sort_order' => 2,
            ],
            [
                'slug' => 'wordpress-corporate-site-seo-uk',
                'title' => 'WordPress Corporate Website for Service Company',
                'category' => 'WordPress Development',
                'client_name' => 'BluePeak Logistics',
                'excerpt' => 'Budget: GBP 6,400 | Custom WordPress theme, case studies, quote workflow, technical SEO, and page speed hardening.',
                'description' => 'Designed and developed a scalable WordPress site for a B2B logistics brand. Added service funnels, gated enquiry forms, and authority-focused content layout. Implemented cache strategy, structured data, and tracking for sales team reporting.',
                'image_path' => 'assets/images/project/portfolio-page-1-3.jpg',
                'sort_order' => 3,
            ],
            [
                'slug' => 'wix-booking-site-healthcare-uk',
                'title' => 'Wix Booking Website for Healthcare Clinic Group',
                'category' => 'Wix Website',
                'client_name' => 'Sterling Clinics',
                'excerpt' => 'Budget: GBP 4,700 | Multi-location Wix site, doctor profile pages, appointment request forms, and automated confirmation emails.',
                'description' => 'Built a clean patient-first website for a clinic network with clear service journeys. Implemented location pages, fast appointment flow, and strong trust elements. Configured lead routing and CRM-ready form capture for admin operations.',
                'image_path' => 'assets/images/project/portfolio-page-1-4.jpg',
                'sort_order' => 4,
            ],
            [
                'slug' => 'custom-crm-field-operations-uk',
                'title' => 'Custom CRM Platform for Field Operations Team',
                'category' => 'CRM / Portal',
                'client_name' => 'Horizon Utility Works',
                'excerpt' => 'Budget: GBP 28,000 | Lead pipeline, role-based access, dispatch dashboard, SLA alerts, and monthly KPI reporting.',
                'description' => 'Delivered a custom CRM for operations management across sales and service teams. Added lead stages, engineer assignment workflows, and visibility dashboards. Included export-ready reports for finance and delivery management.',
                'image_path' => 'assets/images/project/portfolio-page-1-5.jpg',
                'sort_order' => 5,
            ],
            [
                'slug' => 'restaurant-multi-location-ordering-uk',
                'title' => 'Multi-Location Restaurant Website + Online Ordering',
                'category' => 'Hospitality Website',
                'client_name' => 'Taste of Stoke Group',
                'excerpt' => 'Budget: GBP 7,200 | Branded menu pages, branch finder, order integrations, campaign landing pages, and social proof modules.',
                'description' => 'Created a fast-loading hospitality website supporting multiple branches in the UK. Improved navigation around menu discovery and branch-specific offers. Integrated enquiry, booking, and order actions to increase online conversions.',
                'image_path' => 'assets/images/project/portfolio-page-1-6.jpg',
                'sort_order' => 6,
            ],
            [
                'slug' => 'property-listing-portal-agency-uk',
                'title' => 'Property Listing Portal for Regional Estate Agency',
                'category' => 'Portal Development',
                'client_name' => 'Midlands Property Partners',
                'excerpt' => 'Budget: GBP 14,500 | Search filters, listing CMS, valuation form funnels, map integration, and lead routing to branch teams.',
                'description' => 'Developed a property portal with scalable listing management and user-friendly filters. Built conversion-focused valuation and viewing request forms. Added performance analytics and branch-level enquiry routing to speed response times.',
                'image_path' => 'assets/images/project/portfolio-page-1-1.jpg',
                'sort_order' => 7,
            ],
            [
                'slug' => 'mvp-saas-workflow-platform-uk',
                'title' => 'SaaS MVP for Workflow Automation Startup',
                'category' => 'MVP Development',
                'client_name' => 'FlowPilot Labs',
                'excerpt' => 'Budget: GBP 36,000 | Product discovery, admin panel, user onboarding, subscription logic, and staged release delivery.',
                'description' => 'Built an MVP platform from discovery to launch for a UK SaaS founder team. Implemented onboarding flows, account controls, and milestone-based rollouts. Prepared product analytics and roadmap hooks for growth iterations.',
                'image_path' => 'assets/images/project/portfolio-page-1-2.jpg',
                'sort_order' => 8,
            ],
            [
                'slug' => 'elearning-training-portal-uk',
                'title' => 'eLearning Portal for Training Provider',
                'category' => 'Education Platform',
                'client_name' => 'SkillForge Academy',
                'excerpt' => 'Budget: GBP 18,600 | Course catalog, learner dashboards, payment setup, certification workflow, and support ticket modules.',
                'description' => 'Delivered an education portal focused on easy learner onboarding and course progression. Added instructor controls, attendance tracking, and certificate logic. Optimized structure for SEO visibility around course-specific UK queries.',
                'image_path' => 'assets/images/project/portfolio-page-1-3.jpg',
                'sort_order' => 9,
            ],
            [
                'slug' => 'annual-support-growth-retainer-uk',
                'title' => 'Annual Website Support + Growth Retainer',
                'category' => 'Maintenance & Growth',
                'client_name' => 'Multiple UK SME Clients',
                'excerpt' => 'Budget: GBP 24,000/year | Monthly improvements, bug fixes, landing page creation, SEO updates, reporting, and conversion experiments.',
                'description' => 'Managed an ongoing growth retainer for multiple UK SMEs requiring stable support and predictable delivery. Included monthly implementation sprints, SEO tasks, and design/UX upgrades. Helped clients scale without internal technical overhead.',
                'image_path' => 'assets/images/project/portfolio-page-1-4.jpg',
                'sort_order' => 10,
            ],
        ];

        foreach ($portfolioSeeds as $portfolioData) {
            Portfolio::updateOrCreate(
                ['slug' => $portfolioData['slug']],
                [
                    'title' => $portfolioData['title'],
                    'category' => $portfolioData['category'],
                    'client_name' => $portfolioData['client_name'],
                    'excerpt' => $portfolioData['excerpt'],
                    'description' => $portfolioData['description'],
                    'image_path' => $portfolioData['image_path'],
                    'project_url' => null,
                    'is_published' => true,
                    'sort_order' => $portfolioData['sort_order'],
                ]
            );
        }

        Portfolio::query()
            ->where('slug', 'uk-retail-redesign')
            ->whereNotIn('slug', collect($portfolioSeeds)->pluck('slug')->all())
            ->update(['is_published' => false]);

        // Keep live portfolio aligned with the richer catalog used in local demos.
        $this->call(PortfolioCatalogSeeder::class);

        BlockedContact::firstOrCreate(
            ['email' => 'bot-spam@example.net', 'ip' => null],
            ['reason' => 'Spam bot', 'is_active' => true]
        );

        $saveMonthlyMetric = function (string $monthDate, array $payload): MonthlyMetric {
            $metric = MonthlyMetric::query()
                ->where(function ($query) use ($monthDate) {
                    $query->where('month', $monthDate)
                        ->orWhere('month', $monthDate . ' 00:00:00');
                })
                ->first();

            if (!$metric) {
                $metric = new MonthlyMetric();
                $metric->month = $monthDate;
            }

            $metric->fill($payload);
            $metric->save();

            return $metric;
        };

        $m1 = $saveMonthlyMetric(now()->subMonths(2)->startOfMonth()->toDateString(), [
            'sales_amount' => 9500,
            'work_value' => 14000,
            'new_clients_count' => 4,
            'leads_count' => 23,
            'notes' => 'Strong referrals and SEO leads.',
        ]);

        $m2 = $saveMonthlyMetric(now()->subMonth()->startOfMonth()->toDateString(), [
            'sales_amount' => 12200,
            'work_value' => 16800,
            'new_clients_count' => 5,
            'leads_count' => 29,
            'notes' => 'Ecommerce projects increased.',
        ]);

        $m3 = $saveMonthlyMetric(now()->startOfMonth()->toDateString(), [
            'sales_amount' => 13600,
            'work_value' => 18200,
            'new_clients_count' => 6,
            'leads_count' => 34,
            'notes' => 'Higher UK inbound and repeat clients.',
        ]);

        foreach ([
            [$m3, 'Google Organic', 12, 3, 5600],
            [$m3, 'Referral', 9, 2, 4900],
            [$m3, 'Social Media', 7, 1, 3100],
        ] as [$month, $sourceName, $leads, $clients, $sales]) {
            MonthlySourceMetric::firstOrCreate(
                ['monthly_metric_id' => $month->id, 'source_name' => $sourceName],
                ['leads_count' => $leads, 'clients_count' => $clients, 'sales_amount' => $sales]
            );
        }
    }
}
