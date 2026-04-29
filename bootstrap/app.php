<?php

use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\SetLocale;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocale::class,
        ]);
        $middleware->alias([
            'check.subscription' => CheckSubscription::class,
        ]);
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            return '/main';
        });

        Authenticate::redirectUsing(function ($request) {
            return '/main';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
