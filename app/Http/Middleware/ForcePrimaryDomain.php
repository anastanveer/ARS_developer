<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePrimaryDomain
{
    public function handle(Request $request, Closure $next): Response
    {
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

        if (
            $request->is('privacy-policy')
            || $request->is('terms-and-conditions')
            || $request->is('cookie-policy')
        ) {
            $response->headers->set('X-Robots-Tag', 'noindex, follow', true);
            return $response;
        }

        $response->headers->set('X-Robots-Tag', $defaultRobots, true);
        return $response;
    }
}
