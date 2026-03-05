<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations\Snapshots;

final class SchemaSnapshot
{
    public function __construct(
        public readonly array $tables
    ) {}

    public function toArray(): array
    {
        return [
            'tables' => $this->tables,
        ];
    }
}
