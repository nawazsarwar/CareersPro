<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\LifecycleState;
use App\Models\Application;
use App\Models\Post;
use App\Models\User;
use App\Support\Canonical\CanonicalJson;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * Submission, atomically.
 *
 * Snapshot written, hash computed, ruleset versions copied, gate rows created
 * for the ACTIVE gates only, dossier locked, number allocated -- or none of it.
 * A partial submission is the worst outcome available here: a candidate with a
 * number and no snapshot cannot be scored, and one with a snapshot and no
 * number cannot be found.
 */
final class SubmitApplication
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly AllocateApplicationNumber $numbers,
        private readonly AssertCompleteness $completeness,
        private readonly BuildSnapshotPayload $payload,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(User $user, Post $post, ?string $category = null): Application
    {
        if (! $post->isOpen()) {
            throw new RuntimeException('That vacancy is not open for applications.');
        }

        $missing = $this->completeness->check($user, $post);

        if ($missing !== []) {
            throw new RuntimeException('The dossier is incomplete: '.implode('; ', $missing));
        }

        return $this->connection->transaction(function () use ($user, $post, $category): Application {
            $application = Application::query()->create([
                'application_no' => $this->numbers->next($post),
                'user_id' => $user->getKey(),
                'post_id' => $post->getKey(),
                'advertisement_id' => $post->advertisement_id,
                'applied_under_category' => $category,
                'lifecycle_state' => LifecycleState::Submitted,
            ]);

            $advertisement = $post->advertisement;

            $application->forceFill([
                'submitted' => true,
                'submitted_at' => CarbonImmutable::now(),
                // Copied, not referenced. The advertisement's own values are
                // already frozen; copying them means a later correction to the
                // advertisement cannot re-score an application made under the
                // old ones.
                'rule_set_version_id' => $advertisement->rule_set_version_id,
                'relaxation_policy_version_id' => $advertisement->relaxation_policy_version_id,
            ])->save();

            $payload = $this->payload->for($application->load('user.profile'));

            $application->snapshots()->create([
                'taken_at' => CarbonImmutable::now()->format('Y-m-d H:i:s.u'),
                'reason' => 'submit',
                'payload' => $payload,
                'content_hash' => CanonicalJson::hash($payload),
            ]);

            /*
             * The ACTIVE gates only. An interview-only post gets two rows, not
             * three, so the workbench cannot offer a written-test decision for
             * a post that has no written test -- which the legacy modal did.
             */
            foreach ($post->activeGates() as $gate) {
                $application->eligibilityDecisions()->create([
                    'gate' => $gate,
                    'decision' => null,      // pending, and not the same as rejected
                ]);
            }

            $application->statusHistory()->create([
                'from_state' => LifecycleState::Draft->value,
                'to_state' => LifecycleState::Submitted->value,
                'actor_id' => $user->getKey(),
                'at' => CarbonImmutable::now(),
            ]);

            // The dossier is locked from here. A deficiency reopens a specific
            // field for a bounded window (M18); the legacy hard lock after
            // payment had no way back at all.
            $user->profile?->forceFill(['locked' => true])->save();

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::ApplicationSubmitted,
                properties: ['application_no' => $application->application_no, 'post_id' => (int) $post->getKey()],
                subject: $application,
                actorId: (int) $user->getKey(),
            ));

            return $application->refresh();
        });
    }
}
