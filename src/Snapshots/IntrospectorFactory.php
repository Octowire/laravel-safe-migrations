<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations\Snapshots;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Octowire\SafeMigrations\Snapshots\Introspectors\MySqlIntrospector;
use Octowire\SafeMigrations\Snapshots\Introspectors\PostgresIntrospector;
use Octowire\SafeMigrations\Snapshots\Introspectors\SqliteIntrospector;
use RuntimeException;

final class IntrospectorFactory
{
    public function make(?string $connectionName = null): SchemaIntrospector
    {
        /** @var ConnectionInterface $connection */
        $connection = DB::connection($connectionName);

        return match ($connection->getDriverName()) {
            'sqlite' => new SqliteIntrospector($connection),
            'mysql', 'mariadb' => new MySqlIntrospector($connection),
            'pgsql' => new PostgresIntrospector($connection),
            default => throw new RuntimeException(sprintf(
                'safe-migrations: driver "%s" is not supported for schema snapshots.',
                $connection->getDriverName()
            )),
        };
    }
}
