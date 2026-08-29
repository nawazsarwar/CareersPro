<?php

declare(strict_types=1);

namespace App\Domain\Eligibility;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Models\Application;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

final class DecideGate
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly ActiveGates $activeGates,
        private readonly GateOrder $order,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(
        Application $application,
        EligibilityGate $gate,
        ?GateDecision $decision,
        ?string $remark,
        User $actor,
    ): void {
        if (! $this->activeGates->includes($application->post, $gate)) {
            throw new RuntimeException(sprintf(
                'This post has no %s gate, so it cannot be decided.',
                $gate->value,
            ));
        }

        // A rejection without a reason is not appealable, and an unappealable
        // rejection is one the University cannot defend.
        if ($decision === GateDecision::Rejected && trim((string) $remark) === '') {
            throw new RuntimeException('A rejection must state its reason.');
        }

        // An officer must not decide their own application. The guard is here
        // rather than in the policy because it is a property of the decision,
        // not of the screen it was reached from.
        if ((int) $application->user_id === (int) $actor->getKey()) {
            throw new RuntimeException('An officer cannot decide their own application.');
        }

        $application->loadMissing('eligibilityDecisions');

        $this->order->assertDecidable($application, $gate);

        $this->connection->transaction(function () use ($application, $gate, $decision, $remark, $actor): void {
            $row = $application->eligibilityDecisions()
                ->where('gate', $gate)
                ->firstOrFail();

            $previous = $row->decision;

            $row->forceFill([
                'decision' => $decision,
                'remark' => $remark,
                // Always the acting user, never a default: "who decided this"
                // is the first question a service appeal asks.
                'decided_by_id' => $actor->getKey(),
                'decided_at' => CarbonImmutable::now(),
            ])->save();

            // Revisable, never silently overwritten. The prior value travels
            // with the entry (CRR Rule 22.4 permits verification at any time,
            // even after joining).
            $this->audit->handle(new AuditEvent(
                event: AuditEventName::EligibilityDecided,
                properties: [
                    'gate' => $gate->value,
                    'from' => $previous?->value,
                    'to' => $decision?->value,
                    'remark' => $remark,
                ],
                subject: $application,
                actorId: (int) $actor->getKey(),
                actorRole: $actor->auditRole(),
            ));
        });
    }
}
