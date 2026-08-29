<?php

declare(strict_types=1);

namespace App\Domain\Audit;

use App\Models\AuditLog;
use App\Support\Canonical\CanonicalJson;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;

/**
 * Appends one link to the chain (M26 §3).
 *
 * Sequence allocation, hashing and insertion happen in one transaction, so a
 * failure anywhere returns the sequence number rather than leaving a gap that
 * would read as a deleted record.
 */
final class RecordAuditEvent
{
    /**
     * A checkpoint every 10,000 entries (M26-R11). It lets verification start
     * from a known-good cumulative hash instead of replaying from genesis.
     */
    private const CHECKPOINT_INTERVAL = 10_000;

    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly SequenceAllocator $sequence,
        private readonly RedactProperties $redact,
    ) {}

    public function handle(AuditEvent $event): AuditLog
    {
        return $this->connection->transaction(function () use ($event): AuditLog {
            $sequence = $this->sequence->next();
            $previousHash = $this->previousHash($sequence);

            // Server-generated, never taken from the caller (M26 §5).
            $occurredAt = $event->occurredAt ?? CarbonImmutable::now();

            $payload = [
                'sequence' => $sequence,
                'previous_hash' => $previousHash,
                'event' => $event->event->value,
                'subject_type' => $event->subjectType(),
                'subject_id' => $event->subjectId(),
                'actor_id' => $event->actorId,
                'impersonator_id' => $event->impersonatorId,
                'actor_ip' => $event->actorIp,
                'actor_role' => $event->actorRole,
                'properties' => $this->redact->handle($event->properties),
                'occurred_at' => $occurredAt->format('Y-m-d H:i:s.u'),
            ];

            $log = new AuditLog;
            $log->forceFill($payload + ['hash' => self::hash($payload)]);
            $log->save();

            $this->checkpointIfDue($sequence, $log->hash);

            return $log;
        });
    }

    /**
     * The hash covers every field of the entry including its position in the
     * chain, so altering any one of them, or reordering two entries, breaks it.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function hash(array $payload): string
    {
        return CanonicalJson::hash($payload);
    }

    private function previousHash(int $sequence): string
    {
        if ($sequence === 1) {
            return AuditLog::GENESIS_HASH;
        }

        $previous = AuditLog::query()
            ->where('sequence', $sequence - 1)
            ->value('hash');

        // A missing predecessor means the chain is already broken; refusing to
        // append is better than papering over it with a genesis hash.
        return is_string($previous) ? $previous : AuditLog::GENESIS_HASH;
    }

    private function checkpointIfDue(int $sequence, string $hash): void
    {
        if ($sequence % self::CHECKPOINT_INTERVAL !== 0) {
            return;
        }

        $this->connection->table('audit_checkpoints')->insert([
            'sequence' => $sequence,
            'cumulative_hash' => $hash,
            'created_at' => CarbonImmutable::now()->format('Y-m-d H:i:s.u'),
        ]);
    }
}
