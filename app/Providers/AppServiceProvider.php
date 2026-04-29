<?php

namespace App\Providers;

use App\Models\Messages;
use App\Models\Services;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (app()->environment('production')) {
                URL::forceScheme('https');
            }
        });
    }
}
