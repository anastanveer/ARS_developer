<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePrimaryDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($legacyRedirect = $this->legacyPathRedirect($request)) {
            return $legacyRedirect;
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
            'admin',
            'admin/*',
            'meeting/confirmation/*',
            'meeting/manage/*',
            'meeting/cancel/*',
        ];

        foreach ($noindexExact as $path) {
            if ($request->is($path)) {
                return $this->finalizeHtmlResponse($response, 'noindex, follow');
            }
        }

        foreach ($noindexWildcard as $pattern) {
            if ($request->is($pattern)) {
                return $this->finalizeHtmlResponse($response, 'noindex, follow');
            }
        }

        if ($request->is('blog') && trim((string) $request->query('q', '')) !== '') {
            return $this->finalizeHtmlResponse($response, 'noindex, follow');
        }

        if ($this->shouldNoindexForDuplicateQuery($request)) {
            return $this->finalizeHtmlResponse($response, 'noindex, follow');
        }

        return $this->finalizeHtmlResponse($response, $defaultRobots);
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

        return redirect()->to($targetPath, 301);
    }

    private function finalizeHtmlResponse(Response $response, string $robots): Response
    {
        $response->headers->set('X-Robots-Tag', $robots, true);
        $this->enrichImageAltAndTitle($response);
        return $response;
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

        $updated = preg_replace_callback('/<img\b[^>]*>/i', static function (array $matches): string {
            $tag = $matches[0];

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

            return $tag;
        }, $html);

        if (is_string($updated) && $updated !== '') {
            $response->setContent($updated);
        }
    }
}
