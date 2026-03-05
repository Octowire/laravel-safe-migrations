<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

it('blocks rollback in production by default', function (): void {
    config()->set('safe-migrations.rollback_guard.enabled', true);
    config()->set('safe-migrations.rollback_guard.protected_environments', ['production']);
    config()->set('safe-migrations.rollback_guard.allow_rollback_env_key', 'SAFE_MIGRATIONS_ALLOW_ROLLBACK');

    $this->app['env'] = 'production';

    $exit = Artisan::call('safe-migrate:rollback');

    expect($exit)->toBe(1)
        ->and(Artisan::output())->toContain('Rollback is blocked in this environment.');
});

it('allows rollback in production when force flag is used', function (): void {
    config()->set('safe-migrations.rollback_guard.enabled', true);
    config()->set('safe-migrations.rollback_guard.protected_environments', ['production']);

    $this->app['env'] = 'production';

    // Fake migrate:rollback to isolate the guard logic — we're testing that the guard
    // doesn't block, not that the underlying command executes correctly.
    Artisan::command('migrate:rollback {--force}', function (): int {
        $this->line('FAKE_ROLLBACK_OK');
        return 0;
    });

    $exit = Artisan::call('safe-migrate:rollback', ['--force-unsafe' => true]);
    $output = Artisan::output();

    expect($exit)->toBe(0)
        ->and($output)->toContain('FAKE_ROLLBACK_OK')
        ->and($output)->not->toContain('Rollback is blocked in this environment.');
});
