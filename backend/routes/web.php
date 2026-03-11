<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

Route::group(['prefix' => 'authentication', 'as' => 'authentication.'], function () {
    Route::redirect('/', '/authentication/login')->name('index');
    Route::get('/login', [App\Http\Controllers\AuthenticationController::class, 'login'])->name('login');
    Route::get('/callback', [App\Http\Controllers\AuthenticationController::class, 'callback'])->name('callback');
    Route::post('/logout', [App\Http\Controllers\AuthenticationController::class, 'logout'])->name('logout');
});

Route::group(['prefix' => 'panel', 'as' => 'panel.', 'middleware' => 'auth.guest'], function () {
    Route::get('/', [App\Http\Controllers\Panel\PanelController::class, 'index'])->name('index');
    Route::get('/notifications', [App\Http\Controllers\Panel\NotificationController::class, 'index'])->name('notifications');

    // Top items
    Route::get('/dashboard', [App\Http\Controllers\Panel\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\Panel\UserController::class, 'index'])->name('user');

    // Modules
    Route::group(['prefix' => 'guilds', 'as' => 'guilds.'], function () {
        Route::redirect('/', '/panel/guilds/overview')->name('index');
        Route::get('/overview', [App\Http\Controllers\Panel\GuildController::class, 'overview'])->name('overview');
        Route::get('/economy', [App\Http\Controllers\Panel\GuildController::class, 'economy'])->name('economy');
        Route::get('/tier', [App\Http\Controllers\Panel\GuildController::class, 'tier'])->name('tier');
        Route::get('/admins', [App\Http\Controllers\Panel\GuildController::class, 'admins'])->name('admins');
        Route::get('/blocked', [App\Http\Controllers\Panel\GuildController::class, 'blocked'])->name('blocked');
        Route::get('/events', [App\Http\Controllers\Panel\GuildController::class, 'events'])->name('events');
    });

    Route::group(['prefix' => 'operators', 'as' => 'operators.'], function () {
        Route::redirect('/', '/panel/operators/teams')->name('index');
        Route::get('/teams', [App\Http\Controllers\Panel\OperatorController::class, 'teams'])->name('teams');
        Route::get('/owners', [App\Http\Controllers\Panel\OperatorController::class, 'owners'])->name('owners');
        Route::get('/members', [App\Http\Controllers\Panel\OperatorController::class, 'members'])->name('members');
    });

    Route::group(['prefix' => 'status', 'as' => 'status.'], function () {
        Route::redirect('/', '/panel/status/sites')->name('index');
        Route::get('/sites', [App\Http\Controllers\Panel\StatusController::class, 'sites'])->name('sites');
        Route::get('/hardware', [App\Http\Controllers\Panel\StatusController::class, 'hardware'])->name('hardware');
    });

    Route::group(['prefix' => 'activity', 'as' => 'activity.'], function () {
        Route::redirect('/', '/panel/activity/logs')->name('index');
        Route::get('/logs', [App\Http\Controllers\Panel\ActivityController::class, 'logs'])->name('logs');
        Route::get('/purchases', [App\Http\Controllers\Panel\ActivityController::class, 'purchases'])->name('purchases');
    });

    Route::group(['prefix' => 'records', 'as' => 'records.'], function () {
        Route::redirect('/', '/panel/records/tickets')->name('index');
        Route::get('/tickets', [App\Http\Controllers\Panel\RecordController::class, 'tickets'])->name('tickets');
        Route::get('/questions', [App\Http\Controllers\Panel\RecordController::class, 'questions'])->name('questions');
        Route::get('/user-reports', [App\Http\Controllers\Panel\RecordController::class, 'userReports'])->name('userReports');
        Route::get('/user-warnings', [App\Http\Controllers\Panel\RecordController::class, 'userWarnings'])->name('userWarnings');
        Route::get('/bug-reports', [App\Http\Controllers\Panel\RecordController::class, 'bugReports'])->name('bugReports');
    });

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::redirect('/', '/panel/settings/modules')->name('index');
        Route::get('/modules', [App\Http\Controllers\Panel\SettingsController::class, 'modules'])->name('modules');
    });
});

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'auth.guest'], function () {
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [App\Http\Controllers\User\UserProfileController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\User\UserProfileController::class, 'update'])->name('update');
        Route::put('/switch/{userProfile}', [App\Http\Controllers\User\UserProfileController::class, 'switch'])->name('switch');
    });

    Route::group(['prefix' => 'preferences', 'as' => 'preferences.'], function () {
        Route::get('/', [App\Http\Controllers\User\UserPreferencesController::class, 'index'])->name('index');
    });
});
