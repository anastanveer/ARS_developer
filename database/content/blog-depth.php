<?php

/**
 * Per-post depth sections, written for one post each.
 *
 * Stripping the templated filler left the shorter posts at 200-400 words. The fix
 * is not to hide them but to make them worth the visit, and the test every section
 * here has to pass is simple: move it to another post and it should become false or
 * irrelevant. Anything that would read equally well on thirty pages is the padding
 * that was just removed, so it does not belong here.
 *
 * Keyed by slug. `blog:add-depth` inserts these ahead of the FAQ and skips any
 * heading already present.
 */

return [

    // ---------------------------------------------------------------- Laravel ---

    'why-your-laravel-app-is-slow-revenue-fixes-stoke-on-trent' => [
        [
            'heading' => 'Measure before you change anything',
            'body' => [
                'Most Laravel slowness gets misdiagnosed because the symptom and the cause sit in different layers. A page that "feels slow" might be spending 40ms in PHP and 2.5 seconds waiting on a third-party script the browser loaded afterwards. Split the number before you spend money on it: time to first byte tells you about the server, and everything after it is the front end.',
                'Laravel Telescope or Debugbar on a staging copy will show the query count for a single request. If a listing page fires 300 queries, no amount of faster hosting will fix it, because the problem is 300 round trips rather than the speed of each one.',
            ],
        ],
        [
            'heading' => 'The revenue question, not the speed question',
            'body' => [
                'Not every slow page is worth fixing. Sort your pages by sessions multiplied by their share of conversions, and the list usually collapses to three or four templates: the product or service page, the listing that feeds it, the basket, and the checkout or enquiry form. An admin report that takes nine seconds annoys your staff; a product page that takes four seconds costs orders.',
                'That ordering also protects the budget. Fixing the two templates that carry the money is often a day or two of work, where "make the whole app fast" is an open-ended project with no obvious finish line.',
            ],
        ],
    ],

    'laravel-performance-7-bottlenecks-costing-orders' => [
        [
            'heading' => 'How to confirm each bottleneck is actually yours',
            'body' => [
                'N+1 queries show up as a repeated, near-identical query in the Telescope timeline — the same SELECT with a different id, once per row on screen. The fix is an eager load on the relationship, and the verification is the query count dropping from the row count to two or three.',
                'A missing index is different: one slow query rather than many fast ones. Run EXPLAIN on it and look for a full table scan. Where the query filters on two columns together, a composite index in that order usually matters more than two separate ones, which is the part most often got wrong.',
            ],
        ],
        [
            'heading' => 'What each fix typically costs to implement',
            'body' => [
                'Eager loading and indexes are usually hours, not days, and they carry almost no risk because they change how data is fetched rather than what the application does. Caching is the opposite: cheap to add, expensive to get wrong, because the bug it produces is stale data that nobody notices until a customer does.',
                'Moving work to a queue — emails, PDF generation, third-party calls — is the change that most often turns a four-second page into a fast one, but it needs a supervisor process and monitoring to be production-safe. Budget for the monitoring, not just the queue.',
            ],
        ],
    ],

    'laravel-migration-services-uk-when-to-refactor-rebuild-or-replace-legacy-php' => [
        [
            'heading' => 'The strangler pattern beats a big-bang rewrite',
            'body' => [
                'The rewrite that replaces everything on one weekend is the version that goes wrong, because every assumption buried in ten years of legacy code has to be rediscovered at once. The safer route puts the new Laravel application in front of the old system and moves one route at a time, so the old code keeps serving everything that has not been migrated yet.',
                'The seam is usually the database. Both applications read the same tables while the move is in progress, which means sessions and authentication have to be shared deliberately rather than by accident — that is the detail that decides whether the approach works.',
            ],
        ],
        [
            'heading' => 'What the PHP version jump actually breaks',
            'body' => [
                'Legacy PHP applications moving from 5.6 or 7.x to 8.x hit a predictable set of failures: each() and create_function() are gone, mysql_* was removed long ago, and string-to-number comparisons changed in 8.0 so that a check which used to pass now does not. That last one is quiet — no error, just different behaviour.',
                'Before quoting a migration, run the existing codebase through a static analysis pass to get a real count of the incompatibilities. A quote produced without that number is a guess, and it is usually a guess in the client\'s disfavour.',
            ],
        ],
    ],

    'hire-laravel-developer-uk-2026-guide' => [
        [
            'heading' => 'What a useful technical check looks like',
            'body' => [
                'A take-home task that asks someone to build a small CRUD app tells you very little, because anyone can build one and it takes their evening. A better test is to hand over a short piece of existing code with a real problem in it — an N+1, a mass-assignment hole, a migration that will lock a large table — and ask what they would change and why.',
                'The answer shows judgement rather than recall. Someone who explains the trade-off they are making, including what their fix costs, is usually the person you want on a codebase you have to live with afterwards.',
            ],
        ],
        [
            'heading' => 'Verify the work they claim',
            'body' => [
                'Portfolio links are easy to collect and hard to check, so ask a specific question about a specific project: which parts did you build, what was already there, and what would you do differently now. Someone who did the work answers immediately and usually volunteers a mistake.',
                'For UK engagements, settle the employment status question in writing before the first invoice. A contract developer working through their own company, a freelancer, and a fixed-price supplier are three different arrangements for tax, and discovering which one you have after six months is an expensive way to find out.',
            ],
        ],
    ],

    'hire-a-laravel-developer-in-the-uk-freelancer-agency-or-in-house' => [
        [
            'heading' => 'The comparison that is usually missing: bus factor',
            'body' => [
                'Cost comparisons between a freelancer, an agency and an employee tend to stop at the day rate, which is the least interesting number. The one that decides outcomes is how many people understand your system. One freelancer who built everything is a bus factor of one, and it stays one until somebody else has read the code.',
                'An agency is usually a bus factor of two or three, which is better, but only if more than one of their people has actually worked on your project rather than being named on the contract.',
            ],
        ],
        [
            'heading' => 'Match the arrangement to the shape of the work',
            'body' => [
                'Work that is bounded and well understood — a migration, an integration, a performance pass — suits a freelancer or a fixed-price supplier, because the scope can be written down and finished. Work that is continuous and shaped by what users do next suits an employee or a long retainer, because the cost of re-explaining the business every few months eventually exceeds the salary.',
                'The expensive mistake is using the second arrangement for the first kind of work: paying a retainer for a project that finished months ago, and calling it maintenance.',
            ],
        ],
    ],

    'api-development-services-uk' => [
        [
            'heading' => 'Idempotency: the detail that prevents duplicate charges',
            'body' => [
                'Any endpoint that creates something — an order, a payment, a booking — will eventually be called twice for the same intent, because a mobile connection dropped after the request arrived but before the response got back. Without protection, the client retries and the customer is charged twice.',
                'The fix is an idempotency key supplied by the caller and stored with the result. A repeat call with the same key returns the original response instead of doing the work again. It is a small amount of code and it is the difference between a retry being safe and a retry being a refund.',
            ],
        ],
        [
            'heading' => 'Webhooks need signatures, retries and a replay window',
            'body' => [
                'An unauthenticated webhook endpoint is a public URL that changes your data, so the payload should be signed and the signature verified before anything is processed. Compare the signature in constant time and reject requests whose timestamp is outside a few minutes, or a captured request can be replayed later.',
                'On the sending side, assume the receiver will be down sometimes. Retries with increasing delays, and a log you can replay from, turn a missed event into an inconvenience rather than a missing order that nobody notices until the end of the month.',
            ],
        ],
    ],

    // ---------------------------------------------------------------- Shopify ---

    'shopify-custom-theme-development-when-you-need-it' => [
        [
            'heading' => 'What sections and blocks already give you',
            'body' => [
                'Since Online Store 2.0, most of what merchants used to pay a developer for is configuration. Sections can be added to any template, blocks can be reordered per page, and metafields hold structured product data that the theme renders without anyone touching Liquid. A surprising share of "we need a custom theme" briefs are solved inside the theme editor in an afternoon.',
                'The honest test is to try it first. If a free or paid theme gets you to ninety per cent and the remaining ten per cent is cosmetic, custom development is buying you a smaller gap than it looks.',
            ],
        ],
        [
            'heading' => 'The cases where code is genuinely the answer',
            'body' => [
                'Custom work earns its cost when the requirement is structural rather than visual: variant logic a theme cannot express, a product configurator, B2B pricing that changes by customer, a bundle that has to behave as one line item, or a buying journey that needs a step no theme has.',
                'The second case is subtraction. A store carrying fifteen apps for features a developer could implement in the theme is paying monthly fees and a page-weight penalty forever. Replacing four of those with theme code often pays for itself inside a year and makes the product page measurably faster.',
            ],
        ],
    ],

    'shopify-custom-theme-development-revenue-leaks' => [
        [
            'heading' => 'App scripts are usually the largest single leak',
            'body' => [
                'Every installed app is entitled to inject JavaScript into every page, including ones where its feature does not appear. A reviews app loads on the basket, a currency converter loads on the blog, and a store with fifteen apps commonly ships more third-party JavaScript than theme code.',
                'Audit it directly: open the product page with the network panel filtered to scripts and sort by size. Anything you cannot name the purpose of is a candidate for removal, and uninstalling an app does not always remove its script tag — leftovers from apps removed years ago are common.',
            ],
        ],
        [
            'heading' => 'The variant picker decides more sales than the design does',
            'body' => [
                'Sold-out variants that stay selectable, a size that silently resets when a colour changes, or a price that updates a beat after the selection all produce the same outcome: the customer loses confidence and leaves. These are behavioural faults rather than visual ones, so they survive redesigns intact.',
                'Test the picker on a phone with a throttled connection, choosing combinations that do not exist. What the page does at that moment — disabled options, a clear message, a sensible fallback — is worth more than any amount of work on the hero image above it.',
            ],
        ],
    ],

    'shopify-order-notifications-reduce-wismo-tickets' => [
        [
            'heading' => 'The gap that creates the ticket',
            'body' => [
                'Almost every "where is my order" message arrives in the same window: after the order confirmation and before the shipping confirmation. The customer has been charged and has heard nothing since, so the silence is the problem rather than the delivery time.',
                'A single email in that gap removes most of them. It does not need a tracking number — it needs to say the order is being picked, when it is expected to ship, and what happens next. Shopify will not send that automatically, which is why the gap exists on so many stores.',
            ],
        ],
        [
            'heading' => 'Make the shipping email carry its own tracking',
            'body' => [
                'The default shipping confirmation sends customers to a carrier site that often shows nothing for the first day, so they come back and ask anyway. Putting the tracking number, the carrier name and the expected window directly in the email answers the question in the inbox.',
                'Notification templates are Liquid, so they can branch: a different message for a split shipment, for a pre-order, or for an item you know is slower. Those three branches cover most of the remaining tickets, and they are a one-off edit rather than a subscription.',
            ],
        ],
    ],

    'shopify-revenue-leaks-7-point-audit-uk-store-2026' => [
        [
            'heading' => 'Measure the funnel before auditing the pages',
            'body' => [
                'Shopify\'s own analytics gives you sessions, product page views, add-to-carts, reached-checkout and converted. The step with the largest proportional drop tells you which page to audit, and it is frequently not the one the merchant assumed. A store worrying about its homepage is often losing people between basket and checkout.',
                'Record the numbers before you change anything. Without a baseline every later claim about the work is an opinion, and the first thing anyone asks after a conversion project is whether it worked.',
            ],
        ],
        [
            'heading' => 'UK-specific leaks worth checking',
            'body' => [
                'Delivery cost appearing only at the checkout is the most reliable basket-abandonment cause in UK ecommerce, and it is fixable by stating the threshold on the product page. Returns are the second: UK shoppers expect the policy before they buy, and a store that hides it converts worse than one whose policy is merely average but visible.',
                'Check the payment mix as well. A UK store without a wallet option on mobile is asking a customer to type a card number one-handed, which is exactly where the last of the drop-off happens.',
            ],
        ],
    ],

    'best-free-shopify-apps-small-stores-2026' => [
        [
            'heading' => 'What "free" costs on a Shopify store',
            'body' => [
                'Free apps are rarely free of consequence. Most add a script to every page, some add a widget that shifts the layout as it loads, and a few inject a backlink or badge you did not agree to display. On a store with a slim margin, the real price is the speed of the product page.',
                'Install one at a time and measure. Record the product page\'s largest contentful paint before and after, and keep the app only if the feature is worth the number it added. Four or five apps installed in one sitting cannot be untangled afterwards.',
            ],
        ],
        [
            'heading' => 'Uninstalling does not always remove the app',
            'body' => [
                'Apps that edited your theme often leave their code behind when removed, because the uninstall reverses the app\'s own settings rather than the theme changes it made. Stores accumulate these: snippets, script tags and leftover divs from apps that have not been used in years.',
                'After uninstalling, search the theme for the app\'s name and check for orphaned script tags. Duplicate a theme before doing it, so a mistake is a revert rather than an incident on a live store.',
            ],
        ],
    ],

    'free-shopify-order-tracking-app-branded' => [
        [
            'heading' => 'Why a branded tracking page pays for itself',
            'body' => [
                'The tracking link is the most reliably opened message in the whole order lifecycle — customers check it several times per order. Sending that attention to a carrier\'s website spends it on somebody else\'s brand, and the customer sees advertising that is not yours at the moment they are most engaged.',
                'A tracking page on your own domain keeps that traffic, and it is the one place a returning-customer offer is genuinely welcome rather than intrusive, because the visitor came deliberately.',
            ],
        ],
        [
            'heading' => 'What to check before choosing one',
            'body' => [
                'Carrier coverage is the first question, and for UK stores that means Royal Mail, Evri, DPD, Yodel and DHL rather than a long list weighted towards other markets. An app that cannot read your main carrier\'s updates gives you a branded page with nothing on it.',
                'The second is what happens to the data. A tracking app sees every customer\'s name, address and order history, so check where that is stored and what the retention policy is. For a UK store this is a GDPR question, not merely a preference.',
            ],
        ],
    ],

    'shopify-bulk-order-fulfillment-free-app' => [
        [
            'heading' => 'Where bulk fulfilment actually breaks',
            'body' => [
                'Fulfilling hundreds of orders at once runs into Shopify\'s API rate limits, and a tool that does not handle them politely will fail part-way through — leaving some orders fulfilled, some not, and no clear record of which. The recovery is worse than doing it by hand.',
                'Ask how the tool handles a partial failure. Retrying with increasing delays and a report of exactly what succeeded is the difference between a bulk action you can trust on a busy day and one you only use when there is time to check it afterwards.',
            ],
        ],
        [
            'heading' => 'Tracking numbers are where the errors hide',
            'body' => [
                'Bulk fulfilment usually means importing a spreadsheet from the courier, and spreadsheets are where tracking numbers get mangled: leading zeros stripped, long numbers turned into scientific notation, order references matched to the wrong row. The customer receives a confirmation with somebody else\'s tracking number.',
                'Import the file as text, match on order name rather than row order, and have the tool show you a preview before it sends anything. One wrong column on a hundred orders is a hundred support conversations.',
            ],
        ],
    ],

    'wordpress-vs-shopify-uk-small-business' => [
        [
            'heading' => 'The numbers that actually differ',
            'body' => [
                'Shopify charges a monthly fee and, if you use a payment provider other than Shopify Payments, an additional transaction percentage on top of the card fee. WooCommerce has no platform fee but moves those costs to hosting, plugin licences and the person who keeps it updated. For a low-volume store the second is usually cheaper; the crossover comes sooner than most merchants expect.',
                'Work out your own number before choosing: monthly orders multiplied by average value, against the fee structures. A decision made on the sticker price alone is frequently reversed within two years.',
            ],
        ],
        [
            'heading' => 'Checkout control and content depth pull in opposite directions',
            'body' => [
                'Shopify\'s checkout is the strongest part of the platform and the part you can least modify. That is a good trade for most retailers and a bad one for anyone whose sale needs a step the checkout does not have, such as a quote, an approval, or a configuration.',
                'WordPress wins where content does the selling — a business whose traffic comes from long articles, guides and local pages usually finds WordPress less of a fight. Deciding which of the two describes your business is more useful than comparing feature lists.',
            ],
        ],
    ],

    // --------------------------------------------------- Pricing and budgets ---

    'custom-software-development-pricing-uk-what-businesses-should-budget-for-in-2026' => [
        [
            'heading' => 'The pricing model matters more than the rate',
            'body' => [
                'Fixed price moves the risk of a bad estimate onto the supplier, and they price that risk into the number — usually by adding a margin and by defending the scope line closely once work starts. Time and materials moves the risk back to you and removes the padding, but it needs trust and a real reporting rhythm to stay honest.',
                'Milestone pricing sits between them and suits most business software: a fixed price per phase, re-quoted as each phase clarifies the next. The mistake is agreeing a fixed price for the whole system before anyone knows what the second half contains.',
            ],
        ],
        [
            'heading' => 'Why estimates are wrong in a predictable direction',
            'body' => [
                'Software estimates are almost never too high. They miss because the estimate covers the feature and not the work around it: data migration from whatever exists now, the states nobody mentioned, permissions, the reporting somebody needs at month end, and the two weeks of adjustment after real users arrive.',
                'A practical hedge is to hold a contingency of around a fifth of the build as a separate line rather than inflating each item. It makes the estimate honest and it means the first surprise is a decision rather than a renegotiation.',
            ],
        ],
    ],

    'website-cost-uk-2026-pricing-guide' => [
        [
            'heading' => 'The costs that appear after the quote',
            'body' => [
                'The build price is usually the accurate part. What catches businesses out is what sits around it: content and copywriting if it is not supplied, photography, a licence for a premium theme or plugin, email deliverability setup, and the hours spent moving an existing site\'s URLs across without losing search visibility.',
                'Ask any supplier which of those are included. The answer is more informative than the total, because two quotes that differ by thousands often differ only in how much of this list one of them has quietly left out.',
            ],
        ],
        [
            'heading' => 'What actually makes a project cost more than quoted',
            'body' => [
                'Scope change is the usual explanation and it is rarely the real one. The more common cause is slow decisions: a build waiting three weeks for content, or a design revisited after development started because the wrong person saw it first. Both are billed as time and neither shows up in the original brief.',
                'The cheapest thing a client can do is name one decision-maker and supply content before development begins. That single arrangement removes most overruns, and it costs nothing.',
            ],
        ],
    ],

    'custom-crm-development-cost-uk-what-affects-budget-and-timeline' => [
        [
            'heading' => 'Roles multiply the work more than features do',
            'body' => [
                'The cost driver most often underestimated in a CRM is the number of distinct user roles. Each role needs its own permissions, its own view of the data, and its own testing — and the permission rules have to be enforced where the data is fetched rather than hidden in the interface, or the system merely looks secure.',
                'A CRM for one team is a moderate build. The same CRM for sales, operations, finance and an external client login is several times the work, because four roles means four sets of rules that have to be right in every screen and every export.',
            ],
        ],
        [
            'heading' => 'Integrations cost what the other system decides',
            'body' => [
                'Connecting a CRM to accounting, email or a phone system is priced by the other end. A modern API with documentation and a sandbox is a few days; a system with no API, or one that exports a nightly CSV, is a different project involving reconciliation logic and error handling for the times the file does not arrive.',
                'Establish what each integration actually offers before the quote. "Integrates with your accounting software" is a sentence that can mean two days or two months.',
            ],
        ],
    ],

    'mvp-development-cost-uk-how-founders-budget-for-version-one' => [
        [
            'heading' => 'Budget against runway, not against the feature list',
            'body' => [
                'The useful question is not what version one costs but how many funded attempts you get. A founder with twelve months of runway who spends nine on the first build has one attempt; the same budget split into three releases has three, and the second and third are informed by real users.',
                'That arithmetic should decide the scope. Whatever can be learned from a smaller release is worth more than whatever is gained by launching a larger one later, because the first version is mostly a way of finding out which assumptions were wrong.',
            ],
        ],
        [
            'heading' => 'The features founders build too early',
            'body' => [
                'A recurring pattern: an admin panel before there are enough customers to administer, team accounts and invitations before anyone has asked for a colleague, a settings page full of options nobody has requested, and an analytics dashboard that reports on data that does not exist yet.',
                'Each of these is real work and none of it tests whether the product is wanted. Most can be replaced for months by a database client and a founder willing to do the task manually — which has the side effect of teaching you what the feature should actually do.',
            ],
        ],
    ],

    // ------------------------------------------ CRM, SaaS and custom software ---

    'crm-automation-uk-how-custom-portals-reduce-admin-and-speed-up-sales' => [
        [
            'heading' => 'Automate the handover, not the judgement',
            'body' => [
                'The admin that automates well is the movement of information: copying an enquiry into a record, notifying the person whose turn it is, chasing a document that has not arrived, producing the same weekly export somebody assembles by hand. These are rule-based, verifiable, and boring — exactly the right qualities.',
                'What automates badly is anything requiring judgement, such as deciding whether a lead is worth pursuing or what to say to an unhappy customer. Automating those produces confident, wrong outcomes that take longer to undo than the task took to do.',
            ],
        ],
        [
            'heading' => 'A client portal removes the chasing, not the work',
            'body' => [
                'Most of the admin around a client relationship is chasing: a document, an approval, an answer to a question asked three weeks ago. A portal that shows the client what is outstanding, with a date, removes the majority of that traffic because the client can see their own position without asking.',
                'It also produces a record. When a project is disputed, the question is always who was waiting for whom, and a portal answers it with timestamps rather than a search through email.',
            ],
        ],
    ],

    'subscription-software-development-uk-how-saas-products-are-planned-priced-and-built' => [
        [
            'heading' => 'Billing is where subscription products actually get hard',
            'body' => [
                'The happy path — a customer signs up and pays monthly — is a small part of the code. The work is in the edges: an upgrade mid-cycle and what gets refunded, a downgrade and when it takes effect, a failed card and how many times it is retried before access is suspended, a cancellation and whether the remaining days are honoured.',
                'Decide each of those as a business rule before anything is built, because they are difficult to change once customers have been charged under the previous behaviour.',
            ],
        ],
        [
            'heading' => 'Failed payments quietly decide your revenue',
            'body' => [
                'Cards expire, get replaced after fraud, and are declined for limits that have nothing to do with willingness to pay. Without deliberate handling, those customers simply disappear, and the loss looks like churn rather than a solvable payments problem.',
                'A retry schedule over a couple of weeks, an email that tells the customer what happened rather than that "something went wrong", and a way to update the card without logging a support ticket will recover a meaningful share of them. It is the least glamorous part of a SaaS build and among the best paid.',
            ],
        ],
    ],

    'saas-mvp-development-uk-what-to-build-first-and-what-to-delay' => [
        [
            'heading' => 'Build the thing that is hard to fake',
            'body' => [
                'Everything in a product is either the core mechanism or scaffolding around it. Build the mechanism — the calculation, the matching, the transformation, whatever your product actually does — and fake the scaffolding for as long as possible. Onboarding can be an email you send by hand; reporting can be a query you run on request.',
                'This ordering is uncomfortable because the scaffolding is what a product looks like. It is also what any competent developer can add later in a fortnight, where the mechanism is the part that might not work.',
            ],
        ],
        [
            'heading' => 'Decide what a successful first release proves',
            'body' => [
                'Write down, before building, what result would make you continue and what would make you stop. Ten paying customers, a retention figure at week four, a support load below some level — the specific measure matters less than agreeing it in advance.',
                'Without it, every outcome gets interpreted as encouraging, and the second version gets built on the assumption that the first one worked. That is the most expensive way to discover otherwise.',
            ],
        ],
    ],

    'custom-software-development-uk-discovery-scope-and-budget-without-guesswork' => [
        [
            'heading' => 'What discovery should produce on paper',
            'body' => [
                'Discovery that ends in a conversation and a number has not happened. It should produce artefacts you could hand to a different supplier: the workflows as they run today including the exceptions, a list of the data and where it currently lives, the user roles and what each may see, and the integrations with what each system can actually offer.',
                'Those documents are the real output, and they are worth paying for separately. A business that owns them can get comparable quotes; a business that does not is buying from whoever produced the friendliest estimate.',
            ],
        ],
        [
            'heading' => 'Map the exceptions, because they are the system',
            'body' => [
                'Every business describes its process as a straight line and then runs it with a dozen exceptions: the customer who pays differently, the order that skips a step, the approval that happens by phone. Software built for the straight line gets rejected by the people who have to use it, because it cannot express their Tuesday.',
                'Asking "when does this not happen that way?" at each step is the single most useful question in discovery, and it is where most of the scope that later appears as a change request is actually hiding.',
            ],
        ],
    ],

    'custom-web-application-development-uk-what-businesses-should-build-first' => [
        [
            'heading' => 'Start with the spreadsheet that hurts most',
            'body' => [
                'Nearly every business commissioning a first web application already runs the process in a spreadsheet, and that spreadsheet is a complete specification written by the people who use it. It shows the fields that matter, the statuses that exist, and — in its awkward columns and colour-coding — precisely where it has stopped coping.',
                'Replacing one spreadsheet properly beats building a system that addresses four processes shallowly. It also gives you users on day one, because they were already doing the work.',
            ],
        ],
        [
            'heading' => 'Get the data model right before the screens',
            'body' => [
                'Screens are cheap to change and the data model underneath them is not. Deciding what an order, a customer or a job actually is — and what may exist without the other — is the decision that either makes the second year of features straightforward or turns each one into a migration.',
                'The warning sign is a field being used for two purposes, or a status column that has quietly become a workflow. Both are easy to fix in week two and expensive in year two.',
            ],
        ],
    ],

    'how-to-hire-software-development-company-founders-guide' => [
        [
            'heading' => 'Read the proposal for what is missing',
            'body' => [
                'A proposal with no assumptions section, no exclusions, and no statement of what happens when an estimate is wrong is not a plan; it is a sales document. The suppliers worth shortlisting are usually the ones whose proposal contains the uncomfortable parts, because writing them down means they have thought about the project rather than the sale.',
                'Be equally wary of a quote that arrives within a day of a complex brief. Speed there means the supplier has priced a system they have imagined rather than the one you described.',
            ],
        ],
        [
            'heading' => 'Ask references about the difficult month',
            'body' => [
                'References are usually asked whether they were happy, which produces no information. The useful question is what went wrong and how the supplier behaved when it did — every project of any size has such a month, and the answer tells you what yours will be like.',
                'Follow it with a practical one: could you change supplier if you needed to? A reference who does not know where their code lives has told you something important about the company that built it.',
            ],
        ],
    ],

    // -------------------------------------------------------- SEO and search ---

    'programmatic-seo-uk-when-service-businesses-should-and-should-not-scale-location-pages' => [
        [
            'heading' => 'The threshold: what do you know about this town',
            'body' => [
                'A location page earns its place when you can say something about that place that is not true of the next one — work you have done there, a council or regulation that applies, travel time, a local competitor landscape, prices that genuinely differ. If the only variable is the town name in the heading, you have one page repeated.',
                'The practical test is to open three of them side by side and read the middle. When a reviewer or a crawler does that and finds the same five paragraphs, the whole set is treated as one thin page rather than as a hundred opportunities.',
            ],
        ],
        [
            'heading' => 'Scale the pages you can serve, not the ones you can generate',
            'body' => [
                'Generating four hundred town pages is an afternoon\'s work and it is the wrong end of the problem. The constraint is how many places you can actually deliver in, and a set matching that reality — a dozen pages with genuine detail — consistently outperforms hundreds of variations.',
                'It also fails more gracefully. A small set that underperforms can be improved; a large one that gets classed as scaled content takes the rest of the site\'s credibility with it, which is a much harder position to recover from.',
            ],
        ],
    ],

    'answer-engine-optimization-for-uk-service-pages-how-to-rank-in-chatgpt-google-ai-and-ai-mode' => [
        [
            'heading' => 'Being retrieved is a different problem to ranking',
            'body' => [
                'An AI answer is assembled from passages rather than pages, so the unit that competes is a few hundred words that fully answer one question. A page that ranks well by covering a topic broadly can be passed over for a smaller page that answers the specific thing cleanly, because the latter is easier to lift and attribute.',
                'That changes how a service page should be built: each distinct question a buyer asks gets its own heading and a self-contained answer directly beneath it, rather than being distributed across an argument that needs the whole page for context.',
            ],
        ],
        [
            'heading' => 'Write the sentence you would want quoted',
            'body' => [
                'Assistants reproduce claims that are specific, attributable and verifiable. "We offer competitive pricing" cannot be quoted; "installation typically takes two days and starts at £1,400" can, and it carries your business name with it when it is.',
                'This is also why vagueness now costs more than it used to. A page hedging every number gives an assistant nothing to repeat, so a competitor who states theirs plainly becomes the source — even where the underlying service is the same.',
            ],
        ],
    ],

    'google-search-console-insights-uk-how-to-find-easy-seo-wins-faster' => [
        [
            'heading' => 'Positions five to twenty are where the work pays',
            'body' => [
                'Filter Search Console to queries with impressions above a sensible floor and average position between about five and twenty. Those are queries Google already considers you relevant for and which sit just below where clicks begin, so movement of two or three places changes the traffic materially.',
                'Aiming at position one for something you rank fortieth for is the same effort for a far smaller chance. This list is the difference between SEO that shows a result in a month and SEO that shows one eventually.',
            ],
        ],
        [
            'heading' => 'Click-through outliers point at the title, not the ranking',
            'body' => [
                'Sort your ranking pages by click-through rate and look for the ones well below what their position should earn. A page averaging fourth place with a very low rate is not a ranking problem; the title or description is failing to match what the searcher wanted.',
                'That is an edit of two lines with no technical work and no link building, and it is the fastest improvement available in most accounts. Record the before figure, because the change is easy to overstate afterwards.',
            ],
        ],
    ],

    'seo-company-stoke-on-trent-for-small-businesses-what-actually-drives-enquiries' => [
        [
            'heading' => 'The local pack is a separate competition',
            'body' => [
                'For searches with local intent, the map results sit above the ordinary listings and they are won differently. Proximity to the searcher, the completeness of the business profile, the categories chosen, and the volume and recency of reviews decide them — none of which is affected by the work done on the website.',
                'A business can therefore rank respectably in the ordinary results and be invisible where most of the clicks are. Check both before commissioning anything, because the fix for one does very little for the other.',
            ],
        ],
        [
            'heading' => 'Consistent details beat most link building locally',
            'body' => [
                'Local ranking leans on confirmation that a business exists as described. The same company name, address and phone number, in exactly the same form, across the business profile, the website, directories and social profiles is the cheapest available signal.',
                'It is also where small businesses usually have an obvious problem: an old address after a move, two phone numbers, a trading name that differs from the registered one. Fixing those is an afternoon and it removes the ambiguity everything else is built on.',
            ],
        ],
    ],

    // ------------------------------------------------ Websites and delivery ---

    'small-business-website-development-uk-features-that-actually-generate-enquiries' => [
        [
            'heading' => 'The form is usually the feature costing you most',
            'body' => [
                'Enquiry forms are built once and rarely examined afterwards, which is why so many ask for a company name, a job title, a budget range and how the visitor heard about you before they have decided to make contact. Every field is a reason to stop, and the fields that qualify a lead are the ones most likely to prevent it existing.',
                'Ask for the minimum that lets you reply — usually a name, one contact method, and a sentence about the job. Qualification is a conversation, and it works better once somebody is already talking to you.',
            ],
        ],
        [
            'heading' => 'Answer the price question somehow',
            'body' => [
                'The most common reason a service visitor leaves is that the site will not indicate what anything costs. Most businesses cannot publish a single figure, which is used as a reason to publish nothing — and the visitor goes to a competitor who gave them a range.',
                'A starting price, a typical project band, or a worked example with real numbers all serve. It filters out enquiries you could not have served anyway, which is time returned rather than business lost.',
            ],
        ],
    ],

    'ecommerce-website-development-uk-what-makes-a-store-ready-for-growth' => [
        [
            'heading' => 'Growth breaks operations before it breaks the website',
            'body' => [
                'A store that handles ten orders a day and one that handles two hundred are rarely different websites. What differs is everything behind it: whether stock counts are accurate, whether picking can be done from the order screen, whether returns have a process rather than an inbox, and whether someone can tell a customer where their parcel is without asking a colleague.',
                'The platform is usually not the constraint. Before spending on the storefront, run the numbers on what happens at five times the volume on a Monday, and fix whatever answers "we would cope somehow".',
            ],
        ],
        [
            'heading' => 'Stock accuracy decides how growth feels',
            'body' => [
                'Overselling is the failure that costs most, because it arrives after payment — the customer has bought something that does not exist and every option from there damages the relationship. It happens where stock lives in two systems that reconcile on a schedule rather than immediately.',
                'Decide which system is authoritative and make everything else read from it. That single decision prevents more growth pain than any amount of work on the checkout.',
            ],
        ],
    ],

    'business-website-redesign-uk-9-signs-your-current-site-is-costing-you-leads' => [
        [
            'heading' => 'Redesign without losing the rankings you have',
            'body' => [
                'The most common way a redesign loses traffic is a URL structure changed without a mapping. Before anything is built, export every URL that has earned an impression in the last year and decide where each one goes. Anything that moves needs a permanent redirect to the closest equivalent — not to the homepage, which search engines treat as a page having been deleted.',
                'Do it as a specified deliverable rather than a launch-week task. Recovering rankings lost this way takes months, where preventing it takes a spreadsheet.',
            ],
        ],
        [
            'heading' => 'Record what works before you replace it',
            'body' => [
                'A site being replaced is usually described as failing, but parts of it are working — a page that brings most of the organic traffic, a form arrangement that converts, a piece of content people link to. Those are easy to lose because the redesign discussion focuses on what is wrong.',
                'Take the numbers first: the top landing pages, the current conversion rate, the search queries already earning clicks. They are the baseline for judging the new site, and without them a redesign can only be assessed on whether people prefer how it looks.',
            ],
        ],
    ],

    'ai-website-development-uk-how-service-businesses-build-faster-and-convert-better' => [
        [
            'heading' => 'Where AI genuinely shortens a build',
            'body' => [
                'The savings are real in the repetitive middle of a project: scaffolding components, writing the first draft of tests, converting designs to markup, producing schema, and drafting the eighty per cent of copy that is structure rather than substance. Each is a task with a known shape and a reviewable output.',
                'What it does not shorten is deciding what to build. Discovery, data modelling and the conversation about what the business actually needs take the same time they always did, and they are where projects succeed or fail.',
            ],
        ],
        [
            'heading' => 'The content it must not write unreviewed',
            'body' => [
                'Anything a customer could rely on has to be checked by someone who knows the answer: prices, timescales, guarantees, qualifications, coverage areas, and claims about clients. A generated page that invents a certification or a client relationship is a misrepresentation regardless of how it got there.',
                'This is also an advertising and search problem. Assistants increasingly repeat what a site states, so a fabricated claim propagates, and the correction never travels as far as the original did.',
            ],
        ],
    ],

    'website-development-company-stoke-on-trent-what-businesses-should-expect' => [
        [
            'heading' => 'What the client has to supply, and when',
            'body' => [
                'Most projects that run late are waiting on the client rather than the developer, and always for the same things: content, photographs, logins to the existing hosting or domain, sign-off from someone who has been on holiday, and a decision about something nobody wanted to own.',
                'Agree those as dated items in the plan, the same as development tasks. A project where the client knows content is due in week two behaves completely differently to one where it is requested in week five.',
            ],
        ],
        [
            'heading' => 'A realistic shape for a small business build',
            'body' => [
                'For a straightforward business site, expect roughly a week of discovery and structure, two to three weeks of design and build, a week of content population and review, and a short period after launch for the adjustments that only appear once it is live. Bigger scopes extend the middle rather than adding new stages.',
                'Compress that and something gets skipped, usually testing or content. Both failures surface after launch, when they cost more attention than they would have taken to do properly.',
            ],
        ],
    ],

    'hire-a-full-stack-developer-uk-what-to-check-before-you-commit' => [
        [
            'heading' => 'Full stack covers a range, so find the edge',
            'body' => [
                'The term stretches from someone comfortable across a framework and its templating to someone who also handles servers, deployment, databases at size, and security. Both are legitimately full stack and they are not interchangeable for a system that has to stay up.',
                'The way to find the edge is to ask what they would hand to someone else. A candidate who says they would bring in help for a particular thing is describing their limits accurately, which is considerably more useful than a claim to cover everything.',
            ],
        ],
        [
            'heading' => 'Check the operational half',
            'body' => [
                'The gap that causes trouble is not writing features; it is everything around them. Ask how they would set up backups and when they would test a restore, what they would put in place to know the site is down before a customer says so, and how a database change gets applied to a live system safely.',
                'Someone who has run production software answers these from experience and usually with a story attached. Someone who has only built things answers in principles, and the difference shows up during the first incident.',
            ],
        ],
    ],

    'ai-business-automation-uk-2026' => [
        [
            'heading' => 'Automate where being wrong is cheap',
            'body' => [
                'The sensible first candidates are tasks where an error is visible immediately and costs little to correct: drafting a reply someone sends, categorising an enquiry a human then reads, summarising a document for a person who has it open, extracting fields from an invoice before approval.',
                'What should wait is anything acting without review — sending the email, issuing the refund, changing the price. The failure mode of these systems is confident and plausible, which means a wrong action does not look wrong until its consequences arrive.',
            ],
        ],
        [
            'heading' => 'Keep a record of what the automation did',
            'body' => [
                'Every automated decision should leave a row: what came in, what was decided, which version of the rules decided it, and whether a person intervened. Without that you cannot answer a customer asking why something happened, and you cannot tell whether a change made things better.',
                'It is also the difference between improving an automation and replacing it. Six months of logged decisions shows exactly where the mistakes cluster; six months without them leaves an argument about whether it is working at all.',
            ],
        ],
    ],

];
