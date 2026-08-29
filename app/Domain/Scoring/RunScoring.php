<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\Application;
use App\Models\ApplicationSnapshot;
use App\Models\RuleSetVersion;
use App\Models\ScoreRun;
use App\Models\User;
use App\Support\Canonical\CanonicalJson;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * Computes a score, or records why it could not.
 *
 * All five invariants meet here:
 *
 *   I1  the version comes from the application, never from what is active now
 *   I2  the input is a snapshot, and snapshots are append-only
 *   I3  input_hash = H(snapshot ‖ ruleset); the same input gives the same
 *       output_hash, by construction rather than by assertion
 *   I4  every line carries a rule id and a citation, both NOT NULL
 *   I5  an unratified rule blocks the run instead of being guessed at
 */
final class RunScoring
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly ResolveStrategy $resolve,
    ) {}

    public function handle(
        Application $application,
        ?User $actor = null,
        bool $sandbox = false,
        ?RuleSetVersion $override = null,
    ): ScoreRun {
        $snapshot = $application->snapshots()->latest('taken_at')->first();

        if (! $snapshot instanceof ApplicationSnapshot) {
            throw new RuntimeException('That application has no snapshot to score.');
        }

        // I1. A sandbox run may override the version deliberately -- that is
        // what a sandbox is for -- but a real run never may.
        $version = $override ?? RuleSetVersion::query()->find($application->rule_set_version_id);

        if (! $version instanceof RuleSetVersion) {
            throw new RuntimeException('That application carries no frozen ruleset version.');
        }

        $strategy = $this->resolve->for($version);

        // I3. Both halves: change either the dossier or the rules and this
        // changes, which is what makes a repeat run comparable.
        $inputHash = hash('sha256', $snapshot->content_hash.'|'.$version->content_hash);

        return $this->connection->transaction(function () use (
            $application, $snapshot, $version, $strategy, $inputHash, $actor, $sandbox
        ): ScoreRun {
            try {
                $result = $strategy->score($snapshot, $version);
            } catch (PendingRatificationError $e) {
                // I5. Blocked is a recorded outcome naming the rule, not an
                // error somebody has to find in a log. The Executive Council
                // decides; the engine does not.
                return $this->record($application, $snapshot, $version, $strategy, $inputHash, $actor, $sandbox, blockedBy: $e->ruleId);
            }

            $run = $this->record($application, $snapshot, $version, $strategy, $inputHash, $actor, $sandbox, total: $result->total);

            foreach ($result->lines as $line) {
                $run->lines()->create($line);
            }

            // The output hash covers the lines as well as the total: two runs
            // that agree on the total but disagree on why are not the same run.
            $run->forceFill([
                'output_hash' => CanonicalJson::hash([
                    'total' => $result->total,
                    'lines' => $result->lines,
                ]),
            ])->save();

            return $run->refresh();
        });
    }

    private function record(
        Application $application,
        ApplicationSnapshot $snapshot,
        RuleSetVersion $version,
        ScoringStrategy $strategy,
        string $inputHash,
        ?User $actor,
        bool $sandbox,
        ?float $total = null,
        ?string $blockedBy = null,
    ): ScoreRun {
        return ScoreRun::query()->create([
            'application_id' => $application->getKey(),
            'snapshot_id' => $snapshot->getKey(),
            'rule_set_version_id' => $version->getKey(),
            'strategy' => $strategy->name(),
            'total' => $total,
            'status' => $blockedBy === null ? 'computed' : 'blocked',
            'blocked_by_rule' => $blockedBy,
            'input_hash' => $inputHash,
            'is_sandbox' => $sandbox,
            'computed_at' => CarbonImmutable::now(),
            'computed_by_id' => $actor?->getKey(),
        ]);
    }
}
