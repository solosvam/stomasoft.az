<?php

namespace App\Providers;

use App\Models\Notes;
use App\Models\PageVideo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;

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

            $pageVideo = null;
            $currentRoute = Route::currentRouteName();


            if ($currentRoute) {
                $pageVideo = PageVideo::where('route', $currentRoute)
                    ->where('active', 1)
                    ->first();
            }

            $view->with([
                'notesCount' => $notesCount,
                'pageVideo'  => $pageVideo,
            ]);

            if (app()->environment('production')) {
                URL::forceScheme('https');
            }
        });
    }
}
