<?php

namespace App\Providers;

use App\Models\Messages;
use App\Models\Notes;
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
            $notesCount = 0;

            if (auth()->check()) {
                $notesCount = Notes::where('user_id', auth()->id())
                    ->count();
            }

            $view->with([
                'notesCount' => $notesCount
            ]);

            if (app()->environment('production')) {
                URL::forceScheme('https');
            }
        });
    }
}
