<?php

declare(strict_types=1);

use App\Domain\Scoring\Apportion;
use App\Domain\Scoring\RunScoring;
use App\Models\Application;
use App\Models\RuleSetVersion;
use App\Models\ScoreRun;
use App\Support\Canonical\CanonicalJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->version = RuleSetVersion::factory()->create();
    $this->application = Application::factory()->create(['rule_set_version_id' => $this->version->getKey()]);
});

function snapshotWith(Application $application, array $payload): void
{
    $application->snapshots()->create([
        'taken_at' => now()->format('Y-m-d H:i:s.u'),
        'reason' => 'submit',
        'payload' => $payload,
        'content_hash' => CanonicalJson::hash($payload),
    ]);
}

function claim(array $overrides = []): array
{
    return array_merge([
        'id' => 1,
        'category' => 'journal_paper',
        'evidence_document_id' => 10,
        'authorship_role' => 'sole',
        'coauthor_count' => 1,
    ], $overrides);
}

// The error that would have made every senior determination wrong.

it('gives a Principal Investigator and a Co-Investigator fifty per cent each', function (): void {
    $apportion = app(Apportion::class);

    // The Gazette, the FN-1 form and the AMU Ordinances all say 50/50. The
    // previous rules file asserted PI 100 per cent and Co-PI 50 per cent,
    // which would have inflated every Associate Professor and Professor
    // determination involving a joint project.
    expect($apportion->for(['authorship_role' => 'pi'], $this->version))->toBe(0.5)
        ->and($apportion->for(['authorship_role' => 'co_pi'], $this->version))->toBe(0.5);
});

it('applies the joint-authorship apportionment from the Gazette', function (): void {
    $apportion = app(Apportion::class);

    expect($apportion->for(['coauthor_count' => 1], $this->version))->toBe(1.0)
        ->and($apportion->for(['coauthor_count' => 2], $this->version))->toBe(0.7)
        ->and($apportion->for(['coauthor_count' => 4, 'authorship_role' => 'first'], $this->version))->toBe(0.7)
        ->and($apportion->for(['coauthor_count' => 4, 'authorship_role' => 'joint'], $this->version))->toBe(0.3);
});

it('scores Column II at ten points and Column I at eight', function (): void {
    // DR-014 puts Librarian and Physical Education cadres in Column II. A flat
    // base of 8 -- which the previous file used -- understates every Column II
    // candidate by a fifth.
    snapshotWith($this->application, ['candidate' => ['faculty_column' => 'II'], 'claims' => [claim()]]);

    $run = app(RunScoring::class)->handle($this->application);

    expect((float) $run->total)->toBe(10.0);

    $second = Application::factory()->create(['rule_set_version_id' => $this->version->getKey()]);
    snapshotWith($second, ['candidate' => ['faculty_column' => 'I'], 'claims' => [claim()]]);

    expect((float) app(RunScoring::class)->handle($second)->total)->toBe(8.0);
});

it('has six impact-factor bands, beginning below one', function (): void {
    // The previous file omitted the "less than 1" band, so every band shifted
    // down one and a paper with no impact factor scored as one with a high
    // impact factor.
    $bands = $this->version->rule('impact_factor.bands');

    expect($bands[0]['max'])->toBe(1)
        ->and($bands[0]['points'])->toBe(5)
        ->and($bands[1]['points'])->toBe(10);
});

// I4 — explainability.

it('writes a rule id and a citation on every line', function (): void {
    snapshotWith($this->application, ['claims' => [claim(), claim(['id' => 2, 'category' => 'book'])]]);

    $run = app(RunScoring::class)->handle($this->application);

    expect($run->lines)->toHaveCount(2);

    foreach ($run->lines as $line) {
        // A total without per-line citations is a number the University cannot
        // defend to the candidate it was used against.
        expect($line->rule_id)->not->toBeEmpty()
            ->and($line->citation)->toContain('UGC 2018');
    }
});

it('scores a claim with no evidence at zero and says why', function (): void {
    // Table 2's header enumerates the evidence each claim must carry. It is
    // recorded as a line rather than dropped, so the candidate can see why.
    snapshotWith($this->application, ['claims' => [claim(['evidence_document_id' => null])]]);

    $run = app(RunScoring::class)->handle($this->application);

    expect((float) $run->total)->toBe(0.0)
        ->and($run->lines->first()->explanation)->toBe(__('scoring.no_evidence'));
});

// I3 — determinism.

it('produces the same output hash for the same input', function (): void {
    snapshotWith($this->application, ['claims' => [claim()]]);

    $first = app(RunScoring::class)->handle($this->application);
    $second = app(RunScoring::class)->handle($this->application->refresh());

    // By construction rather than by assertion.
    expect($second->input_hash)->toBe($first->input_hash)
        ->and($second->output_hash)->toBe($first->output_hash);
});

