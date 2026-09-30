<?php

/**
 * Posts that were rewritten onto a different subject.
 *
 * The blog had six posts on answer-engine optimisation, five on hiring a Laravel
 * developer, three on what a CRM costs and three on SaaS MVPs. Each set asked
 * roughly the same question with the words rearranged, which is what a generated
 * blog looks like from the outside — and it is the impression a reviewer forms
 * before reading a word of the content.
 *
 * The strongest post in each set kept its subject. The rest were moved onto
 * subjects the business genuinely has something to say about and which nothing else
 * on the site covers: data retention, deliverability, accessibility, payments,
 * backups, consent, CMS migration, account security, selling abroad, hosting and
 * form spam.
 *
 * Keyed by the OLD slug. `blog:retopic` applies the rewrite;
 * BlogPageController::show() reads the same file to 301 the old URL to the new one.
 */

return [

    'answer-engine-optimization-uk-how-service-businesses-structure-content-for-ai-search' => [
        'slug' => 'gdpr-data-retention-uk-web-applications',
        'image' => 'assets/images/blog/it-data-retention.svg',
        'image_alt' => 'Retention schedule applied to records in a UK web application',
        'title' => 'GDPR Data Retention for UK Web Applications: What to Delete, and When',
        'excerpt' => 'UK GDPR requires personal data to be kept no longer than necessary. Here is how to turn that into a retention schedule your web application can actually enforce.',
        'meta_title' => 'GDPR Data Retention for UK Web Applications | ARS Developer',
        'meta_description' => 'How UK businesses set retention periods for enquiry forms, customer accounts, logs and backups — and build deletion into the application rather than promising it.',
        'answer' => 'UK GDPR does not set retention periods for you. It requires that you set them, justify them, write them down, and then actually apply them — which is where most web applications fail, because deletion is a feature nobody asks for and every system quietly hoards data by default.',
        'sections' => [
            [
                'heading' => 'Start with the data you forgot you were keeping',
                'body' => [
                    'The customer records are the part everyone thinks about. What accumulates unnoticed is everything around them: every enquiry form submission sitting in a database table since launch, abandoned registrations, email logs containing names and addresses, exported CSVs in a downloads folder, and application logs that captured request bodies during a debugging session two years ago.',
                    'Before writing a policy, inventory what personal data your application actually holds and where. A retention schedule that covers the customers table while five years of form submissions sit in another is a document rather than a control.',
                ],
            ],
            [
                'heading' => 'Choose periods you can defend',
                'body' => [
                    'The test is necessity: how long do you genuinely need this to do the thing the person gave it to you for, or to meet another legal duty. An enquiry that never converted does not need keeping for six years. Records supporting a transaction generally do, because HMRC expects business records to be available for six years from the end of the accounting period they relate to.',
                    'Write the reasoning next to each period. "Enquiries: 24 months, because that is the longest realistic sales cycle for our services" is defensible. A period with no stated reason is the first thing an ICO enquiry asks about.',
                ],
            ],
            [
                'heading' => 'Build deletion in, because manual deletion does not happen',
                'body' => [
                    'A retention policy applied by someone remembering to run a query is a policy that lapses the first busy month. It belongs in a scheduled job that runs nightly, deletes what has passed its period, and writes a line recording how many rows it removed.',
                    'Two details matter. Soft deletes are not deletion — a row flagged as deleted is still personal data, so the job has to remove it properly once any grace period has passed. And test the job against a copy first: a retention script with an off-by-one in its date arithmetic deletes live records, and nobody notices until a customer asks about their order.',
                ],
            ],
            [
                'heading' => 'Backups are the part that catches people out',
                'body' => [
                    'Deleting a record from the live database does not remove it from last night\'s backup, or from the six months of backups behind it. Strictly, that data still exists and is still yours to account for.',
                    'The accepted position is that backups are put beyond use rather than individually edited: you document that deleted data persists in backups, that backups are encrypted and access-controlled, that they expire on a defined cycle, and that a restore would have the retention job re-applied. What matters is that backup retention is deliberate and written down rather than infinite by accident.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Does UK GDPR state how long I can keep data?', 'a' => 'No. It requires you to decide a period that is no longer than necessary for your purpose, record your reasoning, and apply it consistently.'],
            ['q' => 'Do I have to delete data from backups?', 'a' => 'Not individually. The usual approach is to document that backups expire on a defined cycle, are encrypted and access-controlled, and that retention rules are re-applied after any restore.'],
            ['q' => 'What about anonymised data?', 'a' => 'Genuinely anonymised data falls outside UK GDPR, but the bar is high — if the individual can be re-identified by combining it with anything else you hold, it is still personal data.'],
            ['q' => 'How long should contact form submissions be kept?', 'a' => 'There is no fixed answer, but a period tied to your sales cycle, commonly one to two years, is straightforward to justify. The important thing is choosing it deliberately.'],
        ],
        'cta' => 'If your application has no retention job and nobody is sure what is in the older tables, an inventory is the sensible first step. <a href="/contact">Get in touch</a> and we can scope one.',
    ],

    'llm-seo-uk-how-to-structure-service-pages-for-chatgpt-google-ai-and-answer-engines' => [
        'slug' => 'email-deliverability-uk-small-business-spf-dkim-dmarc',
        'image' => 'assets/images/blog/it-email-deliverability.svg',
        'image_alt' => 'Authenticated business email passing delivery checks',
        'title' => 'Why Your Business Email Goes to Spam: SPF, DKIM and DMARC Explained',
        'excerpt' => 'Enquiry notifications landing in junk, invoices never arriving, order confirmations going missing. Almost always the same three DNS records — here is what they do and how to set them.',
        'meta_title' => 'Email Deliverability for UK Businesses: SPF, DKIM, DMARC | ARS Developer',
        'meta_description' => 'Why business email lands in spam, what SPF, DKIM and DMARC actually do, and how to set them up without cutting off your own mail.',
        'answer' => 'If your website\'s emails are going to junk, the cause is almost never the wording. It is that your domain has not told the receiving mail server which servers are allowed to send on its behalf, so a message claiming to be from you cannot be distinguished from one that is not.',
        'sections' => [
            [
                'heading' => 'What the three records actually do',
                'body' => [
                    'SPF is a list, published in your DNS, of the servers permitted to send email using your domain. The receiving server checks whether the message came from one of them. It is the easiest to set up and the easiest to break, because every service that sends on your behalf — your website, your mailing list, your invoicing software — has to be in the list.',
                    'DKIM adds a cryptographic signature to each message, so the receiver can confirm it was genuinely sent by an authorised system and has not been altered. DMARC then tells the receiver what to do when SPF and DKIM disagree with each other, and asks it to report back.',
                ],
            ],
            [
                'heading' => 'The mistake that breaks half of all setups',
                'body' => [
                    'A domain may publish only one SPF record. Businesses acquire a second when a new service asks them to add one — the website host, then an email marketing platform — and the result is not two lists but an invalid configuration that some receivers treat as a failure.',
                    'The fix is to merge everything into a single record with one include per service. While you are there, check the ending: `~all` asks receivers to treat unlisted senders with suspicion, `-all` asks them to reject outright. Move to the stricter setting only once you are confident the list is complete.',
                ],
            ],
            [
                'heading' => 'Introduce DMARC gradually',
                'body' => [
                    'Publishing a DMARC record set to reject before you know which systems send as your domain is how a business discovers, on a Monday morning, that its accounting software\'s invoices have stopped arriving. Almost every organisation has a forgotten sender somewhere.',
                    'Start at `p=none` with a reporting address. That changes nothing about delivery but produces reports naming every system sending as you. Run it for a few weeks, fix or authorise what appears, then move to quarantine and only afterwards to reject.',
                ],
            ],
            [
                'heading' => 'Send transactional mail through a service, not the web server',
                'body' => [
                    'Contact form notifications and order confirmations sent by PHP\'s mail function from a shared host go out from an IP address shared with hundreds of other sites, with no authentication and no delivery reporting. When one of those neighbours sends spam, your mail suffers with it.',
                    'Routing transactional email through a dedicated provider gives you authentication, a reputation you control, and a log showing whether each message was delivered, bounced or opened. The last of those is worth the change on its own — the difference between "the customer says they never got it" and knowing.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Can I have two SPF records?', 'a' => 'No. A domain may publish only one, and a second makes the configuration invalid. Merge every sending service into a single record.'],
            ['q' => 'How long do DNS changes take?', 'a' => 'Usually minutes to a few hours, governed by the TTL on the record. Lower the TTL before making changes if you want to iterate quickly.'],
            ['q' => 'Is DMARC required?', 'a' => 'Not legally, but the large mailbox providers increasingly expect it from bulk senders, and without it SPF and DKIM failures are handled inconsistently.'],
            ['q' => 'Will this fix emails going to a customer\'s junk folder?', 'a' => 'It removes the most common technical cause. Content, sending volume and prior recipient behaviour also matter, but authentication is the part you control outright.'],
        ],
        'cta' => 'If enquiries from your website are not arriving reliably, the DNS is the first place to look. <a href="/contact">Send us the domain</a> and we can tell you what is currently published.',
    ],

    'ai-mode-seo-uk-how-service-brands-create-content-that-earns-clicks' => [
        'slug' => 'website-accessibility-uk-businesses-wcag-2-2',
        'image' => 'assets/images/blog/it-accessibility.svg',
        'image_alt' => 'Accessibility review of a UK business website',
        'title' => 'Website Accessibility for UK Businesses: What WCAG 2.2 Actually Requires',
        'excerpt' => 'Accessibility is a legal duty for UK businesses under the Equality Act, not a nice-to-have. Here is what that means in practice, and the handful of fixes that cover most of it.',
        'meta_title' => 'Website Accessibility for UK Businesses: WCAG 2.2 | ARS Developer',
        'meta_description' => 'What the Equality Act expects of UK business websites, what WCAG 2.2 adds, and the practical fixes that resolve most real accessibility failures.',
        'answer' => 'UK businesses providing services to the public have a duty under the Equality Act 2010 to make reasonable adjustments, and that includes their website. WCAG 2.2 is the standard used to judge whether a site meets it — and the majority of real failures come down to about six recurring problems.',
        'sections' => [
            [
                'heading' => 'The six failures that account for most of it',
                'body' => [
                    'Automated testing consistently finds the same things: text that does not have enough contrast against its background, images without alternative text, form inputs with no associated label, links whose text is "click here" or "read more" out of context, pages with no heading structure, and content that cannot be reached or operated with a keyboard alone.',
                    'None of these is difficult. They persist because nobody tested — and because a designer choosing light grey on white, or a developer using a div where a button belongs, produces something that looks correct to them.',
                ],
            ],
            [
                'heading' => 'Test with the keyboard first',
                'body' => [
                    'Put the mouse aside and move through your own site using only Tab, Shift+Tab and Enter. You will find out in two minutes whether a keyboard user can operate it. Watch for focus that disappears entirely, a menu that cannot be opened, a modal that traps you inside it, and a cookie banner that takes fifty presses to get past.',
                    'This single test catches more genuine problems than any automated scan, because scanners check what can be checked mechanically and keyboard operability is mostly a question of behaviour.',
                ],
            ],
            [
                'heading' => 'What WCAG 2.2 added that catches sites out',
                'body' => [
                    'The 2.2 criteria are mostly about not obstructing people. Focus must not be hidden behind a sticky header — a common failure on modern sites where the focused element scrolls under a fixed bar. Dragging must have a single-pointer alternative, so a slider or a drag-to-reorder control needs buttons as well.',
                    'There is also a requirement not to make people re-enter information they have already given in the same process, and one about not relying on the user remembering something to complete a task. Both tend to affect multi-step checkouts and booking flows.',
                ],
            ],
            [
                'heading' => 'Accessibility overlays are not a fix',
                'body' => [
                    'The widgets that promise instant compliance from one line of JavaScript are widely rejected by disabled users and by accessibility professionals, and they have featured in legal claims rather than preventing them. They sit on top of the problem instead of removing it, and they frequently interfere with the assistive technology a visitor is already using.',
                    'The work that genuinely helps is unglamorous: fix the contrast, label the inputs, write the alt text, use real buttons, get the heading order right. It is a few days on most sites and it does not recur.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Is accessibility legally required for a private UK business?', 'a' => 'The Equality Act 2010 requires service providers to make reasonable adjustments, and this is generally understood to include websites. The stricter public sector regulations apply to public bodies specifically.'],
            ['q' => 'Which WCAG level should we aim for?', 'a' => 'AA is the level referenced by UK and international regulation and is the practical target. AAA is not expected across a whole site.'],
            ['q' => 'Do automated checkers find everything?', 'a' => 'No. They reliably catch contrast, missing alt text and unlabelled inputs, but they cannot judge whether alt text is useful or whether a journey makes sense with a screen reader.'],
            ['q' => 'How long does it take to fix a typical small business site?', 'a' => 'Most sites are a few days of work once the issues are identified, because the same handful of problems repeat across every template.'],
        ],
        'cta' => 'An accessibility audit tells you where you actually stand rather than where a widget claims you do. <a href="/contact">Ask us</a> for one on your current site.',
    ],

    'ai-seo-services-uk-how-businesses-turn-ai-search-visibility-into-qualified-leads' => [
        'slug' => 'uk-payment-providers-stripe-gocardless-paypal-direct-debit',
        'image' => 'assets/images/blog/it-uk-payments.svg',
        'image_alt' => 'Card payments and Direct Debit collection for a UK business',
        'title' => 'Taking Payments as a UK Business: Cards, Direct Debit and the Real Costs',
        'excerpt' => 'Card fees, Direct Debit, payment links and invoices all suit different businesses. A practical comparison of what each actually costs and where each one fits.',
        'meta_title' => 'UK Payment Providers Compared: Cards vs Direct Debit | ARS Developer',
        'meta_description' => 'How UK businesses should choose between card payments, Direct Debit and invoicing — with the fee structures, the failure modes and what each suits.',
        'answer' => 'The right payment method depends on what you are charging for, not on which provider markets hardest. Cards suit one-off purchases and impulse decisions; Direct Debit suits recurring payments and larger amounts, where a percentage fee stops making sense.',
        'sections' => [
            [
                'heading' => 'Percentage fees stop working above a certain amount',
                'body' => [
                    'Card processing is priced as a percentage plus a small fixed amount, which is sensible on a £30 order and expensive on a £3,000 invoice. Direct Debit is usually a small percentage capped at a low fixed ceiling, so the cost of collecting a large payment is closer to the price of a coffee than to a percentage of the sale.',
                    'Work out your own crossover point from your actual average transaction value. For businesses invoicing in the hundreds or thousands, moving recurring collections to Direct Debit is frequently the single largest saving available in the payment stack.',
                ],
            ],
            [
                'heading' => 'Direct Debit is slower, and that changes cash flow',
                'body' => [
                    'A card payment authorises in seconds. A Direct Debit collection has to be notified in advance and takes several working days to clear, and a first collection needs longer still because the mandate must be set up. For a subscription that is irrelevant; for a business that wants the money before it does the work, it matters a great deal.',
                    'The other side is that Direct Debit does not expire. Cards are replaced after fraud, loss or expiry, and each replacement is a payment that silently fails — which is why subscription businesses on cards lose customers who never intended to leave.',
                ],
            ],
            [
                'heading' => 'Do not touch card details yourself',
                'body' => [
                    'Any system where card numbers pass through your own server pulls you into a far more demanding level of PCI DSS compliance. Using a hosted payment page, or a provider\'s embedded fields that submit directly to them, keeps the numbers out of your infrastructure entirely and reduces what you have to attest to.',
                    'The corollary is to be suspicious of any integration that asks you to collect the card number in your own form and post it onward. It is technically possible and it moves a liability onto you that you have no reason to accept.',
                ],
            ],
            [
                'heading' => 'Plan for the failures, because they are routine',
                'body' => [
                    'Declines, expired cards, failed Direct Debits and chargebacks are a normal part of taking payments rather than exceptions. A system that treats a failed payment as an error and stops has handed you a manual job; one that retries on a schedule, emails the customer something they can act on, and shows your team what is outstanding has handled it.',
                    'Reconciliation deserves the same attention. Providers pay out in batches, net of fees, which means one bank entry covers many transactions. Deciding early how that is recorded saves a great deal of accounting argument later.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Is Direct Debit cheaper than cards?', 'a' => 'For larger or recurring amounts, usually yes, because the fee is generally capped rather than a straight percentage. For small one-off payments cards are often comparable.'],
            ['q' => 'Can I take payments without PCI compliance?', 'a' => 'No, but using a hosted page or a provider\'s embedded fields keeps card data off your servers and reduces your obligations substantially.'],
            ['q' => 'What about Apple Pay and Google Pay?', 'a' => 'They are worth enabling for any store with mobile traffic. They remove card entry on a phone, which is where much of the final drop-off happens.'],
            ['q' => 'Do I need both cards and Direct Debit?', 'a' => 'Many UK service businesses do: cards for deposits and one-off work, Direct Debit for retainers and instalments.'],
        ],
        'cta' => 'If payments are being reconciled by hand or failures are handled in someone\'s inbox, that is a workflow problem rather than a provider problem. <a href="/contact">Tell us how it runs</a> and we will suggest where to start.',
    ],

    'hiring-laravel-development-company-2026' => [
        'slug' => 'backup-disaster-recovery-uk-small-business',
        'image' => 'assets/images/blog/it-backup-recovery.svg',
        'image_alt' => 'Backup copies and a tested restore for a small business',
        'title' => 'Backups for Small UK Businesses: The Restore Is the Only Part That Counts',
        'excerpt' => 'Most businesses have backups. Far fewer have ever restored one. Here is how to find out which you are, before the day it matters.',
        'meta_title' => 'Backups and Disaster Recovery for UK Small Businesses | ARS Developer',
        'meta_description' => 'What to back up, how often, where to keep it, and how to test a restore — plus the two numbers that decide what your backup strategy should cost.',
        'answer' => 'A backup is a belief until somebody restores it. The only meaningful question is not whether backups are running but how long it would take to get trading again, and how much work you would lose — and both are answerable in an afternoon.',
        'sections' => [
            [
                'heading' => 'Two numbers decide everything else',
                'body' => [
                    'The first is how much data you can afford to lose, measured in time. If nightly backups are acceptable, you can lose a day\'s orders. If that is unthinkable, you need something continuous, and it costs more.',
                    'The second is how long you can be down. An hour, a day, a week — the answer determines whether you need a standby environment ready to take over or simply a copy you could restore onto new hosting. Deciding these two numbers with the business, rather than letting the hosting plan decide them for you, is the whole of backup strategy.',
                ],
            ],
            [
                'heading' => 'Keep one copy somewhere your provider cannot reach',
                'body' => [
                    'A backup stored in the same hosting account as the site is not a backup against the failures that actually happen — an account suspended over a billing dispute, a compromised control panel, or a provider outage. A ransomware infection with access to a mounted backup drive encrypts that too.',
                    'The usual guidance is three copies, on two kinds of storage, one of them off-site, and at least one that cannot be altered or deleted from the live system. For a small business that can be as simple as the host\'s own backups plus a nightly copy pushed to separate object storage with versioning switched on.',
                ],
            ],
            [
                'heading' => 'Test the restore on a schedule',
                'body' => [
                    'Restore tests find the problems backups hide: the database dumped without its schema, the uploads directory nobody included, an archive that has been silently truncated for months, an encryption key stored only on the server that failed.',
                    'Twice a year, restore to a scratch environment and actually use the result — log in, place an order, run a report. Write down how long it took. That figure is your real recovery time, and it is usually several times the estimate.',
                ],
            ],
            [
                'heading' => 'Back up the things that are not the database',
                'body' => [
                    'A database restored onto a server nobody can configure is not a recovered business. The list that gets forgotten: uploaded files and images, the environment configuration and its secrets, DNS records, SSL certificates, the list of third-party services and their credentials, and who at each supplier can authorise a change.',
                    'Keep that as a written document somewhere outside the system, because the moment you need it is precisely the moment you cannot log in to look it up.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Is my host\'s backup enough?', 'a' => 'It is a reasonable first copy and a poor only copy, because it fails in exactly the scenarios where your account or provider is the problem.'],
            ['q' => 'How often should backups run?', 'a' => 'Frequently enough that the data you would lose is acceptable. For most small businesses nightly is the baseline; anything taking orders continuously usually wants more.'],
            ['q' => 'How long should backups be kept?', 'a' => 'Long enough to cover a problem you did not notice immediately — commonly a few weeks of daily copies plus a few monthly ones. Remember that retention here interacts with your data retention policy.'],
            ['q' => 'What is the most common backup failure?', 'a' => 'Discovering at restore time that something essential was never included, most often uploaded files or configuration. Only a test restore finds it.'],
        ],
        'cta' => 'If nobody can say how long a restore would take, that is the thing worth finding out first. <a href="/contact">Ask us</a> to run a restore test against a copy.',
    ],

    'laravel-development-agency-uk-what-good-delivery-looks-like-before-you-sign' => [
        'slug' => 'cookie-consent-analytics-uk-compliance',
        'image' => 'assets/images/blog/it-cookie-consent.svg',
        'image_alt' => 'Cookie consent preferences with accept and reject offered equally',
        'title' => 'Cookie Banners and Analytics in the UK: Doing It Properly Without Losing Your Data',
        'excerpt' => 'UK rules require consent before non-essential cookies are set. Most banners get this wrong in a way that is both non-compliant and bad for your analytics.',
        'meta_title' => 'Cookie Consent and Analytics Compliance in the UK | ARS Developer',
        'meta_description' => 'What UK law requires of cookie banners, why most implementations fail, and how to keep useful measurement without pretending consent was given.',
        'answer' => 'Under PECR and UK GDPR, non-essential cookies — including analytics — need consent before they are set, and consent must be as easy to refuse as to give. A banner that loads the tracking anyway, or that offers "Accept" without an equally prominent "Reject", is decoration rather than compliance.',
        'sections' => [
            [
                'heading' => 'The two failures that appear on most sites',
                'body' => [
                    'The first is loading the analytics script before the visitor has answered. If the cookie is written as the page renders, the banner is a notification, and the consent it records happened after the thing it was meant to authorise.',
                    'The second is making refusal harder than acceptance — a prominent "Accept all" alongside a link to a settings page where refusal takes three more clicks. The ICO has been explicit that this is not valid consent, and it is the pattern most consent plugins ship with by default.',
                ],
            ],
            [
                'heading' => 'Essential cookies do not need consent, and few cookies are essential',
                'body' => [
                    'Cookies strictly necessary for a service the user asked for are exempt: the session that keeps them logged in, the basket contents, a load balancer\'s routing cookie, the record of their consent choice itself.',
                    'Analytics is not exempt, however useful it is to you, because the visitor did not ask to be measured. Nor are advertising, heatmapping, session recording or embedded media that sets cookies on load. Auditing which cookies your site actually sets, and why, is the necessary first step — most sites set more than their owners realise, usually through embeds.',
                ],
            ],
            [
                'heading' => 'Keep useful measurement without pretending',
                'body' => [
                    'Refusal rates mean analytics under-reports, and the temptation is to make the banner misleading. The better answer is to reduce how much you depend on cookies in the first place. Server-side measurement of the things that matter commercially — enquiries submitted, orders placed, revenue — does not depend on consent because it is your own transaction data.',
                    'Privacy-focused analytics tools that avoid cookies and do not identify individuals are also worth considering. They give a consistent picture of traffic rather than a consented sample, which for most business decisions is more useful than a precise view of some visitors.',
                ],
            ],
            [
                'heading' => 'Honour the choice, and let people change it',
                'body' => [
                    'Consent has to be withdrawable as easily as it was given, so a persistent link in the footer that reopens the preferences is effectively required. It also has to be recorded: what was consented to, when, and against which version of your cookie list.',
                    'Then check that refusal actually works. Refuse, reload, and look at what cookies exist. A surprising number of banners record the refusal correctly and load the scripts regardless, which is the worst of both outcomes — no data you can rely on and no compliance either.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Do I need consent for Google Analytics in the UK?', 'a' => 'Yes. Analytics cookies are not strictly necessary, so they require consent before being set.'],
            ['q' => 'Can "Accept" be more prominent than "Reject"?', 'a' => 'No. The ICO expects refusing to be as straightforward as accepting, which means both options at the same level.'],
            ['q' => 'What counts as a strictly necessary cookie?', 'a' => 'One required to deliver something the user asked for — login sessions, basket contents, security tokens, and the consent record itself.'],
            ['q' => 'Will a compliant banner hurt my analytics?', 'a' => 'It will reduce recorded sessions. Measuring commercial outcomes server-side gives you numbers that do not depend on consent at all.'],
        ],
        'cta' => 'If you are not sure what your site sets before anyone clicks anything, that is worth checking. <a href="/contact">Send us the URL</a> and we will tell you what loads.',
    ],

    'custom-crm-development-cost-uk-2026' => [
        'slug' => 'account-security-business-systems-2fa-uk',
        'image' => 'assets/images/blog/it-account-security.svg',
        'image_alt' => 'Two-factor authentication protecting a business system login',
        'title' => 'Securing the Accounts Your Business Runs On: 2FA, Access and Leavers',
        'excerpt' => 'Most small business breaches are not clever. They are a reused password, an admin account belonging to someone who left, or a domain nobody could prove they owned.',
        'meta_title' => 'Business Account Security: 2FA, Access Control and Offboarding | ARS Developer',
        'meta_description' => 'The practical account security that prevents most UK small business incidents — two-factor authentication, least privilege, offboarding and domain control.',
        'answer' => 'The incidents that actually happen to small businesses are mundane: a password reused from a site that was breached, an administrator account still active for someone who left last year, or a hosting login shared by four people. Fixing those costs almost nothing and removes most of the risk.',
        'sections' => [
            [
                'heading' => 'Two-factor authentication, starting with the email account',
                'body' => [
                    'Turn it on for email first. Email is the recovery route for everything else, so an attacker who controls it can reset the hosting, the domain, the bank and the payment provider regardless of how strong those passwords are.',
                    'Prefer an authenticator app or a hardware key over SMS. SMS is far better than nothing, but it is defeated by SIM swapping, which is a realistic threat for any business whose owner is easy to identify online — which is to say, any business with a website.',
                ],
            ],
            [
                'heading' => 'Named accounts, not shared logins',
                'body' => [
                    'A single administrator login shared among the team cannot be audited, cannot be revoked for one person, and has to be changed for everyone whenever anybody leaves — so in practice it never is. Every system that supports individual accounts should have them.',
                    'Give each person the least access their job needs. A content editor does not need the ability to install plugins or add users, and the cost of that restraint is roughly nil compared with the cost of a compromised account that could do anything.',
                ],
            ],
            [
                'heading' => 'Offboarding is where the gaps accumulate',
                'body' => [
                    'When someone leaves, the email account usually gets dealt with. What survives is everything else: the hosting panel, the CMS, the analytics property, the payment dashboard, the social accounts, the domain registrar, and any personal device still holding a session.',
                    'Keep a written list of every system and who has access, and work through it on the day someone leaves rather than from memory. The same list is what you will need if you ever have to answer how an incident happened.',
                ],
            ],
            [
                'heading' => 'Own your domain, and prove it',
                'body' => [
                    'The domain is the one asset whose loss is close to unrecoverable, because everything — email, website, logins — depends on it. It should be registered to the business rather than to a developer or a former employee, with the registrar account under your control and its own two-factor authentication.',
                    'Enable the registrar\'s transfer lock, set the renewal to automatic, and make sure the contact address on the account is one somebody still reads. Domains are lost to an expiry notice sent to an address nobody has checked since the person who set it up moved on.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Is SMS two-factor good enough?', 'a' => 'It is much better than nothing and much weaker than an app or hardware key, because SIM swapping defeats it. Use it where nothing better is offered.'],
            ['q' => 'Do we need a password manager?', 'a' => 'For any team sharing access to business systems, yes. It is the only practical way to have unique passwords per service and to revoke access cleanly when someone leaves.'],
            ['q' => 'What should we check first?', 'a' => 'Two-factor on the email account that can reset everything else, then who still has access to your hosting and domain registrar.'],
            ['q' => 'Who should own the domain registration?', 'a' => 'The business, in an account the business controls. A domain in a supplier\'s or employee\'s name is a dependency you cannot resolve quickly if the relationship ends.'],
        ],
        'cta' => 'An access review across your hosting, domain, CMS and payment accounts takes an hour and usually finds something. <a href="/contact">Ask us</a> to run one with you.',
    ],

    'website-development-cost-uk-in-2026-what-small-businesses-should-budget-for' => [
        'slug' => 'choosing-uk-web-hosting-what-matters',
        'image' => 'assets/images/blog/it-uk-hosting.svg',
        'image_alt' => 'Server resources that decide whether hosting is adequate',
        'title' => 'Choosing Web Hosting in the UK: What Actually Matters, and What Does Not',
        'excerpt' => 'Hosting is sold on storage and bandwidth, which are almost never the constraint. Here is what genuinely differs between plans, and when moving is worth the disruption.',
        'meta_title' => 'Choosing UK Web Hosting: What Actually Matters | ARS Developer',
        'meta_description' => 'Unlimited storage, bandwidth and uptime promises tell you little. What decides whether hosting is adequate — and the questions worth asking before you sign.',
        'answer' => 'Hosting is marketed on the numbers that are cheap to give away. Storage and bandwidth are rarely what limits a small business site; what limits it is CPU and memory allocation, the PHP and database versions available, and how quickly a human answers when something breaks.',
        'sections' => [
            [
                'heading' => 'The limits that are not in the headline',
                'body' => [
                    'Shared hosting plans advertising unlimited storage typically enforce a quiet ceiling on processes, memory per process, database connections or CPU seconds. Exceeding it does not produce an error you can read — it produces a slow site, or a 503 during your busiest hour.',
                    'Ask for the actual figures: memory limit, maximum execution time, concurrent process count, and what happens when they are reached. A provider that answers plainly is telling you something; one that redirects to the marketing page has also answered.',
                ],
            ],
            [
                'heading' => 'Version support tells you how long the plan lasts',
                'body' => [
                    'Which PHP versions are offered, and how soon after release. A host still capping you at a version that has passed its security support date is a host you will have to leave the moment your framework moves on, and Laravel in particular drops old PHP versions at a steady pace.',
                    'The same goes for the database. Being able to choose the version, and having a path to upgrade, matters far more over three years than the disk allowance you were sold on.',
                ],
            ],
            [
                'heading' => 'Where the server is still matters, less than it did',
                'body' => [
                    'For a UK audience, a server in the UK or nearby Europe removes latency on every uncached request. A CDN handles the static files wherever they are, but the dynamic response — a logged-in page, a basket, a form submission — comes from the origin every time.',
                    'There is also a practical argument for UK or EU hosting where personal data is involved. It is not that other locations are prohibited, but that keeping data in the UK removes a set of transfer questions you would otherwise have to document and defend.',
                ],
            ],
            [
                'heading' => 'Ask what a migration away looks like',
                'body' => [
                    'Before signing, find out how you would leave: whether you can take a full backup yourself, whether the control panel is a standard one, and whether anything about the setup is proprietary. Hosting that is easy to leave tends to be hosting you do not need to.',
                    'Check the support arrangement too — whether there are humans outside office hours, and whether they will look at an application-level problem or only confirm the server is up. On the day the site is down, that distinction is the entire value of the plan.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Is unlimited bandwidth real?', 'a' => 'Effectively yes for a small business site, because bandwidth is rarely the constraint. CPU and memory allocation almost always bite first.'],
            ['q' => 'Does a 99.9% uptime guarantee mean much?', 'a' => 'Little. It permits several hours of downtime a year and the remedy is usually a partial credit rather than compensation for lost trade.'],
            ['q' => 'Do I need UK-based hosting?', 'a' => 'Not strictly, but it reduces latency for UK visitors and simplifies the data-transfer questions when you hold personal data.'],
            ['q' => 'When is it worth moving host?', 'a' => 'When you are hitting resource limits, when the PHP or database version you need is unavailable, or when support cannot help with problems above the server layer.'],
        ],
        'cta' => 'If the site is slow and nobody can say whether it is the hosting or the code, that is answerable in a day. <a href="/contact">Ask us</a> to measure it.',
    ],

    'saas-mvp-development-uk-launch-without-overspending' => [
        'slug' => 'selling-internationally-from-a-uk-online-store',
        'image' => 'assets/images/blog/it-international-selling.svg',
        'image_alt' => 'International orders shipped from a UK online store',
        'title' => 'Selling Abroad from a UK Store: Tax, Currency and the Parts That Bite',
        'excerpt' => 'Opening up international shipping is a checkbox. Getting the tax, the duties and the returns right is the part that decides whether it is profitable.',
        'meta_title' => 'Selling Internationally from a UK Online Store | ARS Developer',
        'meta_description' => 'What UK stores need to handle when selling abroad: VAT and destination tax, customs data, duties at delivery, currency display and the cost of returns.',
        'answer' => 'Enabling international shipping takes a minute. What costs money afterwards is tax you did not register for, parcels held at customs for missing data, and customers refusing delivery because a duty charge arrived that nobody warned them about.',
        'sections' => [
            [
                'heading' => 'The customer must not be surprised at the door',
                'body' => [
                    'Sold on a delivered-duty-unpaid basis, an overseas customer receives a demand from the courier before they can have their parcel — often a duty charge plus a handling fee that can rival the order value. A large share simply refuse, and you pay the return leg as well as losing the sale.',
                    'The alternative is to calculate and collect duties and destination tax at checkout so the price shown is the final price. It is more work to set up and it converts considerably better, because nothing unexpected happens later.',
                ],
            ],
            [
                'heading' => 'Registration thresholds arrive sooner than expected',
                'body' => [
                    'Selling into the EU brings destination-country VAT into play, and there are simplified schemes intended to let a non-EU seller account for it through a single registration rather than one per country. The US is different again, where sales tax obligations depend on state-level thresholds measured by revenue or transaction count.',
                    'None of this matters at ten orders a month and all of it matters at a thousand. Decide the threshold at which you will take advice, put a reminder against it, and do not discover the obligation retrospectively.',
                ],
            ],
            [
                'heading' => 'Customs data is a product data problem',
                'body' => [
                    'Every international parcel needs a commodity code, a country of origin, an accurate description and a declared value. These are attributes of the product, so they belong in the product record where they can be maintained in bulk — not typed into a courier\'s form per shipment.',
                    'Getting them wrong causes delays that look to the customer like your fault. Getting them systematically wrong, particularly the declared value, causes a different and more serious kind of problem.',
                ],
            ],
            [
                'heading' => 'Price in their currency, and price deliberately',
                'body' => [
                    'Showing prices in the visitor\'s currency measurably improves conversion, but a live conversion produces prices like 27.43, which reads as a foreign price converted rather than a price. Rounding to a sensible local figure looks like a store that means to sell there.',
                    'Build in a margin for exchange movement and for the provider\'s conversion fee. A store pricing at the mid-market rate is absorbing both, which quietly removes a chunk of the margin the international expansion was supposed to add.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Do I charge UK VAT on exports?', 'a' => 'Goods exported from the UK are generally zero-rated for UK VAT, but destination-country tax may apply instead and the rules vary by market and value.'],
            ['q' => 'Should I collect duties at checkout?', 'a' => 'Where the destination commonly charges them, yes. It costs more to implement and it prevents refused deliveries, which are the expensive outcome.'],
            ['q' => 'What is a commodity code?', 'a' => 'A standard classification for what you are shipping, used to determine duty. It belongs on the product record so it can be maintained in bulk.'],
            ['q' => 'How should international returns work?', 'a' => 'Decide before you launch. Return shipping from abroad often exceeds the item\'s value, and a policy written after the first request is always the expensive one.'],
        ],
        'cta' => 'If international orders are being handled manually at the courier\'s website, that is where the errors come from. <a href="/contact">Tell us how it works now</a> and we will suggest what to automate first.',
    ],

    'software-development-company-tunbridge-wells-how-to-choose-the-right-partner' => [
        'slug' => 'stop-contact-form-spam-without-losing-enquiries',
        'image' => 'assets/images/blog/it-form-spam.svg',
        'image_alt' => 'Spam filtered from a contact form while genuine enquiries pass',
        'title' => 'Stopping Contact Form Spam Without Losing Real Enquiries',
        'excerpt' => 'Every anti-spam measure blocks some genuine visitors. Here is how to cut the noise while keeping the enquiries you actually want.',
        'meta_title' => 'Stop Contact Form Spam Without Losing Enquiries | ARS Developer',
        'meta_description' => 'Why contact forms fill with spam, which defences work, and how to avoid blocking real customers — including the accessibility problem with CAPTCHAs.',
        'answer' => 'Most form spam is automated and can be removed with techniques the visitor never sees. Reach for a CAPTCHA last, because it is the measure that costs you real enquiries — and the people it turns away are disproportionately those using assistive technology or an older device.',
        'sections' => [
            [
                'heading' => 'Invisible defences first',
                'body' => [
                    'A honeypot is a field hidden from people and visible to simple bots; anything that fills it is discarded. A timestamp check discards submissions completed in under a couple of seconds, because nobody reads and completes a form that quickly. Together these remove a large share of automated submissions and cost a genuine visitor nothing.',
                    'Hide the honeypot with CSS rather than the hidden attribute, and label it so a screen reader announces it as one to leave blank. A field that is invisible to everyone including assistive technology will catch the people you least want to block.',
                ],
            ],
            [
                'heading' => 'Rate limiting handles the rest of the volume',
                'body' => [
                    'Spam arrives in bursts from a small number of sources, so limiting submissions per address over a window removes most of the volume without touching a normal visitor, who submits once. Laravel has this built in; most other frameworks do too.',
                    'Set the limit generously — several submissions an hour rather than one a day — because shared office connections and mobile networks put many people behind one address. A limit tight enough to catch every bot will eventually catch a customer at a co-working space.',
                ],
            ],
            [
                'heading' => 'CAPTCHAs are a real cost, so use them last',
                'body' => [
                    'Image challenges are difficult for people with visual impairments, for some people with dyslexia, and for anyone on a slow connection. Invisible scoring systems are better, but they still occasionally challenge legitimate visitors, and they add a third-party script and a consent question to every page they appear on.',
                    'If you do need one, put it only on the forms that are actually attacked, keep the threshold forgiving, and make sure a failed challenge gives the person another route to contact you rather than a dead end.',
                ],
            ],
            [
                'heading' => 'Check what you are actually catching',
                'body' => [
                    'Any filter will misclassify something, so for the first month keep rejected submissions rather than discarding them. Read through them weekly. The genuine enquiry sitting in that list is what tells you the threshold is wrong, and it is the only way to find out — a blocked customer does not usually try again.',
                    'It is also worth confirming the notifications reach you at all. A form that works perfectly and emails into a spam folder produces the same symptom as one that is broken, and it is a more common cause than it should be.',
                ],
            ],
        ],
        'faq' => [
            ['q' => 'Does a honeypot still work?', 'a' => 'Against the bulk of automated submissions, yes. It will not stop a targeted attack, but most form spam is indiscriminate.'],
            ['q' => 'Are CAPTCHAs an accessibility problem?', 'a' => 'They can be. Image and audio challenges create real barriers, which is why they are better used as a last resort on the forms that need them.'],
            ['q' => 'Why did spam suddenly increase?', 'a' => 'Usually because your form was discovered by a crawler. A change to the field names and the addition of a honeypot often ends it.'],
            ['q' => 'Should rejected submissions be stored?', 'a' => 'For a short period, yes, so you can confirm nothing genuine is being caught. Then they should fall under the same retention rules as everything else.'],
        ],
        'cta' => 'If your enquiry form is either full of spam or suspiciously quiet, both are worth checking. <a href="/contact">Get in touch</a> and we will look at how it is set up.',
    ],
];
