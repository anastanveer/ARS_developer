<!DOCTYPE html>
<html lang="en">

<head>
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
                'title' => 'UK Software Agency for Web, CRM and WordPress Development',
                'description' => 'ARSDeveloper helps UK businesses with custom websites, CRM software, WordPress development, SEO, and digital growth strategies.',
                'keywords' => 'web development agency uk, custom software company uk, crm development uk, wordpress agency uk, shopify developers uk, wix website experts uk, seo services uk, business automation uk',
                'type' => 'WebPage',
                'preload_image' => asset('assets/images/resources/main-slider-img-1-1.png'),
                'faq_items' => [
                    [
                        'question' => 'How fast can a UK business website project start?',
                        'answer' => 'Most website and CRM projects can start within one to five business days after scope confirmation and onboarding.',
                    ],
                    [
                        'question' => 'Do you provide both development and SEO support?',
                        'answer' => 'Yes. We provide web delivery, CRM implementation, technical SEO, and monthly growth support in one execution flow.',
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
            ],
            '/about' => [
                'title' => 'About ARSDeveloper UK Team for Web, CRM and SEO Delivery',
                'description' => 'Meet ARSDeveloper UK, delivering business websites, CRM systems, and SEO growth with transparent scope and practical project ownership.',
                'keywords' => 'about arsdeveloper uk, uk web development team, crm development partner uk, seo delivery team uk, software agency stoke-on-trent',
                'type' => 'AboutPage',
            ],
            '/services.php' => [
                'title' => 'Software Development Services in UK',
                'description' => 'UK software services for web development, CRM systems, app development, WordPress builds, and SEO growth.',
                'keywords' => 'software development services uk, website development services uk, crm development services uk, wordpress development services uk, ecommerce website development uk, shopify development uk, wix development uk, seo services uk',
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
                'title' => 'Digital Marketing Services UK',
                'description' => 'Digital marketing and PPC services for UK businesses focused on visibility, leads, and conversion growth.',
                'keywords' => 'digital marketing agency uk, lead generation services uk, seo marketing uk, ppc management uk, social media marketing uk, conversion rate optimization uk',
                'type' => 'Service',
            ],
            '/web-design-development.php' => [
                'title' => 'Web Design and Development UK',
                'description' => 'Web design and development for UK businesses focused on speed, UX, and lead conversion.',
                'keywords' => 'web design agency uk, website development company uk, small business website uk, corporate website development uk, responsive website design uk, custom php development uk',
                'type' => 'Service',
            ],
            '/search-engine-optimization.php' => [
                'title' => 'SEO Services for UK Businesses',
                'description' => 'Technical and on-page SEO services to improve UK rankings, organic traffic, and qualified leads.',
                'keywords' => 'seo services uk, local seo uk, technical seo agency uk, on page seo uk, ecommerce seo uk, seo consultant uk, google rankings uk',
                'type' => 'Service',
            ],
            '/design-and-branding.php' => [
                'title' => 'Design and Branding Services UK',
                'description' => 'Brand identity and UI/UX design services that help UK businesses stand out and grow online.',
                'keywords' => 'branding agency uk, ui ux design services uk, brand identity design uk, logo and visual branding uk, startup branding uk',
                'type' => 'Service',
            ],
            '/app-development.php' => [
                'title' => 'App Development Services UK',
                'description' => 'Mobile and web app development for UK businesses building scalable products and workflows.',
                'keywords' => 'app development company uk, mobile app development uk, web app development uk, custom saas development uk, mvp development uk',
                'type' => 'Service',
            ],
            '/software-development.php' => [
                'title' => 'Custom Software Development Company UK',
                'description' => 'Custom software development for UK businesses that need secure workflows, integrations, and scalable delivery systems.',
                'keywords' => 'custom software development uk, software development company uk, bespoke software uk, business automation software uk, enterprise software developers uk',
                'type' => 'Service',
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
            ],
            '/portfolio.php' => [
                'title' => 'Portfolio of UK Web Development, CRM and SEO Projects',
                'description' => 'View portfolio projects delivered by ARSDeveloper across website development, CRM, SEO, and software solutions.',
                'keywords' => 'software development portfolio uk, web development case studies uk, ecommerce project portfolio uk, crm implementation case study uk',
                'type' => 'CollectionPage',
            ],
            '/portfolio' => [
                'title' => 'Portfolio of UK Web Development, CRM and SEO Projects',
                'description' => 'View portfolio projects delivered by ARSDeveloper across website development, CRM, SEO, and software solutions.',
                'keywords' => 'software development portfolio uk, web development case studies uk, ecommerce project portfolio uk, crm implementation case study uk',
                'type' => 'CollectionPage',
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
                'title' => 'FAQ for UK Web Development, CRM and SEO Services',
                'description' => 'Find answers to common questions about website development, CRM software, SEO, and project delivery.',
                'keywords' => 'software agency FAQ UK, web development questions UK, CRM development FAQ',
                'type' => 'FAQPage',
            ],
            '/faq' => [
                'title' => 'FAQ for UK Web Development, CRM and SEO Services',
                'description' => 'Find answers to common questions about website development, CRM software, SEO, and project delivery.',
                'keywords' => 'software agency FAQ UK, web development questions UK, CRM development FAQ',
                'type' => 'FAQPage',
            ],
            '/blog.php' => [
                'title' => 'Blog - UK Web Development and SEO Insights',
                'description' => 'Practical insights on software development, WordPress, SEO, CRM systems, and digital growth in the UK.',
                'keywords' => 'web development blog UK, SEO blog UK, CRM blog UK, WordPress tips UK',
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
                'title' => 'Contact ARSDeveloper UK Software Agency',
                'description' => 'Contact ARSDeveloper for web development, CRM software, WordPress projects, and SEO services.',
                'keywords' => 'hire web developers uk, contact software development company uk, request website quote uk, crm consultation uk',
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
        $companyPhone = (string) config('company.phone', '+44747803428');
        $companyStreetAddress = (string) config('company.street_address', '38 Elm Street');
        $companyPostalCode = (string) config('company.postal_code', 'ST6 2HN');
        $companyLocality = (string) config('company.address_locality', 'Stoke-on-Trent');
        $companyCountryCode = (string) config('company.address_country', 'GB');
        $companyCountryName = (string) config('company.country_name', 'United Kingdom');
        $companyOpeningHours = (string) config('company.opening_hours', 'Mo-Fr 09:00-17:00');
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
                    '@type' => 'Country',
                    'name' => $companyCountryName,
                ],
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
                'closes' => '17:00',
            ],
        ];
        $schemaGraph[3]['openingHours'] = $companyOpeningHours;
        $schemaGraph[3]['sameAs'] = $organizationSameAs;

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
    <meta name="geo.region" content="GB-STS" />
    <meta name="geo.placename" content="Stoke-on-Trent" />
    <meta name="geo.position" content="53.0027;-2.1794" />
    <meta name="ICBM" content="53.0027, -2.1794" />
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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-S9CN4PVV3B"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-S9CN4PVV3B');
    </script>
    <script src="https://t.contentsquare.net/uxa/28ccfb7bbc307.js"></script>
    <script type="text/javascript">
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


    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom-animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-all.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jarallax.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />


    <link rel="stylesheet" href="{{ asset('assets/css/module-css/slider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/footer.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/brand.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/services.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/about.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/why-choose.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/process.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/portfolio.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/testimonial.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/pricing.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/blog.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/newsletter.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/cta.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/counter.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/shop.css') }}" />
    @if($isComingSoonPath)
        <link rel="stylesheet" href="{{ asset('assets/css/module-css/coming-soon.css') }}" />
    @endif
    @if($isErrorPath)
        <link rel="stylesheet" href="{{ asset('assets/css/module-css/error.css') }}" />
    @endif
    @if($isGalleryPath)
        <link rel="stylesheet" href="{{ asset('assets/css/module-css/gallery.css') }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/faq.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/google-map.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/contact.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/page-header.css') }}" />
    @if($isAboutPath)
        <link rel="stylesheet" href="{{ asset('assets/css/module-css/awards.css') }}" />
    @endif

    <!-- template styles -->
    <link rel="preload" href="{{ asset('assets/css/style.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/css/responsive.css') }}" as="style">
    @if($isHomePath)
        <link rel="preload" href="{{ asset('assets/images/resources/main-slider-img-1-1.png') }}" as="image" fetchpriority="high">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    <style>
        /* Fallback so page never stays blank if JS fails before preloader close */
        .js-preloader {
            animation: preloader-fallback-hide 0s linear 1.2s forwards;
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

        .main-slider__content {
            max-width: 52%;
            padding-right: 46px;
        }

        .main-slider__title {
            font-size: 74px;
            line-height: 0.98em;
        }

        .main-slider__text {
            max-width: 720px;
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
            }

            .main-slider__title {
                font-size: 54px;
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
            }

            .main-slider__img-box {
                display: none;
            }

            .main-slider__title {
                font-size: 46px;
            }
        }

    </style>
</head>

<body class="custom-cursor{{ $isHomePath ? ' is-home' : '' }}">



    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>


    <!--Start Preloader-->
    <div class="loader js-preloader">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <!--End Preloader-->




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
                                <p><a class="main-menu-two__phone-link" href="tel:+44747803428">+44 747803428</a></p>
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
                    <p class="main-menu-two__top-welcome-text">{{ __('ui.welcome_uk') }}</p>
                    <div class="main-menu-two__top-right">
                        <div class="main-menu-two__top-time">
                            <div class="main-menu-two__top-time-icon">
                                <span class="icon-time"></span>
                            </div>
                            <p class="main-menu-two__top-text">Mon - Fri: 09:00 - 05:00</p>
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
                                <li><a href="{{ route('client.portal.access') }}">Client Portal</a></li>
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
                                    <p class="main-menu-two__call-number"><a href="tel:+44747803428">+44 747803428</a></p>
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
