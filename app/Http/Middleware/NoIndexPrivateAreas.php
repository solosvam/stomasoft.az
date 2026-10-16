<?php

namespace App\Http\Middleware;

use App\Support\Landing;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps search engines out of everything except the public landing pages:
 * the app./demo. subdomains and all panel routes (login, admin, ajax) get
 * an X-Robots-Tag header. Crawling stays allowed on purpose so Google can
 * actually see the noindex instead of listing a blocked URL.
 */
class NoIndexPrivateAreas
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $domain      = config('app.domain');
        $privateHost = in_array($request->getHost(), ["app.$domain", "demo.$domain"], true);

        if ($privateHost || !$this->isPublicRoute($request)) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        }

        return $response;
    }

    private function isPublicRoute(Request $request): bool
    {
        $name = $request->route()?->getName();

        return Landing::pageOf($name) !== null || in_array($name, ['seo.sitemap'], true);
    }
}
