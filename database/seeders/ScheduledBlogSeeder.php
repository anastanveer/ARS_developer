<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class ScheduledBlogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->posts() as $p) {
            BlogPost::updateOrCreate(['slug' => $p['slug']], $p);
            $this->command->info("Scheduled: {$p['slug']} -> {$p['published_at']}");
        }
    }

    private function posts(): array
    {
        $base = 'assets/images/blog/seo-2026/';
        return [
            // 1 — 15 June
            [
                'slug' => 'best-free-shopify-apps-small-stores-2026',
                'title' => 'Best Free Shopify Apps for Small Stores in 2026',
                'category' => 'Shopify',
                'author_name' => 'ARS Developer Team',
                'excerpt' => 'A curated list of the best free Shopify apps for small stores in 2026 — packing slips, fulfillment, tracking, reviews and more, without monthly fees.',
                'content' => $this->c1(),
                'featured_image' => $base.'best-free-shopify-apps-2026.webp',
                'featured_image_alt' => 'Best free Shopify apps for small stores 2026',
                'is_published' => true,
                'published_at' => '2026-06-15 09:00:00',
                'sort_order' => 0,
                'meta_title' => 'Best Free Shopify Apps for Small Stores 2026',
                'meta_description' => 'The best free Shopify apps for small stores in 2026 — fulfillment, packing slips, order tracking, reviews and marketing tools with no monthly fees.',
                'meta_keywords' => 'best free shopify apps, free shopify apps 2026, free shopify apps for small business, shopify apps no monthly fee, packslip',
                'meta_robots' => 'index, follow',
                'og_title' => 'Best Free Shopify Apps for Small Stores in 2026',
                'og_description' => 'Free Shopify apps for fulfillment, packing slips, tracking and marketing — no monthly fees.',
                'og_image' => $base.'best-free-shopify-apps-2026.webp',
                'twitter_title' => 'Best Free Shopify Apps 2026',
                'twitter_description' => 'Free Shopify apps for fulfillment, packing slips, tracking and marketing.',
                'twitter_image' => $base.'best-free-shopify-apps-2026.webp',
            ],
            // 2 — 18 June
            [
                'slug' => 'shopify-order-notifications-reduce-wismo-tickets',
                'title' => 'Shopify Order Notifications: How to Cut "Where Is My Order?" Tickets',
                'category' => 'Shopify',
                'author_name' => 'ARS Developer Team',
                'excerpt' => 'WISMO ("where is my order?") tickets drain support time. Learn how automated Shopify order notifications and branded tracking cut them dramatically — free.',
                'content' => $this->c2(),
                'featured_image' => $base.'shopify-order-notifications.webp',
                'featured_image_alt' => 'Shopify order notifications reduce where is my order tickets',
                'is_published' => true,
                'published_at' => '2026-06-18 09:00:00',
                'sort_order' => 0,
                'meta_title' => 'Shopify Order Notifications — Cut WISMO Tickets',
                'meta_description' => 'Reduce "where is my order?" support tickets with automated Shopify order notifications, WhatsApp alerts and a branded tracking page — free with PackSlip.',
                'meta_keywords' => 'shopify order notifications, where is my order, shopify shipping notifications, wismo tickets, shopify whatsapp notifications',
                'meta_robots' => 'index, follow',
                'og_title' => 'Cut "Where Is My Order?" Tickets on Shopify',
                'og_description' => 'Automated order notifications, WhatsApp alerts and branded tracking — free.',
                'og_image' => $base.'shopify-order-notifications.webp',
                'twitter_title' => 'Cut WISMO Tickets on Shopify',
                'twitter_description' => 'Automated notifications + branded tracking, free with PackSlip.',
                'twitter_image' => $base.'shopify-order-notifications.webp',
            ],
            // 3 — 21 June
            [
                'slug' => 'hire-laravel-developer-uk-2026-guide',
                'title' => 'Hire a Laravel Developer in the UK: Complete 2026 Guide',
                'category' => 'Laravel',
                'author_name' => 'ARS Developer Team',
                'excerpt' => 'What to look for when you hire a Laravel developer in the UK — skills, rates, red flags and how to brief your project for a successful custom build.',
                'content' => $this->c3(),
                'featured_image' => $base.'hire-laravel-developer-uk.webp',
                'featured_image_alt' => 'Hire a Laravel developer in the UK 2026 guide',
                'is_published' => true,
                'published_at' => '2026-06-21 09:00:00',
                'sort_order' => 0,
                'meta_title' => 'Hire a Laravel Developer UK — 2026 Guide',
                'meta_description' => 'How to hire a Laravel developer in the UK in 2026 — skills to check, typical rates, red flags and how to brief your project for a reliable custom build.',
                'meta_keywords' => 'hire laravel developer uk, laravel developer uk, laravel development company uk, custom laravel development, hire php developer uk',
                'meta_robots' => 'index, follow',
                'og_title' => 'Hire a Laravel Developer in the UK — 2026 Guide',
                'og_description' => 'Skills, rates, red flags and how to brief your Laravel project for success.',
                'og_image' => $base.'hire-laravel-developer-uk.webp',
                'twitter_title' => 'Hire a Laravel Developer UK',
                'twitter_description' => 'Skills, rates and red flags to check before you hire.',
                'twitter_image' => $base.'hire-laravel-developer-uk.webp',
            ],
            // 4 — 24 June
            [
                'slug' => 'wordpress-vs-shopify-uk-small-business',
                'title' => 'WordPress vs Shopify for UK Small Business: Which Should You Choose?',
                'category' => 'Strategy',
                'author_name' => 'ARS Developer Team',
                'excerpt' => 'WordPress or Shopify for your UK small business? A practical comparison of cost, SEO, ecommerce, maintenance and control to help you choose with confidence.',
                'content' => $this->c4(),
                'featured_image' => $base.'wordpress-vs-shopify.webp',
                'featured_image_alt' => 'WordPress vs Shopify for UK small business comparison',
                'is_published' => true,
                'published_at' => '2026-06-24 09:00:00',
                'sort_order' => 0,
                'meta_title' => 'WordPress vs Shopify for UK Small Business',
                'meta_description' => 'WordPress vs Shopify for UK small business — compare cost, SEO, ecommerce, maintenance and control to choose the right platform with confidence.',
                'meta_keywords' => 'wordpress vs shopify, wordpress or shopify uk, shopify vs wordpress small business, best platform for uk small business',
                'meta_robots' => 'index, follow',
                'og_title' => 'WordPress vs Shopify for UK Small Business',
                'og_description' => 'Cost, SEO, ecommerce, maintenance and control compared.',
                'og_image' => $base.'wordpress-vs-shopify.webp',
                'twitter_title' => 'WordPress vs Shopify (UK)',
                'twitter_description' => 'Which platform fits your UK small business? A practical comparison.',
                'twitter_image' => $base.'wordpress-vs-shopify.webp',
            ],
            // 5 — 27 June
            [
                'slug' => 'website-cost-uk-2026-pricing-guide',
                'title' => 'How Much Does a Website Cost in the UK? (2026 Pricing Guide)',
                'category' => 'Web Development',
                'author_name' => 'ARS Developer Team',
                'excerpt' => 'A clear, honest 2026 guide to website costs in the UK — from simple brochure sites to custom web applications, with real price ranges and what affects them.',
                'content' => $this->c5(),
                'featured_image' => $base.'website-cost-uk-2026.webp',
                'featured_image_alt' => 'How much does a website cost in the UK 2026 pricing guide',
                'is_published' => true,
                'published_at' => '2026-06-27 09:00:00',
                'sort_order' => 0,
                'meta_title' => 'How Much Does a Website Cost in the UK? 2026',
                'meta_description' => 'A clear 2026 guide to UK website costs — brochure sites, ecommerce and custom web apps, with real price ranges and the factors that change the price.',
                'meta_keywords' => 'website cost uk, how much does a website cost uk, web design cost uk, website pricing uk, custom website cost uk',
                'meta_robots' => 'index, follow',
                'og_title' => 'How Much Does a Website Cost in the UK? (2026)',
                'og_description' => 'Real UK price ranges for brochure sites, ecommerce and custom web apps.',
                'og_image' => $base.'website-cost-uk-2026.webp',
                'twitter_title' => 'UK Website Cost Guide 2026',
                'twitter_description' => 'Real price ranges for UK websites and what changes the price.',
                'twitter_image' => $base.'website-cost-uk-2026.webp',
            ],
        ];
    }

    private function cta(string $href, string $label): string
    {
        return '<p style="text-align:center;margin:32px 0;"><a href="'.$href.'" target="_blank" rel="noopener" style="display:inline-block;background:#1d93ff;color:#fff;text-decoration:none;padding:16px 36px;border-radius:50px;font-size:16px;font-weight:700;">'.$label.' &#8594;</a></p>';
    }

    private function c1(): string
    {
        $cta = $this->cta('https://packslip.arsdeveloper.co.uk/', 'Install PackSlip Free on Shopify');
        return <<<HTML
<p>Running a small Shopify store means watching every cost. The good news: many of the tools you need for fulfillment, marketing and customer experience are available free. Here are the best free Shopify apps for small stores in 2026 — and where one free app can replace several paid ones.</p>

<h2>Fulfillment & Shipping</h2>
<p>This is where small stores waste the most time and money. Branded packing slips, bulk order fulfillment and order tracking are usually sold as separate \$10–\$20/month apps. <a href="https://packslip.arsdeveloper.co.uk/" target="_blank" rel="noopener">PackSlip</a> bundles all of these — plus WhatsApp and email shipping alerts — into one free app with 34 modules. For a small store, it removes the entire fulfillment app bill.</p>

<h2>Reviews & Social Proof</h2>
<p>Product reviews increase conversion. Look for a free reviews module that collects and displays ratings on product pages. PackSlip includes a reviews module alongside its fulfillment tools, so you do not need a separate paid reviews app.</p>

<h2>Email & Abandoned Cart</h2>
<p>Abandoned-cart recovery and email capture pay for themselves. Shopify Email gives you a free monthly send allowance, and PackSlip's storefront tools add email capture and back-in-stock alerts at no cost.</p>

<h2>Trust & Conversion</h2>
<p>Trust badges, a branded tracking page and clear delivery messaging all lift conversion. These are small touches that free apps handle well — and PackSlip's branded tracking page keeps customers on your store instead of a courier site.</p>

<h2>How to Choose</h2>
<ul>
  <li>Prefer one app that covers several jobs over five single-purpose apps — fewer logins, fewer conflicts, lower cost.</li>
  <li>Check there are no hidden order limits on the free tier.</li>
  <li>Make sure it installs on your theme without code changes.</li>
</ul>

<h2>Frequently Asked Questions</h2>
<h3>Are free Shopify apps actually good?</h3>
<p>Yes — many free apps match paid ones for small-store needs. The key is choosing well-built apps with no hidden limits.</p>
<h3>What is the best free fulfillment app?</h3>
<p>PackSlip is a strong choice: branded packing slips, bulk fulfillment, order tracking and WhatsApp alerts across 34 free modules.</p>

{$cta}
HTML;
    }

    private function c2(): string
    {
        $cta = $this->cta('https://packslip.arsdeveloper.co.uk/', 'Install PackSlip Free on Shopify');
        return <<<HTML
<p>"Where is my order?" — known as WISMO — is the single most common support message ecommerce stores receive. Each ticket costs time and, often, a discount to keep the customer happy. The fix is not faster replies; it is removing the reason customers ask in the first place.</p>

<h2>Why Customers Ask "Where Is My Order?"</h2>
<p>They ask because they are uncertain. Shopify's default notifications are minimal and send customers to a generic courier page with no branding and little detail. If a customer cannot easily see their order's progress, they email you.</p>

<h2>Three Changes That Cut WISMO Tickets</h2>
<ol>
  <li><strong>Proactive notifications at every stage</strong> — order confirmed, dispatched, out for delivery, delivered. The more the customer is kept informed, the less they ask.</li>
  <li><strong>A branded tracking page</strong> on your own domain with a clear progress bar and timeline, so customers self-serve their status.</li>
  <li><strong>WhatsApp alerts</strong> — for many customers, a WhatsApp message is seen instantly, unlike email.</li>
</ol>

<h2>How to Set This Up Free</h2>
<p><a href="https://packslip.arsdeveloper.co.uk/" target="_blank" rel="noopener">PackSlip</a> provides all three: automated email and WhatsApp notifications at every fulfillment stage, plus a branded tracking page on your store domain. It installs free with no order limits, so even high-volume stores can cut WISMO tickets without adding cost.</p>

<h2>The Payoff</h2>
<p>Stores that add proactive tracking and notifications typically see a sharp drop in support volume and fewer "please refund / discount" conversations driven by delivery anxiety. Less support time, happier customers, more repeat orders.</p>

<h2>Frequently Asked Questions</h2>
<h3>What does WISMO mean?</h3>
<p>WISMO stands for "Where Is My Order?" — the most common ecommerce support enquiry.</p>
<h3>Can I send WhatsApp order updates on Shopify?</h3>
<p>Yes. PackSlip can send automated WhatsApp and email updates at each fulfillment stage, free.</p>

{$cta}
HTML;
    }

    private function c3(): string
    {
        $cta = $this->cta('https://arsdeveloper.co.uk/contact', 'Talk to a UK Laravel Developer');
        return <<<HTML
<p>Laravel is the framework behind many of the UK's custom web applications, dashboards, CRMs and SaaS platforms. But hiring the right Laravel developer is the difference between a system that scales and one that becomes a maintenance burden. Here is how to hire well in 2026.</p>

<h2>When You Actually Need Laravel</h2>
<p>Choose Laravel when your project is more than a brochure website — customer portals, admin dashboards, ERP or CRM modules, payment logic, API integrations or any custom business workflow. If you only need marketing pages and a blog, a simpler stack may be cheaper.</p>

<h2>Skills to Check</h2>
<ul>
  <li><strong>Core Laravel</strong> — Eloquent, migrations, queues, events, validation, testing.</li>
  <li><strong>Architecture</strong> — clean service classes, not 1,000-line controllers.</li>
  <li><strong>Security</strong> — auth, roles/permissions, input handling, OWASP basics.</li>
  <li><strong>APIs & integrations</strong> — REST, webhooks, third-party services (Stripe, etc.).</li>
  <li><strong>Frontend</strong> — Blade, or a Laravel + React/Vue setup if needed.</li>
  <li><strong>DevOps</strong> — deployment, caching, performance, backups.</li>
</ul>

<h2>Typical UK Rates (2026)</h2>
<p>Freelance Laravel developers in the UK typically range from around £35–£75/hour depending on seniority, with senior specialists and agencies higher. Fixed-price projects depend entirely on scope — a clear brief gets you a clear price.</p>

<h2>Red Flags</h2>
<ul>
  <li>No questions about your business workflow — only about screens.</li>
  <li>No tests, no version control, no staging environment.</li>
  <li>Reliance on heavy plugins to fake custom logic.</li>
  <li>No plan for handover, documentation or ongoing support.</li>
</ul>

<h2>How to Brief Your Project</h2>
<p>Write the business problem in one line, list the users and what each can do, the data you store, the integrations you need and what must stay secure. A good developer turns this into a database and module plan before writing UI.</p>

<h2>Frequently Asked Questions</h2>
<h3>How much does a Laravel developer cost in the UK?</h3>
<p>Roughly £35–£75/hour for freelancers; project pricing depends on scope. A clear brief produces a clear quote.</p>
<h3>Laravel or WordPress for a business system?</h3>
<p>WordPress for content and marketing; Laravel for custom logic, dashboards, portals, roles and integrations.</p>

{$cta}
HTML;
    }

    private function c4(): string
    {
        $cta = $this->cta('https://arsdeveloper.co.uk/contact', 'Get Honest Platform Advice');
        return <<<HTML
<p>For a UK small business, the platform you choose shapes your costs, your SEO and how easily you can grow. WordPress and Shopify are both excellent — for different jobs. Here is a practical comparison to help you choose.</p>

<h2>Quick Answer</h2>
<p>Choose <strong>Shopify</strong> if selling products online is your main goal. Choose <strong>WordPress</strong> if content, services, blogging and full design control matter more than a built-in checkout. Many businesses use WordPress for the brand site and a separate Shopify store for products.</p>

<h2>Cost</h2>
<p>Shopify has a predictable monthly fee plus app costs and transaction fees. WordPress is open-source (free software) but you pay for hosting, a theme and plugins — and a developer if you want something custom. For a simple shop, Shopify is often cheaper to run; for content-heavy sites, WordPress can be more economical.</p>

<h2>Ecommerce</h2>
<p>Shopify is purpose-built for selling: checkout, payments, inventory, shipping and apps all work out of the box. WordPress needs WooCommerce and more setup, but offers more flexibility for unusual requirements.</p>

<h2>SEO & Content</h2>
<p>Both can rank well. WordPress has the edge for large content operations and fine-grained control. Shopify is strong for product SEO and is improving constantly. Technical SEO and Core Web Vitals matter more than the platform itself.</p>

<h2>Maintenance & Control</h2>
<p>Shopify handles hosting, security and updates for you — less maintenance, less control. WordPress gives you full control and ownership but you are responsible for updates, security and backups.</p>

<h2>Which Should a UK Small Business Choose?</h2>
<ul>
  <li><strong>Product-first store</strong> → Shopify.</li>
  <li><strong>Service business, blog, full design control</strong> → WordPress.</li>
  <li><strong>Complex custom workflow</strong> → consider a custom build (Laravel) instead of either.</li>
</ul>

<h2>Frequently Asked Questions</h2>
<h3>Is Shopify or WordPress better for SEO?</h3>
<p>Both can rank well. WordPress offers more content control; Shopify is strong for product pages. Execution matters more than the platform.</p>
<h3>Can I move from Shopify to WordPress later?</h3>
<p>Yes, content and products can be migrated, though it takes planning. Choosing well first saves a migration.</p>

{$cta}
HTML;
    }

    private function c5(): string
    {
        $cta = $this->cta('https://arsdeveloper.co.uk/contact', 'Get a Free Project Quote');
        return <<<HTML
<p>"How much does a website cost in the UK?" has no single answer — because a five-page brochure site and a custom web application are completely different products. Here is an honest 2026 breakdown so you can budget with confidence.</p>

<h2>Typical UK Website Price Ranges (2026)</h2>
<ul>
  <li><strong>Simple brochure site (3–8 pages):</strong> roughly £500–£2,500 — templated design, basic SEO, contact form.</li>
  <li><strong>Professional business site (custom design, 10–25 pages):</strong> roughly £2,500–£8,000 — bespoke design, copy, on-page SEO, integrations.</li>
  <li><strong>Ecommerce (Shopify or WooCommerce):</strong> roughly £3,000–£12,000+ — depends on products, design and integrations.</li>
  <li><strong>Custom web application / dashboard / CRM (Laravel):</strong> £8,000–£50,000+ — driven entirely by features and complexity.</li>
</ul>

<h2>What Changes the Price</h2>
<ul>
  <li><strong>Custom vs templated design</strong> — bespoke costs more but stands out.</li>
  <li><strong>Number of pages and content</strong> — copywriting and structure take time.</li>
  <li><strong>Functionality</strong> — bookings, payments, logins, dashboards, APIs.</li>
  <li><strong>SEO and performance</strong> — proper technical SEO is worth the investment.</li>
  <li><strong>Ongoing support</strong> — maintenance, hosting and updates.</li>
</ul>

<h2>One-Off Cost vs Ongoing Cost</h2>
<p>Budget for both: the build (one-off) and the running costs (hosting, domain, maintenance, and any app or plugin subscriptions). A cheap build with no maintenance plan often costs more later.</p>

<h2>How to Get an Accurate Quote</h2>
<p>The clearer your brief, the more accurate the price. Note your goals, the pages and features you need, examples you like, and your timeline. A good developer will ask about your business — not just your design.</p>

<h2>Frequently Asked Questions</h2>
<h3>How much does a small business website cost in the UK?</h3>
<p>Most small business sites land between £500 and £8,000 depending on design and features. Ecommerce and custom apps cost more.</p>
<h3>Why do website quotes vary so much?</h3>
<p>Because "website" covers everything from a template to a custom application. Scope, design and functionality drive the difference.</p>

{$cta}
HTML;
    }
}
