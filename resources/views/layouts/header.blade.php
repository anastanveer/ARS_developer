<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('recaptcha.enabled') && filled(config('recaptcha.site_key')))
        <script>
            window.arsRecaptchaEnabled = true;
            window.arsRecaptchaSiteKey = @json(config('recaptcha.site_key'));
        </script>
        <script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>
        <style>
            .ars-recaptcha-box {
                margin-top: 18px;
            }

            .ars-recaptcha-box__error {
                display: block;
                margin-top: 8px;
                color: #b42318;
                font-size: 13px;
            }
        </style>
    @endif
    @php
        $currentPath = '/' . trim(request()->path(), '/');
        if ($currentPath === '//') {
            $currentPath = '/';
        }
        $isHomePath = in_array($currentPath, ['/', '/index.php'], true);
        $isGalleryPath = in_array($currentPath, ['/gallery', '/gallery.php'], true);
        $isFaqPath = in_array($currentPath, ['/faq', '/faq.php'], true);
        $isComingSoonPath = in_array($currentPath, ['/coming-soon', '/coming-soon.php'], true);
        $isErrorPath = in_array($currentPath, ['/404', '/404.php'], true);
        $isAboutPath = in_array($currentPath, ['/about', '/about.php'], true);
        $isContactPath = in_array($currentPath, ['/contact', '/contact.php'], true);
        $isClientPortalPath = in_array($currentPath, ['/client-portal', '/client-portal.php'], true);
        $isClientReviewPath = in_array($currentPath, ['/client-review', '/client-review.php'], true);
        $isMeetingManagePath = in_array($currentPath, ['/meeting-manage', '/meeting-manage.php'], true);
        $needsSwiperAssets = $isHomePath;
        $needsPopupAssets = $isAboutPath || $isGalleryPath;
        $needsNiceSelectAssets = $isHomePath || $isContactPath || $isClientPortalPath || $isClientReviewPath || $isMeetingManagePath;
        $needsValidationAssets = $isHomePath || $isContactPath || $isClientReviewPath || $isMeetingManagePath;

        $regionConfig = config('regions.regions', []);
        $selectedRegionKey = 'uk';
        $selectedRegion = $regionConfig[$selectedRegionKey] ?? reset($regionConfig);
        $currentCurrency = $selectedRegion['currency'] ?? 'USD';
        $currentCurrencySymbol = $selectedRegion['symbol'] ?? '$';
        $currentHreflang = $selectedRegion['hreflang'] ?? 'en';
        $currentOgLocale = $selectedRegion['og_locale'] ?? 'en_US';

        $siteName = 'ARSDeveloper';
        $defaultSeo = [
            'title' => 'UK Software Agency',
            'description' => 'ARSDeveloper is a UK software agency delivering web development, custom CRM systems, WordPress websites, mobile apps, SEO, and digital growth services.',
            'keywords' => 'uk software agency, software development company uk, custom web development uk, crm software development uk, wordpress developer uk, ecommerce development uk, seo agency uk, digital transformation services uk',
            'type' => 'WebPage',
        ];

        $seoPages = [
            '/' => [
                'title' => 'UK Software Development Company | Web, CRM & SEO — ARS Developer Stoke-on-Trent',
                'description' => 'ARS Developer is a UK software development company in Stoke-on-Trent delivering custom websites, CRM systems, SEO growth, and AI-ready digital solutions for UK businesses.',
                'keywords' => 'uk software development company, web development agency uk, software development company stoke on trent, custom software company uk, crm development uk, seo agency uk, laravel development uk, wordpress agency uk, shopify developers uk, ai web development uk, software agency staffordshire',
                'type' => 'WebPage',
                'preload_image' => asset('assets/images/resources/main-slider-img-1-1.png'),
                'faq_items' => [
                    [
                        'question' => 'What does ARS Developer do?',
                        'answer' => 'ARS Developer is a UK software development company based in Stoke-on-Trent, Staffordshire. We build custom websites, CRM systems, Laravel applications, and deliver technical SEO and AI search optimisation for UK businesses.',
                    ],
                    [
                        'question' => 'How fast can a UK business website project start?',
                        'answer' => 'Most website and CRM projects can start within one to five business days after scope confirmation and onboarding.',
                    ],
                    [
                        'question' => 'Do you provide both development and SEO support?',
                        'answer' => 'Yes. We provide web delivery, CRM implementation, technical SEO, AEO, GEO, and monthly growth support in one execution flow.',
                    ],
                    [
                        'question' => 'Which UK cities and regions do you serve?',
                        'answer' => 'We work with UK businesses across Stoke-on-Trent, Staffordshire, Manchester, Birmingham, London, and nationwide. All projects are delivered remotely with full UK timezone support.',
                    ],
                    [
                        'question' => 'Can ARS Developer help my business rank on ChatGPT and Google AI?',
                        'answer' => 'Yes. We implement AEO (Answer Engine Optimisation) and GEO (Generative Engine Optimisation) as standard on all content and service pages, structuring answers so they are cited by ChatGPT, Google AI Overviews, and Perplexity.',
                    ],
                ],
            ],
            '/index.php' => [
                'title' => 'UK Software Agency for Web, CRM and WordPress Development',
                'description' => 'ARSDeveloper helps UK businesses with custom websites, CRM software, WordPress development, SEO, and digital growth strategies.',
                'keywords' => 'web development agency uk, custom software company uk, crm development uk, wordpress agency uk, shopify developers uk, wix website experts uk, seo services uk, business automation uk',
                'type' => 'WebPage',
                'preload_image' => asset('assets/images/resources/main-slider-img-1-1.png'),
            ],
            '/about.php' => [
                'title' => 'About ARSDeveloper UK Team for Web, CRM and SEO Delivery',
                'description' => 'Meet ARSDeveloper UK, delivering business websites, CRM systems, and SEO growth with transparent scope and practical project ownership.',
                'keywords' => 'about arsdeveloper uk, uk web development team, crm development partner uk, seo delivery team uk, software agency stoke-on-trent',
                'type' => 'AboutPage',
                'og_image' => url('/assets/images/resources/about-one-img-1.jpg'),
                'og_image_alt' => 'ARS Developer UK software development team',
            ],
            '/about' => [
                'title' => 'About ARSDeveloper UK Team for Web, CRM and SEO Delivery',
                'description' => 'Meet ARSDeveloper UK, delivering business websites, CRM systems, and SEO growth with transparent scope and practical project ownership.',
                'keywords' => 'about arsdeveloper uk, uk web development team, crm development partner uk, seo delivery team uk, software agency stoke-on-trent',
                'type' => 'AboutPage',
                'og_image' => url('/assets/images/resources/about-one-img-1.jpg'),
                'og_image_alt' => 'ARS Developer UK software development team',
            ],
            '/services.php' => [
                'title' => 'UK Software and Web Services — Websites, CRM and SEO',
                'description' => 'UK software agency delivering conversion-focused websites, custom CRM systems, SEO growth, and app development. Problem-solving service delivery with milestone accountability.',
                'keywords' => 'software development services uk, website development services uk, crm development services uk, wordpress development services uk, ecommerce website development uk, shopify development uk, seo agency uk, digital marketing uk',
                'type' => 'Service',
                'faq_items' => [
                    [
                        'question' => 'What software services are most requested by UK businesses?',
                        'answer' => 'Most UK businesses request conversion-focused websites, CRM workflow automation, technical SEO, and ongoing monthly growth support.',
                    ],
                    [
                        'question' => 'Can ARSDeveloper handle strategy, delivery, and post-launch support together?',
                        'answer' => 'Yes. We handle planning, build, launch, and monthly optimization with one accountable delivery model and clear milestones.',
                    ],
                    [
                        'question' => 'How quickly can a UK business start after enquiry?',
                        'answer' => 'Most projects can start within one to five business days once scope, timeline, and onboarding documents are confirmed.',
                    ],
                ],
            ],
            '/digital-marketing.php' => [
                'title' => 'Digital Marketing UK — Lead Generation and Paid Campaigns',
                'description' => 'UK digital marketing combining SEO, paid campaigns, landing page CRO, and AI search visibility. Stop wasting budget on traffic that does not convert.',
                'keywords' => 'digital marketing agency uk, lead generation services uk, ppc management uk, google ads agency uk, conversion rate optimisation uk, aeo marketing uk, ai search marketing uk',
                'type' => 'Service',
                'og_image' => url('/assets/images/services/services-details-img-4.jpg'),
                'og_image_alt' => 'Digital marketing services for UK businesses — ARS Developer',
            ],
            '/web-design-development.php' => [
                'title' => 'Web Design and Development UK — Conversion-Ready Builds',
                'description' => 'Website design and development for UK businesses that need enquiries, not just traffic. SEO-first architecture, Core Web Vitals performance, and conversion-focused UX.',
                'keywords' => 'web design agency uk, website development company uk, conversion focused website uk, seo website development uk, core web vitals uk, responsive website design uk, lead generation website uk',
                'type' => 'Service',
                'og_image' => url('/assets/images/services/services-details-img-2.jpg'),
                'og_image_alt' => 'Web design and development services for UK businesses — ARS Developer',
            ],
            '/search-engine-optimization.php' => [
                'title' => 'SEO Services UK — AEO, GEO and Technical SEO Growth',
                'description' => 'UK SEO services solving rankings, AI search visibility, and qualified leads. Technical audit, buyer intent mapping, AEO, and monthly Search Console reporting.',
                'keywords' => 'seo services uk, aeo services uk, answer engine optimisation uk, ai seo uk, technical seo agency uk, google ai overview optimisation uk, local seo uk, search console optimisation uk',
                'type' => 'Service',
                'og_image' => url('/assets/images/services/services-details-img-1.jpg'),
                'og_image_alt' => 'SEO services for UK businesses — ARS Developer',
            ],
            '/design-and-branding.php' => [
                'title' => 'Brand Design UK — Identity, Messaging and Conversion Systems',
                'description' => 'Brand design for UK businesses that improves conversion rates, clarifies messaging, and builds AI-search entity signals. Stop losing enquiries to clearer competitors.',
                'keywords' => 'branding agency uk, brand identity design uk, conversion design uk, ai brand entity signals uk, website messaging uk, offer positioning uk, ui ux design uk',
                'type' => 'Service',
                'og_image' => url('/assets/images/services/services-details-img-6.jpg'),
                'og_image_alt' => 'Brand design services for UK businesses — ARS Developer',
            ],
            '/app-development.php' => [
                'title' => 'Web App Development UK — Portals, Dashboards and Workflow Tools',
                'description' => 'UK web app development for portals, dashboards, AI-assisted workflows, and business automation. Discovery-led scoping. Milestone delivery. Post-launch support.',
                'keywords' => 'web app development uk, portal development uk, dashboard development uk, workflow automation uk, business app development uk, saas development uk, crm portal uk',
                'type' => 'Service',
                'og_image' => url('/assets/images/services/services-details-img-5.jpg'),
                'og_image_alt' => 'App and portal development for UK businesses — ARS Developer',
            ],
            '/software-development.php' => [
                'title' => 'Custom Software Development Company UK',
                'description' => 'Custom software development for UK businesses that need secure workflows, integrations, and scalable delivery systems.',
                'keywords' => 'custom software development uk, software development company uk, bespoke software uk, business automation software uk, enterprise software developers uk',
                'type' => 'Service',
                'og_image' => url('/assets/images/services/services-details-img-3.jpg'),
                'og_image_alt' => 'Custom software development for UK businesses — ARS Developer',
                'faq_items' => [
                    [
                        'question' => 'What type of custom software projects do you build in the UK?',
                        'answer' => 'We build business workflow systems, admin portals, CRM modules, automation tools, and integration-ready platforms for UK teams.',
                    ],
                    [
                        'question' => 'Do you support existing software or only new builds?',
                        'answer' => 'We support both new systems and existing software modernization, including refactors, integration fixes, and performance upgrades.',
                    ],
                ],
            ],
            '/software-development' => [
                'title' => 'Custom Software Development Company UK',
                'description' => 'Custom software development for UK businesses that need secure workflows, integrations, and scalable delivery systems.',
                'keywords' => 'custom software development uk, software development company uk, bespoke software uk, business automation software uk, enterprise software developers uk',
                'type' => 'Service',
                'og_image' => url('/assets/images/services/services-details-img-3.jpg'),
                'og_image_alt' => 'Custom software development for UK businesses — ARS Developer',
            ],
            '/portfolio.php' => [
                'title' => 'Portfolio of UK Web Development, CRM and SEO Projects',
                'description' => 'View portfolio projects delivered by ARSDeveloper across website development, CRM, SEO, and software solutions.',
                'keywords' => 'software development portfolio uk, web development case studies uk, ecommerce project portfolio uk, crm implementation case study uk',
                'type' => 'CollectionPage',
                'og_image' => url('/assets/images/resources/why-choose-one-img-1.jpg'),
                'og_image_alt' => 'ARS Developer UK portfolio — web development and software projects',
            ],
            '/portfolio' => [
                'title' => 'Portfolio of UK Web Development, CRM and SEO Projects',
                'description' => 'View portfolio projects delivered by ARSDeveloper across website development, CRM, SEO, and software solutions.',
                'keywords' => 'software development portfolio uk, web development case studies uk, ecommerce project portfolio uk, crm implementation case study uk',
                'type' => 'CollectionPage',
                'og_image' => url('/assets/images/resources/why-choose-one-img-1.jpg'),
                'og_image_alt' => 'ARS Developer UK portfolio — web development and software projects',
            ],
            '/portfolio-details.php' => [
                'title' => 'Project Details - ARSDeveloper Portfolio',
                'description' => 'Detailed project outcomes and implementation approach for ARSDeveloper software and web projects.',
                'keywords' => 'project details software agency, web project case study UK',
                'type' => 'WebPage',
            ],
            '/testimonials.php' => [
                'title' => 'Client Testimonials - ARSDeveloper UK',
                'description' => 'Read client reviews and testimonials about ARSDeveloper software development and digital services.',
                'keywords' => 'software agency reviews UK, client testimonials ARSDeveloper',
                'type' => 'CollectionPage',
            ],
            '/testimonial-carousel.php' => [
                'title' => 'Testimonials - ARSDeveloper',
                'description' => 'Client feedback and success stories from UK software, web, and SEO projects.',
                'keywords' => 'client feedback software agency UK, testimonials web development UK',
                'type' => 'CollectionPage',
                'robots' => 'noindex, follow',
            ],
            '/pricing.php' => [
                'title' => 'Pricing Plans for Software and Web Services',
                'description' => 'Transparent pricing options for web development, SEO, CRM, and digital services by ARSDeveloper.',
                'keywords' => 'web development packages uk, software development pricing uk, seo pricing uk, monthly website support uk, crm development cost uk',
                'type' => 'WebPage',
                'faq_items' => [
                    [
                        'question' => 'How is pricing structured for UK projects?',
                        'answer' => 'Pricing is based on delivery scope, timeline, integrations, and post-launch support, with clear milestones and documented outputs.',
                    ],
                    [
                        'question' => 'Can we start with a smaller package and scale later?',
                        'answer' => 'Yes. Many clients start with a focused build and then move to monthly growth support once baseline delivery is complete.',
                    ],
                ],
            ],
            '/gallery.php' => [
                'title' => 'Gallery of UK Web, CRM and SEO Project Work',
                'description' => 'Explore visual highlights from ARSDeveloper digital projects and team capabilities.',
                'keywords' => 'agency gallery UK, software agency work, digital project visuals',
                'type' => 'ImageGallery',
            ],
            '/gallery' => [
                'title' => 'Gallery of UK Web, CRM and SEO Project Work',
                'description' => 'Explore visual highlights from ARSDeveloper digital projects and team capabilities.',
                'keywords' => 'agency gallery UK, software agency work, digital project visuals',
                'type' => 'ImageGallery',
            ],
            '/faq.php' => [
                'title' => 'FAQ — UK Web, Software, SEO and CRM Questions Answered',
                'description' => 'Answers to real UK business questions on SEO, AEO, website delivery, custom software, CRM builds, app development, project timelines, and how to get a free audit.',
                'keywords' => 'software agency faq uk, web development questions uk, crm faq uk, seo faq uk, aeo faq uk, project delivery faq uk, free audit uk',
                'type' => 'FAQPage',
                'og_image' => url('/assets/images/resources/banner-two-img-1.png'),
                'og_image_alt' => 'ARS Developer UK FAQ — web development and SEO questions answered',
            ],
            '/faq' => [
                'title' => 'FAQ — UK Web, Software, SEO and CRM Questions Answered',
                'description' => 'Answers to real UK business questions on SEO, AEO, website delivery, custom software, CRM builds, app development, project timelines, and how to get a free audit.',
                'og_image' => url('/assets/images/resources/banner-two-img-1.png'),
                'og_image_alt' => 'ARS Developer UK FAQ — web development and SEO questions answered',
                'keywords' => 'software agency faq uk, web development questions uk, crm faq uk, seo faq uk, aeo faq uk, project delivery faq uk, free audit uk',
                'type' => 'FAQPage',
            ],
            '/blog.php' => [
                'title' => 'AI SEO Blog | UK Software Development, AEO, GEO and Growth Insights',
                'description' => 'Practical AI SEO, software development, CRM, answer engine optimization, and growth insights for UK businesses.',
                'keywords' => 'ai seo blog uk, aeo blog uk, geo seo uk, software development blog uk, crm blog uk, answer engine optimization uk',
                'type' => 'Blog',
            ],
            '/uk-growth-hub' => [
                'title' => 'UK SEO Growth Hub: AEO, GEO and EEAT Playbook',
                'description' => 'Pillar guide for UK businesses covering AEO, GEO, EEAT, technical SEO, topic clusters, and conversion-focused search growth.',
                'keywords' => 'uk seo strategy 2026, aeo optimization uk, geo seo uk, eeat framework uk, ai overview seo uk',
                'type' => 'CollectionPage',
            ],
            '/blog-list.php' => [
                'title' => 'Blog Articles - ARSDeveloper',
                'description' => 'Browse ARSDeveloper blog articles on software, SEO, web development, and business growth.',
                'keywords' => 'software articles UK, SEO articles UK, web development insights',
                'type' => 'Blog',
                'robots' => 'noindex, follow',
            ],
            '/blog-details.php' => [
                'title' => 'Blog Details - ARSDeveloper',
                'description' => 'In-depth blog content from ARSDeveloper on software strategy, web delivery, and SEO performance.',
                'keywords' => 'detailed SEO article UK, software strategy blog UK',
                'type' => 'Article',
            ],
            '/search' => [
                'title' => 'Search Results - ARSDeveloper UK Software Agency',
                'description' => 'Search ARSDeveloper pages, services, portfolio and blog content for web development, CRM, WordPress and SEO topics.',
                'keywords' => 'search ARSDeveloper, website search UK software agency, service search',
                'type' => 'SearchResultsPage',
                'robots' => 'noindex, follow',
            ],
            '/search.php' => [
                'title' => 'Search Results - ARSDeveloper UK Software Agency',
                'description' => 'Search ARSDeveloper pages, services, portfolio and blog content for web development, CRM, WordPress and SEO topics.',
                'keywords' => 'search ARSDeveloper, website search UK software agency, service search',
                'type' => 'SearchResultsPage',
                'robots' => 'noindex, follow',
            ],
            '/contact.php' => [
                'title' => 'Contact ARSDeveloper — Free Audit and Project Enquiries',
                'description' => 'Contact ARSDeveloper for a free website audit, software scoping, SEO consultation, or CRM project. Response within 1 business day. No obligation.',
                'keywords' => 'contact arsdeveloper uk, free website audit uk, seo audit uk, software project enquiry uk, web development quote uk, crm consultation uk, free seo review uk',
                'type' => 'ContactPage',
                'faq_items' => [
                    [
                        'question' => 'How quickly does ARSDeveloper respond to new UK enquiries?',
                        'answer' => 'New enquiries are usually reviewed within one business day with next-step recommendations and scope clarification points.',
                    ],
                    [
                        'question' => 'Can we book a strategy call before final scope?',
                        'answer' => 'Yes. You can book a strategy call to align goals, budget band, timeline, and the best delivery path for your project.',
                    ],
                ],
            ],
            '/client-portal-access' => [
                'title' => 'Client Portal Access - ARSDeveloper UK',
                'description' => 'Secure access for approved clients to view milestones, invoice status, and project communication in one place.',
                'keywords' => 'client portal access uk, secure project portal software agency',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/client-portal-access.php' => [
                'title' => 'Client Portal Access - ARSDeveloper UK',
                'description' => 'Secure access for approved clients to view milestones, invoice status, and project communication in one place.',
                'keywords' => 'client portal access uk, secure project portal software agency',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/privacy-policy.php' => [
                'title' => 'Privacy Policy for ARSDeveloper UK Software Services',
                'description' => 'Read ARSDeveloper privacy policy for data usage, storage and rights for UK web development, CRM and SEO clients.',
                'keywords' => 'privacy policy software agency UK, ARSDeveloper data policy',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/terms-and-conditions.php' => [
                'title' => 'Terms and Conditions for ARSDeveloper UK Projects',
                'description' => 'Review ARSDeveloper terms and conditions for software development, web design, SEO and digital services.',
                'keywords' => 'terms and conditions software agency UK, ARSDeveloper service terms',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/cookie-policy.php' => [
                'title' => 'Cookie Policy for ARSDeveloper UK Website',
                'description' => 'Understand how ARSDeveloper uses cookies to improve website performance, analytics and user experience.',
                'keywords' => 'cookie policy UK agency, website cookies ARSDeveloper',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/refund-policy.php' => [
                'title' => 'Refund Policy for ARSDeveloper UK Services',
                'description' => 'Read ARSDeveloper refund policy for milestone work, subscriptions, third-party costs and billing review flow.',
                'keywords' => 'refund policy software agency uk, arsdeveloper refund policy',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/service-disclaimer.php' => [
                'title' => 'Service Disclaimer for ARSDeveloper UK',
                'description' => 'Important disclaimer about third-party dependencies, timeline assumptions, and secure payment communication.',
                'keywords' => 'service disclaimer uk, arsdeveloper legal notice',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/coming-soon.php' => [
                'title' => 'Coming Soon - ARSDeveloper',
                'description' => 'New ARSDeveloper digital experiences are coming soon.',
                'keywords' => 'coming soon software agency',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
            '/404.php' => [
                'title' => 'Page Not Found - ARSDeveloper',
                'description' => 'The page you are looking for could not be found.',
                'keywords' => '404 page',
                'type' => 'WebPage',
                'robots' => 'noindex, follow',
            ],
        ];

        $cleanToLegacySeoPath = [
            '/about' => '/about.php',
            '/services' => '/services.php',
            '/digital-marketing' => '/digital-marketing.php',
            '/web-design-development' => '/web-design-development.php',
            '/search-engine-optimization' => '/search-engine-optimization.php',
            '/design-and-branding' => '/design-and-branding.php',
            '/app-development' => '/app-development.php',
            '/software-development' => '/software-development.php',
            '/portfolio' => '/portfolio.php',
            '/portfolio-details' => '/portfolio-details.php',
            '/testimonials' => '/testimonials.php',
            '/testimonial-carousel' => '/testimonial-carousel.php',
            '/pricing' => '/pricing.php',
            '/gallery' => '/gallery.php',
            '/faq' => '/faq.php',
            '/blog' => '/blog.php',
            '/uk-growth-hub' => '/uk-growth-hub',
            '/blog-list' => '/blog-list.php',
            '/blog-details' => '/blog-details.php',
            '/contact' => '/contact.php',
            '/client-portal-access' => '/client-portal-access.php',
            '/privacy-policy' => '/privacy-policy.php',
            '/terms-and-conditions' => '/terms-and-conditions.php',
            '/cookie-policy' => '/cookie-policy.php',
            '/refund-policy' => '/refund-policy.php',
            '/service-disclaimer' => '/service-disclaimer.php',
            '/coming-soon' => '/coming-soon.php',
            '/404' => '/404.php',
        ];
        $seoLookupPath = $cleanToLegacySeoPath[$currentPath] ?? $currentPath;
        $seo = $seoPages[$seoLookupPath] ?? $seoPages[$currentPath] ?? $defaultSeo;
        if (isset($seoOverride) && is_array($seoOverride)) {
            $seo = array_merge($seo, $seoOverride);
        }

        $normalizeText = static function (string $text): string {
            return trim((string) preg_replace('/\s+/', ' ', strip_tags($text)));
        };
        $trimToWordLength = static function (string $text, int $maxLength) use ($normalizeText): string {
            $text = $normalizeText($text);
            if ($text === '' || mb_strlen($text) <= $maxLength) {
                return $text;
            }
            $trimmed = mb_substr($text, 0, $maxLength);
            $trimmed = preg_replace('/\s+\S*$/u', '', $trimmed) ?: $trimmed;
            return rtrim($trimmed, " \t\n\r\0\x0B.,;:-");
        };

        $titleMinLength = 50;
        $titleMaxLength = 60;
        $descriptionMinLength = 120;
        $descriptionMaxLength = 155;

        $seoTitleBase = $normalizeText((string) ($seo['title'] ?? $defaultSeo['title']));
        $brandSuffix = ' | ARSDeveloper UK';
        $seoTitleCandidate = stripos($seoTitleBase, 'arsdeveloper') !== false
            ? $seoTitleBase
            : ($seoTitleBase . $brandSuffix);
        $seoTitle = $trimToWordLength($seoTitleCandidate, $titleMaxLength);
        if (mb_strlen($seoTitle) < $titleMinLength) {
            $seoTitle = $trimToWordLength($seoTitle . ' - UK Software Agency', $titleMaxLength);
        }

        $seoDescriptionBase = $normalizeText((string) ($seo['description'] ?? $defaultSeo['description']));
        if (mb_strlen($seoDescriptionBase) < $descriptionMinLength) {
            $seoDescriptionBase .= ' Trusted UK team for delivery, SEO, CRM, and ongoing support.';
        }
        $seoDescription = $trimToWordLength($seoDescriptionBase, $descriptionMaxLength);
        $seoOgTitle = $trimToWordLength($normalizeText((string) ($seo['og_title'] ?? $seoTitle)), $titleMaxLength);
        $seoOgDescription = $trimToWordLength($normalizeText((string) ($seo['og_description'] ?? $seoDescription)), $descriptionMaxLength);
        $seoTwitterTitle = $trimToWordLength($normalizeText((string) ($seo['twitter_title'] ?? $seoTitle)), $titleMaxLength);
        $seoTwitterDescription = $trimToWordLength($normalizeText((string) ($seo['twitter_description'] ?? $seoDescription)), $descriptionMaxLength);
        $normalizeKeywords = static function (string $rawKeywords): string {
            $items = array_values(array_filter(array_map(
                static fn ($item) => trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $item))),
                explode(',', $rawKeywords)
            ), static fn ($item) => $item !== ''));

            $seen = [];
            $deduped = [];
            foreach ($items as $item) {
                $key = mb_strtolower($item);
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $deduped[] = mb_substr($item, 0, 56);
                if (count($deduped) >= 8) {
                    break;
                }
            }

            return implode(', ', $deduped);
        };

        $seoKeywordsRaw = (string) ($seo['keywords'] ?? $defaultSeo['keywords']);
        $seoKeywords = $normalizeKeywords($seoKeywordsRaw);
        if ($seoKeywords === '') {
            $seoKeywords = 'uk software agency, web development uk, crm development uk, seo services uk';
        }
        $seoType = $seo['type'] ?? 'WebPage';
        $queryParamsRaw = request()->query();
        $queryParams = $queryParamsRaw;
        $blockedCanonicalParams = [
            'region',
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
            'utm_id',
            'utm_source_platform',
            'utm_creative_format',
            'utm_marketing_tactic',
            'gclid',
            'fbclid',
            'msclkid',
            'dclid',
            'twclid',
            'yclid',
            'rb_clickid',
            'srsltid',
            'igshid',
            'gad_source',
            'fb_action_ids',
            'fb_action_types',
            'fb_source',
            'mc_cid',
            'mc_eid',
            '_ga',
            '_gl',
            'sort',
            'filter',
            'ref',
            'source',
            'session',
            'token',
        ];
        foreach ($blockedCanonicalParams as $blockedParam) {
            unset($queryParams[$blockedParam]);
        }
        $canonicalPathRaw = request()->getPathInfo();
        $canonicalPath = $canonicalPathRaw === '/' ? '' : $canonicalPathRaw;
        $legacyToCleanPath = [
            '/index.php' => '/',
            '/about.php' => '/about',
            '/services.php' => '/services',
            '/digital-marketing.php' => '/digital-marketing',
            '/web-design-development.php' => '/web-design-development',
            '/search-engine-optimization.php' => '/search-engine-optimization',
            '/design-and-branding.php' => '/design-and-branding',
            '/app-development.php' => '/app-development',
            '/software-development.php' => '/software-development',
            '/portfolio.php' => '/portfolio',
            '/portfolio-details.php' => '/portfolio-details',
            '/testimonials.php' => '/testimonials',
            '/testimonial-carousel.php' => '/testimonial-carousel',
            '/pricing.php' => '/pricing',
            '/gallery.php' => '/gallery',
            '/faq.php' => '/faq',
            '/404.php' => '/404',
            '/coming-soon.php' => '/coming-soon',
            '/blog.php' => '/blog',
            '/uk-growth-hub.php' => '/uk-growth-hub',
            '/uk-growth-hub' => '/uk-growth-hub',
            '/blog-list.php' => '/blog-list',
            '/blog-details.php' => '/blog-details',
            '/search.php' => '/search',
            '/contact.php' => '/contact',
            '/client-portal-access.php' => '/client-portal-access',
            '/privacy-policy.php' => '/privacy-policy',
            '/terms-and-conditions.php' => '/terms-and-conditions',
            '/cookie-policy.php' => '/cookie-policy',
            '/refund-policy.php' => '/refund-policy',
            '/service-disclaimer.php' => '/service-disclaimer',
        ];
        if ($canonicalPath !== '' && isset($legacyToCleanPath[$canonicalPath])) {
            $canonicalPath = $legacyToCleanPath[$canonicalPath];
        }
        if ($canonicalPath !== '' && str_starts_with($canonicalPath, '/index.php/')) {
            $canonicalPath = '/' . ltrim(substr($canonicalPath, strlen('/index.php/')), '/');
            $canonicalPath = $legacyToCleanPath[$canonicalPath] ?? preg_replace('/\.php$/', '', $canonicalPath);
        }
        if ($canonicalPath === '/') {
            $canonicalPath = '';
        }
        if (request()->is('blog') && isset($queryParams['page']) && (int) $queryParams['page'] <= 1) {
            unset($queryParams['page']);
        }

        $queryKeys = array_keys($queryParams);
        sort($queryKeys);
        $isBlogPaginationQuery = request()->is('blog')
            && $queryKeys === ['page']
            && (int) ($queryParams['page'] ?? 0) > 1;

        $queryString = http_build_query($queryParams);
        $allowQueryCanonical = (bool) ($seo['allow_query_canonical'] ?? false) || $isBlogPaginationQuery;
        $canonicalSuffix = ($allowQueryCanonical && $queryString) ? ('?' . $queryString) : '';
        $ukBase = app()->environment('local')
            ? rtrim((string) url('/'), '/')
            : rtrim((string) ($selectedRegion['base_url'] ?? url('/')), '/');
        $canonicalUrl = $seo['canonical'] ?? ($ukBase . $canonicalPath . $canonicalSuffix);
        $resolvedRobots = (string) ($seo['robots'] ?? 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1');
        $hasQueryParams = count($queryParamsRaw) > 0;
        $isAllowedQueryIndexable = $allowQueryCanonical && $queryString !== '';
        if ($hasQueryParams && !$isAllowedQueryIndexable) {
            $resolvedRobots = 'noindex, follow';
        }
        $siteRootUrl = $ukBase;
        $areaServed = 'United Kingdom';
        $schemaLanguage = $currentHreflang;
        $currentPathKey = $canonicalPath !== '' ? $canonicalPath : '/';

        $companyName = (string) config('company.legal_name', 'ARS Developer Ltd');
        $companyBrand = (string) config('company.brand_name', 'ARSDeveloper');
        $companyEmail = (string) config('company.email', 'info@arsdeveloper.co.uk');
        $companyPhone = (string) config('company.phone', '+447478034328');
        $companyStreetAddress = (string) config('company.street_address', '38 Elm Street');
        $companyPostalCode = (string) config('company.postal_code', 'ST6 2HN');
        $companyLocality = (string) config('company.address_locality', 'Stoke-on-Trent');
        $companyCountryCode = (string) config('company.address_country', 'GB');
        $companyCountryName = (string) config('company.country_name', 'United Kingdom');
        $companyOpeningHours = (string) config('company.opening_hours', 'Mo-Fr 09:00-18:00');
        $entityTopics = array_values(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            (array) config('company.entity_topics', [])
        )));

        $organizationSameAs = array_values(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            (array) config('company.same_as', [])
        )));
        if (count($organizationSameAs) === 0) {
            $organizationSameAs = [
                'https://www.facebook.com/arsdeveloperuk',
                'https://www.linkedin.com/company/arsdeveloperuk',
                'https://www.instagram.com/arsdeveloperuk/',
            ];
        }

        $founderConfig = (array) config('company.founder', []);
        $founderName = trim((string) ($founderConfig['name'] ?? ''));
        $founderJobTitle = trim((string) ($founderConfig['job_title'] ?? 'Founder & Technical Lead'));
        $founderDescription = trim((string) ($founderConfig['description'] ?? ''));
        $founderSameAs = array_values(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            (array) ($founderConfig['same_as'] ?? [])
        )));
        if (count($founderSameAs) === 0) {
            $defaultFounderProfile = trim((string) env('COMPANY_FOUNDER_PROFILE_URL', 'https://github.com/anastanveer'));
            if ($defaultFounderProfile !== '') {
                $founderSameAs = [$defaultFounderProfile];
            }
        }

        $internalLinkMap = [
            '/' => ['/services', '/portfolio', '/pricing', '/blog', '/uk-growth-hub', '/contact'],
            '/about' => ['/services', '/portfolio', '/contact', '/pricing', '/sectors/healthcare', '/sectors/law-firms', '/sectors/ecommerce', '/sectors/b2b'],
            '/services' => ['/web-design-development', '/search-engine-optimization', '/digital-marketing', '/app-development', '/software-development', '/pricing', '/sectors/healthcare', '/sectors/law-firms', '/sectors/ecommerce', '/sectors/b2b'],
            '/web-design-development' => ['/services', '/portfolio', '/pricing', '/contact', '/sectors/healthcare', '/sectors/law-firms'],
            '/search-engine-optimization' => ['/services', '/uk-growth-hub', '/blog', '/contact', '/sectors/healthcare', '/sectors/ecommerce', '/sectors/law-firms'],
            '/digital-marketing' => ['/services', '/pricing', '/portfolio', '/contact', '/sectors/ecommerce', '/sectors/b2b'],
            '/app-development' => ['/services', '/software-development', '/portfolio', '/contact', '/sectors/b2b'],
            '/software-development' => ['/services', '/app-development', '/portfolio', '/contact', '/sectors/b2b', '/sectors/ecommerce'],
            '/portfolio' => ['/services', '/pricing', '/contact', '/blog', '/sectors/healthcare', '/sectors/ecommerce'],
            '/pricing' => ['/services', '/portfolio', '/contact', '/faq'],
            '/blog' => ['/uk-growth-hub', '/services', '/portfolio', '/contact', '/search-engine-optimization', '/sectors/healthcare', '/sectors/law-firms', '/sectors/ecommerce', '/sectors/b2b'],
            '/uk-growth-hub' => ['/blog', '/services', '/pricing', '/contact', '/search-engine-optimization', '/web-design-development'],
            '/contact' => ['/services', '/pricing', '/portfolio', '/faq'],
            '/faq' => ['/services', '/pricing', '/contact', '/uk-growth-hub'],
            '/sectors/healthcare' => ['/services', '/web-design-development', '/search-engine-optimization', '/portfolio', '/contact', '/pricing', '/blog', '/uk-growth-hub'],
            '/sectors/law-firms' => ['/services', '/web-design-development', '/search-engine-optimization', '/portfolio', '/contact', '/pricing', '/blog', '/uk-growth-hub'],
            '/sectors/ecommerce' => ['/services', '/digital-marketing', '/search-engine-optimization', '/portfolio', '/contact', '/pricing', '/blog', '/uk-growth-hub'],
            '/sectors/b2b' => ['/services', '/software-development', '/app-development', '/portfolio', '/contact', '/pricing', '/blog', '/uk-growth-hub'],
            '/portfolio-details' => ['/portfolio', '/services', '/pricing', '/contact'],
            '/testimonials' => ['/portfolio', '/services', '/contact'],
            '/gallery' => ['/portfolio', '/services', '/contact'],
            // UK platform service pages
            '/laravel-developer-uk'       => ['/laravel-developer-london', '/php-developer-uk', '/crm-development-uk', '/app-development', '/services', '/contact', '/pricing', '/portfolio'],
            '/wordpress-developer-uk'     => ['/wordpress-developer-london', '/shopify-developer-uk', '/web-design-development', '/services', '/contact', '/pricing', '/portfolio'],
            '/shopify-developer-uk'       => ['/shopify-developer-london', '/ecommerce-developer-uk', '/wordpress-developer-uk', '/services', '/contact', '/pricing'],
            '/php-developer-uk'           => ['/laravel-developer-uk', '/php-developer-london', '/software-development', '/services', '/contact'],
            '/react-developer-uk'         => ['/nextjs-developer-uk', '/react-developer-london', '/app-development', '/services', '/contact'],
            '/nextjs-developer-uk'        => ['/react-developer-uk', '/react-developer-london', '/laravel-developer-uk', '/services', '/contact'],
            '/crm-development-uk'         => ['/laravel-developer-uk', '/software-development', '/app-development', '/services', '/contact', '/pricing'],
            '/ecommerce-developer-uk'     => ['/shopify-developer-uk', '/wordpress-developer-uk', '/web-design-development', '/search-engine-optimization', '/services', '/contact'],
            // London combos
            '/laravel-developer-london'   => ['/laravel-developer-uk', '/php-developer-london', '/react-developer-london', '/web-developer-london', '/services', '/contact'],
            '/wordpress-developer-london' => ['/wordpress-developer-uk', '/shopify-developer-london', '/web-developer-london', '/services', '/contact'],
            '/shopify-developer-london'   => ['/shopify-developer-uk', '/ecommerce-developer-uk', '/web-developer-london', '/services', '/contact'],
            '/react-developer-london'     => ['/react-developer-uk', '/nextjs-developer-uk', '/laravel-developer-london', '/web-developer-london', '/services', '/contact'],
            '/php-developer-london'       => ['/php-developer-uk', '/laravel-developer-london', '/laravel-developer-uk', '/web-developer-london', '/services', '/contact'],
            // UK city pages
            '/web-developer-london'       => ['/laravel-developer-london', '/wordpress-developer-london', '/shopify-developer-london', '/react-developer-london', '/php-developer-london', '/services', '/contact', '/portfolio'],
            '/web-developer-manchester'   => ['/laravel-developer-uk', '/wordpress-developer-uk', '/web-developer-london', '/services', '/contact', '/portfolio'],
            '/web-developer-birmingham'   => ['/laravel-developer-uk', '/wordpress-developer-uk', '/web-developer-london', '/services', '/contact', '/portfolio'],
            '/web-developer-stoke-on-trent' => ['/laravel-developer-uk', '/wordpress-developer-uk', '/services', '/contact', '/portfolio'],
            '/web-developer-leeds'        => ['/web-developer-manchester', '/web-developer-sheffield', '/laravel-developer-uk', '/wordpress-developer-uk', '/services', '/contact'],
            '/web-developer-sheffield'    => ['/web-developer-leeds', '/web-developer-manchester', '/laravel-developer-uk', '/wordpress-developer-uk', '/services', '/contact'],
            '/web-developer-bristol'      => ['/web-developer-london', '/react-developer-uk', '/laravel-developer-uk', '/shopify-developer-uk', '/services', '/contact'],
            '/web-developer-glasgow'      => ['/web-developer-edinburgh', '/laravel-developer-uk', '/wordpress-developer-uk', '/services', '/contact'],
            '/web-developer-edinburgh'    => ['/web-developer-glasgow', '/laravel-developer-uk', '/wordpress-developer-uk', '/services', '/contact'],
            // High-volume generic UK + new tech stack pages
            '/web-developer-uk'           => ['/laravel-developer-uk', '/wordpress-developer-uk', '/react-developer-uk', '/nextjs-developer-uk', '/shopify-developer-uk', '/web-developer-london', '/web-developer-manchester', '/web-developer-birmingham', '/services', '/contact', '/portfolio'],
            '/nextjs-developer-london'    => ['/nextjs-developer-uk', '/react-developer-london', '/react-developer-uk', '/laravel-developer-london', '/web-developer-london', '/services', '/contact'],
            '/typescript-developer-uk'    => ['/react-developer-uk', '/nextjs-developer-uk', '/node-developer-uk', '/app-development', '/services', '/contact'],
            '/vue-developer-uk'           => ['/react-developer-uk', '/nextjs-developer-uk', '/node-developer-uk', '/app-development', '/services', '/contact'],
            '/node-developer-uk'          => ['/laravel-developer-uk', '/react-developer-uk', '/typescript-developer-uk', '/software-development', '/services', '/contact'],
            // Additional UK city pages
            '/web-developer-liverpool'    => ['/web-developer-manchester', '/web-developer-uk', '/laravel-developer-uk', '/wordpress-developer-uk', '/services', '/contact', '/portfolio'],
            '/web-developer-cardiff'      => ['/web-developer-bristol', '/web-developer-uk', '/wordpress-developer-uk', '/laravel-developer-uk', '/services', '/contact', '/portfolio'],
            '/web-developer-nottingham'   => ['/web-developer-leicester', '/web-developer-birmingham', '/laravel-developer-uk', '/wordpress-developer-uk', '/services', '/contact'],
            '/web-developer-newcastle'    => ['/web-developer-leeds', '/web-developer-sheffield', '/web-developer-uk', '/laravel-developer-uk', '/services', '/contact'],
            '/web-developer-leicester'    => ['/web-developer-nottingham', '/web-developer-coventry', '/web-developer-birmingham', '/laravel-developer-uk', '/services', '/contact'],
            '/web-developer-coventry'     => ['/web-developer-birmingham', '/web-developer-leicester', '/web-developer-uk', '/laravel-developer-uk', '/services', '/contact'],
            // London tech stack combos
            '/vue-developer-london'       => ['/vue-developer-uk', '/react-developer-london', '/nextjs-developer-london', '/web-developer-london', '/services', '/contact'],
            '/node-developer-london'      => ['/node-developer-uk', '/react-developer-london', '/typescript-developer-london', '/laravel-developer-london', '/services', '/contact'],
            '/typescript-developer-london' => ['/typescript-developer-uk', '/react-developer-london', '/node-developer-london', '/nextjs-developer-london', '/services', '/contact'],
            '/crm-developer-london'       => ['/crm-development-uk', '/laravel-developer-london', '/laravel-developer-uk', '/web-developer-london', '/services', '/contact'],
        ];

        $cornerstoneLinks = ['/services', '/portfolio', '/pricing', '/blog', '/uk-growth-hub', '/contact', '/about', '/sectors/healthcare', '/sectors/law-firms', '/sectors/ecommerce', '/sectors/b2b'];

        $entityCoverageMap = [
            '/' => ['Web Development', 'Custom CRM Development', 'WordPress Development', 'Technical SEO', 'Digital Marketing'],
            '/services' => ['Website Development', 'SEO Services', 'CRM Systems', 'Software Engineering', 'Growth Marketing'],
            '/web-design-development' => ['Responsive Web Design', 'Conversion UX', 'Business Website Architecture'],
            '/search-engine-optimization' => ['Technical SEO', 'On-Page SEO', 'Core Web Vitals', 'Search Intent Mapping'],
            '/digital-marketing' => ['Paid Campaigns', 'Landing Page CRO', 'Lead Qualification'],
            '/app-development' => ['Web Application Development', 'MVP Delivery', 'Workflow Automation'],
            '/software-development' => ['Custom Software Development', 'API Integrations', 'Operational Automation'],
            '/portfolio' => ['Case Studies', 'Digital Delivery', 'Project Outcomes'],
            '/blog' => ['AEO', 'GEO', 'EEAT', 'Entity SEO', 'Conversion Strategy'],
            '/uk-growth-hub' => ['AEO', 'GEO', 'EEAT', 'Topic Clusters', 'Featured Snippets'],
            '/sectors/healthcare' => ['Healthcare Website Development', 'Clinic Booking Workflow', 'Medical Lead Generation', 'Private Clinic SEO'],
            '/sectors/law-firms' => ['Law Firm Website Design', 'Legal Service SEO', 'Solicitor Lead Generation', 'Consultation Funnels'],
            '/sectors/ecommerce' => ['Shopify Development UK', 'WooCommerce Growth', 'Ecommerce SEO', 'Checkout Conversion'],
            '/sectors/b2b' => ['B2B Website Development', 'CRM Workflow Automation', 'Lead Pipeline Visibility', 'Operational Dashboards'],
            // UK platform service pages
            '/laravel-developer-uk'       => ['Laravel Development', 'PHP Web Applications', 'REST API Development', 'UK GDPR Compliance', 'SaaS Platform Development'],
            '/wordpress-developer-uk'     => ['WordPress Development', 'WooCommerce UK', 'Gutenberg Block Themes', 'UK GDPR Cookie Consent', 'Core Web Vitals'],
            '/shopify-developer-uk'       => ['Shopify Development', 'UK VAT Three-Tier', 'Post-Brexit Shopify Markets', 'Klarna UK', 'Shopify 2.0'],
            '/php-developer-uk'           => ['PHP Development', 'PHP 8.3', 'Laravel PHP', 'Legacy PHP Migration', 'API Development'],
            '/react-developer-uk'         => ['React Development', 'React Dashboards', 'Single Page Applications', 'Next.js UK', 'TypeScript React'],
            '/nextjs-developer-uk'        => ['Next.js Development', 'App Router', 'Server Side Rendering', 'Headless CMS', 'React Server Components'],
            '/crm-development-uk'         => ['CRM Development', 'Custom CRM', 'GoCardless UK', 'Stripe UK', 'UK GDPR CRM'],
            '/ecommerce-developer-uk'     => ['Ecommerce Development', 'Shopify UK', 'WooCommerce UK', 'UK VAT Ecommerce', 'BNPL Integration'],
            // London combos
            '/laravel-developer-london'   => ['Laravel Development London', 'London Fintech PHP', 'Laravel SaaS London', 'PHP Developer London'],
            '/wordpress-developer-london' => ['WordPress Development London', 'WooCommerce London', 'London Website Design'],
            '/shopify-developer-london'   => ['Shopify Development London', 'London Ecommerce', 'Shopify 2.0 London'],
            '/react-developer-london'     => ['React Development London', 'London SaaS Frontend', 'Next.js London', 'Fintech Dashboard London'],
            '/php-developer-london'       => ['PHP Development London', 'Laravel London', 'Legacy PHP Migration London'],
            // UK city pages
            '/web-developer-london'       => ['Web Development London', 'London Website Design', 'London SEO', 'London Software Agency'],
            '/web-developer-manchester'   => ['Web Development Manchester', 'Manchester Website Design', 'Manchester SEO', 'Northern Powerhouse Digital'],
            '/web-developer-birmingham'   => ['Web Development Birmingham', 'Birmingham Website Design', 'Birmingham SEO', 'West Midlands Digital'],
            '/web-developer-stoke-on-trent' => ['Web Development Stoke-on-Trent', 'Staffordshire Website Design', 'ARS Developer Stoke'],
            '/web-developer-leeds'        => ['Web Development Leeds', 'Leeds Website Design', 'Leeds SEO', 'Yorkshire Digital Agency'],
            '/web-developer-sheffield'    => ['Web Development Sheffield', 'Sheffield Website Design', 'Sheffield SEO', 'South Yorkshire Digital'],
            '/web-developer-bristol'      => ['Web Development Bristol', 'Bristol Website Design', 'Silicon Gorge', 'Southwest UK Digital'],
            '/web-developer-glasgow'      => ['Web Development Glasgow', 'Glasgow Website Design', 'Scottish Digital Agency', 'Glasgow SEO'],
            '/web-developer-edinburgh'    => ['Web Development Edinburgh', 'Edinburgh Website Design', 'Edinburgh SEO', 'Scottish Capital Digital'],
            // Additional UK city pages
            '/web-developer-liverpool'    => ['Web Development Liverpool', 'Liverpool Website Design', 'Liverpool SEO', 'Merseyside Digital Agency'],
            '/web-developer-cardiff'      => ['Web Development Cardiff', 'Cardiff Website Design', 'Welsh Web Developer', 'Bilingual Welsh English Website'],
            '/web-developer-nottingham'   => ['Web Development Nottingham', 'Nottingham Website Design', 'East Midlands Web Developer', 'Nottingham SEO'],
            '/web-developer-newcastle'    => ['Web Development Newcastle', 'Newcastle Website Design', 'Northeast England Web Developer', 'Tyne and Wear Digital'],
            '/web-developer-leicester'    => ['Web Development Leicester', 'Leicester Website Design', 'Leicestershire Web Developer', 'East Midlands Digital'],
            '/web-developer-coventry'     => ['Web Development Coventry', 'Coventry Website Design', 'West Midlands Web Developer', 'Coventry SEO'],
            // London tech stack combos
            '/vue-developer-london'       => ['Vue.js Development London', 'London Vue 3 Composition API', 'Nuxt.js London', 'London SaaS Frontend Vue'],
            '/node-developer-london'      => ['Node.js Development London', 'London Express API', 'London Microservices Node.js', 'Fintech Node.js London'],
            '/typescript-developer-london' => ['TypeScript Development London', 'London TypeScript Strict Mode', 'TypeScript React London', 'London SaaS TypeScript'],
            '/crm-developer-london'       => ['CRM Development London', 'Custom CRM London', 'London Client Portal', 'Bespoke CRM System London'],
            // High-volume generic UK + new tech stack pages
            '/web-developer-uk'           => ['Web Development UK', 'UK Website Developer', 'UK Web Agency', 'UK GDPR Web Development', 'Core Web Vitals UK'],
            '/nextjs-developer-london'    => ['Next.js Development London', 'London React Framework', 'App Router London', 'Headless CMS London', 'London SSR Developer'],
            '/typescript-developer-uk'    => ['TypeScript Development UK', 'TypeScript React', 'TypeScript Node.js', 'Typed JavaScript UK', 'TypeScript API Development'],
            '/vue-developer-uk'           => ['Vue.js Development UK', 'Vue 3 Composition API', 'Nuxt.js UK', 'Vue Dashboards UK', 'Progressive Web Apps Vue'],
            '/node-developer-uk'          => ['Node.js Development UK', 'Express.js UK', 'REST API Node.js', 'Server-Side JavaScript UK', 'Node.js Microservices'],
        ];

        $serviceSchemaCatalog = [
            'web-design-development' => [
                'name' => 'Web Design and Development Services UK',
                'description' => 'Conversion-focused website design and web development for UK businesses with fast performance and structured lead flow.',
                'path' => '/web-design-development',
                'type' => 'Web Development',
            ],
            'search-engine-optimization' => [
                'name' => 'Technical SEO and Search Growth Services UK',
                'description' => 'Technical SEO, entity optimization, and buyer-intent content implementation for UK visibility and qualified enquiries.',
                'path' => '/search-engine-optimization',
                'type' => 'SEO Services',
            ],
            'digital-marketing' => [
                'name' => 'Digital Marketing and Paid Campaign Services UK',
                'description' => 'Lead-focused paid campaigns, funnel optimization, and conversion reporting for UK service and ecommerce businesses.',
                'path' => '/digital-marketing',
                'type' => 'Digital Marketing',
            ],
            'app-development' => [
                'name' => 'App Development Services UK',
                'description' => 'Custom web app and MVP development for UK teams that need scalable product workflows and secure delivery.',
                'path' => '/app-development',
                'type' => 'Application Development',
            ],
            'software-development' => [
                'name' => 'Custom Software Development Services UK',
                'description' => 'Bespoke software systems, integrations, and workflow automation designed for UK operational growth.',
                'path' => '/software-development',
                'type' => 'Custom Software Development',
            ],
            'design-and-branding' => [
                'name' => 'Design and Branding Services UK',
                'description' => 'Brand direction, UI/UX design, and digital identity systems that improve trust and conversion quality.',
                'path' => '/design-and-branding',
                'type' => 'Brand Strategy and UX Design',
            ],
            // ── New UK service pages ─────────────────────────────────
            'laravel-developer-uk' => [
                'name' => 'Laravel Developer UK — Custom Applications and APIs',
                'description' => 'Custom Laravel web applications, REST APIs, CRM systems, and SaaS platforms for UK businesses. UK GDPR compliant. GBP milestone contracts.',
                'path' => '/laravel-developer-uk',
                'type' => 'Laravel Development',
            ],
            'wordpress-developer-uk' => [
                'name' => 'WordPress Developer UK — Custom Themes and WooCommerce',
                'description' => 'Custom WordPress sites, Gutenberg block themes, WooCommerce with UK VAT, and ICO-compliant cookie consent for UK businesses.',
                'path' => '/wordpress-developer-uk',
                'type' => 'WordPress Development',
            ],
            'shopify-developer-uk' => [
                'name' => 'Shopify Developer UK — Custom Themes and UK VAT',
                'description' => 'Shopify 2.0 custom themes, UK VAT three-tier, post-Brexit Shopify Markets, and Klarna UK integration for UK ecommerce brands.',
                'path' => '/shopify-developer-uk',
                'type' => 'Shopify Development',
            ],
            'php-developer-uk' => [
                'name' => 'PHP Developer UK — PHP 8.3 and Laravel',
                'description' => 'PHP 8.3 and Laravel development for UK businesses — legacy migration, REST APIs, and UK GDPR compliant system architecture.',
                'path' => '/php-developer-uk',
                'type' => 'PHP Development',
            ],
            'react-developer-uk' => [
                'name' => 'React Developer UK — SPAs, Dashboards and Next.js',
                'description' => 'React and Next.js web applications for UK businesses — client dashboards, SaaS frontends, and API-connected single-page applications.',
                'path' => '/react-developer-uk',
                'type' => 'React Development',
            ],
            'nextjs-developer-uk' => [
                'name' => 'Next.js Developer UK — App Router and SSR',
                'description' => 'Next.js 15 app router development with React Server Components, streaming SSR, and headless CMS integration for UK businesses.',
                'path' => '/nextjs-developer-uk',
                'type' => 'Next.js Development',
            ],
            'crm-development-uk' => [
                'name' => 'CRM Development UK — Custom Business CRM Systems',
                'description' => 'Custom CRM systems for UK businesses built on Laravel — client portals, billing integration, UK GDPR compliance, and milestone contracts.',
                'path' => '/crm-development-uk',
                'type' => 'CRM Development',
            ],
            'ecommerce-developer-uk' => [
                'name' => 'Ecommerce Developer UK — Shopify, WooCommerce and Custom',
                'description' => 'UK ecommerce development across Shopify, WooCommerce, and custom Laravel — UK VAT, post-Brexit EU markets, and BNPL integration.',
                'path' => '/ecommerce-developer-uk',
                'type' => 'Ecommerce Development',
            ],
            // ── New tech stack pages ─────────────────────────────────
            'web-developer-uk' => [
                'name' => 'Web Developer UK — Custom Websites, Web Apps & SEO',
                'description' => 'UK web developer delivering custom websites, Laravel web applications, CRM systems, and local SEO for businesses across England, Scotland, and Wales.',
                'path' => '/web-developer-uk',
                'type' => 'Web Development',
            ],
            'typescript-developer-uk' => [
                'name' => 'TypeScript Developer UK — Typed Frontend and API Development',
                'description' => 'TypeScript development for UK businesses — typed React frontends, Node.js APIs, and full-stack TypeScript applications with strict type safety.',
                'path' => '/typescript-developer-uk',
                'type' => 'TypeScript Development',
            ],
            'vue-developer-uk' => [
                'name' => 'Vue.js Developer UK — Vue 3 and Nuxt.js Applications',
                'description' => 'Vue 3 and Nuxt.js development for UK businesses — dashboards, SPAs, progressive web apps, and headless CMS frontends.',
                'path' => '/vue-developer-uk',
                'type' => 'Vue.js Development',
            ],
            'node-developer-uk' => [
                'name' => 'Node.js Developer UK — APIs, Microservices and Server-Side JS',
                'description' => 'Node.js development for UK businesses — REST APIs, Express.js backends, real-time applications, and microservices architecture.',
                'path' => '/node-developer-uk',
                'type' => 'Node.js Development',
            ],
            // ── London tech stack combos ──────────────────────────────────
            'vue-developer-london' => [
                'name' => 'Vue Developer London — Vue 3, Nuxt.js & London SaaS',
                'description' => 'Vue 3 and Nuxt.js development for London businesses — Composition API, TypeScript strict mode, and Laravel API integration.',
                'path' => '/vue-developer-london',
                'type' => 'Vue.js Development',
            ],
            'node-developer-london' => [
                'name' => 'Node.js Developer London — APIs, Microservices & Real-Time',
                'description' => 'Node.js REST APIs, microservices, and real-time WebSocket systems for London fintech and SaaS businesses.',
                'path' => '/node-developer-london',
                'type' => 'Node.js Development',
            ],
            'typescript-developer-london' => [
                'name' => 'TypeScript Developer London — Type-Safe React, Node.js & SaaS',
                'description' => 'TypeScript strict mode development for London SaaS, fintech, and React/Node.js applications.',
                'path' => '/typescript-developer-london',
                'type' => 'TypeScript Development',
            ],
            'crm-developer-london' => [
                'name' => 'CRM Developer London — Custom CRM Systems & Business Portals',
                'description' => 'Custom Laravel CRM systems, client portals, and GoCardless/Stripe billing integration for London businesses.',
                'path' => '/crm-developer-london',
                'type' => 'CRM Development',
            ],
        ];

        $servicePageMap = [
            '/services' => array_keys($serviceSchemaCatalog),
            '/web-design-development' => ['web-design-development'],
            '/search-engine-optimization' => ['search-engine-optimization'],
            '/digital-marketing' => ['digital-marketing'],
            '/app-development' => ['app-development'],
            '/software-development' => ['software-development'],
            '/design-and-branding' => ['design-and-branding'],
            '/sectors/healthcare' => ['web-design-development', 'search-engine-optimization'],
            '/sectors/law-firms' => ['web-design-development', 'search-engine-optimization'],
            '/sectors/ecommerce' => ['web-design-development', 'digital-marketing', 'search-engine-optimization'],
            '/sectors/b2b' => ['software-development', 'app-development', 'search-engine-optimization'],
            // UK service page map entries
            '/laravel-developer-uk'       => ['laravel-developer-uk', 'php-developer-uk'],
            '/wordpress-developer-uk'     => ['wordpress-developer-uk', 'web-design-development'],
            '/shopify-developer-uk'       => ['shopify-developer-uk', 'ecommerce-developer-uk'],
            '/php-developer-uk'           => ['php-developer-uk', 'laravel-developer-uk'],
            '/react-developer-uk'         => ['react-developer-uk', 'nextjs-developer-uk'],
            '/nextjs-developer-uk'        => ['nextjs-developer-uk', 'react-developer-uk'],
            '/crm-development-uk'         => ['crm-development-uk', 'software-development'],
            '/ecommerce-developer-uk'     => ['ecommerce-developer-uk', 'shopify-developer-uk'],
            '/laravel-developer-london'   => ['laravel-developer-uk', 'php-developer-uk'],
            '/wordpress-developer-london' => ['wordpress-developer-uk', 'web-design-development'],
            '/shopify-developer-london'   => ['shopify-developer-uk', 'ecommerce-developer-uk'],
            '/react-developer-london'     => ['react-developer-uk', 'nextjs-developer-uk'],
            '/php-developer-london'       => ['php-developer-uk', 'laravel-developer-uk'],
            '/web-developer-london'       => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-manchester'   => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-birmingham'   => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-stoke-on-trent' => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-leeds'        => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-sheffield'    => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-bristol'      => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-glasgow'      => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-edinburgh'    => ['web-design-development', 'search-engine-optimization'],
            // High-volume generic UK + new tech stack pages
            '/web-developer-uk'           => ['web-developer-uk', 'web-design-development', 'search-engine-optimization'],
            '/nextjs-developer-london'    => ['nextjs-developer-uk', 'react-developer-uk'],
            '/typescript-developer-uk'    => ['typescript-developer-uk', 'react-developer-uk'],
            '/vue-developer-uk'           => ['vue-developer-uk', 'react-developer-uk'],
            '/node-developer-uk'          => ['node-developer-uk', 'software-development'],
            // Additional UK city pages
            '/web-developer-liverpool'    => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-cardiff'      => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-nottingham'   => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-newcastle'    => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-leicester'    => ['web-design-development', 'search-engine-optimization'],
            '/web-developer-coventry'     => ['web-design-development', 'search-engine-optimization'],
            // London tech stack combos
            '/vue-developer-london'       => ['vue-developer-uk', 'react-developer-uk'],
            '/node-developer-london'      => ['node-developer-uk', 'software-development'],
            '/typescript-developer-london' => ['typescript-developer-uk', 'react-developer-uk'],
            '/crm-developer-london'       => ['crm-development-uk', 'laravel-developer-uk'],
        ];

        $schemaGraph = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                '@id' => $siteRootUrl . '#organization',
                'name' => $companyName,
                'url' => $siteRootUrl,
                'email' => $companyEmail,
                'telephone' => $companyPhone,
                'sameAs' => $organizationSameAs,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $companyStreetAddress,
                    'postalCode' => $companyPostalCode,
                    'addressLocality' => $companyLocality,
                    'addressCountry' => $companyCountryCode,
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                '@id' => $siteRootUrl . '#website',
                'url' => $siteRootUrl,
                'name' => $companyBrand,
                'publisher' => ['@id' => $siteRootUrl . '#organization'],
                'inLanguage' => $schemaLanguage,
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => $siteRootUrl . '/search?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'ProfessionalService',
                '@id' => $siteRootUrl . '#local-business',
                'name' => $companyName,
                'url' => $siteRootUrl,
                'image' => url('/assets/images/resources/ars-logo-dark.png'),
                'telephone' => $companyPhone,
                'email' => $companyEmail,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $companyStreetAddress,
                    'postalCode' => $companyPostalCode,
                    'addressLocality' => $companyLocality,
                    'addressCountry' => $companyCountryCode,
                ],
                'areaServed' => $areaServed,
                'serviceType' => [
                    'Web Development',
                    'CRM Development',
                    'WordPress Development',
                    'SEO Services',
                    'Custom Software Development',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                '@id' => $siteRootUrl . '#localbusiness',
                'name' => $companyName,
                'url' => $siteRootUrl,
                'image' => url('/assets/images/resources/ars-logo-dark.png'),
                'telephone' => $companyPhone,
                'email' => $companyEmail,
                'priceRange' => 'GBP',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $companyStreetAddress,
                    'postalCode' => $companyPostalCode,
                    'addressLocality' => $companyLocality,
                    'addressCountry' => $companyCountryCode,
                ],
                'areaServed' => [
                    ['@type' => 'Country', 'name' => $companyCountryName],
                    ['@type' => 'City', 'name' => 'Stoke-on-Trent'],
                    ['@type' => 'AdministrativeArea', 'name' => 'Staffordshire'],
                    ['@type' => 'City', 'name' => 'Manchester'],
                    ['@type' => 'City', 'name' => 'Birmingham'],
                    ['@type' => 'City', 'name' => 'London'],
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => '53.0027',
                    'longitude' => '-2.1794',
                ],
                'hasMap' => 'https://maps.google.com/?q=Stoke-on-Trent,Staffordshire,UK',
                'parentOrganization' => ['@id' => $siteRootUrl . '#organization'],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => $seoType,
                '@id' => $canonicalUrl . '#webpage',
                'url' => $canonicalUrl,
                'name' => $seoTitle,
                'description' => $seoDescription,
                'inLanguage' => $schemaLanguage,
                'isPartOf' => ['@id' => $siteRootUrl . '#website'],
            ],
        ];

        if (!empty($entityTopics)) {
            $schemaGraph[0]['knowsAbout'] = $entityTopics;
        }

        $foundingDate = trim((string) config('company.incorporation_date', ''));
        if ($foundingDate !== '') {
            $schemaGraph[0]['foundingDate'] = $foundingDate;
        }

        $schemaGraph[0]['legalName'] = $companyName;
        $schemaGraph[0]['description'] = 'UK software agency for web development, CRM systems, SEO implementation, and conversion-focused delivery.';
        $schemaGraph[0]['slogan'] = 'Milestone-Based UK Web Development You Can Hold To Account';
        $schemaGraph[0]['numberOfEmployees'] = ['@type' => 'QuantitativeValue', 'minValue' => 1, 'maxValue' => 10];
        $schemaGraph[0]['identifier'] = [
            ['@type' => 'PropertyValue', 'name' => 'Companies House', 'value' => '17039150', 'url' => 'https://find-and-update.company-information.service.gov.uk/company/17039150'],
        ];
        $schemaGraph[0]['knowsLanguage'] = [['@type' => 'Language', 'name' => 'English', 'alternateName' => 'en']];
        $schemaGraph[0]['paymentAccepted'] = ['Bank Transfer', 'Stripe', 'GoCardless'];
        $schemaGraph[0]['currenciesAccepted'] = 'GBP';

        // makesOffer — all 22 service pages + core service pages for strong entity signal
        $schemaGraph[0]['makesOffer'] = array_values(array_map(
            static fn (string $key, array $s) => [
                '@type'       => 'Offer',
                '@id'         => $siteRootUrl . $s['path'] . '#offer',
                'name'        => $s['name'],
                'description' => $s['description'],
                'url'         => $siteRootUrl . $s['path'],
                'priceCurrency' => 'GBP',
                'itemOffered' => [
                    '@type'       => 'Service',
                    'name'        => $s['name'],
                    'serviceType' => $s['type'],
                    'areaServed'  => 'United Kingdom',
                ],
            ],
            array_keys($serviceSchemaCatalog),
            $serviceSchemaCatalog
        ));
        $schemaGraph[0]['logo'] = [
            '@type' => 'ImageObject',
            'url' => url('/assets/images/resources/ars-logo-dark.png'),
        ];
        $schemaGraph[0]['areaServed'] = [
            [
                '@type' => 'Country',
                'name' => $companyCountryName,
            ],
        ];
        $schemaGraph[0]['contactPoint'] = [
            [
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'telephone' => $companyPhone,
                'email' => $companyEmail,
                'availableLanguage' => ['English'],
                'areaServed' => 'GB',
            ],
            [
                '@type' => 'ContactPoint',
                'contactType' => 'sales',
                'telephone' => $companyPhone,
                'email' => $companyEmail,
                'availableLanguage' => ['English'],
                'areaServed' => 'GB',
            ],
        ];

        $schemaGraph[3]['openingHoursSpecification'] = [
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'opens' => '09:00',
                'closes' => '18:00',
            ],
        ];
        $schemaGraph[3]['openingHours'] = $companyOpeningHours;
        $schemaGraph[3]['sameAs'] = $organizationSameAs;
        $schemaGraph[2]['openingHoursSpecification'] = $schemaGraph[3]['openingHoursSpecification'];
        $schemaGraph[2]['openingHours'] = $companyOpeningHours;
        $schemaGraph[2]['sameAs'] = $organizationSameAs;

        $schemaGraph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'SiteNavigationElement',
            '@id' => $siteRootUrl . '#site-navigation',
            'name' => ['Home', 'About', 'Services', 'Portfolio', 'Pricing', 'Blog', 'UK SEO Growth Hub', 'Contact'],
            'url' => [
                $siteRootUrl . '/',
                $siteRootUrl . '/about',
                $siteRootUrl . '/services',
                $siteRootUrl . '/portfolio',
                $siteRootUrl . '/pricing',
                $siteRootUrl . '/blog',
                $siteRootUrl . '/uk-growth-hub',
                $siteRootUrl . '/contact',
            ],
        ];
        $schemaGraph[1]['hasPart'] = [
            ['@id' => $siteRootUrl . '#site-navigation'],
            ['@id' => $siteRootUrl . '#offer-catalog'],
        ];

        $pillarPageLinks = [
            $siteRootUrl . '/',
            $siteRootUrl . '/services',
            $siteRootUrl . '/sectors/healthcare',
            $siteRootUrl . '/sectors/law-firms',
            $siteRootUrl . '/sectors/ecommerce',
            $siteRootUrl . '/sectors/b2b',
            $siteRootUrl . '/portfolio',
            $siteRootUrl . '/pricing',
            $siteRootUrl . '/blog',
            $siteRootUrl . '/uk-growth-hub',
            $siteRootUrl . '/contact',
        ];
        $schemaGraph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            '@id' => $siteRootUrl . '#pillar-page-map',
            'name' => 'ARSDeveloper UK Cornerstone Pages',
            'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
            'itemListElement' => collect($pillarPageLinks)->values()->map(
                static fn ($url, $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => $url,
                ]
            )->all(),
        ];

        $offerCatalogId = $siteRootUrl . '#offer-catalog';
        $offerCatalogItems = collect($serviceSchemaCatalog)->values()->map(
            static fn ($service, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Offer',
                    'name' => $service['name'],
                    'url' => $siteRootUrl . $service['path'],
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $service['name'],
                        'serviceType' => $service['type'],
                    ],
                ],
            ]
        )->all();

        $schemaGraph[] = [
            '@context' => 'https://schema.org',
            '@type' => 'OfferCatalog',
            '@id' => $offerCatalogId,
            'name' => 'ARSDeveloper UK Service Offer Catalog',
            'url' => $siteRootUrl . '/services',
            'itemListElement' => $offerCatalogItems,
        ];
        $schemaGraph[0]['hasOfferCatalog'] = ['@id' => $offerCatalogId];
        $schemaGraph[1]['about'] = ['@id' => $siteRootUrl . '#organization'];
        $schemaGraph[4]['about'][] = ['@id' => $siteRootUrl . '#organization'];

        if ($founderName !== '') {
            $founderNode = [
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                '@id' => $siteRootUrl . '#founder',
                'name' => $founderName,
                'jobTitle' => $founderJobTitle,
                'worksFor' => ['@id' => $siteRootUrl . '#organization'],
            ];
            if ($founderDescription !== '') {
                $founderNode['description'] = $founderDescription;
            }
            if (!empty($founderSameAs)) {
                $founderNode['sameAs'] = $founderSameAs;
            }
            $schemaGraph[0]['founder'] = ['@id' => $siteRootUrl . '#founder'];
            $schemaGraph[2]['founder'] = ['@id' => $siteRootUrl . '#founder'];
            $schemaGraph[] = $founderNode;
        }

        if ($currentPathKey === '/about' && $founderName !== '') {
            $schemaGraph[4]['mainEntity'] = ['@id' => $siteRootUrl . '#founder'];
            $schemaGraph[4]['about'][] = ['@id' => $siteRootUrl . '#founder'];
        }

        $clusterLinks = [
            $siteRootUrl . '/uk-growth-hub',
            $siteRootUrl . '/blog/uk-seo-growth-system-2026-aeo-geo-eeat-guide',
            $siteRootUrl . '/blog/how-uk-service-businesses-generate-more-leads-with-conversion-focused-websites',
            $siteRootUrl . '/blog/technical-seo-checklist-for-uk-websites-before-launch',
            $siteRootUrl . '/blog/landing-page-cro-for-uk-campaigns-7-fixes-that-increase-enquiries',
        ];
        if ($currentPathKey === '/uk-growth-hub' || $currentPathKey === '/blog' || str_starts_with($currentPathKey, '/blog/')) {
            $schemaGraph[] = [
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                '@id' => $siteRootUrl . '#uk-growth-cluster',
                'name' => 'UK SEO Growth Topic Cluster',
                'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
                'numberOfItems' => count($clusterLinks),
                'itemListElement' => collect($clusterLinks)->values()->map(
                    static fn ($url, $index) => [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'url' => $url,
                    ]
                )->all(),
            ];
        }

        $pathLookupKey = $currentPathKey;
        if (!array_key_exists($pathLookupKey, $internalLinkMap)) {
            if (str_starts_with($pathLookupKey, '/blog/')) {
                $pathLookupKey = '/blog';
            } elseif (str_starts_with($pathLookupKey, '/portfolio-details/')) {
                $pathLookupKey = '/portfolio-details';
            } elseif (str_starts_with($pathLookupKey, '/sectors/')) {
                $pathLookupKey = '/services';
            }
        }

        $entityLookupKey = $currentPathKey;
        if (!array_key_exists($entityLookupKey, $entityCoverageMap)) {
            if (str_starts_with($entityLookupKey, '/blog/')) {
                $entityLookupKey = '/blog';
            } elseif (str_starts_with($entityLookupKey, '/portfolio-details/')) {
                $entityLookupKey = '/portfolio';
            } elseif (str_starts_with($entityLookupKey, '/sectors/')) {
                $entityLookupKey = '/services';
            }
        }

        $pageEntityCoverage = $entityCoverageMap[$entityLookupKey] ?? [];
        if (!empty($pageEntityCoverage)) {
            $schemaGraph[4]['about'] = collect($pageEntityCoverage)->map(
                static fn ($name) => ['@type' => 'Thing', 'name' => $name]
            )->all();
        }

        $significantLinks = $internalLinkMap[$pathLookupKey] ?? [];
        if (str_starts_with($currentPathKey, '/blog/')) {
            $significantLinks = array_merge($significantLinks, $clusterLinks);
        }
        $significantLinks = array_merge($significantLinks, $cornerstoneLinks);
        if (!empty($seo['related_links']) && is_array($seo['related_links'])) {
            $significantLinks = array_merge($significantLinks, $seo['related_links']);
        }
        $significantLinks = array_values(array_unique(array_filter(array_map(
            static function ($path) use ($siteRootUrl) {
                $path = trim((string) $path);
                if ($path === '') {
                    return null;
                }
                return str_starts_with($path, 'http') ? $path : ($siteRootUrl . '/' . ltrim($path, '/'));
            },
            $significantLinks
        ))));
        if (!empty($significantLinks)) {
            $schemaGraph[4]['significantLink'] = $significantLinks;
        }

        // Speakable — mark description for Google Assistant + AI Overview citations
        $schemaGraph[4]['speakable'] = [
            '@type'       => 'SpeakableSpecification',
            'cssSelector' => ['.svc-hero__title', '.svc-hero__desc', 'h1', '.sector-hero__title', '.sector-hero__summary'],
        ];

        $activeServiceKeys = $servicePageMap[$currentPathKey] ?? [];
        if (!empty($activeServiceKeys)) {
            $serviceNodes = [];
            foreach ($activeServiceKeys as $serviceKey) {
                $serviceData = $serviceSchemaCatalog[$serviceKey] ?? null;
                if (!$serviceData) {
                    continue;
                }

                $serviceNodes[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    '@id' => $siteRootUrl . '#service-' . $serviceKey,
                    'name' => $serviceData['name'],
                    'description' => $serviceData['description'],
                    'serviceType' => $serviceData['type'],
                    'url' => $siteRootUrl . $serviceData['path'],
                    'provider' => ['@id' => $siteRootUrl . '#organization'],
                    'areaServed' => [
                        '@type' => 'Country',
                        'name' => $companyCountryName,
                    ],
                    'audience' => [
                        '@type' => 'BusinessAudience',
                        'audienceType' => 'UK businesses',
                    ],
                    'availableChannel' => [
                        [
                            '@type' => 'ServiceChannel',
                            'serviceUrl' => $siteRootUrl . $serviceData['path'],
                        ],
                    ],
                ];
            }

            if (!empty($serviceNodes)) {
                $schemaGraph = array_merge($schemaGraph, $serviceNodes);
                $schemaGraph[4]['mainEntity'] = ['@id' => $serviceNodes[0]['@id']];
            }

            if ($currentPathKey === '/services' && !empty($serviceNodes)) {
                $schemaGraph[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    '@id' => $siteRootUrl . '#service-catalog',
                    'name' => 'ARSDeveloper UK Service Catalog',
                    'itemListElement' => collect($serviceNodes)->values()->map(
                        static fn ($item, $index) => [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'url' => $item['url'],
                            'name' => $item['name'],
                        ]
                    )->all(),
                ];
            }
        }

        $breadcrumbPath = trim((string) ($canonicalPath ?: ''), '/');
        $breadcrumbMap = [
            'about' => 'About',
            'services' => 'Services',
            'digital-marketing' => 'Digital Marketing',
            'web-design-development' => 'Web Design & Development',
            'search-engine-optimization' => 'SEO Services',
            'design-and-branding' => 'Design & Branding',
            'app-development' => 'App Development',
            'software-development' => 'Software Development',
            'portfolio' => 'Portfolio',
            'portfolio-details' => 'Project Details',
            'testimonials' => 'Testimonials',
            'testimonial-carousel' => 'Testimonials',
            'pricing' => 'Pricing',
            'gallery' => 'Gallery',
            'faq' => 'FAQs',
            'blog' => 'Blog',
            'uk-growth-hub' => 'UK SEO Growth Hub',
            'contact' => 'Contact',
            'client-portal-access' => 'Client Portal Access',
            'privacy-policy' => 'Privacy Policy',
            'terms-and-conditions' => 'Terms and Conditions',
            'cookie-policy' => 'Cookie Policy',
            'refund-policy' => 'Refund Policy',
            'service-disclaimer' => 'Service Disclaimer',
            'search' => 'Search',
            // UK service pages — proper breadcrumb labels
            'laravel-developer-uk'        => 'Laravel Developer UK',
            'wordpress-developer-uk'      => 'WordPress Developer UK',
            'shopify-developer-uk'        => 'Shopify Developer UK',
            'php-developer-uk'            => 'PHP Developer UK',
            'react-developer-uk'          => 'React Developer UK',
            'nextjs-developer-uk'         => 'Next.js Developer UK',
            'crm-development-uk'          => 'CRM Development UK',
            'ecommerce-developer-uk'      => 'Ecommerce Developer UK',
            'laravel-developer-london'    => 'Laravel Developer London',
            'wordpress-developer-london'  => 'WordPress Developer London',
            'shopify-developer-london'    => 'Shopify Developer London',
            'react-developer-london'      => 'React Developer London',
            'php-developer-london'        => 'PHP Developer London',
            'web-developer-london'        => 'Web Developer London',
            'web-developer-manchester'    => 'Web Developer Manchester',
            'web-developer-birmingham'    => 'Web Developer Birmingham',
            'web-developer-stoke-on-trent' => 'Web Developer Stoke-on-Trent',
            'web-developer-leeds'         => 'Web Developer Leeds',
            'web-developer-sheffield'     => 'Web Developer Sheffield',
            'web-developer-bristol'       => 'Web Developer Bristol',
            'web-developer-glasgow'       => 'Web Developer Glasgow',
            'web-developer-edinburgh'     => 'Web Developer Edinburgh',
            // High-volume generic UK + new tech stack pages
            'web-developer-uk'              => 'Web Developer UK',
            'nextjs-developer-london'       => 'Next.js Developer London',
            'typescript-developer-uk'       => 'TypeScript Developer UK',
            'vue-developer-uk'              => 'Vue.js Developer UK',
            'node-developer-uk'             => 'Node.js Developer UK',
            // Additional UK city pages
            'web-developer-liverpool'       => 'Web Developer Liverpool',
            'web-developer-cardiff'         => 'Web Developer Cardiff',
            'web-developer-nottingham'      => 'Web Developer Nottingham',
            'web-developer-newcastle'       => 'Web Developer Newcastle',
            'web-developer-leicester'       => 'Web Developer Leicester',
            'web-developer-coventry'        => 'Web Developer Coventry',
            // London tech stack combos
            'vue-developer-london'          => 'Vue.js Developer London',
            'node-developer-london'         => 'Node.js Developer London',
            'typescript-developer-london'   => 'TypeScript Developer London',
            'crm-developer-london'          => 'CRM Developer London',
        ];

        $breadcrumbItems = [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => $siteRootUrl,
            ],
        ];

        if ($breadcrumbPath !== '') {
            $segments = array_values(array_filter(explode('/', $breadcrumbPath), fn ($segment) => trim((string) $segment) !== ''));
            $pathAccumulator = '';
            $position = 2;

            foreach ($segments as $segment) {
                $pathAccumulator .= '/' . $segment;
                $segmentKey = strtolower(trim((string) $segment));
                $segmentLabel = $breadcrumbMap[$segmentKey]
                    ?? ucfirst(str_replace(['-', '_'], ' ', $segmentKey));

                $isLast = $position === (count($segments) + 1);
                if ($isLast && isset($seoTitleBase) && is_string($seoTitleBase) && trim($seoTitleBase) !== '') {
                    $segmentLabel = trim((string) $seoTitleBase);
                }

                $breadcrumbItems[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $segmentLabel,
                    'item' => $siteRootUrl . $pathAccumulator,
                ];
                $position++;
            }
        }

        if (count($breadcrumbItems) >= 1) {
            $breadcrumbId = $canonicalUrl . '#breadcrumb';
            $schemaGraph[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                '@id' => $breadcrumbId,
                'itemListElement' => $breadcrumbItems,
            ];
            $schemaGraph[4]['breadcrumb'] = ['@id' => $breadcrumbId];
        }

        $faqItems = $seo['faq_items'] ?? [];
        $isIndexableForFaqSchema = !str_contains(strtolower((string) $resolvedRobots), 'noindex');
        if ($isIndexableForFaqSchema && is_array($faqItems) && count($faqItems) > 0) {
            $faqEntities = [];
            foreach ($faqItems as $faqItem) {
                $question = trim((string) ($faqItem['question'] ?? ''));
                $answer = trim((string) ($faqItem['answer'] ?? ''));
                if ($question === '' || $answer === '') {
                    continue;
                }

                $faqEntities[] = [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $answer,
                    ],
                ];
            }

            if (count($faqEntities) > 0) {
                $schemaGraph[] = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    '@id' => $canonicalUrl . '#faq',
                    'mainEntity' => $faqEntities,
                ];
            }
        }

        $articleSchemaData = is_array($seo['article'] ?? null) ? $seo['article'] : [];
        if (strtolower((string) $seoType) === 'article' || count($articleSchemaData) > 0) {
            $articleHeadline = trim((string) ($articleSchemaData['headline'] ?? $seoTitle));
            $articleDescription = trim((string) ($articleSchemaData['description'] ?? $seoDescription));
            $articleImage = (string) ($articleSchemaData['image'] ?? ($seo['og_image'] ?? url('/assets/images/resources/ars-logo-dark.png')));
            $articlePublished = $articleSchemaData['datePublished'] ?? null;
            $articleModified = $articleSchemaData['dateModified'] ?? $articlePublished;
            $articleAuthor = trim((string) ($articleSchemaData['author'] ?? $companyName));
            $articleAuthorType = strtolower(trim((string) ($articleSchemaData['authorType'] ?? 'person')));
            $articleAuthorUrl = trim((string) ($articleSchemaData['authorUrl'] ?? ''));
            $articleSection = trim((string) ($articleSchemaData['articleSection'] ?? ''));
            $articleWordCount = (int) ($articleSchemaData['wordCount'] ?? 0);
            $articleKeywords = $articleSchemaData['keywords'] ?? [];
            $articleAbout = $articleSchemaData['about'] ?? [];
            $articleMentions = $articleSchemaData['mentions'] ?? [];
            $articleCitation = $articleSchemaData['citation'] ?? ($articleSchemaData['citations'] ?? []);
            $articleIsAccessibleForFree = $articleSchemaData['isAccessibleForFree'] ?? true;

            $articleSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                '@id' => $canonicalUrl . '#article',
                'mainEntityOfPage' => $canonicalUrl,
                'headline' => $articleHeadline,
                'description' => $articleDescription,
                'image' => [$articleImage],
                'publisher' => [
                    '@type' => 'Organization',
                    '@id' => $siteRootUrl . '#organization',
                    'name' => $companyName,
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => url('/assets/images/resources/ars-logo-dark.png'),
                    ],
                ],
                'inLanguage' => $schemaLanguage,
                'isAccessibleForFree' => (bool) $articleIsAccessibleForFree,
            ];

            if ($articleAuthorType === 'organization') {
                $articleSchema['author'] = [
                    '@type' => 'Organization',
                    '@id' => $siteRootUrl . '#organization',
                    'name' => $articleAuthor !== '' ? $articleAuthor : $companyName,
                    'url' => $articleAuthorUrl !== '' ? $articleAuthorUrl : $siteRootUrl,
                ];
            } else {
                $articleSchema['author'] = [
                    '@type' => 'Person',
                    'name' => $articleAuthor !== '' ? $articleAuthor : 'ARS Developer Editorial Team',
                ];
                if ($articleAuthorUrl !== '') {
                    $articleSchema['author']['url'] = $articleAuthorUrl;
                }
            }

            if ($articlePublished) {
                $articleSchema['datePublished'] = $articlePublished;
            }
            if ($articleModified) {
                $articleSchema['dateModified'] = $articleModified;
            }
            if ($articleSection !== '') {
                $articleSchema['articleSection'] = $articleSection;
            }
            if ($articleWordCount > 0) {
                $articleSchema['wordCount'] = $articleWordCount;
            }
            if (is_array($articleKeywords) && count($articleKeywords) > 0) {
                $articleSchema['keywords'] = implode(', ', array_values(array_filter(array_map(
                    static fn ($item) => trim((string) $item),
                    $articleKeywords
                ))));
            } elseif (is_string($articleKeywords) && trim($articleKeywords) !== '') {
                $articleSchema['keywords'] = trim($articleKeywords);
            }
            if (is_array($articleAbout) && count($articleAbout) > 0) {
                $articleSchema['about'] = $articleAbout;
            }
            if (is_array($articleMentions) && count($articleMentions) > 0) {
                $articleSchema['mentions'] = $articleMentions;
            }
            if (is_string($articleCitation) && trim($articleCitation) !== '') {
                $articleCitation = [trim($articleCitation)];
            }
            if (is_array($articleCitation) && count($articleCitation) > 0) {
                $articleSchema['citation'] = array_values(array_filter(array_map(
                    static fn ($item) => trim((string) $item),
                    $articleCitation
                )));
            }
            if (!empty($articleSchemaData['speakable'])) {
                $articleSchema['speakable'] = [
                    '@type' => 'SpeakableSpecification',
                    'xpath' => (array) $articleSchemaData['speakable'],
                ];
            }

            $schemaGraph[] = $articleSchema;
        }

        // --- HowTo Schema for Service Pages (2026 AEO signal) ---
        $howToMap = [
            '/search-engine-optimization' => [
                'name' => 'How to Improve UK SEO Rankings in 6 Steps',
                'description' => 'A structured 6-step SEO delivery process for UK service businesses targeting stronger rankings, better click-through rates, and qualified commercial enquiries.',
                'totalTime' => 'P90D',
                'steps' => [
                    ['name' => 'Technical SEO Audit', 'text' => 'Crawl the full site to identify indexation blockers, Core Web Vitals failures, canonical errors, and internal link gaps that suppress rankings on commercial service URLs.'],
                    ['name' => 'Search Console Analysis', 'text' => 'Identify pages already earning Google impressions but not converting them to clicks. These pages have the highest quick-win potential and are prioritised first.'],
                    ['name' => 'Buyer Intent Mapping', 'text' => 'Map every service page to the real questions UK buyers type into Google, AI search tools, and voice assistants — not just keyword volume data.'],
                    ['name' => 'On-Page and AEO Upgrades', 'text' => 'Rewrite titles, meta descriptions, headings, and body content to match search intent, answer-first structure, and AI citation patterns. Add FAQ schema, structured data, and entity coverage.'],
                    ['name' => 'Internal Linking and Authority Architecture', 'text' => 'Connect service pages, blog posts, sector pages, and the UK Growth Hub with contextual anchors so topical authority builds across the whole site.'],
                    ['name' => 'Monthly Reporting and Iteration', 'text' => 'Track ranking movement, click-through rate improvement, and qualified enquiry growth. Every month produces a prioritised action plan based on real Search Console movement.'],
                ],
            ],
            '/web-design-development' => [
                'name' => 'How to Build a UK Business Website That Ranks and Converts',
                'description' => 'A 6-stage website development process for UK businesses that combines SEO-first architecture, conversion-focused design, and performance-optimised build delivery.',
                'totalTime' => 'P42D',
                'steps' => [
                    ['name' => 'Discovery and Scope Planning', 'text' => 'Map target audience, buyer journey, service priorities, and conversion goals before any design work starts to prevent scope drift.'],
                    ['name' => 'SEO-First Information Architecture', 'text' => 'Plan sitemap, URL structure, heading hierarchy, and internal linking with search intent so Google and AI engines understand every page.'],
                    ['name' => 'Conversion-Focused Design', 'text' => 'Build UI around trust signals, clear calls to action, credibility blocks, and frictionless enquiry paths aligned to UK buyer behaviour.'],
                    ['name' => 'Performance-Optimised Build', 'text' => 'Implement code quality, image loading, caching, and Core Web Vitals compliance from development — not patched after launch.'],
                    ['name' => 'Schema and Structured Data', 'text' => 'Implement Organisation, Service, BreadcrumbList, FAQ, and LocalBusiness markup so Google and AI search tools understand your entity and service coverage.'],
                    ['name' => 'Launch QA, Handover and Support', 'text' => 'Pre-launch testing covers forms, speed, mobile rendering, analytics setup, and Search Console verification for a stable launch from day one.'],
                ],
            ],
            '/software-development' => [
                'name' => 'How to Deliver Custom Software for UK Businesses in 5 Stages',
                'description' => 'A milestone-based custom software delivery process for UK businesses that reduces project risk, prevents scope overruns, and ensures post-launch usability.',
                'totalTime' => 'P60D',
                'steps' => [
                    ['name' => 'Requirements Discovery', 'text' => 'Map business workflow, user roles, integration requirements, and commercial goals before any technical decisions are made.'],
                    ['name' => 'Architecture and Scope Definition', 'text' => 'Define database structure, API integrations, user journeys, and admin controls in a documented scope reviewed and approved before development starts.'],
                    ['name' => 'Milestone-Based Build', 'text' => 'Break development into reviewable phases where each milestone produces working functionality the client can test and approve.'],
                    ['name' => 'QA, Testing, and Handover', 'text' => 'Full testing covers user flows, edge cases, performance under load, and security. Documentation and admin access delivered for team self-management.'],
                    ['name' => 'Post-Launch Support and Scaling', 'text' => 'Provide support windows for fixes, feature additions, and performance improvements with transparent monthly pricing.'],
                ],
            ],
            '/digital-marketing' => [
                'name' => 'How to Build a Revenue-Focused Digital Marketing Campaign for UK Businesses',
                'description' => 'A structured digital marketing delivery approach for UK businesses that aligns paid campaigns, SEO, and conversion tracking to qualified lead generation.',
                'totalTime' => 'P30D',
                'steps' => [
                    ['name' => 'Channel Audit and Intent Mapping', 'text' => 'Identify which channels your UK buyers use at each stage of their decision journey and map campaign structure to commercial intent.'],
                    ['name' => 'Conversion Infrastructure Setup', 'text' => 'Build dedicated landing pages with specific offers, clear trust signals, and single calls to action matched to ad intent before traffic is sent.'],
                    ['name' => 'Campaign Launch and Tracking', 'text' => 'Launch campaigns with CPL, ROAS, and revenue-per-lead tracking from day one so optimisation decisions are data-driven not guesswork.'],
                    ['name' => 'Weekly Optimisation', 'text' => 'Tune targeting, messaging, and bids weekly to reduce cost per qualified lead and improve sales-ready opportunities.'],
                    ['name' => 'Monthly Growth Review', 'text' => 'Review channel performance, lead quality, and ROI with a next-step action plan and updated priorities for the following month.'],
                ],
            ],
        ];

        $currentHowTo = $howToMap[$currentPathKey] ?? null;
        if ($currentHowTo !== null) {
            $howToSteps = [];
            foreach ($currentHowTo['steps'] as $stepIndex => $step) {
                $howToSteps[] = [
                    '@type' => 'HowToStep',
                    'position' => $stepIndex + 1,
                    'name' => $step['name'],
                    'text' => $step['text'],
                ];
            }
            $schemaGraph[] = [
                '@context' => 'https://schema.org',
                '@type' => 'HowTo',
                '@id' => $canonicalUrl . '#howto',
                'name' => $currentHowTo['name'],
                'description' => $currentHowTo['description'],
                'totalTime' => $currentHowTo['totalTime'],
                'inLanguage' => $schemaLanguage,
                'author' => ['@id' => $siteRootUrl . '#organization'],
                'step' => $howToSteps,
            ];
        }

        // --- SpeakableSpecification for service and pillar pages (voice search + AI search) ---
        $speakablePageTypes = [
            '/search-engine-optimization', '/web-design-development', '/software-development',
            '/app-development', '/digital-marketing', '/design-and-branding',
            '/uk-growth-hub', '/faq', '/about', '/',
        ];
        if (in_array($currentPathKey, $speakablePageTypes, true)) {
            $schemaGraph[4]['speakable'] = [
                '@type' => 'SpeakableSpecification',
                'cssSelector' => [
                    '.services-details__title-1',
                    '.services-details__text-1',
                    '.seo-problem-block__title',
                    '.seo-process-block__title',
                    '.hero-text-slider__title-box',
                    '.page-header__inner h1',
                    'h1.seo-hidden-heading',
                    'h2.seo-hidden-heading',
                ],
            ];
        }

        $metaAuthor = trim((string) (
            (strtolower((string) $seoType) === 'article')
                ? ($articleSchemaData['author'] ?? $companyName)
                : $companyName
        ));
        if ($metaAuthor === '') {
            $metaAuthor = 'ARS Developer Ltd';
        }
    @endphp
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $seoTitle }}</title>
    <!-- favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicons/favicon.png') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicons/favicon.png') }}" />
    <meta name="description" content="{{ $seoDescription }}" />
    <meta name="keywords" content="{{ $seoKeywords }}" />
    <meta name="author" content="{{ $metaAuthor }}" />
    @if (env('GOOGLE_SITE_VERIFICATION'))
        <meta name="google-site-verification" content="{{ env('GOOGLE_SITE_VERIFICATION') }}" />
    @endif
    <meta name="msvalidate.01" content="07A57B3E08C81EF8DECA42EA91321EC0" />
    <meta name="robots" content="{{ $resolvedRobots }}" />
    <meta name="googlebot" content="{{ $resolvedRobots }}" />
    <meta name="bingbot" content="{{ $resolvedRobots }}" />
    <link rel="canonical" href="{{ $canonicalUrl }}" />
    <link rel="alternate" hreflang="en-GB" href="{{ $canonicalUrl }}" />
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}" />
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ $siteRootUrl }}/sitemap.xml" />
    <link rel="home" href="{{ $siteRootUrl }}/" />
    @if ($currentPathKey !== '/')
        <link rel="up" href="{{ $siteRootUrl }}/" />
    @endif
    @foreach(array_slice($significantLinks, 0, 12) as $relatedHref)
        @if($relatedHref !== $canonicalUrl)
            <link rel="related" href="{{ $relatedHref }}" />
        @endif
    @endforeach
    @if(!empty($seo['preload_image']))
        <link rel="preload" as="image" href="{{ $seo['preload_image'] }}" fetchpriority="high">
    @endif
    <meta name="language" content="{{ $currentHreflang }}" />
    @php
        $geoPageMap = [
            '/web-developer-london'        => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/laravel-developer-london'    => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/wordpress-developer-london'  => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/shopify-developer-london'    => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/react-developer-london'      => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/php-developer-london'        => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/nextjs-developer-london'     => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/vue-developer-london'        => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/node-developer-london'       => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/typescript-developer-london' => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/crm-developer-london'        => ['region' => 'GB-LND', 'placename' => 'London',               'lat' => '51.5074', 'lng' => '-0.1278'],
            '/web-developer-manchester'    => ['region' => 'GB-ENG', 'placename' => 'Manchester',           'lat' => '53.4808', 'lng' => '-2.2426'],
            '/web-developer-birmingham'    => ['region' => 'GB-ENG', 'placename' => 'Birmingham',           'lat' => '52.4862', 'lng' => '-1.8904'],
            '/web-developer-leeds'         => ['region' => 'GB-ENG', 'placename' => 'Leeds',                'lat' => '53.8008', 'lng' => '-1.5491'],
            '/web-developer-sheffield'     => ['region' => 'GB-ENG', 'placename' => 'Sheffield',            'lat' => '53.3811', 'lng' => '-1.4701'],
            '/web-developer-bristol'       => ['region' => 'GB-ENG', 'placename' => 'Bristol',              'lat' => '51.4545', 'lng' => '-2.5879'],
            '/web-developer-glasgow'       => ['region' => 'GB-SCT', 'placename' => 'Glasgow',              'lat' => '55.8642', 'lng' => '-4.2518'],
            '/web-developer-edinburgh'     => ['region' => 'GB-SCT', 'placename' => 'Edinburgh',            'lat' => '55.9533', 'lng' => '-3.1883'],
            '/web-developer-liverpool'     => ['region' => 'GB-ENG', 'placename' => 'Liverpool',            'lat' => '53.4084', 'lng' => '-2.9916'],
            '/web-developer-cardiff'       => ['region' => 'GB-WLS', 'placename' => 'Cardiff',              'lat' => '51.4816', 'lng' => '-3.1791'],
            '/web-developer-nottingham'    => ['region' => 'GB-ENG', 'placename' => 'Nottingham',           'lat' => '52.9548', 'lng' => '-1.1581'],
            '/web-developer-newcastle'     => ['region' => 'GB-ENG', 'placename' => 'Newcastle upon Tyne',  'lat' => '54.9783', 'lng' => '-1.6178'],
            '/web-developer-leicester'     => ['region' => 'GB-ENG', 'placename' => 'Leicester',            'lat' => '52.6369', 'lng' => '-1.1398'],
            '/web-developer-coventry'      => ['region' => 'GB-ENG', 'placename' => 'Coventry',             'lat' => '52.4068', 'lng' => '-1.5197'],
            '/web-developer-stoke-on-trent' => ['region' => 'GB-STS', 'placename' => 'Stoke-on-Trent',     'lat' => '53.0027', 'lng' => '-2.1794'],
        ];
        $geoMeta = $geoPageMap[$currentPathKey] ?? ['region' => 'GB-STS', 'placename' => 'Stoke-on-Trent', 'lat' => '53.0027', 'lng' => '-2.1794'];
    @endphp
    <meta name="geo.region" content="{{ $geoMeta['region'] }}" />
    <meta name="geo.placename" content="{{ $geoMeta['placename'] }}" />
    <meta name="geo.position" content="{{ $geoMeta['lat'] }};{{ $geoMeta['lng'] }}" />
    <meta name="ICBM" content="{{ $geoMeta['lat'] }}, {{ $geoMeta['lng'] }}" />
    <meta property="og:locale" content="{{ $currentOgLocale }}" />
    <meta property="og:type" content="{{ strtolower($seoType) === 'article' ? 'article' : 'website' }}" />
    <meta property="og:title" content="{{ $seoOgTitle }}" />
    <meta property="og:description" content="{{ $seoOgDescription }}" />
    <meta property="og:url" content="{{ $canonicalUrl }}" />
    <meta property="og:site_name" content="ARSDeveloper" />
    <meta property="og:image" content="{{ $seo['og_image'] ?? url('/assets/images/resources/ars-logo-dark.png') }}" />
    <meta property="og:image:alt" content="{{ $seo['og_image_alt'] ?? 'ARSDeveloper UK Software Agency' }}" />
    @if(!empty($articleSchemaData['datePublished']))
        <meta property="article:published_time" content="{{ $articleSchemaData['datePublished'] }}" />
    @endif
    @if(!empty($articleSchemaData['dateModified']))
        <meta property="article:modified_time" content="{{ $articleSchemaData['dateModified'] }}" />
    @endif
    @if(!empty($articleSchemaData['author']))
        <meta property="article:author" content="{{ $articleSchemaData['author'] }}" />
    @endif
    @if(strtolower((string) $seoType) === 'article')
        <meta property="article:publisher" content="{{ $companyName }}" />
    @endif
    <meta name="twitter:card" content="{{ $seo['twitter_card'] ?? 'summary_large_image' }}" />
    <meta name="twitter:title" content="{{ $seoTwitterTitle }}" />
    <meta name="twitter:description" content="{{ $seoTwitterDescription }}" />
    <meta name="twitter:image" content="{{ $seo['twitter_image'] ?? ($seo['og_image'] ?? url('/assets/images/resources/ars-logo-dark.png')) }}" />
    <meta name="theme-color" content="#102A4D" />
    @include('partials.ga4-tracking')
    <script type="text/javascript">
        (function () {
            function loadScript(src) {
                var script = document.createElement('script');
                script.async = true;
                script.src = src;
                document.head.appendChild(script);
            }

            function loadAnalyticsVendors() {
                if (window.__arsAnalyticsVendorsLoaded) {
                    return;
                }

                window.__arsAnalyticsVendorsLoaded = true;
                loadScript('https://t.contentsquare.net/uxa/28ccfb7bbc307.js');

                (function(c, l, a, r, i, t, y) {
                    c[a] = c[a] || function() {
                        (c[a].q = c[a].q || []).push(arguments);
                    };
                    t = l.createElement(r);
                    t.async = 1;
                    t.src = "https://www.clarity.ms/tag/" + i;
                    y = l.getElementsByTagName(r)[0];
                    y.parentNode.insertBefore(t, y);
                })(window, document, "clarity", "script", "vlqqrnt61k");
            }

            if ('requestIdleCallback' in window) {
                window.addEventListener('load', function () {
                    requestIdleCallback(loadAnalyticsVendors, { timeout: 4000 });
                }, { once: true });
            } else {
                window.addEventListener('load', function () {
                    setTimeout(loadAnalyticsVendors, 2500);
                }, { once: true });
            }
        })();
    </script>
    <script type="application/ld+json">
        {!! json_encode(['@' . 'context' => 'https://schema.org', '@' . 'graph' => $schemaGraph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script>
        window.arsRegion = {!! json_encode([
            'key' => 'uk',
            'currency' => $currentCurrency,
            'symbol' => $currentCurrencySymbol,
            'rates' => collect($regionConfig)->mapWithKeys(function ($item, $key) {
                return [
                    $key => [
                        'currency' => $item['currency'] ?? 'USD',
                        'symbol' => $item['symbol'] ?? '$',
                        'rate_from_usd' => (float) ($item['rate_from_usd'] ?? 1),
                    ],
                ];
            }),
        ], JSON_UNESCAPED_SLASHES) !!};
    </script>

    <!-- fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Koulen&display=swap" rel="stylesheet">


    <link rel="preload" href="{{ asset('assets/css/bundle.css') }}" as="style">
    @if($isHomePath)
        <link rel="preload" href="{{ asset('assets/images/resources/banner-one-img-1.png') }}" as="image" fetchpriority="high">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/bundle.css') }}?v={{ filemtime(public_path('assets/css/bundle.css')) }}" />
    <style>
        /* Fallback so page never stays blank if JS fails before preloader close */
        .js-preloader {
            animation: preloader-fallback-hide 0s linear 1.2s forwards;
        }

        .ars-media-loading {
            filter: blur(12px);
            transform: scale(1.02);
            opacity: .72;
            transition: filter .45s ease, transform .45s ease, opacity .45s ease;
        }

        .ars-media-ready {
            filter: blur(0);
            transform: scale(1);
            opacity: 1;
        }

        @keyframes preloader-fallback-hide {
            to {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }
        }
    </style>
    <style>
        .main-menu-two__logo img,
        .stricky-header .main-menu-two__logo img {
            width: auto;
            max-height: 52px;
        }

        .mobile-nav__content .logo-box img {
            width: auto;
            max-height: 48px;
        }

        .main-menu-two__main-menu-box {
            flex: 1 1 auto;
            display: flex;
            justify-content: center;
            min-width: 0;
        }

        .main-menu-two .main-menu__list {
            flex-wrap: nowrap;
        }

        .main-menu-two__right {
            flex-shrink: 0;
        }

        @media (max-width: 1199px) {
            .main-menu-two__wrapper-inner {
                flex-wrap: nowrap;
                gap: 12px;
            }

            .main-menu-two__left {
                flex: 0 1 auto;
                min-width: 0;
            }

            .main-menu-two__logo {
                padding: 18px 0;
            }

            .main-menu-two__logo img,
            .stricky-header .main-menu-two__logo img {
                max-width: 100%;
                max-height: 46px;
            }

            .main-menu-two__main-menu-box {
                flex: 0 0 auto;
                width: auto;
                min-width: auto;
                margin-left: auto;
                justify-content: flex-end;
            }

            .main-menu-two .mobile-nav__toggler {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 10px 0 10px 12px;
                line-height: 1;
                top: 0;
            }
        }

        @media only screen and (min-width: 1200px) and (max-width: 1599px) {
            .main-menu-two__wrapper-inner {
                padding: 0 24px;
            }

            .main-menu-two .main-menu__list>li+li {
                margin-left: 8px;
            }

            .main-menu-two .main-menu__list>li>a {
                font-size: 15px;
                padding: 10px 9px;
            }

            .main-menu-two__right {
                gap: 14px;
            }

            .main-menu-two__btn-box {
                display: none;
            }
        }

        @media only screen and (min-width: 1200px) and (max-width: 1700px) {
            .main-menu-two__top {
                display: block;
            }

        .main-menu-two__top-inner {
            flex-wrap: nowrap;
            padding: 8px 20px;
            min-height: 52px;
        }

            .main-menu-two__contact-list {
                min-width: 0;
                flex: 1 1 auto;
            }

            .main-menu-two__contact-list li+li {
                margin-left: 18px;
            }

            .main-menu-two__contact-list li .text p {
                font-size: 13px;
                line-height: 16px;
            }

            .main-menu-two__contact-list li:last-child .text p {
                max-width: 390px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .main-menu-two__top-welcome-text,
            .main-menu-two__top-time {
                display: none;
            }

            .main-menu-two__top-right {
                flex: 0 0 auto;
            }

            .main-menu-two__social {
                margin-left: 14px;
                gap: 8px;
            }

            .main-menu-two__social a {
                width: 36px;
                height: 36px;
                min-width: 36px;
                min-height: 36px;
                font-size: 13px;
            }

            .main-menu-two__logo {
                padding: 20px 0;
            }
        }

        @media only screen and (min-width: 1200px) and (max-width: 1399px) {
            .main-menu-two__top-inner {
                padding: 10px 20px 9px;
            }

            .main-menu-two__top-welcome-text {
                display: none;
            }

            .main-menu-two__contact-list li+li {
                margin-left: 18px;
            }
        }

        .main-slider__content {
            max-width: 52%;
            padding-right: 46px;
            min-height: 380px;
        }

        .main-slider__title {
            font-size: 74px;
            line-height: 0.98em;
            min-height: 150px;
        }

        .main-slider__text {
            max-width: 720px;
            min-height: 92px;
        }

        .hero-text-slider__title-box {
            min-height: 320px;
        }

        .hero-text-slider__proof {
            min-height: 27px;
        }

        .hero-text-slider__cta {
            min-height: 70px;
        }

        .hero-text-slider__stats {
            min-height: 86px;
        }

        .main-slider__satisfied-client-img {
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
        }

        .main-slider__satisfied-client-img img,
        .main-menu-two__logo img,
        .stricky-header .main-menu-two__logo img {
            height: auto;
        }

        body.is-home .sticky-quick-actions {
            contain: layout paint;
        }

        @supports (content-visibility: auto) {
            body.is-home .portfolio-two,
            body.is-home .meeting-scheduler,
            body.is-home .client-portal-discovery,
            body.is-home .lead-forms-tabs,
            body.is-home .audit-lead,
            body.is-home .cost-estimator,
            body.is-home .blog-two,
            body.is-home .testimonial-two,
            body.is-home .faq-one--home,
            body.is-home .cta-one {
                content-visibility: auto;
                contain-intrinsic-size: 1000px;
            }
        }

        .main-slider .main-slider__img-box {
            right: 340px !important;
            max-width: 30%;
            top: 225px !important;
            bottom: auto !important;
            transform: translateX(220px) !important;
        }

        .main-slider .active .main-slider__img-box {
            transform: translateX(0) !important;
        }

        .main-slider__img img {
            max-width: 100%;
            max-height: 580px;
            height: auto;
            object-fit: contain;
            object-position: center center;
        }

        .main-slider__img {
            overflow: hidden;
            pointer-events: none;
        }

        .main-slider__shape-bg,
        .main-slider__shape-bg-2,
        .main-slider__shape-bg-3,
        .main-slider__shape-1,
        .main-slider__shape-2,
        .main-slider__shape-3,
        .main-slider__shape-4 {
            pointer-events: none;
        }

        .seo-hidden-heading {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        @media (max-width: 1399px) {
            .main-slider__content {
                max-width: 54%;
                padding-right: 38px;
            }

            .main-slider__title {
                font-size: 64px;
                min-height: 132px;
            }

            .hero-text-slider__title-box {
                min-height: 294px;
            }
            .main-slider .main-slider__img-box {
                right: 260px !important;
                top: 230px !important;
                max-width: 31%;
            }
        }

        @media (max-width: 1199px) {
            .main-slider__content {
                max-width: 58%;
                padding-right: 28px;
                min-height: 350px;
            }

            .main-slider__title {
                font-size: 54px;
                min-height: 116px;
            }

            .hero-text-slider__title-box {
                min-height: 270px;
            }

            .main-slider .main-slider__img-box {
                right: 175px !important;
                top: 240px !important;
                bottom: auto !important;
                max-width: 33%;
            }

            .main-slider__img img {
                max-height: 520px;
            }
        }

        @media (max-width: 991px) {
            .main-slider__content {
                max-width: 100%;
                min-height: auto;
            }

            .main-slider__img-box {
                display: none;
            }

            .main-slider__title {
                font-size: 46px;
                min-height: auto;
            }

            .main-slider__text,
            .hero-text-slider__title-box,
            .hero-text-slider__proof,
            .hero-text-slider__cta,
            .hero-text-slider__stats {
                min-height: auto;
            }
        }

    /* Fix WOW.js black flash — WOW sets visibility:hidden before trigger */
    .wow { visibility: visible !important; }
    [data-aos] { pointer-events: auto !important; }
    </style>

    {{-- Google AdSense. Shared publisher account with anastanveer.com; one AdSense
         account can serve multiple sites, so this is the same ca-pub id. --}}
    <link rel="preconnect" href="https://pagead2.googlesyndication.com">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9027630763788678" crossorigin="anonymous"></script>
</head>

<body class="{{ $isHomePath ? 'is-home' : 'custom-cursor' }}">

    @unless($isHomePath)
        <div class="custom-cursor__cursor"></div>
        <div class="custom-cursor__cursor-two"></div>
    @endunless


    @unless($isHomePath)
        <!--Start Preloader-->
        <div class="loader js-preloader">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <!--End Preloader-->
    @endunless




    <div class="page-wrapper">
        <header class="main-header-two">
            <div class="main-menu-two__top">
                <div class="main-menu-two__top-inner">
                    <ul class="list-unstyled main-menu-two__contact-list">
                        <li>
                            <div class="icon">
                                <i class="icon-phone"></i>
                            </div>
                            <div class="text">
                                <p><a class="main-menu-two__phone-link" href="tel:+447478034328">+44 7478034328</a></p>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <i class="icon-envelope"></i>
                            </div>
                            <div class="text">
                                <p><a href="mailto:info@arsdeveloper.co.uk">info@arsdeveloper.co.uk</a>
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <i class="icon-pin"></i>
                            </div>
                            <div class="text">
                                <p>38 Elm Street, ST6 2HN, Stoke-on-Trent, United Kingdom</p>
                            </div>
                        </li>
                    </ul>
                    @if($isHomePath)
                    <div class="ars-top-ticker" style="flex:1;overflow:hidden;min-width:0;margin:0 24px;">
                        <div class="ars-top-ticker__inner" style="display:flex;align-items:center;white-space:nowrap;animation:ars-top-ticker 32s linear infinite;">
                            @foreach([
                                ['fas fa-shield-alt','#22c55e','UK Ltd · Co. No: 17039150'],
                                ['fas fa-star','#f59e0b','5.0★ Rated by UK Clients'],
                                ['fas fa-layer-group','#38bdf8','50+ Projects Delivered'],
                                ['fas fa-user-check','#a78bfa','Founder-Direct · No Middlemen'],
                                ['fas fa-lock','#34d399','No Payment Until Approved'],
                                ['fas fa-map-marker-alt','#fb923c','Based in Stoke-on-Trent, UK'],
                                ['fas fa-file-invoice','#60a5fa','Invoice in 1 Business Day'],
                                ['fas fa-check-circle','#22c55e','UK Registered · England &amp; Wales'],
                                ['fas fa-shield-alt','#22c55e','UK Ltd · Co. No: 17039150'],
                                ['fas fa-star','#f59e0b','5.0★ Rated by UK Clients'],
                                ['fas fa-layer-group','#38bdf8','50+ Projects Delivered'],
                                ['fas fa-user-check','#a78bfa','Founder-Direct · No Middlemen'],
                            ] as $ti)
                            <span style="display:inline-flex;align-items:center;gap:7px;margin-right:36px;font-size:12px;color:#cbd5e1;font-family:Arial,sans-serif;font-weight:500;">
                                <i class="{{ $ti[0] }}" style="color:{{ $ti[1] }};font-size:11px;flex-shrink:0;" aria-hidden="true"></i>
                                {!! $ti[2] !!}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <style>
                    @keyframes ars-top-ticker {
                        0%   { transform: translateX(0); }
                        100% { transform: translateX(-50%); }
                    }
                    </style>
                    @else
                    <p class="main-menu-two__top-welcome-text">{{ __('ui.welcome_uk') }}</p>
                    @endif
                    <div class="main-menu-two__top-right">
                        <div class="main-menu-two__top-time">
                            <div class="main-menu-two__top-time-icon">
                                <span class="icon-time"></span>
                            </div>
                            <p class="main-menu-two__top-text">Mon – Fri: 09:00 – 18:00</p>
                        </div>
                        <div class="main-menu-two__social">
                            <a class="main-menu-two__social-link" href="https://www.facebook.com/arsdeveloperuk" target="_blank" rel="noopener" aria-label="ARSDeveloper on Facebook"><i class="fab fa-facebook"></i></a>
                            <a class="main-menu-two__social-link" href="https://linkedin.com/company/arsdeveloperuk" target="_blank" rel="noopener" aria-label="ARSDeveloper on LinkedIn"><i class="fab fa-linkedin"></i></a>
                            <a class="main-menu-two__social-link" href="https://www.instagram.com/arsdeveloperuk/" target="_blank" rel="noopener" aria-label="ARSDeveloper on Instagram"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="main-menu main-menu-two">
                <div class="main-menu-two__wrapper">
                    <div class="main-menu-two__wrapper-inner">
                        <div class="main-menu-two__left">
                            <div class="main-menu-two__logo">
                                <a href="/"><img src="{{ asset('assets/images/resources/ars-logo-nav-white.png') }}" alt="ARSDeveloper"></a>
                            </div>
                        </div>
                        <div class="main-menu-two__main-menu-box">
                            <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                            <ul class="main-menu__list">
                                <li><a href="/">{{ __('ui.home') }}</a></li>
                                <li><a href="/about">{{ __('ui.about') }}</a></li>
                                <li><a href="/portfolio">{{ __('ui.portfolio') }}</a></li>
                                <li><a href="/services">{{ __('ui.services') }}</a></li>
                                <li><a href="/pricing">{{ __('ui.pricing') }}</a></li>
                                <li><a href="/blog">{{ __('ui.blog') }}</a></li>
                                <li><a href="/contact">{{ __('ui.contact') }}</a></li>
                            </ul>
                        </div>
                        <div class="main-menu-two__right">
                            <div class="main-menu-two__call">
                                <div class="main-menu-two__call-icon">
                                    <i class="icon-phone"></i>
                                </div>
                                <div class="main-menu-two__call-content">
                                    <p class="main-menu-two__call-sub-title">{{ __('ui.call_anytime') }}</p>
                                    <p class="main-menu-two__call-number"><a href="tel:+447478034328">+44 7478034328</a></p>
                                </div>
                            </div>
                            <div class="main-menu-two__search-cart-box">
                                <div class="main-menu-two__search-cart-box">
                                    <div class="main-menu-two__search-box">
                                        <span class="main-menu-two__search searcher-toggler-box icon-search-1"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="main-menu-two__btn-box">
                                <a href="/contact" class="thm-btn thm-btn-two main-menu-two__btn">{{ __('ui.contact_us') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div class="stricky-header stricked-menu main-menu main-menu-two">
            <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
        </div><!-- /.stricky-header -->
        <main id="main-content" role="main" aria-label="Main content">
