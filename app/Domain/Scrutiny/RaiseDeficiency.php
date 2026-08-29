<?php

declare(strict_types=1);

namespace App\Domain\Scrutiny;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\LifecycleState;
use App\Models\Application;
use App\Models\Deficiency;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * The differentiator CU-Chayan lacks, and the answer to the legacy portal's
 * hard lock.
 *
 * The legacy system told candidates they "are not allowed to update/modify in
 * any circumstances" once they had paid, so a missing certificate meant
 * rejection with no way back. CU-Chayan's users report the same: screening
 * outcomes with no itemised reason and no window to respond.
 *
 * Here a deficiency names a specific field, opens a bounded window, and
 * re-opens only that field. It is not a general re-open: a candidate who could
 * rewrite their whole dossier after the closing date would be applying under
 * different terms from everybody else.
 */
final class RaiseDeficiency
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(
        Application $application,
        User $actor,
        string $description,
        ?string $fieldReference = null,
        ?int $windowDays = null,
    ): Deficiency {
        if (trim($description) === '') {
            throw new RuntimeException('A deficiency must say what is deficient.');
        }

        $days = $windowDays ?? (int) config('scrutiny.rectification_window_days', 7);

        return $this->connection->transaction(function () use ($application, $actor, $description, $fieldReference, $days): Deficiency {
            $deficiency = $application->deficiencies()->create([
                'raised_by_id' => $actor->getKey(),
                'raised_at' => CarbonImmutable::now(),
                'field_reference' => $fieldReference,
                'description' => $description,
                // A window with an end, stated to the candidate. "We will let
                // you know" is what the legacy process offered.
                'rectification_window_closes_at' => CarbonImmutable::now()->addDays($days)->endOfDay(),
            ]);

            $application->forceFill(['lifecycle_state' => LifecycleState::Deficient])->save();

            $application->statusHistory()->create([
                'from_state' => LifecycleState::UnderScrutiny->value,
                'to_state' => LifecycleState::Deficient->value,
                'actor_id' => $actor->getKey(),
                'at' => CarbonImmutable::now(),
                'reason' => $description,
            ]);

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::DeficiencyRaised,
                properties: ['field' => $fieldReference, 'window_days' => $days],
                subject: $application,
                actorId: (int) $actor->getKey(),
                actorRole: $actor->auditRole(),
            ));

            return $deficiency;
        });
    }
}
