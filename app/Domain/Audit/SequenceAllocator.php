<?php

declare(strict_types=1);

namespace App\Domain\Audit;

use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * Hands out the next audit sequence number (M26-R05).
 *
 * The sequence must be gapless, because a gap is indistinguishable from a
 * deleted record -- which is exactly the claim the chain exists to refute.
 * Auto-increment cannot provide that: it burns values on rollback and on
 * duplicate-key failure.
 *
 * So the value comes from a single counter row taken FOR UPDATE. Concurrent
 * writers serialise on that row, and the allocation lives inside the caller's
 * transaction, so a rolled-back write returns its number rather than leaving a
 * hole.
 */
final class SequenceAllocator
{
    public function __construct(private readonly ConnectionInterface $connection) {}

    public function next(): int
    {
        if ($this->connection->transactionLevel() === 0) {
            throw new RuntimeException(
                'A sequence must be allocated inside a transaction, or a failed write leaves a gap.'
            );
        }

        $row = $this->connection->table('audit_sequence')
            ->where('id', 1)
            ->lockForUpdate()
            ->first();

        if ($row === null) {
            throw new RuntimeException('The audit sequence counter is missing; the audit log cannot be written.');
        }

        /** @var int $next */
        $next = (int) $row->next_value;

        $this->connection->table('audit_sequence')
            ->where('id', 1)
            ->update(['next_value' => $next + 1]);

        return $next;
    }
}
