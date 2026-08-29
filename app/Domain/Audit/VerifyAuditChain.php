<?php

declare(strict_types=1);

namespace App\Domain\Audit;

use App\Models\AuditLog;

/**
 * Recomputes the chain and reports the first sequence that does not match
 * (M26-R04).
 *
 * Two independent things are checked at every link: that the stored hash still
 * describes the stored row, and that the row's previous_hash still matches its
 * predecessor's hash. The first catches an edited field; the second catches a
 * deleted or reordered entry. A tamperer has to defeat both, for every
 * subsequent row, and the signed checkpoints as well.
 */
final class VerifyAuditChain
{
    public function handle(?int $from = null, ?int $to = null): ChainReport
    {
        $from = max(1, $from ?? 1);
        $to ??= (int) AuditLog::query()->max('sequence');

        if ($to < $from) {
            return new ChainReport(intact: true, from: $from, to: $from, verified: 0);
        }

        $expectedPrevious = $this->hashBefore($from);
        $expectedSequence = $from;
        $verified = 0;

        /** @var iterable<int, AuditLog> $entries */
        $entries = AuditLog::query()
            ->whereBetween('sequence', [$from, $to])
            ->orderBy('sequence')
            ->cursor();

        foreach ($entries as $entry) {
            if ($entry->sequence !== $expectedSequence) {
                return new ChainReport(
                    intact: false,
                    from: $from,
                    to: $to,
                    verified: $verified,
                    brokenAt: $expectedSequence,
                    reason: sprintf(
                        'Expected sequence %d, found %d. An entry is missing.',
                        $expectedSequence,
                        $entry->sequence,
                    ),
                );
            }

            if ($entry->previous_hash !== $expectedPrevious) {
                return new ChainReport(
                    intact: false,
                    from: $from,
                    to: $to,
                    verified: $verified,
                    brokenAt: $entry->sequence,
                    reason: 'The previous hash does not match the preceding entry.',
                );
            }

            $recomputed = RecordAuditEvent::hash($this->payloadOf($entry));

            if (! hash_equals($entry->hash, $recomputed)) {
                return new ChainReport(
                    intact: false,
                    from: $from,
                    to: $to,
                    verified: $verified,
                    brokenAt: $entry->sequence,
                    reason: 'The stored hash does not match the stored content; the entry was altered.',
                );
            }

            $expectedPrevious = $entry->hash;
            $expectedSequence++;
            $verified++;
        }

        if ($verified === 0) {
            return new ChainReport(
                intact: false,
                from: $from,
                to: $to,
                verified: 0,
                brokenAt: $from,
                reason: 'No entries found in the requested range.',
            );
        }

        return new ChainReport(intact: true, from: $from, to: $to, verified: $verified);
    }

    private function hashBefore(int $sequence): string
    {
        if ($sequence <= 1) {
            return AuditLog::GENESIS_HASH;
        }

        $hash = AuditLog::query()->where('sequence', $sequence - 1)->value('hash');

        return is_string($hash) ? $hash : AuditLog::GENESIS_HASH;
    }

    /**
     * Rebuilds exactly the payload that was hashed at write time. The field
     * order is irrelevant -- CanonicalJson sorts keys -- but the set of fields
     * and their types are not.
     *
     * @return array<string, mixed>
     */
    private function payloadOf(AuditLog $entry): array
    {
        return [
            'sequence' => $entry->sequence,
            'previous_hash' => $entry->previous_hash,
            'event' => $entry->event,
            'subject_type' => $entry->subject_type,
            'subject_id' => $entry->subject_id === null ? null : (int) $entry->subject_id,
            'actor_id' => $entry->actor_id === null ? null : (int) $entry->actor_id,
            'impersonator_id' => $entry->impersonator_id === null ? null : (int) $entry->impersonator_id,
            'actor_ip' => $entry->actor_ip,
            'actor_role' => $entry->actor_role,
            'properties' => $entry->properties,
            'occurred_at' => $entry->occurred_at->format('Y-m-d H:i:s.u'),
        ];
    }
}