it('changes the input hash when either the dossier or the rules change', function (): void {
    snapshotWith($this->application, ['claims' => [claim()]]);
    $first = app(RunScoring::class)->handle($this->application);

    snapshotWith($this->application, ['claims' => [claim(), claim(['id' => 2])]]);
    $second = app(RunScoring::class)->handle($this->application->refresh());

    expect($second->input_hash)->not->toBe($first->input_hash);
});

// I1 — the frozen ruleset.

it('scores against the application version, not whatever is active now', function (): void {
    snapshotWith($this->application, ['claims' => [claim()]]);

    $newer = RuleSetVersion::factory()->create(['version' => '2018.2']);

    $run = app(RunScoring::class)->handle($this->application);

    // An advertisement published under 2018 rules scores under 2018 rules for
    // ever, whatever is notified afterwards.
    expect($run->rule_set_version_id)->toBe((int) $this->version->getKey())
        ->and($run->rule_set_version_id)->not->toBe((int) $newer->getKey());
});

// I5 — refuse, never guess.

it('blocks rather than guessing at the impact-factor ambiguity', function (): void {
    $version = RuleSetVersion::factory()->pendingRatification()->create(['version' => '2018.pending']);
    $application = Application::factory()->create(['rule_set_version_id' => $version->getKey()]);

    snapshotWith($application, ['claims' => [claim(['impact_factor' => 3.2])]]);

    $run = app(RunScoring::class)->handle($application);

    // Whether the impact-factor values replace or are added to the base is
    // worth 160 to 200 points to a Professor applicant with twenty papers,
    // against a threshold of 120. A scoring engine that picks a reading has
    // quietly made University policy.
    expect($run->isBlocked())->toBeTrue()
        ->and($run->blocked_by_rule)->toBe('impact_factor')
        ->and($run->total)->toBeNull();
});

it('records the blocked run rather than throwing it away', function (): void {
    $version = RuleSetVersion::factory()->pendingRatification()->create(['version' => '2018.pending2']);
    $application = Application::factory()->create(['rule_set_version_id' => $version->getKey()]);

    snapshotWith($application, ['claims' => [claim(['impact_factor' => 3.2])]]);
    app(RunScoring::class)->handle($application);

    // Blocked is an outcome the Executive Council can act on, not an error
    // somebody has to find in a log.
    expect(ScoreRun::query()->where('status', 'blocked')->count())->toBe(1);
});

it('refuses a rule that carries no citation', function (): void {
    $payload = $this->version->payload;
    unset($payload['categories']['journal_paper']['citation']);

    $version = RuleSetVersion::factory()->create([
        'version' => '2018.uncited',
        'payload' => $payload,
        'content_hash' => CanonicalJson::hash($payload),
    ]);

    $application = Application::factory()->create(['rule_set_version_id' => $version->getKey()]);
    snapshotWith($application, ['claims' => [claim()]]);

    // No value enters a ruleset without a clause reference. An empty citation
    // would satisfy the column while defeating its purpose.
    expect(fn () => app(RunScoring::class)->handle($application))
        ->toThrow(LogicException::class, 'carries no citation');
});

// The 2025 draft — why scoring is polymorphic at all.

it('counts areas rather than points under the 2025 draft', function (): void {
    $version = RuleSetVersion::factory()->draft2025()->create();
    $application = Application::factory()->create(['rule_set_version_id' => $version->getKey()]);

    snapshotWith($application, ['claims' => [
        ['area' => 'teaching', 'evidence_document_id' => 1],
        ['area' => 'research', 'evidence_document_id' => 2],
    ]]);

    $run = app(RunScoring::class)->handle($application);

    // The draft abolishes the Research Score outright. A single class built
    // for Table 2 would not have survived it.
    expect($run->strategy)->toBe('threshold_count')
        ->and((float) $run->total)->toBe(2.0);
});

it('keeps the non-teaching skill test qualifying only', function (): void {
    $version = RuleSetVersion::factory()->nonTeaching()->create();
    $application = Application::factory()->create(['rule_set_version_id' => $version->getKey()]);

    snapshotWith($application, ['marks' => [
        'paper_one' => 60, 'paper_two' => 55, 'interview' => 15, 'skill_test' => 40,
    ]]);

    $run = app(RunScoring::class)->handle($application);

    // 60 + 55 + (15 × 0.2) = 118. The skill test passes and adds nothing.
    expect((float) $run->total)->toBe(118.0);

    $skill = $run->lines->firstWhere('rule_id', 'components.skill_test');

    expect((float) $skill->points)->toBe(0.0)
        ->and($skill->explanation)->toContain('does not add to the rank');
});

it('selects the strategy from the frozen ruleset, not a runtime flag', function (): void {
    $teaching = RuleSetVersion::factory()->create(['version' => '2018.a']);
    $draft = RuleSetVersion::factory()->draft2025()->create(['version' => '2025.a']);

    expect(app(App\Domain\Scoring\ResolveStrategy::class)->for($teaching)->name())->toBe('weighted_points')
        ->and(app(App\Domain\Scoring\ResolveStrategy::class)->for($draft)->name())->toBe('threshold_count');
});
