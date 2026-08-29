<?php

declare(strict_types=1);

namespace App\Domain\Custody;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Models\DestructionBatch;
use App\Models\HardcopyReceipt;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Records that paper was destroyed, and by whose authority.
 *
 * The electronic record is untouched. What this writes is the fact that a
 * batch of physical dossiers was destroyed on a date by a named officer --
 * which is what an RTI request or a service appeal will ask for, and what the
 * previous system could not answer because it recorded nothing.
 */
final class ExecuteDestruction
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(User $authorisedBy, ?CarbonImmutable $on = null, ?string $note = null): DestructionBatch
    {
        $on ??= CarbonImmutable::now();

        return $this->connection->transaction(function () use ($authorisedBy, $on, $note): DestructionBatch {
            $due = HardcopyReceipt::query()
                ->whereNull('destruction_batch_id')
                ->whereNotNull('destruction_due_on')
                ->whereDate('destruction_due_on', '<=', $on->toDateString())
                ->get();

            if ($due->isEmpty()) {
                throw new RuntimeException('No dossiers are due for destruction.');
            }

            $batch = DestructionBatch::query()->create([
                'reference' => 'DEST-'.strtoupper(Str::random(8)),
                'destroyed_on' => $on->toDateString(),
                // Named, never a system default: somebody authorised this.
                'authorised_by_id' => $authorisedBy->getKey(),
                'dossier_count' => $due->count(),
                'note' => $note,
            ]);

            HardcopyReceipt::query()
                ->whereIn('id', $due->modelKeys())
                ->update(['destruction_batch_id' => $batch->getKey()]);

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::HardcopyDestroyed,
                properties: ['batch' => $batch->reference, 'count' => $due->count()],
                subject: $batch,
                actorId: (int) $authorisedBy->getKey(),
                actorRole: $authorisedBy->auditRole(),
            ));

            return $batch;
        });
    }
}
