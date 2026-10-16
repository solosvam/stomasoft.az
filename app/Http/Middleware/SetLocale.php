<?php

namespace App\Http\Middleware;

use App\Support\Landing;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $route = $request->route();

        // Public pages: the language comes from the URL (/ru/doctors), never from the session
        if ($landingLocale = $route?->defaults['locale'] ?? null) {
            $legacy = $request->query('lang');

            // Old ?lang=ru links permanently move to the language's own address
            if ($request->isMethod('GET') && in_array($legacy, Landing::LOCALES, true)) {
                $page = Landing::pageOf($route->getName()) ?? 'home';

                return redirect()->to((new Landing)->url($page, $legacy), 301);
            }

            session(['locale' => $landingLocale]);
            app()->setLocale($landingLocale);

            return $next($request);
        }

        if ($request->has('lang') && in_array($request->lang, Landing::LOCALES)) {
            session(['locale' => $request->lang]);
        }

        app()->setLocale(session('locale', 'az'));

        return $next($request);
    }
}
