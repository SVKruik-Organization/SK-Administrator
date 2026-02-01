<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

Route::group(['prefix' => 'authentication'], function () {
    Route::get('/login', [App\Http\Controllers\Authentication\LoginController::class, 'login'])->name('login');
    Route::get('/callback', [App\Http\Controllers\Authentication\LoginController::class, 'callback'])->name('callback');
});

Route::group(['prefix' => 'panel', 'middleware' => 'auth.guest'], function () {
    Route::get('/', [App\Http\Controllers\PanelController::class, 'index'])->name('panel.index');
});
