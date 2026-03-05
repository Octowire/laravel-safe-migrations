<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations;

use Illuminate\Support\ServiceProvider;
use Octowire\SafeMigrations\Console\SafeRollbackCommand;

final class SafeMigrationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/safe-migrations.php', 'safe-migrations');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/safe-migrations.php' => $this->app->configPath('safe-migrations.php'),
        ], 'safe-migrations-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                SafeRollbackCommand::class,
            ]);
        }
    }
}
