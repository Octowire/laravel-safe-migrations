<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations\Snapshots;

final class SnapshotManager
{
    public function __construct(
        private readonly IntrospectorFactory $factory,
    ) {}

    public function capture(?string $connectionName = null): SchemaSnapshot
    {
        $introspector = $this->factory->make($connectionName);

        return new SchemaSnapshot($introspector->tables());
    }

    public function store(SchemaSnapshot $snapshot): string
    {
        $path = storage_path('safe-migrations');

        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $file = $path . '/snapshot_' . date('Ymd_His') . '.json';

        file_put_contents(
            $file,
            json_encode($snapshot->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $file;
    }
}
