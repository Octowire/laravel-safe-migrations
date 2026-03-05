<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations\Snapshots;

interface SchemaIntrospector
{
    /**
     * @return array<string, array{columns: list<string>}>
     */
    public function tables(): array;
}
