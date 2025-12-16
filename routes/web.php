<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Socialite OAuth Routes
|--------------------------------------------------------------------------
*/
Route::get('/auth/{provider}', [SocialiteController::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('auth.callback');
