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
                'title' => 'UK SEO Growth System 2026: AEO, GEO and EEAT Playbook for Service Businesses',
                'slug' => 'uk-seo-growth-system-2026-aeo-geo-eeat-guide',
                'category' => 'Pillar Guide',
                'excerpt' => 'Core UK strategy for AEO, GEO, EEAT, topic clusters, and conversion-focused SEO implementation across services, blog content, and commercial pages.',
                'content' => "<h2>What is the most reliable UK SEO growth model in 2026?</h2><p>The most reliable model combines one pillar page, supporting buyer-intent articles, strong internal links, and technical quality signals that AI and Google systems can trust.</p><h3>Why AEO and GEO now matter for service businesses</h3><p>Search behavior is shifting toward AI summaries and direct answers. Your content must answer commercial questions clearly, then support each answer with proof and linked deeper pages.</p><h3>EEAT implementation checklist</h3><ul><li>Show real experience with project outcomes and delivery proof.</li><li>Keep legal identity, contact details, and team ownership transparent.</li><li>Use consistent schema for Organization, LocalBusiness, FAQ, and Article.</li><li>Maintain clean canonical, robots, and index-control governance.</li></ul><h3>Recommended cluster path</h3><p>Start with this pillar, then read: <a href=\"/blog/how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites\">conversion-focused websites</a>, <a href=\"/blog/technical-seo-checklist-for-uk-websites-before-launch\">technical SEO launch checklist</a>, and <a href=\"/blog/why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm\">CRM workflow scale planning</a>. For execution scope, review our <a href=\"/services\">services</a> and <a href=\"/pricing\">pricing options</a>.</p>",
                'meta_keywords' => 'uk seo strategy 2026, aeo uk, geo seo uk, eeat framework uk, ai overview seo',
            ],
            [
                'title' => 'How UK Service Businesses Generate More Leads with Conversion-Focused Websites',
                'slug' => 'how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
                'category' => 'Web Development',
                'excerpt' => 'A practical UK framework to turn brochure websites into enquiry engines with conversion-first structure, trust layers, and fast booking flow.',
                'content' => "<h2>Why do UK service websites often get traffic but weak enquiry volume?</h2><p>The main reason is page structure. Many websites look polished but miss commercial intent mapping, proof placement, and clear conversion paths.</p><h3>What to fix first for higher lead quality</h3><ul><li>Place one primary CTA above the fold with matching service intent.</li><li>Use trust blocks near form actions: reviews, credentials, and outcomes.</li><li>Build service pages around buyer questions, not generic agency wording.</li><li>Connect each page to a meaningful next step: quote, call, or audit.</li></ul><h3>How this supports AEO and GEO visibility</h3><p>Question-led headings plus direct answer paragraphs improve extractability for AI search systems. Keep answers concise in the first paragraph and expand with proof below.</p><p>Read the full framework in our <a href=\"/uk-growth-hub\">UK SEO Growth Hub</a> and review related <a href=\"/portfolio\">case studies</a>.</p>",
                'meta_keywords' => 'uk web development, conversion website uk, lead generation website, service business website uk',
            ],
            [
                'title' => 'WordPress vs Shopify for UK Businesses: Which Platform Fits Your Growth Stage?',
                'slug' => 'wordpress-vs-shopify-for-uk-businesses-which-platform-fits-your-growth-stage',
                'category' => 'Ecommerce',
                'excerpt' => 'WordPress vs Shopify in the UK: choose based on catalog model, operational load, SEO depth, and growth speed.',
                'content' => "<h2>Which platform wins for UK ecommerce growth: Shopify or WordPress?</h2><p>Shopify wins for speed and lower maintenance overhead. WordPress plus WooCommerce wins when you need deeper content architecture and custom workflows.</p><h3>Choose Shopify when speed-to-market matters</h3><ul><li>Fast launch and managed stack reduce technical drag.</li><li>App ecosystem supports quick integrations.</li><li>Checkout stability is easier to maintain at scale.</li></ul><h3>Choose WordPress when content depth drives revenue</h3><ul><li>Advanced category and editorial structures for SEO clusters.</li><li>Greater control over templates and custom data models.</li><li>Flexible integration with bespoke CRM and operations systems.</li></ul><p>Use this with our <a href=\"/uk-growth-hub\">pillar framework</a> and <a href=\"/search-engine-optimization\">technical SEO service plan</a> before committing platform scope.</p>",
                'meta_keywords' => 'shopify vs wordpress uk, ecommerce development uk, woocommerce agency uk, uk ecommerce platform comparison',
            ],
            [
                'title' => 'Technical SEO Checklist for UK Websites Before Launch',
                'slug' => 'technical-seo-checklist-for-uk-websites-before-launch',
                'category' => 'SEO',
                'excerpt' => 'Launch-day SEO mistakes kill visibility. Use this UK pre-launch checklist to protect indexing and rankings from day one.',
                'content' => "<h2>What should be checked before launching a UK website for SEO safety?</h2><p>Before launch, confirm canonical rules, heading structure, redirects, robots controls, and XML coverage. This prevents indexing loss and duplicate-page issues.</p><h3>Pre-launch checks that protect visibility</h3><ol><li>Validate canonical tags on all indexable URLs.</li><li>Keep one meaningful H1 and clean H2-H3 hierarchy.</li><li>Verify robots.txt and sitemap.xml health status.</li><li>Map legacy URLs with tested 301 redirect rules.</li><li>Lock image dimensions and improve Core Web Vitals.</li><li>Check structured data output and schema validity.</li></ol><h3>Post-launch monitoring path</h3><p>Submit sitemap in Search Console, inspect priority pages, and track crawl/index anomalies for two weeks. Pair this with our <a href=\"/uk-growth-hub\">AEO + GEO pillar strategy</a> for stronger long-term performance.</p>",
                'meta_keywords' => 'technical seo checklist uk, website launch seo, core web vitals uk, google search console checklist',
            ],
            [
                'title' => 'Why Growing Teams in the UK Move from Spreadsheets to Custom CRM',
                'slug' => 'why-growing-teams-in-the-uk-move-from-spreadsheets-to-custom-crm',
                'category' => 'CRM',
                'excerpt' => 'When leads and operations grow, spreadsheets break. A custom CRM gives visibility, automation, and better delivery control.',
                'content' => "<h2>When should a UK service team replace spreadsheets with a custom CRM?</h2><p>Teams should switch when lead ownership is unclear, follow-up delays are frequent, and reporting becomes manual. That is the point where revenue risk starts increasing.</p><h3>What a custom CRM solves first</h3><ul><li>Lead routing by source, region, and service type.</li><li>Pipeline status with SLA reminders and accountability.</li><li>Milestone, invoice, and payment tracking in one flow.</li><li>Role-based access for sales, delivery, and admin teams.</li></ul><h3>How to roll out with low operational friction</h3><p>Start with lead and task modules, then add automation and reporting. Match this rollout with your <a href=\"/services\">delivery scope</a> and overall <a href=\"/uk-growth-hub\">search growth strategy</a>.</p>",
                'meta_keywords' => 'custom crm uk, crm development company uk, workflow automation uk, lead management crm uk',
            ],
            [
                'title' => 'Landing Page CRO for UK Campaigns: 7 Fixes That Increase Enquiries',
                'slug' => 'landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries',
                'category' => 'Digital Marketing',
                'excerpt' => 'Traffic without conversion is waste. These 7 UK landing page CRO fixes improve enquiry quality and close-rate.',
                'content' => "<h2>How can UK landing pages increase qualified enquiries quickly?</h2><p>The fastest gains come from message match, clearer value hierarchy, and lower form friction. Conversion quality improves when proof and intent alignment are added around CTA zones.</p><h3>7 CRO fixes with immediate impact</h3><ul><li>Ad-to-page message match.</li><li>Benefit-focused first fold.</li><li>Single primary CTA path.</li><li>Mobile-first low-friction form.</li><li>Proof stack near CTA.</li><li>Trust badges plus compliance notes.</li><li>Fast follow-up automation.</li></ul><h3>How to measure real buying intent</h3><p>Track qualified enquiry rate, sales acceptance rate, and cost per qualified lead. Connect this page strategy with your <a href=\"/digital-marketing\">campaign delivery</a> and the <a href=\"/uk-growth-hub\">full SEO cluster framework</a>.</p>",
                'meta_keywords' => 'cro services uk, landing page optimization uk, lead conversion strategy, ppc landing page uk',
            ],
        ];

        foreach ($posts as $index => $post) {
            $slug = $post['slug'] ?? Str::slug($post['title']);

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
