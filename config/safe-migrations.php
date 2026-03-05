<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Rollback guard
    |--------------------------------------------------------------------------
    |
    | When enabled, the package will provide a safe rollback command that
    | refuses to run in production unless explicitly forced.
    */
    'rollback_guard' => [
        'enabled' => true,

        'protected_environments' => ['production'],

        'allow_rollback_env_key' => 'SAFE_MIGRATIONS_ALLOW_ROLLBACK',

        'force_flag' => '--force-unsafe',
    ],


    /*
    |--------------------------------------------------------------------------
    | Snapshot Configuration
    |--------------------------------------------------------------------------
    |
    | These options control how schema snapshots are generated.
    |
    | Snapshots capture the structure of the database schema (tables and
    | columns) and store it as JSON files. These snapshots can later be used
    | for schema diffing, auditing migrations, or detecting potentially
    | dangerous changes.
    |
    | The "exclude_tables" option allows you to ignore specific tables when
    | generating snapshots. The Laravel "migrations" table is excluded by
    | default — it tracks migration history, not application schema structure.
    */
    'snapshots' => [

        'exclude_tables' => [
            'migrations',
        ],

    ],


];
