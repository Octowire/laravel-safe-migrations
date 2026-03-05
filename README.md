# Laravel Safe Migrations

Prevents accidental rollbacks in production by blocking `migrate:rollback` unless explicitly authorized.

Lightweight, safe by default, and fully compatible with Laravel's native migration system.

---

## Features

- Blocks `migrate:rollback` in protected environments (production by default)
- Explicit override via `--force-unsafe` flag or environment variable
- Drop-in replacement — delegates to Laravel's native `migrate:rollback` under the hood
- Zero impact on your existing migration workflow

---

## Installation

```bash
composer require octowire/laravel-safe-migrations
```

Laravel will auto-discover the service provider.

---

## Usage

Replace `migrate:rollback` with:

```bash
php artisan safe-migrate:rollback
```

All native options (`--step`, `--batch`, `--pretend`, `--path`, `--database`) are supported.

---

## Rollback Guard

By default, rollbacks are **blocked in production**. Two ways to override this:

### `--force-unsafe` flag

```bash
php artisan safe-migrate:rollback --force-unsafe
```

A warning will be printed. Use with caution.

### Environment variable

```env
SAFE_MIGRATIONS_ALLOW_ROLLBACK=true
```

Useful for CI/CD pipelines or staging environments that share a protected environment name.

---

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=safe-migrations-config
```

`config/safe-migrations.php`:

```php
return [

    'rollback_guard' => [

        // Set to false to disable all protection
        'enabled' => true,

        // Environments where rollback is blocked
        'protected_environments' => [
            'production',
        ],

        // Env key that allows rollback even in protected environments
        'allow_rollback_env_key' => 'SAFE_MIGRATIONS_ALLOW_ROLLBACK',

    ],

];
```

---

## Compatibility

| Laravel    | PHP  |
|------------|------|
| 10, 11, 12 | 8.2+ |

---

## Contributing

PRs and issues welcome.

---

## License

MIT
