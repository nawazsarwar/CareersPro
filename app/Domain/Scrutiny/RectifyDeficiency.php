<?php

declare(strict_types=1);

namespace App\Domain\Scrutiny;

use App\Domain\Application\BuildSnapshotPayload;
use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\LifecycleState;
use App\Models\Deficiency;
use App\Models\User;
use App\Support\Canonical\CanonicalJson;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * A rectification writes a NEW snapshot. The earlier one is untouched.
 *
 * That is the whole point of an append-only snapshot table: what the dossier
 * said at submission and what it says after correction are both evidence, and
 * a scrutiny decision made against the first must remain reconstructible.
 */
final class RectifyDeficiency
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly BuildSnapshotPayload $payload,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(Deficiency $deficiency, User $actor, string $resolution): void
    {
        if (! $deficiency->isOpen()) {
            throw new RuntimeException('That deficiency has already been resolved.');
        }

        if ($deficiency->windowHasClosed()) {
            throw new RuntimeException('The window for correcting this deficiency has closed.');
        }

        $this->connection->transaction(function () use ($deficiency, $actor, $resolution): void {
            $deficiency->forceFill([
                'rectified_at' => CarbonImmutable::now(),
                'rectified_by_id' => $actor->getKey(),
                'resolution' => $resolution,
            ])->save();

            $application = $deficiency->application;
            $payload = $this->payload->for($application->load('user.profile'));

            $application->snapshots()->create([
                'taken_at' => CarbonImmutable::now()->format('Y-m-d H:i:s.u'),
                'reason' => 'correction_window',
                'payload' => $payload,
                'content_hash' => CanonicalJson::hash($payload),
            ]);

            // Back to scrutiny only when nothing else is outstanding.
            if ($application->deficiencies()->whereNull('rectified_at')->doesntExist()) {
                $application->forceFill(['lifecycle_state' => LifecycleState::UnderScrutiny])->save();

                $application->statusHistory()->create([
                    'from_state' => LifecycleState::Deficient->value,
                    'to_state' => LifecycleState::UnderScrutiny->value,
                    'actor_id' => $actor->getKey(),
                    'at' => CarbonImmutable::now(),
                ]);
            }

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::DeficiencyRectified,
                properties: ['deficiency_id' => (int) $deficiency->getKey()],
                subject: $application,
                actorId: (int) $actor->getKey(),
            ));
        });
    }
}
