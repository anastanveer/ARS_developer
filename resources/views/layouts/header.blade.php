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
            ],
            '/index.php' => [
                'title' => 'UK Software Agency for Web, CRM and WordPress Development',
                'description' => 'ARSDeveloper helps UK businesses with custom websites, CRM software, WordPress development, SEO, and digital growth strategies.',
                'keywords' => 'web development agency uk, custom software company uk, crm development uk, wordpress agency uk, shopify developers uk, wix website experts uk, seo services uk, business automation uk',
                'type' => 'WebPage',
            ],
            '/about.php' => [
                'title' => 'About ARSDeveloper: UK Web, CRM and SEO Agency',
                'description' => 'Meet ARSDeveloper, a UK software agency delivering web development, CRM systems, and SEO support for growing businesses.',
                'keywords' => 'about software agency UK, ARSDeveloper, UK digital agency, software company Stoke-on-Trent',
                'type' => 'AboutPage',
            ],
            '/services.php' => [
                'title' => 'Software Development Services in UK',
                'description' => 'UK software services for web development, CRM systems, app development, WordPress builds, and SEO growth.',
                'keywords' => 'software development services uk, website development services uk, crm development services uk, wordpress development services uk, ecommerce website development uk, shopify development uk, wix development uk, seo services uk',
                'type' => 'Service',
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
                'type' => 'ReviewNewsArticle',
            ],
            '/testimonial-carousel.php' => [
                'title' => 'Testimonials - ARSDeveloper',
                'description' => 'Client feedback and success stories from UK software, web, and SEO projects.',
                'keywords' => 'client feedback software agency UK, testimonials web development UK',
                'type' => 'ReviewNewsArticle',
                'robots' => 'noindex, follow',
            ],
            '/pricing.php' => [
                'title' => 'Pricing Plans for Software and Web Services',
                'description' => 'Transparent pricing options for web development, SEO, CRM, and digital services by ARSDeveloper.',
                'keywords' => 'web development packages uk, software development pricing uk, seo pricing uk, monthly website support uk, crm development cost uk',
                'type' => 'WebPage',
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
            ],
            '/search.php' => [
                'title' => 'Search Results - ARSDeveloper UK Software Agency',
                'description' => 'Search ARSDeveloper pages, services, portfolio and blog content for web development, CRM, WordPress and SEO topics.',
                'keywords' => 'search ARSDeveloper, website search UK software agency, service search',
                'type' => 'SearchResultsPage',
            ],
            '/contact.php' => [
                'title' => 'Contact ARSDeveloper UK Software Agency',
                'description' => 'Contact ARSDeveloper for web development, CRM software, WordPress projects, and SEO services.',
                'keywords' => 'hire web developers uk, contact software development company uk, request website quote uk, crm consultation uk',
                'type' => 'ContactPage',
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
            '/portfolio' => '/portfolio.php',
            '/portfolio-details' => '/portfolio-details.php',
            '/testimonials' => '/testimonials.php',
            '/testimonial-carousel' => '/testimonial-carousel.php',
            '/pricing' => '/pricing.php',
            '/gallery' => '/gallery.php',
            '/faq' => '/faq.php',
            '/blog' => '/blog.php',
            '/blog-list' => '/blog-list.php',
            '/blog-details' => '/blog-details.php',
            '/contact' => '/contact.php',
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
        $queryParams = request()->query();
        $blockedCanonicalParams = [
            'region',
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
            'gclid',
            'fbclid',
            'msclkid',
        ];
        foreach ($blockedCanonicalParams as $blockedParam) {
            unset($queryParams[$blockedParam]);
        }
        $queryString = http_build_query($queryParams);
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
            '/blog-list.php' => '/blog-list',
            '/blog-details.php' => '/blog-details',
            '/search.php' => '/search',
            '/contact.php' => '/contact',
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
        $allowQueryCanonical = (bool) ($seo['allow_query_canonical'] ?? false);
        $canonicalSuffix = ($allowQueryCanonical && $queryString) ? ('?' . $queryString) : '';
        $ukBase = app()->environment('local')
            ? rtrim((string) url('/'), '/')
            : rtrim((string) ($selectedRegion['base_url'] ?? url('/')), '/');
        $canonicalUrl = $seo['canonical'] ?? ($ukBase . $canonicalPath . $canonicalSuffix);
        $siteRootUrl = $ukBase;
        $areaServed = 'United Kingdom';
        $schemaLanguage = $currentHreflang;

        $schemaGraph = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                '@id' => $siteRootUrl . '#organization',
                'name' => config('company.legal_name', 'ARS Developer Ltd'),
                'url' => $siteRootUrl,
                'email' => 'info@arsdeveloper.co.uk',
                'telephone' => '+971542435418',
                'sameAs' => [
                    'https://www.facebook.com/arsdeveloperuk',
                    'http://linkedin.com/company/arsdeveloperuk',
                    'https://www.instagram.com/arsdeveloperuk/',
                ],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '38 Elm Street',
                    'postalCode' => 'ST6 2HN',
                    'addressLocality' => 'Stoke-on-Trent',
                    'addressCountry' => 'GB',
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                '@id' => $siteRootUrl . '#website',
                'url' => $siteRootUrl,
                'name' => config('company.legal_name', 'ARS Developer Ltd'),
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
                'name' => config('company.legal_name', 'ARS Developer Ltd'),
                'url' => $siteRootUrl,
                'image' => url('/assets/images/resources/ars-logo-dark.png'),
                'telephone' => '+971542435418',
                'email' => 'info@arsdeveloper.co.uk',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '38 Elm Street',
                    'postalCode' => 'ST6 2HN',
                    'addressLocality' => 'Stoke-on-Trent',
                    'addressCountry' => 'GB',
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
                '@type' => $seoType,
                '@id' => $canonicalUrl . '#webpage',
                'url' => $canonicalUrl,
                'name' => $seoTitle,
                'description' => $seoDescription,
                'inLanguage' => $schemaLanguage,
                'isPartOf' => ['@id' => $siteRootUrl . '#website'],
            ],
        ];

        $breadcrumbPath = trim((string) ($canonicalPath ?: ''), '/');
        $breadcrumbMap = [
            'about' => 'About',
            'services' => 'Services',
            'digital-marketing' => 'Digital Marketing',
            'web-design-development' => 'Web Design & Development',
            'search-engine-optimization' => 'SEO Services',
            'design-and-branding' => 'Design & Branding',
            'app-development' => 'App Development',
            'portfolio' => 'Portfolio',
            'portfolio-details' => 'Project Details',
            'testimonials' => 'Testimonials',
            'testimonial-carousel' => 'Testimonials',
            'pricing' => 'Pricing',
            'gallery' => 'Gallery',
            'faq' => 'FAQs',
            'blog' => 'Blog',
            'contact' => 'Contact',
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

        if (count($breadcrumbItems) > 1) {
            $schemaGraph[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $breadcrumbItems,
            ];
        }
    @endphp
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $seoTitle }}</title>
    <!-- favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('assets/images/favicons/site.webmanifest') }}" />
    <meta name="description" content="{{ $seoDescription }}" />
    <meta name="keywords" content="{{ $seoKeywords }}" />
    <meta name="author" content="ARSDeveloper" />
    @if (env('GOOGLE_SITE_VERIFICATION'))
        <meta name="google-site-verification" content="{{ env('GOOGLE_SITE_VERIFICATION') }}" />
    @endif
    <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1' }}" />
    <link rel="canonical" href="{{ $canonicalUrl }}" />
    <meta name="language" content="{{ $currentHreflang }}" />
    <meta name="geo.region" content="GB-STS" />
    <meta name="geo.placename" content="Stoke-on-Trent" />
    <meta name="geo.position" content="53.0027;-2.1794" />
    <meta name="ICBM" content="53.0027, -2.1794" />
    <link rel="alternate" hreflang="en-gb" href="{{ $canonicalUrl }}" />
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}" />
    <meta property="og:locale" content="{{ $currentOgLocale }}" />
    <meta property="og:type" content="{{ strtolower($seoType) === 'article' ? 'article' : 'website' }}" />
    <meta property="og:title" content="{{ $seoOgTitle }}" />
    <meta property="og:description" content="{{ $seoOgDescription }}" />
    <meta property="og:url" content="{{ $canonicalUrl }}" />
    <meta property="og:site_name" content="ARSDeveloper" />
    <meta property="og:image" content="{{ $seo['og_image'] ?? url('/assets/images/resources/ars-logo-dark.png') }}" />
    <meta property="og:image:alt" content="{{ $seo['og_image_alt'] ?? 'ARSDeveloper UK Software Agency' }}" />
    <meta name="twitter:card" content="{{ $seo['twitter_card'] ?? 'summary_large_image' }}" />
    <meta name="twitter:title" content="{{ $seoTwitterTitle }}" />
    <meta name="twitter:description" content="{{ $seoTwitterDescription }}" />
    <meta name="twitter:image" content="{{ $seo['twitter_image'] ?? ($seo['og_image'] ?? url('/assets/images/resources/ars-logo-dark.png')) }}" />
    <meta name="theme-color" content="#102A4D" />
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
    <link rel="stylesheet" href="{{ asset('assets/css/module-css/sliding-text.css') }}" />
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

<body class="custom-cursor">



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
                                <p><a href="tel:+971542435418">+971 54 243 5418</a></p>
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
                            <a href="https://www.facebook.com/arsdeveloperuk" target="_blank" rel="noopener"><i class="fab fa-facebook"></i></a>
                            <a href="http://linkedin.com/company/arsdeveloperuk" target="_blank" rel="noopener"><i class="fab fa-linkedin"></i></a>
                            <a href="https://www.instagram.com/arsdeveloperuk/" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
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
                                    <h5 class="main-menu-two__call-number"><a href="tel:+971542435418">+971 54 243 5418</a></h5>
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
