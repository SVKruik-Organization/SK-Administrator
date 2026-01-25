<?php

declare(strict_types=1);

namespace App\Providers;

use App\Session\DatabaseSessionHandler;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('session', function (SessionManager $manager, Application $app) {
            $manager->extend('database', function () use ($app) {
                /** @var string|null $connectionName */
                $connectionName = Config::get('session.connection');

                /** @var \Illuminate\Database\ConnectionInterface $connection */
                $connection = DB::connection($connectionName ?? null);

                $tableValue = Config::get('session.table', 'sessions');
                /** @var string $table */
                $table = is_string($tableValue) ? $tableValue : 'sessions';

                $lifetimeValue = Config::get('session.lifetime', 120);
                /** @var int $lifetime */
                $lifetime = is_int($lifetimeValue) ? $lifetimeValue : (is_numeric($lifetimeValue) ? (int) $lifetimeValue : 120);

                return new DatabaseSessionHandler($connection, $table, $lifetime, $app);
            });

            return $manager;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn (): ?Password => app()->isProduction()
                ? Password::min(12)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : null
        );
    }
}
