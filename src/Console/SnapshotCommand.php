<?php

declare(strict_types=1);

namespace Octowire\SafeMigrations\Console;

use Illuminate\Console\Command;
use Octowire\SafeMigrations\Snapshots\SnapshotManager;

final class SnapshotCommand extends Command
{
    protected $signature = 'safe-migrate:snapshot {--database= : The database connection to use}';

    protected $description = 'Capture a snapshot of the current database schema';

    public function handle(SnapshotManager $manager): int
    {
        $snapshot = $manager->capture($this->option('database'));

        $file = $manager->store($snapshot);

        $this->info("Snapshot stored at: {$file}");

        return self::SUCCESS;
    }
}
