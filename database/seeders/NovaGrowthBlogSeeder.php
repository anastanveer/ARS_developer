<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class NovaGrowthBlogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->posts() as $post) {
            BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }

    private function posts(): array
    {
        return [
            [
                'title' => "Why your Laravel app is slow (and the 4 fixes that recover the most revenue) — a Stoke-on-Trent dev's guide",
                'slug' => 'why-your-laravel-app-is-slow-revenue-fixes-stoke-on-trent',
                'category' => 'Laravel',
                'author_name' => 'ARS Developer',
                'excerpt' => 'Slow Laravel apps quietly leak revenue. Learn the four fixes that usually recover the most value: queries, indexes, caching and assets.',
                'content' => $this->laravelSlowContent(),
                'featured_image' => 'assets/images/blog/growth-2026/laravel-app-slow-revenue-fixes-stoke-on-trent.png',
                'featured_image_alt' => 'Laravel performance audit in Stoke-on-Trent showing slow page revenue fixes',
                'is_published' => true,
                'published_at' => '2026-06-30 11:30:00',
                'meta_title' => 'Why Your Laravel App Is Slow | ARS Developer',
                'meta_description' => 'A Stoke-on-Trent Laravel performance guide covering N+1 queries, indexes, caching and asset fixes that recover revenue from slow apps.',
                'meta_keywords' => 'laravel development company, software development stoke-on-trent, laravel performance, fix slow website, laravel n+1 query',
                'og_title' => 'Why Your Laravel App Is Slow',
                'og_description' => 'The four Laravel performance fixes that recover the most revenue from slow web apps and stores.',
                'og_image' => 'assets/images/blog/growth-2026/laravel-app-slow-revenue-fixes-stoke-on-trent.png',
                'twitter_title' => 'Why Your Laravel App Is Slow',
                'twitter_description' => 'N+1 queries, missing indexes, no caching and heavy assets are usually where the money leaks.',
                'twitter_image' => 'assets/images/blog/growth-2026/laravel-app-slow-revenue-fixes-stoke-on-trent.png',
                'canonical_url' => 'https://arsdeveloper.co.uk/blog/why-your-laravel-app-is-slow-revenue-fixes-stoke-on-trent',
                'sort_order' => 0,
            ],
            [
                'title' => "How to hire a software development company (a buyer's guide for non-technical founders)",
                'slug' => 'how-to-hire-software-development-company-founders-guide',
                'category' => 'Software Development',
                'author_name' => 'ARS Developer',
                'excerpt' => 'A plain-English buyer guide for founders choosing a software development company, including seven questions that reveal the right partner.',
                'content' => $this->softwareCompanyContent(),
                'featured_image' => 'assets/images/blog/growth-2026/hire-software-development-company-founders-guide.png',
                'featured_image_alt' => 'Checklist for hiring a software development company in Stoke-on-Trent',
                'is_published' => true,
                'published_at' => '2026-07-02 11:30:00',
                'meta_title' => 'How to Hire a Software Development Company',
                'meta_description' => 'Seven questions non-technical founders should ask before hiring a software development company or Laravel development partner.',
                'meta_keywords' => 'software development company stoke-on-trent, laravel development company, hire a development agency, how to choose a software developer',
                'og_title' => 'How to Hire a Software Development Company',
                'og_description' => 'A founder-friendly checklist for choosing the right software development partner before you sign.',
                'og_image' => 'assets/images/blog/growth-2026/hire-software-development-company-founders-guide.png',
                'twitter_title' => 'How to Hire a Software Development Company',
                'twitter_description' => 'Seven buyer questions that separate a real software partner from a reseller.',
                'twitter_image' => 'assets/images/blog/growth-2026/hire-software-development-company-founders-guide.png',
                'canonical_url' => 'https://arsdeveloper.co.uk/blog/how-to-hire-software-development-company-founders-guide',
                'sort_order' => 0,
            ],
            [
                'title' => "Shopify Custom Theme Development: When You Actually Need It (and When You're Just Losing Money)",
                'slug' => 'shopify-custom-theme-development-when-you-need-it',
                'category' => 'Shopify',
                'author_name' => 'ARS Developer',
                'excerpt' => 'Custom Shopify themes can help, but only when the real problem is theme capability, not speed, checkout friction or recovery flows.',
                'content' => $this->shopifyThemeContent(),
                'featured_image' => 'assets/images/blog/growth-2026/shopify-custom-theme-development-rebuild-vs-fix.png',
                'featured_image_alt' => 'Shopify custom theme development comparison between rebuild and fix sprint',
                'is_published' => true,
                'published_at' => '2026-07-03 21:00:00',
                'meta_title' => 'Shopify Custom Theme Development: When You Need It',
                'meta_description' => 'A practical guide to when Shopify custom theme development is worth it, and when speed, CRO or recovery fixes are the better move.',
                'meta_keywords' => 'shopify custom theme development, shopify theme customization, shopify store optimization, shopify developer uk',
                'og_title' => 'Shopify Custom Theme Development: When You Need It',
                'og_description' => 'Custom theme or focused fix sprint? Learn which one is actually costing you sales.',
                'og_image' => 'assets/images/blog/growth-2026/shopify-custom-theme-development-rebuild-vs-fix.png',
                'twitter_title' => 'Shopify Custom Theme Development',
                'twitter_description' => 'When custom pays off, and when it is just an expensive distraction.',
                'twitter_image' => 'assets/images/blog/growth-2026/shopify-custom-theme-development-rebuild-vs-fix.png',
                'canonical_url' => 'https://arsdeveloper.co.uk/blog/shopify-custom-theme-development-when-you-need-it',
                'sort_order' => 0,
            ],
            [
                'title' => 'Hiring a Laravel Development Company in 2026: What to Check Before You Sign',
                'slug' => 'hiring-laravel-development-company-2026',
                'category' => 'Laravel',
                'author_name' => 'ARS Developer',
                'excerpt' => 'A five-point checklist for hiring a Laravel development company in 2026, from versions and tests to queues, ownership and references.',
                'content' => $this->laravelCompanyContent(),
                'featured_image' => 'assets/images/blog/growth-2026/hiring-laravel-development-company-2026.png',
                'featured_image_alt' => 'Laravel development company hiring checklist for UK businesses',
                'is_published' => true,
                'published_at' => '2026-07-07 11:30:00',
                'meta_title' => 'Hiring a Laravel Development Company in 2026',
                'meta_description' => 'What UK businesses should check before hiring a Laravel development company: versioning, tests, queues, ownership and references.',
                'meta_keywords' => 'laravel development company, software development company stoke-on-trent, laravel developers uk, hire laravel developer, laravel agency, custom web application development uk',
                'og_title' => 'Hiring a Laravel Development Company in 2026',
                'og_description' => 'The checklist we would want every client to use before hiring a Laravel team.',
                'og_image' => 'assets/images/blog/growth-2026/hiring-laravel-development-company-2026.png',
                'twitter_title' => 'Hiring a Laravel Development Company in 2026',
                'twitter_description' => 'Versions, tests, queues, ownership and references: the five checks before you sign.',
                'twitter_image' => 'assets/images/blog/growth-2026/hiring-laravel-development-company-2026.png',
                'canonical_url' => 'https://arsdeveloper.co.uk/blog/hiring-laravel-development-company-2026',
                'sort_order' => 0,
            ],
            [
                'title' => 'Shopify Custom Theme Development: 7 Revenue Leaks Hiding in Your Store (And How to Fix Them)',
                'slug' => 'shopify-custom-theme-development-revenue-leaks',
                'category' => 'Shopify',
                'author_name' => 'ARS Developer',
                'excerpt' => 'Most Shopify stores do not have a traffic problem. They have revenue leaks in speed, apps, checkout, recovery, product pages and follow-up.',
                'content' => $this->shopifyLeaksContent(),
                'featured_image' => 'assets/images/blog/growth-2026/shopify-custom-theme-revenue-leaks.png',
                'featured_image_alt' => 'Shopify custom theme revenue leaks audit showing speed checkout and cart problems',
                'is_published' => true,
                'published_at' => '2026-07-08 11:30:00',
                'meta_title' => 'Shopify Custom Theme Development: 7 Revenue Leaks',
                'meta_description' => 'Seven Shopify revenue leaks hiding in your store, from slow themes and bloated apps to checkout friction and weak recovery flows.',
                'meta_keywords' => 'shopify custom theme development, shopify revenue leak audit, shopify speed optimization, shopify cro, shopify store optimization',
                'og_title' => 'Shopify Custom Theme Development: 7 Revenue Leaks',
                'og_description' => 'Find the hidden Shopify leaks draining revenue before you spend more on ads.',
                'og_image' => 'assets/images/blog/growth-2026/shopify-custom-theme-revenue-leaks.png',
                'twitter_title' => 'Shopify Revenue Leaks Hiding in Your Store',
                'twitter_description' => 'Seven leaks in Shopify speed, apps, checkout, cart recovery and post-purchase follow-up.',
                'twitter_image' => 'assets/images/blog/growth-2026/shopify-custom-theme-revenue-leaks.png',
                'canonical_url' => 'https://arsdeveloper.co.uk/blog/shopify-custom-theme-development-revenue-leaks',
                'sort_order' => 0,
            ],
            [
                'title' => 'Shopify Revenue Leaks: The 7-Point Audit Every UK Store Should Run in 2026',
                'slug' => 'shopify-revenue-leaks-7-point-audit-uk-store-2026',
                'category' => 'Shopify',
                'author_name' => 'ARS Developer',
                'excerpt' => 'A practical seven-point Shopify audit for UK stores: speed, checkout friction, abandoned carts, product clarity, search, SEO and support speed.',
                'content' => $this->shopifyRevenueAuditContent(),
                'featured_image' => 'assets/images/blog/growth-2026/shopify-revenue-leaks-7-point-audit-uk-store-2026.png',
                'featured_image_alt' => 'Seven point Shopify revenue leak audit checklist for UK stores',
                'is_published' => true,
                'published_at' => '2026-07-13 12:00:00',
                'meta_title' => 'Shopify Revenue Leaks: 7-Point UK Store Audit',
                'meta_description' => 'Run this 7-point Shopify revenue leak audit for UK stores covering speed, checkout friction, abandoned carts, CRO, technical SEO and support speed.',
                'meta_keywords' => 'shopify revenue leak audit, shopify cro checklist, shopify speed optimization uk, shopify abandoned cart recovery, shopify seo audit 2026',
                'og_title' => 'Shopify Revenue Leaks: The 7-Point Audit',
                'og_description' => 'A practical UK Shopify audit to find the hidden friction costing your store orders.',
                'og_image' => 'assets/images/blog/growth-2026/shopify-revenue-leaks-7-point-audit-uk-store-2026.png',
                'twitter_title' => 'Shopify Revenue Leaks: 7-Point Audit',
                'twitter_description' => 'Speed, checkout, carts, product pages, search, SEO and support: the Shopify leaks to check first.',
                'twitter_image' => 'assets/images/blog/growth-2026/shopify-revenue-leaks-7-point-audit-uk-store-2026.png',
                'canonical_url' => 'https://arsdeveloper.co.uk/blog/shopify-revenue-leaks-7-point-audit-uk-store-2026',
                'sort_order' => 0,
            ],
            [
                'title' => 'Laravel Performance: The 7 Bottlenecks Costing You Orders (and How to Fix Each)',
                'slug' => 'laravel-performance-7-bottlenecks-costing-orders',
                'category' => 'Laravel',
                'author_name' => 'ARS Developer',
                'excerpt' => 'A proof-based Laravel performance guide covering the seven bottlenecks that hurt ecommerce revenue and how to fix each one.',
                'content' => $this->laravelPerformanceBottlenecksContent(),
                'featured_image' => 'assets/images/blog/growth-2026/laravel-performance-7-bottlenecks-costing-orders.png',
                'featured_image_alt' => 'Laravel performance profiler showing seven bottlenecks costing orders',
                'is_published' => true,
                'published_at' => '2026-07-16 12:00:00',
                'meta_title' => 'Laravel Performance: 7 Bottlenecks Costing Orders',
                'meta_description' => 'A practical, proof-based guide to seven Laravel performance bottlenecks that hurt ecommerce revenue, from N+1 queries to queues and cache gaps.',
                'meta_keywords' => 'Laravel performance, Laravel optimization, Laravel N+1 query, Laravel slow query, Laravel caching Redis, Laravel queue jobs, Laravel ecommerce performance',
                'og_title' => 'Laravel Performance: 7 Bottlenecks Costing Orders',
                'og_description' => 'The bottlenecks behind slow Laravel ecommerce apps, and the fixes that recover speed and revenue.',
                'og_image' => 'assets/images/blog/growth-2026/laravel-performance-7-bottlenecks-costing-orders.png',
                'twitter_title' => 'Laravel Performance: 7 Bottlenecks',
                'twitter_description' => 'Profile first, fix the real bottleneck, then measure the revenue impact.',
                'twitter_image' => 'assets/images/blog/growth-2026/laravel-performance-7-bottlenecks-costing-orders.png',
                'canonical_url' => 'https://arsdeveloper.co.uk/blog/laravel-performance-7-bottlenecks-costing-orders',
                'sort_order' => 0,
            ],
        ];
    }

    private function shopifyRevenueAuditContent(): string
    {
        return <<<'HTML'
<p>If your Shopify store gets traffic but the revenue feels stuck, the problem usually is not demand. It is leakage: paid-for visitors who never convert because of friction you cannot see from the dashboard. This is a practical, no-fluff audit you can run today.</p>

<h2>1. Mobile speed</h2>
<p>Test your store on a real phone on 4G. If the product page takes more than about three seconds, you are losing sales at the door. Check image sizes, unused apps, render-blocking scripts and theme bloat.</p>

<h2>2. Checkout friction</h2>
<p>Count the steps and fields between Add to Cart and Order Placed. Every extra field, forced account creation or late shipping reveal raises abandonment. Display Klarna or Clearpay clearly for UK buyers who expect it.</p>

<h2>3. Abandoned cart recovery</h2>
<p>Do you follow up within the first hour? Email plus WhatsApp or SMS inside 60 minutes usually recovers more than a single next-day email.</p>

<h2>4. Product page clarity</h2>
<p>Price, delivery time, returns and trust signals should be visible early. Confusion is friction; friction is lost revenue.</p>

<h2>5. On-site search and navigation</h2>
<p>Visitors who search convert at much higher rates if search actually works. Test your top ten queries and check whether the right products appear.</p>

<h2>6. Technical SEO</h2>
<p>Missing meta titles, slow Core Web Vitals and unindexed collection pages cost you free traffic. Check Search Console for coverage errors and high-value pages with low impressions.</p>

<h2>7. Support response speed</h2>
<p>How long until a pre-purchase question gets answered? If it is hours, an AI assistant that answers instantly can rescue carts that would otherwise vanish.</p>

<h2>Turn the audit into pounds</h2>
<p>Rank each leak by estimated revenue impact, not by how easy it is to fix. The fastest win is rarely the biggest one. That ranking is exactly what our Shopify Revenue Leak Audit produces: measured, prioritised and explained in plain English by a UK team.</p>

<p>Run the seven points yourself, or have us run them for you. Either way, you will know the number you are leaving on the table and which fix pays back first.</p>

<p><a href="https://arsdeveloper.co.uk/contact">Book a done-for-you Shopify Revenue Leak Audit</a>. We will rank the fixes, show the real numbers and avoid locking you into a retainer you do not need.</p>
HTML;
    }

    private function laravelPerformanceBottlenecksContent(): string
    {
        return <<<'HTML'
<p>Slow software does not announce itself. There is no error, no alert, just a checkout that feels sluggish and a conversion rate that quietly underperforms.</p>

<p>We are a Stoke-on-Trent software agency, and most “we need a bigger server” requests we get turn out to be one of seven fixable Laravel bottlenecks. Here is each one, with the fix and the kind of numbers we measure.</p>

<h2>1. N+1 queries: the silent multiplier</h2>
<p>An N+1 issue happens when one database query becomes hundreds because the app loads related records inside a loop. Use Laravel Debugbar, Telescope or query logs to spot it, then eager-load the relationships that the page actually needs. A page doing 240 queries can often drop to fewer than ten.</p>

<h2>2. Missing database indexes</h2>
<p>If your orders, users or products tables are growing, missing indexes turn simple filters into table scans. Use slow-query logs and <code>EXPLAIN</code> to find the pressure points. Index foreign keys and the columns used in common <code>WHERE</code>, <code>JOIN</code> and <code>ORDER BY</code> clauses.</p>

<h2>3. Blocking third-party calls</h2>
<p>Payment checks, shipping APIs, CRM syncs and webhooks should not block a customer-facing request unless the user genuinely needs the answer immediately. Move the non-critical work into queued jobs and give the customer a fast response first.</p>

<h2>4. Cache gaps</h2>
<p>Laravel gives you route, config and view caching, but many apps still rerun expensive query work on every visit. Cache stable data with sensible TTLs, and use Redis when the workload deserves it.</p>

<h2>5. Queue misuse</h2>
<p>Email, image processing, imports, exports and webhook retries belong in jobs. The queue still needs worker sizing, retries and monitoring. A queue that is always behind is just a hidden performance problem.</p>

<h2>6. Frontend asset bloat</h2>
<p>Laravel performance is not only PHP. Heavy JavaScript, unused CSS and uncompressed media damage Core Web Vitals. Keep Vite builds lean, defer non-critical JavaScript and compress images before they hit production.</p>

<h2>7. Slow application boot</h2>
<p>Production should use config caching, route caching, opcache and careful service provider loading. Slow boot makes every request pay a tax before your code does useful work.</p>

<h2>Profile before you upgrade hosting</h2>
<p>Performance is an architecture discipline, not a hosting upgrade. Profile first, fix the bottleneck, measure the result, then decide whether infrastructure is still the constraint.</p>

<p><a href="https://arsdeveloper.co.uk/contact">Book a fixed-scope Laravel performance audit</a>. We will report exactly what is slowing you down and what each fix is worth.</p>

<p>Shopify or freelance development work? View the portfolio at <a href="https://anastanveer.com" target="_blank" rel="noopener">anastanveer.com</a>.</p>
HTML;
    }

    private function laravelSlowContent(): string
    {
        return <<<'HTML'
<p>If your web app or store feels sluggish, it is not “just how it is.” Slow software is a revenue leak, and in our experience four issues cause most of it. Here is how we diagnose and fix them.</p>

<h2>1. N+1 queries</h2>
<p>Your code asks the database one question, then a hundred follow-ups. We had a client page firing 1,900 queries; eager-loading relationships dropped it to 7.</p>
<p><strong>Fix:</strong> load related data up front instead of in a loop.</p>

<h2>2. Missing database indexes</h2>
<p>Without an index, the database reads every row to find one. On a 200k-row orders table, that can be the difference between 2ms and 2 seconds.</p>
<p><strong>Fix:</strong> index the columns you filter and join on.</p>

<h2>3. No caching</h2>
<p>The same expensive query runs on every visit.</p>
<p><strong>Fix:</strong> cache results that do not change every second. Product feeds, category pages and configuration data are usually good candidates. Even 60 seconds of caching can cut server load dramatically.</p>

<h2>4. Unoptimised assets</h2>
<p>Oversized images and render-blocking scripts choke the page before your content loads.</p>
<p><strong>Fix:</strong> compress images, lazy-load non-critical media and defer scripts that are not needed for first paint.</p>

<h2>Why this matters for revenue</h2>
<p>A one-second delay can cut conversions by around 7%. On a store doing £40k/month, that is roughly £2,800 a month walking out the door — invisible, because nobody on the team can see the load time their customers feel.</p>

<p>We are a software and Laravel development team based in Stoke-on-Trent. We diagnose these leaks, give you the numbers in plain English and fix the ones that pay for themselves fastest.</p>

<p>Want the audit? It starts with one slow page and a 24-hour turnaround on findings.</p>

<p><a href="https://arsdeveloper.co.uk/contact">Book a free revenue-leak audit</a> and we will show you what your slowest page is costing you.</p>

<p>Shopify or freelance development work? View the portfolio at <a href="https://anastanveer.com" target="_blank" rel="noopener">anastanveer.com</a>.</p>
HTML;
    }

    private function softwareCompanyContent(): string
    {
        return <<<'HTML'
<p>Most founders hire a development agency the wrong way: they pick the cheapest quote, then spend the next year paying for it.</p>

<p>This guide gives you the seven questions that separate a real software development company from a reseller of someone else’s time.</p>

<h2>1. “Show me a project like mine — and the result, not the screenshot.”</h2>
<p>Ask for the metric: faster load, more conversions, fewer support tickets. If they only show pretty UI, walk.</p>

<h2>2. “Who actually writes my code?”</h2>
<p>Many agencies subcontract. Ask who, where and whether you can talk to them.</p>

<h2>3. “What happens when it breaks at 2am?”</h2>
<p>Support and ownership matter more than the build itself.</p>

<h2>4. “Can you explain a trade-off in plain English?”</h2>
<p>If they cannot explain build-vs-buy without jargon, they will bill you for confusion.</p>

<h2>5. “Do you measure the result?”</h2>
<p>Good agencies tie work to a number: speed, revenue, retention or reduced support load.</p>

<h2>6. “Who owns the code and the accounts?”</h2>
<p>You should. Always. Get it in writing.</p>

<h2>7. “What would you not build for me?”</h2>
<p>The best partners talk you out of waste.</p>

<p>At ARS Developer, we are a Stoke-on-Trent software development company that builds with Laravel, Shopify and AI automation. We lead every project with one question: what is this actually worth to the business?</p>

<p>If you are weighing up a build, start with a teardown, not a quote.</p>

<p><a href="https://arsdeveloper.co.uk/contact">Book a free 20-minute discovery call</a>. We will tell you what to build, what to skip and what it is worth.</p>

<p>Shopify or freelance development work? View the portfolio at <a href="https://anastanveer.com" target="_blank" rel="noopener">anastanveer.com</a>.</p>
HTML;
    }

    private function shopifyThemeContent(): string
    {
        return <<<'HTML'
<p>If you are searching for Shopify custom theme development, you are usually trying to solve one of two problems: your store looks generic, or it does not convert. Those are different problems, and a custom theme only fixes one of them.</p>

<h2>When a custom theme is the right call</h2>
<ul>
  <li>Your brand needs to look distinct and a paid theme cannot get you there.</li>
  <li>You have specific functionality such as bundles, subscriptions or custom product configurators that no off-the-shelf theme handles cleanly.</li>
  <li>Your current theme is bloated, slow and patched with apps until it is unmaintainable.</li>
</ul>

<h2>When a custom theme is not your problem</h2>
<ul>
  <li>Your store is slow. That is often app bloat and unoptimised code, not a full rebuild requirement.</li>
  <li>Your traffic does not convert. That is usually CRO: product pages, trust signals and checkout flow.</li>
  <li>You are losing carts. That is a recovery flow — email and WhatsApp — not a redesign.</li>
</ul>

<p>A lot of store owners spend on a beautiful new theme and watch conversion stay flat because the leak was never the design. It was speed, friction and follow-up.</p>

<h2>What a good Shopify developer actually does</h2>
<p>A strong build is fast first, pretty second. Clean, lightweight code. Mobile load under around 2.5 seconds. Minimal app dependency. Conversion-focused layout that leads with the customer’s problem and surfaces proof.</p>

<p>The design serves the revenue, not the other way around.</p>

<h2>The smart first step</h2>
<p>The right partner starts by asking what is actually losing you money, not by selling you a rebuild. Sometimes the answer is a custom theme. Just as often, it is a focused fix sprint on speed, SEO and conversion that costs a fraction and moves revenue faster.</p>

<p><a href="https://arsdeveloper.co.uk/contact">Book a free Shopify revenue-leak audit</a> and we will show you what is worth fixing first.</p>
HTML;
    }

    private function laravelCompanyContent(): string
    {
        return <<<'HTML'
<p>Most Laravel projects do not fail because of Laravel. They fail because the wrong team was hired for the brief.</p>

<p>We are a Laravel development company based in Stoke-on-Trent. We build and maintain Laravel apps for UK businesses: internal tools, customer portals, SaaS products and API back-ends. This is the checklist we would want a client to use on us before they spend a penny.</p>

<h2>1. Ask what version they are on, and why</h2>
<p>Laravel ships major versions yearly. A serious team runs current or current-minus-one and can explain their upgrade path. If they are stuck three versions back, you will inherit that debt.</p>

<h2>2. Ask to see a test suite</h2>
<p>No tests means every change is a gamble. Ask: “What is your code coverage on the last project you shipped?” A real answer beats a vague one every time.</p>

<h2>3. Ask how they handle queues, caching and background jobs</h2>
<p>This is where Laravel apps slow down at scale. If the answer is only “we use Laravel”, that is not an answer.</p>

<h2>4. Ask who owns the code</h2>
<p>You should own your repository, your server and your data on day one. Get it in writing.</p>

<h2>5. Ask for one reference you can phone</h2>
<p>Not a logo wall. A person.</p>

<h2>What we do differently</h2>
<ul>
  <li>Fixed-scope discovery before any build, so you see the plan and the price before you commit.</li>
  <li>Tested code, version-controlled and handed over with documentation.</li>
  <li>A 30-minute call where we look at your actual problem, not a sales script.</li>
</ul>

<p>If you are weighing up a Laravel build or rescuing one that stalled, we will review your brief for free and tell you honestly whether it is a fit.</p>

<p><a href="https://arsdeveloper.co.uk/contact">Book a free 30-minute Laravel project review</a>. We will tell you what it really takes to build, fix or rescue your app.</p>

<p>Shopify or freelance development work? View the portfolio at <a href="https://anastanveer.com" target="_blank" rel="noopener">anastanveer.com</a>.</p>
HTML;
    }

    private function shopifyLeaksContent(): string
    {
        return <<<'HTML'
<p>Most Shopify stores do not have a traffic problem. They have a leak problem.</p>

<p>You pay for ads. People click. Then 97 out of 100 leave without buying. The money is not lost at the top of the funnel. It drains out through a slow theme, a clunky checkout and a cart nobody follows up on.</p>

<h2>1. A theme that loads in 6+ seconds</h2>
<p>Every extra second of load time drops conversions. Custom Shopify theme development is not only about looks. It is about shaving page weight so mobile buyers do not bounce before they see the Add to Cart button.</p>

<h2>2. Bloated apps</h2>
<p>Ten apps each inject scripts. Together they can add seconds to load and break your layout on mobile. Audit, remove and replace with native code where possible.</p>

<h2>3. A checkout with friction</h2>
<p>Hidden shipping, no Apple Pay or a forced account can each become a reason to abandon.</p>

<h2>4. No abandoned-cart recovery</h2>
<p>Many stores recover 0% of abandoned carts because nobody set up the flow. A simple email and WhatsApp sequence can recover meaningful revenue.</p>

<h2>5. Product pages that do not sell</h2>
<p>No social proof above the fold, weak copy and no urgency all make the buyer’s decision harder.</p>

<h2>6. Slow, generic search</h2>
<p>Buyers who use site search convert higher, but only if search actually works.</p>

<h2>7. Zero post-purchase follow-up</h2>
<p>Your cheapest sale is the second one. Most stores never ask for it.</p>

<h2>How we fix it</h2>
<p>Start with a Shopify revenue-leak audit. We screen-record the store, score each leak and create a prioritised fix list with the revenue each fix should return. Then we run a Speed + SEO + CRO sprint and layer in automation for support, cart recovery and WhatsApp follow-up where it makes sense.</p>

<p><a href="https://arsdeveloper.co.uk/contact">Book a free Shopify revenue-leak audit</a>. We will show you where the money leaks and what each fix is worth.</p>

<p>Need Dubai freelance web development? Visit <a href="https://anastanveer.com" target="_blank" rel="noopener">anastanveer.com</a>.</p>
HTML;
    }
}
