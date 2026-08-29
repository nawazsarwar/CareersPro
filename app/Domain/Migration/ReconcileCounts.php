<?php

declare(strict_types=1);

namespace App\Domain\Migration;

use Illuminate\Support\Facades\DB;

/**
 * Row counts must agree before cut-over.
 *
 * A migration that "mostly worked" is a failed migration, and the only way to
 * know is to count both sides and compare. This reports rather than asserts,
 * because the answer is a decision for the Registrar's Office and the Finance
 * Office to sign off, not for a script to take.
 *
 * The three orphan backup tables -- 215,946 rows across
 * `applicationforms_20102025_1856`, `applicationforms_24072026_0300` and
 * `applicationforms_backup_27012025_1709` -- are deliberately NOT counted as
 * source. They are point-in-time copies of a table being migrated in full, and
 * the working assumption is archive to cold storage. OQ-004 must confirm it.
 */
final class ReconcileCounts
{
    /**
     * @var array<string, string> legacy table => local table
     */
    private const PAIRS = [
        'users' => 'users',
        'applicationforms' => 'applications',
        'orders' => 'orders',
    ];

    /**
     * @return list<array{source: string, target: string, source_rows: int|null, target_rows: int, migrated: int, quarantined: int, reconciled: bool}>
     */
    public function handle(): array
    {
        $report = [];

        foreach (self::PAIRS as $source => $target) {
            $sourceRows = $this->countSource($source);
            $migrated = (int) DB::table($target)->whereNotNull('legacy_id')->count();
            $quarantined = (int) DB::table('migration_quarantine')->where('source_table', $source)->count();

            $report[] = [
                'source' => $source,
                'target' => $target,
                // Null where the legacy connection is absent: the suite and a
                // developer machine must not need it (DR-009's principle
                // applied to migration too).
                'source_rows' => $sourceRows,
                'target_rows' => (int) DB::table($target)->count(),
                'migrated' => $migrated,
                'quarantined' => $quarantined,
                'reconciled' => $sourceRows !== null && $sourceRows === $migrated + $quarantined,
            ];
        }

        return $report;
    }

    private function countSource(string $table): ?int
    {
        if (! array_key_exists('mysql_readonly', (array) config('database.connections'))) {
            return null;
        }

        try {
            return (int) DB::connection('mysql_readonly')->table($table)->count();
        } catch (\Throwable) {
            // Unreachable is not zero. Reporting zero would make an
            // unreconciled migration look perfectly reconciled.
            return null;
        }
    }
}
