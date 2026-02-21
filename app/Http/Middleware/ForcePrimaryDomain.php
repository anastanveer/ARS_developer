<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePrimaryDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($queryCleanupRedirect = $this->redirectLowValueQueryUrls($request)) {
            return $queryCleanupRedirect;
        }

        if ($legacyRedirect = $this->legacyPathRedirect($request)) {
            return $legacyRedirect;
        }

        if ($mixedCaseRedirect = $this->redirectMixedCasePath($request)) {
            return $mixedCaseRedirect;
        }

        $host = strtolower((string) $request->getHost());
        if ($host === '' || in_array($host, ['127.0.0.1', 'localhost'], true)) {
            $response = $next($request);
            return $this->withRobotsHeaders($request, $response);
        }

        if (app()->environment('local')) {
            $response = $next($request);
            return $this->withRobotsHeaders($request, $response);
        }

        $primaryDomain = strtolower((string) env('APP_PRIMARY_DOMAIN', 'arsdeveloper.co.uk'));
        if ($primaryDomain === '' || $host === $primaryDomain) {
            $response = $next($request);
            return $this->withRobotsHeaders($request, $response);
        }

        $redirectHosts = array_values(array_filter(array_map(
            static fn ($item) => strtolower(trim((string) $item)),
            explode(',', (string) env('APP_REDIRECT_DOMAINS', 'arsdeveloper.com,www.arsdeveloper.com,www.arsdeveloper.co.uk'))
        )));

        if (!in_array($host, $redirectHosts, true)) {
            $response = $next($request);
            return $this->withRobotsHeaders($request, $response);
        }

        $scheme = (string) env('APP_CANONICAL_SCHEME', 'https');
        $target = $scheme . '://' . $primaryDomain . $request->getRequestUri();

        return redirect()->to($target, 301);
    }

    private function redirectMixedCasePath(Request $request): ?Response
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return null;
        }

        $path = (string) $request->getPathInfo();
        if ($path === '' || $path === '/') {
            return null;
        }

        // Skip case-sensitive private/public utility routes where tokens may be mixed-case.
        $caseSensitivePrefixes = [
            '/admin',
            '/client-portal/',
            '/review/',
            '/meeting/',
            '/stripe/',
        ];
        foreach ($caseSensitivePrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return null;
            }
        }

        if (!preg_match('/[A-Z]/', $path)) {
            return null;
        }

        $normalizedPath = mb_strtolower($path, 'UTF-8');
        $queryString = (string) $request->getQueryString();
        if ($queryString !== '') {
            $normalizedPath .= '?' . $queryString;
        }

        $host = strtolower((string) $request->getHost());
        $isLocalHost = $host === '' || in_array($host, ['127.0.0.1', 'localhost'], true) || app()->environment('local');

        if ($isLocalHost) {
            return redirect()->to($normalizedPath, 301);
        }

        $scheme = (string) env('APP_CANONICAL_SCHEME', 'https');
        $primaryDomain = strtolower((string) env('APP_PRIMARY_DOMAIN', 'arsdeveloper.co.uk'));
        $targetUrl = $scheme . '://' . ($primaryDomain !== '' ? $primaryDomain : $host) . $normalizedPath;

        return redirect()->to($targetUrl, 301);
    }

    private function redirectLowValueQueryUrls(Request $request): ?Response
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return null;
        }

        $query = $request->query();
        if (count($query) === 0) {
            return null;
        }

        if (
            $request->is('admin') || $request->is('admin/*')
            || $request->is('client-portal/*')
            || $request->is('review/*')
            || $request->is('meeting/*')
            || $request->is('stripe/*')
        ) {
            return null;
        }

        $blockedQueryParams = [
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

        $cleanedQuery = $query;
        foreach ($blockedQueryParams as $param) {
            unset($cleanedQuery[$param]);
        }

        // Keep only meaningful crawlable pagination for blog archives.
        if ($request->is('blog')) {
            $allowed = [];
            $page = (int) ($cleanedQuery['page'] ?? 1);
            if ($page > 1) {
                $allowed['page'] = $page;
            }
            $cleanedQuery = $allowed;
        }

        if ($cleanedQuery === $query) {
            return null;
        }

        $host = strtolower((string) $request->getHost());
        $isLocalHost = $host === '' || in_array($host, ['127.0.0.1', 'localhost'], true) || app()->environment('local');

        $path = (string) $request->getPathInfo();
        $targetPath = $path === '' ? '/' : $path;
        $targetQuery = http_build_query($cleanedQuery);
        if ($targetQuery !== '') {
            $targetPath .= '?' . $targetQuery;
        }

        if ($isLocalHost) {
            return redirect()->to($targetPath, 301);
        }

        $scheme = (string) env('APP_CANONICAL_SCHEME', 'https');
        $primaryDomain = strtolower((string) env('APP_PRIMARY_DOMAIN', 'arsdeveloper.co.uk'));
        $targetUrl = $scheme . '://' . ($primaryDomain !== '' ? $primaryDomain : $host) . $targetPath;

        return redirect()->to($targetUrl, 301);
    }

    private function withRobotsHeaders(Request $request, Response $response): Response
    {
        $defaultRobots = 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
        $noindexExact = [
            'privacy-policy',
            'privacy-policy.php',
            'terms-and-conditions',
            'terms-and-conditions.php',
            'cookie-policy',
            'cookie-policy.php',
            'refund-policy',
            'refund-policy.php',
            'service-disclaimer',
            'service-disclaimer.php',
            'search',
            'search.php',
            'testimonial-carousel',
            'testimonial-carousel.php',
            'coming-soon',
            'coming-soon.php',
            '404',
            '404.php',
            'client-portal-access',
            'client-portal-access.php',
            'blog-list',
            'blog-list.php',
            'meeting-availability',
            'search/suggest',
            'up',
        ];

        $noindexWildcard = [
            'client-portal/*',
            'review/*',
            'admin',
            'admin/*',
            'meeting/confirmation/*',
            'meeting/manage/*',
            'meeting/cancel/*',
        ];

        foreach ($noindexExact as $path) {
            if ($request->is($path)) {
                return $this->finalizeHtmlResponse($request, $response, 'noindex, follow');
            }
        }

        foreach ($noindexWildcard as $pattern) {
            if ($request->is($pattern)) {
                return $this->finalizeHtmlResponse($request, $response, 'noindex, follow');
            }
        }

        if ($request->is('blog') && trim((string) $request->query('q', '')) !== '') {
            return $this->finalizeHtmlResponse($request, $response, 'noindex, follow');
        }

        if ($this->shouldNoindexForDuplicateQuery($request)) {
            return $this->finalizeHtmlResponse($request, $response, 'noindex, follow');
        }

        return $this->finalizeHtmlResponse($request, $response, $defaultRobots);
    }

    private function shouldNoindexForDuplicateQuery(Request $request): bool
    {
        $query = $request->query();
        if (count($query) === 0) {
            return false;
        }

        // Keep paginated blog archives indexable for crawl depth distribution.
        if ($request->is('blog')) {
            $keys = array_keys($query);
            sort($keys);
            if ($keys === ['page']) {
                $page = (int) $request->query('page', 1);
                return $page <= 1;
            }
        }

        return true;
    }

    private function legacyPathRedirect(Request $request): ?Response
    {
        $path = '/' . ltrim((string) $request->path(), '/');
        if ($path === '//') {
            $path = '/';
        }

        $legacyToClean = [
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
            '/blog.php' => '/blog',
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
            '/coming-soon.php' => '/coming-soon',
            '/404.php' => '/404',
            '/uk-growth-hub.php' => '/uk-growth-hub',
        ];

        if (!array_key_exists($path, $legacyToClean)) {
            return null;
        }

        $targetPath = $legacyToClean[$path];
        $queryString = (string) $request->getQueryString();
        if ($queryString !== '') {
            $targetPath .= '?' . $queryString;
        }

        $host = strtolower((string) $request->getHost());
        $isLocalHost = $host === '' || in_array($host, ['127.0.0.1', 'localhost'], true) || app()->environment('local');
        if ($isLocalHost) {
            return redirect()->to($targetPath, 301);
        }

        $scheme = (string) env('APP_CANONICAL_SCHEME', 'https');
        $primaryDomain = strtolower((string) env('APP_PRIMARY_DOMAIN', 'arsdeveloper.co.uk'));
        $absoluteTarget = $scheme . '://' . ($primaryDomain !== '' ? $primaryDomain : $host) . $targetPath;

        return redirect()->to($absoluteTarget, 301);
    }

    private function finalizeHtmlResponse(Request $request, Response $response, string $robots): Response
    {
        $response->headers->set('X-Robots-Tag', $robots, true);
        $this->applyCanonicalHeader($request, $response);
        $this->applySecurityHeaders($request, $response);
        $this->enrichImageAltAndTitle($response);
        return $response;
    }

    private function applyCanonicalHeader(Request $request, Response $response): void
    {
        $contentType = strtolower((string) $response->headers->get('Content-Type', ''));
        if ($contentType !== '' && !str_contains($contentType, 'text/html')) {
            return;
        }

        $primaryDomain = strtolower((string) env('APP_PRIMARY_DOMAIN', 'arsdeveloper.co.uk'));
        $scheme = (string) env('APP_CANONICAL_SCHEME', 'https');
        $host = strtolower((string) $request->getHost());
        $isLocalHost = $host === '' || in_array($host, ['127.0.0.1', 'localhost'], true) || app()->environment('local');

        $baseUrl = $isLocalHost
            ? rtrim((string) url('/'), '/')
            : ($scheme . '://' . ($primaryDomain !== '' ? $primaryDomain : $host));

        $path = (string) $request->getPathInfo();
        if ($path === '') {
            $path = '/';
        }

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
            '/blog.php' => '/blog',
            '/uk-growth-hub.php' => '/uk-growth-hub',
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

        if (isset($legacyToCleanPath[$path])) {
            $path = $legacyToCleanPath[$path];
        } elseif (str_starts_with($path, '/index.php/')) {
            $path = '/' . ltrim(substr($path, strlen('/index.php/')), '/');
            if (isset($legacyToCleanPath[$path])) {
                $path = $legacyToCleanPath[$path];
            } elseif (str_ends_with($path, '.php')) {
                $path = preg_replace('/\.php$/', '', $path) ?: $path;
            }
        }

        $canonicalPath = $path === '/' ? '' : $path;

        $query = $request->query();
        $blockedQueryParams = [
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
        foreach ($blockedQueryParams as $blockedParam) {
            unset($query[$blockedParam]);
        }

        $allowQueryCanonical = false;
        if ($request->is('blog')) {
            $keys = array_keys($query);
            sort($keys);
            $allowQueryCanonical = ($keys === ['page']) && ((int) ($query['page'] ?? 1) > 1);
        }

        $querySuffix = $allowQueryCanonical && count($query) > 0 ? ('?' . http_build_query($query)) : '';
        $canonicalUrl = $baseUrl . $canonicalPath . $querySuffix;
        if ($canonicalUrl === '') {
            return;
        }

        $response->headers->set('Link', '<' . $canonicalUrl . '>; rel="canonical"', true);
    }

    private function applySecurityHeaders(Request $request, Response $response): void
    {
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);
        $response->headers->set(
            'Permissions-Policy',
            'accelerometer=(), autoplay=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()',
            false
        );
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups', false);
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-site', false);
        $response->headers->set('Content-Security-Policy', 'upgrade-insecure-requests', false);

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload', false);
        }
    }

    private function enrichImageAltAndTitle(Response $response): void
    {
        $contentType = strtolower((string) $response->headers->get('Content-Type', ''));
        if ($contentType !== '' && !str_contains($contentType, 'text/html')) {
            return;
        }

        $html = $response->getContent();
        if (!is_string($html) || $html === '' || stripos($html, '<img') === false) {
            return;
        }

        $imageIndex = 0;

        $updated = preg_replace_callback('/<img\b[^>]*>/i', function (array $matches) use (&$imageIndex): string {
            $tag = $matches[0];
            $imageIndex++;

            $altValue = '';
            $altMatch = [];
            if (preg_match('/\balt\s*=\s*([\'"])(.*?)\1/i', $tag, $altMatch)) {
                $altValue = trim((string) html_entity_decode($altMatch[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            }

            if ($altValue === '') {
                $src = '';
                $srcMatch = [];
                if (preg_match('/\bsrc\s*=\s*([\'"])(.*?)\1/i', $tag, $srcMatch)) {
                    $src = (string) $srcMatch[2];
                }

                $file = '';
                if ($src !== '') {
                    $path = (string) parse_url($src, PHP_URL_PATH);
                    $file = basename($path);
                }

                $label = preg_replace('/\.[a-z0-9]+$/i', '', $file);
                $label = preg_replace('/[_-]+/', ' ', (string) $label);
                $label = trim((string) preg_replace('/\s+/', ' ', (string) $label));
                $altValue = $label !== '' ? ('ARSDeveloper UK - ' . $label) : 'ARSDeveloper UK service visual';
            }

            $safeAlt = htmlspecialchars($altValue, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
            if (preg_match('/\balt\s*=\s*([\'"])(.*?)\1/i', $tag)) {
                $tag = preg_replace('/\balt\s*=\s*([\'"])(.*?)\1/i', 'alt="' . $safeAlt . '"', $tag, 1) ?? $tag;
            } else {
                $tag = preg_replace('/\s*\/?>$/', ' alt="' . $safeAlt . '"$0', $tag, 1) ?? $tag;
            }

            $titleMatch = [];
            $titleValue = '';
            if (preg_match('/\btitle\s*=\s*([\'"])(.*?)\1/i', $tag, $titleMatch)) {
                $titleValue = trim((string) html_entity_decode($titleMatch[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            }
            if ($titleValue === '') {
                if (preg_match('/\btitle\s*=\s*([\'"])(.*?)\1/i', $tag)) {
                    $tag = preg_replace('/\btitle\s*=\s*([\'"])(.*?)\1/i', 'title="' . $safeAlt . '"', $tag, 1) ?? $tag;
                } else {
                    $tag = preg_replace('/\s*\/?>$/', ' title="' . $safeAlt . '"$0', $tag, 1) ?? $tag;
                }
            }

            if (!preg_match('/\bdecoding\s*=\s*([\'"])(.*?)\1/i', $tag)) {
                $tag = preg_replace('/\s*\/?>$/', ' decoding="async"$0', $tag, 1) ?? $tag;
            }

            $isEarlyImage = $imageIndex <= 2;
            if (!preg_match('/\bloading\s*=\s*([\'"])(.*?)\1/i', $tag)) {
                $tag = preg_replace('/\s*\/?>$/', ' loading="' . ($isEarlyImage ? 'eager' : 'lazy') . '"$0', $tag, 1) ?? $tag;
            }

            if (!preg_match('/\bfetchpriority\s*=\s*([\'"])(.*?)\1/i', $tag) && $isEarlyImage) {
                $tag = preg_replace('/\s*\/?>$/', ' fetchpriority="high"$0', $tag, 1) ?? $tag;
            }

            $hasWidth = preg_match('/\bwidth\s*=\s*([\'"])(.*?)\1/i', $tag) === 1;
            $hasHeight = preg_match('/\bheight\s*=\s*([\'"])(.*?)\1/i', $tag) === 1;
            if (!$hasWidth || !$hasHeight) {
                $resolvedSrc = '';
                $resolvedSrcMatch = [];
                if (preg_match('/\bsrc\s*=\s*([\'"])(.*?)\1/i', $tag, $resolvedSrcMatch)) {
                    $resolvedSrc = trim((string) $resolvedSrcMatch[2]);
                }

                [$width, $height] = $this->resolveImageDimensions($resolvedSrc);
                if ($width > 0 && $height > 0) {
                    if (!$hasWidth) {
                        $tag = preg_replace('/\s*\/?>$/', ' width="' . $width . '"$0', $tag, 1) ?? $tag;
                    }
                    if (!$hasHeight) {
                        $tag = preg_replace('/\s*\/?>$/', ' height="' . $height . '"$0', $tag, 1) ?? $tag;
                    }
                }
            }

            return $tag;
        }, $html);

        if (is_string($updated) && $updated !== '') {
            $response->setContent($updated);
        }
    }

    private function resolveImageDimensions(string $src): array
    {
        $src = trim($src);
        if ($src === '' || str_starts_with($src, 'data:')) {
            return [0, 0];
        }

        static $dimensionCache = [];
        if (isset($dimensionCache[$src])) {
            return $dimensionCache[$src];
        }

        $path = (string) parse_url($src, PHP_URL_PATH);
        if ($path === '') {
            return $dimensionCache[$src] = [0, 0];
        }

        // Avoid expensive filesystem probes for decorative assets with low CLS impact.
        $measureablePath = str_contains($path, '/resources/')
            || str_contains($path, '/gallery/')
            || str_contains($path, '/blog/')
            || str_contains($path, '/services/')
            || str_contains($path, '/portfolio/');
        if (!$measureablePath) {
            return $dimensionCache[$src] = [0, 0];
        }

        $publicFile = public_path(ltrim($path, '/'));
        $candidateFiles = [$publicFile];

        if (str_starts_with($path, '/storage/')) {
            $candidateFiles[] = storage_path('app/public/' . ltrim(substr($path, strlen('/storage/')), '/'));
        }

        foreach ($candidateFiles as $file) {
            if (!is_string($file) || $file === '' || !is_file($file)) {
                continue;
            }

            $size = @getimagesize($file);
            if (!is_array($size) || !isset($size[0], $size[1])) {
                continue;
            }

            $width = max(0, (int) $size[0]);
            $height = max(0, (int) $size[1]);
            if ($width > 0 && $height > 0) {
                return $dimensionCache[$src] = [$width, $height];
            }
        }

        return $dimensionCache[$src] = [0, 0];
    }
}
