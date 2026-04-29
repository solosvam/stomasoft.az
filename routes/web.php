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

Route::controller(PagesController::class)->name('page.')->group(function () {
    Route::get('/','home')->name('homee');
    Route::get('/home','home')->name('home');
});

