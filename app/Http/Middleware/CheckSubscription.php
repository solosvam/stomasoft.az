<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || $user->role == 'admin') {
            return $next($request);
        }

        if ($request->routeIs('admin.logout')) {
            return $next($request);
        }

        if ($user->subscription_ends_at) {
            $end = \Carbon\Carbon::parse($user->subscription_ends_at)->endOfDay();
            $today = now();

            if ($today->gt($end->copy()->addDays(3))) {
                return response()->view('errors.subscription-expired');
            }

            if ($today->between($end->copy()->subDays(3), $end->copy()->addDays(3))) {
                view()->share('subscriptionWarning', true);
                view()->share('subscriptionEndDate', $end);
            }
        }

        return $next($request);
    }
}
