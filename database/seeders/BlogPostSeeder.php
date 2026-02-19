<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'How UK Service Businesses Generate More Leads with Conversion-Focused Websites',
                'category' => 'Web Development',
                'excerpt' => 'A practical UK framework to turn brochure websites into enquiry engines with conversion-first structure, trust layers, and fast booking flow.',
                'content' => "<h2>Why most UK business websites fail to convert</h2><p>Many sites look fine but hide their main CTA, overload menus, and miss trust proof. This causes low enquiry rates even with decent traffic.</p><h3>What to fix first</h3><ul><li>Clear primary CTA in hero and sticky nav</li><li>Service pages with local intent keywords</li><li>Fast quote form with only essential fields</li><li>Trust signals: reviews, certifications, case studies</li></ul><h3>UK SEO structure that supports lead generation</h3><p>Use one core service page per intent: web design, CRM setup, ecommerce build, SEO management. Add internal links between service and case-study pages to strengthen topical authority.</p><p>Need implementation? See our <a href=\"/services\">services</a> and <a href=\"/portfolio\">portfolio</a>.</p>",
                'meta_keywords' => 'uk web development, conversion website uk, lead generation website, service business website uk',
            ],
            [
                'title' => 'WordPress vs Shopify for UK Businesses: Which Platform Fits Your Growth Stage?',
                'category' => 'Ecommerce',
                'excerpt' => 'WordPress vs Shopify in the UK: choose based on catalog model, operational load, SEO depth, and growth speed.',
                'content' => "<h2>Platform decision: growth speed vs flexibility</h2><p>Shopify is ideal for faster launch and lower technical overhead. WordPress + WooCommerce gives deeper customization and content-led SEO control.</p><h3>Choose Shopify if</h3><ul><li>You need fast go-live and simpler operations</li><li>Your team prefers app-based integrations</li><li>You want stable checkout performance quickly</li></ul><h3>Choose WordPress if</h3><ul><li>You need advanced content architecture</li><li>You want deeper custom functionality</li><li>You require tight integration with custom CRM workflows</li></ul><p>For UK ecommerce, both can rank well if technical SEO, schema, and Core Web Vitals are handled correctly.</p>",
                'meta_keywords' => 'shopify vs wordpress uk, ecommerce development uk, woocommerce agency uk, uk ecommerce platform comparison',
            ],
            [
                'title' => 'Technical SEO Checklist for UK Websites Before Launch',
                'category' => 'SEO',
                'excerpt' => 'Launch-day SEO mistakes kill visibility. Use this UK pre-launch checklist to protect indexing and rankings from day one.',
                'content' => "<h2>Pre-launch checklist that prevents ranking loss</h2><ol><li>Validate canonical tags on all indexable pages</li><li>Ensure only one H1 and clean heading hierarchy</li><li>Set XML sitemap and robots.txt correctly</li><li>Map all old URLs with 301 redirects</li><li>Compress images and serve next-gen formats</li><li>Pass mobile usability and CLS checks</li></ol><h3>Critical post-launch checks</h3><p>Submit sitemap in Google Search Console, inspect key URLs, and monitor crawl/index coverage for two weeks. Fix soft 404, duplicate canonical, and blocked resource issues immediately.</p>",
                'meta_keywords' => 'technical seo checklist uk, website launch seo, core web vitals uk, google search console checklist',
            ],
            [
                'title' => 'Why Growing Teams in the UK Move from Spreadsheets to Custom CRM',
                'category' => 'CRM',
                'excerpt' => 'When leads and operations grow, spreadsheets break. A custom CRM gives visibility, automation, and better delivery control.',
                'content' => "<h2>Where spreadsheet workflows collapse</h2><p>Teams lose lead ownership, duplicate tasks, and miss follow-ups. Reporting becomes reactive instead of actionable.</p><h3>What a custom CRM solves</h3><ul><li>Lead routing by source and service type</li><li>Status pipeline with SLA reminders</li><li>Role-based access and approvals</li><li>Milestones, invoices, and payment tracking</li></ul><h3>Phased rollout model</h3><p>Start with lead + task modules, then add automation, analytics, and client portal features. This lowers risk and keeps teams productive during transition.</p>",
                'meta_keywords' => 'custom crm uk, crm development company uk, workflow automation uk, lead management crm uk',
            ],
            [
                'title' => 'Landing Page CRO for UK Campaigns: 7 Fixes That Increase Enquiries',
                'category' => 'Digital Marketing',
                'excerpt' => 'Traffic without conversion is waste. These 7 UK landing page CRO fixes improve enquiry quality and close-rate.',
                'content' => "<h2>7 CRO improvements that impact conversions fast</h2><ul><li>Ad-to-page message match</li><li>Benefit-focused first fold</li><li>Single primary CTA path</li><li>Friction-free mobile form</li><li>Proof stack near CTA</li><li>Trust badges + compliance notes</li><li>Faster follow-up automation</li></ul><h3>Measure what matters</h3><p>Track form completion rate, cost per qualified lead, and sales acceptance rate. Optimize based on quality, not only click volume.</p><p>Need help improving campaign performance? Contact our <a href=\"/digital-marketing\">digital marketing team</a>.</p>",
                'meta_keywords' => 'cro services uk, landing page optimization uk, lead conversion strategy, ppc landing page uk',
            ],
        ];

        foreach ($posts as $index => $post) {
            $slug = Str::slug($post['title']);

            BlogPost::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $post['title'],
                    'slug' => $slug,
                    'category' => $post['category'],
                    'author_name' => 'ARS Developer Team',
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                    'meta_keywords' => $post['meta_keywords'],
                    'meta_robots' => 'index, follow',
                    'og_title' => $post['title'],
                    'og_description' => $post['excerpt'],
                    'twitter_title' => $post['title'],
                    'twitter_description' => $post['excerpt'],
                    'featured_image' => 'assets/images/blog/blog-2-' . (($index % 3) + 1) . '.jpg',
                    'featured_image_alt' => $post['title'],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                    'published_at' => now()->subDays(($index + 1) * 4),
                ]
            );
        }
    }
}
