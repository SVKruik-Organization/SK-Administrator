<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

Route::group(['prefix' => 'authentication', 'as' => 'authentication.'], function () {
    Route::redirect('/', '/authentication/login')->name('index');
    Route::get('/login', [App\Http\Controllers\Authentication\LoginController::class, 'login'])->name('login');
    Route::get('/callback', [App\Http\Controllers\Authentication\LoginController::class, 'callback'])->name('callback');
});

Route::group(['prefix' => 'panel', 'middleware' => 'auth.guest', 'as' => 'panel.'], function () {
    Route::get('/', [App\Http\Controllers\Panel\PanelController::class, 'index'])->name('index');

    // Top items
    Route::get('/dashboard', [App\Http\Controllers\Panel\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\Panel\UserController::class, 'index'])->name('user');

    // Modules
    Route::group(['prefix' => 'activity', 'as' => 'activity.'], function () {
        Route::redirect('/', '/panel/activity/logs')->name('index');
        Route::get('/logs', [App\Http\Controllers\Panel\ActivityController::class, 'logs'])->name('logs');
        Route::get('/purchases', [App\Http\Controllers\Panel\ActivityController::class, 'purchases'])->name('purchases');
    });

    Route::group(['prefix' => 'guilds', 'as' => 'guilds.'], function () {
        Route::redirect('/', '/panel/guilds/admins')->name('index');
        Route::get('/admins', [App\Http\Controllers\Panel\GuildController::class, 'admins'])->name('admins');
        Route::get('/blocked', [App\Http\Controllers\Panel\GuildController::class, 'blocked'])->name('blocked');
        Route::get('/economy', [App\Http\Controllers\Panel\GuildController::class, 'economy'])->name('economy');
        Route::get('/events', [App\Http\Controllers\Panel\GuildController::class, 'events'])->name('events');
        Route::get('/overview', [App\Http\Controllers\Panel\GuildController::class, 'overview'])->name('overview');
        Route::get('/tier', [App\Http\Controllers\Panel\GuildController::class, 'tier'])->name('tier');
    });

    Route::group(['prefix' => 'operators', 'as' => 'operators.'], function () {
        Route::redirect('/', '/panel/operators/members')->name('index');
        Route::get('/members', [App\Http\Controllers\Panel\OperatorController::class, 'members'])->name('members');
        Route::get('/owners', [App\Http\Controllers\Panel\OperatorController::class, 'owners'])->name('owners');
        Route::get('/teams', [App\Http\Controllers\Panel\OperatorController::class, 'teams'])->name('teams');
    });

    Route::group(['prefix' => 'preferences', 'as' => 'preferences.'], function () {
        Route::redirect('/', '/panel/preferences/notifications')->name('index');
        Route::get('/notifications', [App\Http\Controllers\Panel\PreferencesController::class, 'notifications'])->name('notifications');
        Route::get('/profile', [App\Http\Controllers\Panel\PreferencesController::class, 'profile'])->name('profile');
        Route::get('/security', [App\Http\Controllers\Panel\PreferencesController::class, 'security'])->name('security');
    });

    Route::group(['prefix' => 'records', 'as' => 'records.'], function () {
        Route::redirect('/', '/panel/records/bug-reports')->name('index');
        Route::get('/bug-reports', [App\Http\Controllers\Panel\RecordController::class, 'bugReports'])->name('bugReports');
        Route::get('/questions', [App\Http\Controllers\Panel\RecordController::class, 'questions'])->name('questions');
        Route::get('/tickets', [App\Http\Controllers\Panel\RecordController::class, 'tickets'])->name('tickets');
        Route::get('/user-reports', [App\Http\Controllers\Panel\RecordController::class, 'userReports'])->name('userReports');
        Route::get('/user-warnings', [App\Http\Controllers\Panel\RecordController::class, 'userWarnings'])->name('userWarnings');
    });

    Route::group(['prefix' => 'status', 'as' => 'status.'], function () {
        Route::redirect('/', '/panel/status/hardware')->name('index');
        Route::get('/hardware', [App\Http\Controllers\Panel\StatusController::class, 'hardware'])->name('hardware');
        Route::get('/sites', [App\Http\Controllers\Panel\StatusController::class, 'sites'])->name('sites');
    });
});

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [App\Http\Controllers\User\UserProfileController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\User\UserProfileController::class, 'update'])->name('update');
        Route::put('/switch/{userProfile}', [App\Http\Controllers\User\UserProfileController::class, 'switch'])->name('switch');
    });
});
