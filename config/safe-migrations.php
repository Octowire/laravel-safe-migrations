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
    ]
];
