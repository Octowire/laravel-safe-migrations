<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;

final class SafeRollbackCommand extends Command
{
    protected $signature = 'safe-migrate:rollback
        {--database= : The database connection to use}
        {--path=* : The path(s) to the migrations files to be executed}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--step=0 : The number of migrations to be reverted}
        {--batch= : The batch of migrations that should be reverted}
        {--pretend : Dump the SQL queries that would be run}
        {--force-unsafe : Allow rollback in protected environments (DANGEROUS)}';

    protected $description = 'Safely rollback migrations (blocked in production by default).';

    public function handle(Application $app): int
    {
        if (! $this->isRollbackAllowed($app)) {
            $this->components->error('Rollback is blocked in this environment.');
            $this->line('To proceed anyway, use --force-unsafe or enable the env flag configured by safe-migrations.');
            return self::FAILURE;
        }


        $exit = $this->callRollbackCommand();

        if ($exit === self::SUCCESS) {
            $this->components->info('Rollback executed.');
        }

        return $exit;
    }

    private function isRollbackAllowed(Application $app): bool
    {
        $guard = (array) config('safe-migrations.rollback_guard', []);

        $enabled = (bool) ($guard['enabled'] ?? true);
        if (! $enabled) {
            return true;
        }

        $protectedEnvs = (array) ($guard['protected_environments'] ?? ['production']);
        $isProtectedEnv = in_array($app->environment(), $protectedEnvs, true);

        if (! $isProtectedEnv) {
            return true;
        }

        if ((bool) $this->option('force-unsafe') === true) {
            $this->components->warn('WARNING: You are bypassing rollback protection (--force-unsafe).');
            return true;
        }

        $envKey = (string) ($guard['allow_rollback_env_key'] ?? 'SAFE_MIGRATIONS_ALLOW_ROLLBACK');
        $envValue = env($envKey);

        return filter_var($envValue, FILTER_VALIDATE_BOOL) === true;
    }

    private function callRollbackCommand(): int
    {
        $options = [
            '--database' => $this->option('database'),
            '--realpath' => (bool) $this->option('realpath'),
            '--pretend'  => (bool) $this->option('pretend'),

            // IMPORTANT:
            // migrate:rollback prompts for confirmation in production unless --force is provided.
            // Since our command already guards dangerous environments, we always force the underlying command.
            '--force'    => true,
        ];

        $path = (array) $this->option('path');
        if ($path !== []) {
            $options['--path'] = $path;
        }

        $step = (int) ($this->option('step') ?? 0);
        if ($step > 0) {
            $options['--step'] = $step;
        }

        $batch = $this->option('batch');
        if ($batch !== null && $batch !== '') {
            $options['--batch'] = $batch;
        }

        return $this->call(
            'migrate:rollback',
            array_filter($options, fn ($v) => $v !== null && $v !== false && $v !== '')
        );
    }
}
