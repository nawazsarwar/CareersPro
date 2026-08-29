<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Migration\ReconcileCounts;
use Illuminate\Console\Command;

/**
 * Console only. A migration behind a web request is a migration that times
 * out halfway through 78,232 rows.
 */
class MigrateLegacyReconcile extends Command
{
    protected $signature = 'migrate:legacy:reconcile';

    protected $description = 'Compare legacy row counts against migrated rows and report any gap.';

    public function handle(ReconcileCounts $reconcile): int
    {
        $rows = $reconcile->handle();

        $this->table(
            ['Source', 'Target', 'Source rows', 'Migrated', 'Quarantined', 'Reconciled'],
            array_map(static fn (array $row): array => [
                $row['source'],
                $row['target'],
                $row['source_rows'] ?? 'unreachable',
                $row['migrated'],
                $row['quarantined'],
                $row['reconciled'] ? 'yes' : 'NO',
            ], $rows),
        );

        $unreconciled = array_filter($rows, static fn (array $row): bool => ! $row['reconciled']);

        if ($unreconciled !== []) {
            $this->error('Not reconciled. A migration that mostly worked is a failed migration.');

            return self::FAILURE;
        }

        $this->info('Reconciled.');

        return self::SUCCESS;
    }
}
