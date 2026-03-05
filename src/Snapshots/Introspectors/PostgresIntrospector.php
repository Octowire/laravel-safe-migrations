<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations\Snapshots\Introspectors;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Schema;
use Octowire\SafeMigrations\Snapshots\SchemaIntrospector;

final class PostgresIntrospector implements SchemaIntrospector
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {}

    public function driver(): string
    {
        return 'pgsql';
    }

    public function tables(): array
    {
        $rows = $this->connection->select(
            "
            SELECT tablename AS name
            FROM pg_tables
            WHERE schemaname = 'public'
            ORDER BY tablename
            "
        );

        $tables = [];

        $excluded = (array) config('safe-migrations.snapshots.exclude_tables', []);

        foreach ($rows as $row) {
            $table = (string) ($row->name ?? '');

            if ($table === '') {
                continue;
            }

            if (in_array($table, $excluded, true)) {
                continue;
            }

            $tables[$table] = [
                'columns' => Schema::connection($this->connection->getName())->getColumnListing($table),
            ];
        }

        ksort($tables);

        return $tables;
    }
}
