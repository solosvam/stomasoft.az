<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PagesController;

$domain = config('app.domain');

require __DIR__.'/admin.php';

Route::domain("app.$domain")->group(function () {
    Route::get('/', fn () => redirect('/login'));
});

Route::domain("demo.$domain")->group(function () {
    Route::get('/', fn () => redirect('/login'));
});

Route::get('/admin', function () {
    return Auth::guard()->check()
        ? redirect()->route('admin.main')
        : redirect()->route('admin.login');
});

// Public pages: Azerbaijani at the root, other languages under /en, /ru, /tr
foreach (\App\Support\Landing::LOCALES as $locale) {
    Route::prefix($locale === 'az' ? '' : $locale)
        ->name($locale === 'az' ? 'page.' : "page.$locale.")
        ->controller(PagesController::class)
        ->group(function () use ($locale) {
            Route::get('/', 'home')->name($locale === 'az' ? 'homee' : 'home')->defaults('locale', $locale);
            if ($locale === 'az') {
                Route::get('/home', 'home')->name('home')->defaults('locale', $locale);
            }
            Route::get('/doctors', 'doctors')->name('doctors')->defaults('locale', $locale);
            Route::get('/patients', 'patients')->name('patients')->defaults('locale', $locale);
        });
}

Route::controller(PagesController::class)->name('page.')->group(function () {
    Route::get('/reservation-hours','reservationHours')->name('reservation.hours');
    Route::post('/reservation-store','reservation')->name('reservation.store');
});

Route::controller(\App\Http\Controllers\Frontend\SeoController::class)->name('seo.')->group(function () {
    Route::get('/sitemap.xml', 'sitemap')->name('sitemap');
});
