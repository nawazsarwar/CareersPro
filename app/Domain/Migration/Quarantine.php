<?php

declare(strict_types=1);

namespace App\Domain\Migration;

use Illuminate\Support\Facades\DB;

/**
 * Rows the migration could not map, kept rather than skipped.
 *
 * "Mostly worked" is a failed migration. A quarantined row is one a human has
 * to look at; a skipped row is one nobody ever will.
 */
final class Quarantine
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function hold(string $sourceTable, int $sourceId, string $reason, array $payload, ?string $note = null): void
    {
        DB::table('migration_quarantine')->updateOrInsert(
            ['source_table' => $sourceTable, 'source_id' => $sourceId],
            [
                'reason' => $reason,
                'payload' => (string) json_encode($payload),
                'note' => $note,
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }

    public function outstanding(?string $sourceTable = null): int
    {
        return (int) DB::table('migration_quarantine')
            ->whereNull('resolved_at')
            ->when($sourceTable !== null, static fn ($query) => $query->where('source_table', $sourceTable))
            ->count();
    }
}
