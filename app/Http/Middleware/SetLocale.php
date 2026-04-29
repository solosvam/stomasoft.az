<?php

namespace App\Http\Middleware;

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
        if ($request->has('lang') && in_array($request->lang, ['az', 'en', 'ru'])) {
            session(['locale' => $request->lang]);
        }

        app()->setLocale(session('locale', 'az'));

        return $next($request);
    }
}
