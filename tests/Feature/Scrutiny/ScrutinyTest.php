<?php

declare(strict_types=1);

use App\Domain\Eligibility\DecideGate;
use App\Domain\Scrutiny\BuildQueue;
use App\Domain\Scrutiny\ExpireDeficiencies;
use App\Domain\Scrutiny\OpenScrutiny;
use App\Domain\Scrutiny\RaiseDeficiency;
use App\Domain\Scrutiny\RectifyDeficiency;
use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Enums\LifecycleState;
use App\Enums\RoleSlug;
use App\Models\Application;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
    $this->seed(Database\Seeders\MasterDataSeeder::class);

    $this->officer = User::factory()->staff()->withTotp()->create();
    $this->officer->roles()->attach(Role::query()->where('slug', RoleSlug::ScrutinyOfficer->value)->firstOrFail());
    $this->officer->load('roles');

    $this->application = Application::factory()->create([
        'submitted' => true,
        'paid' => true,
        'lifecycle_state' => LifecycleState::Submitted,
    ]);

    foreach (['scrutiny', 'interview'] as $gate) {
        $this->application->eligibilityDecisions()->create(['gate' => $gate, 'decision' => null]);
    }

    $this->application->load('eligibilityDecisions', 'post');
});

it('opens an application for scrutiny and records the transition', function (): void {
    app(OpenScrutiny::class)->handle($this->application, $this->officer);

    expect($this->application->refresh()->lifecycle_state)->toBe(LifecycleState::UnderScrutiny)
        ->and($this->application->statusHistory()->count())->toBe(1);
});

it('refuses an officer their own application', function (): void {
    $own = Application::factory()->create([
        'user_id' => $this->officer->getKey(),
        'submitted' => true,
    ]);

    expect(fn () => app(OpenScrutiny::class)->handle($own, $this->officer))
        ->toThrow(RuntimeException::class, 'their own application');

    expect($this->officer->can('scrutinise', $own))->toBeFalse();
});

// M34 — a gate that is not active cannot be decided.

it('refuses a gate this post does not have', function (): void {
    // The legacy modal enabled all three regardless, so an officer could
    // record a written-test decision for a post with no written test.
    expect(fn () => app(DecideGate::class)->handle(
        $this->application,
        EligibilityGate::WrittenTest,
        GateDecision::Eligible,
        null,
        $this->officer,
    ))->toThrow(RuntimeException::class, 'no written_test gate');
});

it('refuses a rejection without a reason', function (): void {
    // A rejection without a reason is not appealable, and an unappealable
    // rejection is one the University cannot defend.
    expect(fn () => app(DecideGate::class)->handle(
        $this->application,
        EligibilityGate::Scrutiny,
        GateDecision::Rejected,
        '   ',
        $this->officer,
    ))->toThrow(RuntimeException::class, 'must state its reason');
});

it('records a decision with the acting officer and a timestamp', function (): void {
    app(DecideGate::class)->handle(
        $this->application,
        EligibilityGate::Scrutiny,
        GateDecision::Eligible,
        'Documents verified against claims.',
        $this->officer,
    );

    $decision = $this->application->refresh()->eligibilityDecisions->firstWhere('gate.value', 'scrutiny');

    expect($decision->decision)->toBe(GateDecision::Eligible)
        // "Who decided this" is the first question a service appeal asks.
        ->and($decision->decided_by_id)->toBe((int) $this->officer->getKey())
        ->and($decision->decided_at)->not->toBeNull();
});

// Ordered, but independent.

it('refuses a later gate until the earlier one has cleared', function (): void {
    expect(fn () => app(DecideGate::class)->handle(
        $this->application,
        EligibilityGate::Interview,
        GateDecision::Eligible,
        null,
        $this->officer,
    ))->toThrow(RuntimeException::class, 'until scrutiny has cleared');
});

it('leaves an earlier decision untouched when a later gate rejects', function (): void {
    $decide = app(DecideGate::class);

    $decide->handle($this->application, EligibilityGate::Scrutiny, GateDecision::Eligible, null, $this->officer);
    $decide->handle($this->application->refresh()->load('eligibilityDecisions'), EligibilityGate::Interview, GateDecision::Rejected, 'Did not attend.', $this->officer);

    // "Cleared scrutiny, failed the interview" is a different thing from
    // "rejected at scrutiny" and carries different rights.
    $decisions = $this->application->refresh()->eligibilityDecisions->keyBy('gate.value');

    expect($decisions['scrutiny']->decision)->toBe(GateDecision::Eligible)
        ->and($decisions['interview']->decision)->toBe(GateDecision::Rejected);
});

it('carries the prior value when a decision is revised', function (): void {
    $decide = app(DecideGate::class);

    $decide->handle($this->application, EligibilityGate::Scrutiny, GateDecision::Eligible, null, $this->officer);
    $decide->handle($this->application->refresh()->load('eligibilityDecisions'), EligibilityGate::Scrutiny, GateDecision::Rejected, 'Certificate found invalid.', $this->officer);

    // Revisable, never silently overwritten (CRR Rule 22.4).
    $entries = AuditLog::query()->where('event', 'eligibility.decided')->orderBy('sequence')->get();

    expect($entries)->toHaveCount(2)
        ->and($entries[1]->properties['from'])->toBe('eligible')
        ->and($entries[1]->properties['to'])->toBe('rejected');
});

it('lets a decision be cleared back to pending', function (): void {
    $decide = app(DecideGate::class);

    $decide->handle($this->application, EligibilityGate::Scrutiny, GateDecision::Eligible, null, $this->officer);
    $decide->handle($this->application->refresh()->load('eligibilityDecisions'), EligibilityGate::Scrutiny, null, null, $this->officer);

    expect($this->application->refresh()->eligibilityDecisions->firstWhere('gate.value', 'scrutiny')->decision)
        ->toBeNull();
});

// The rectification window.

it('raises a deficiency with a stated closing time', function (): void {
    $deficiency = app(RaiseDeficiency::class)->handle(
        $this->application,
        $this->officer,
        'The category certificate is missing.',
        'profile.category_certificate',
    );

    // The legacy portal told candidates they "are not allowed to update or
    // modify in any circumstances" after paying, so a missing certificate
    // meant rejection with no way back.
    expect($deficiency->rectification_window_closes_at)->not->toBeNull()
        ->and($deficiency->isOpen())->toBeTrue()
        ->and($this->application->refresh()->lifecycle_state)->toBe(LifecycleState::Deficient);
});

it('writes a new snapshot on rectification and leaves the first untouched', function (): void {
    $deficiency = app(RaiseDeficiency::class)->handle($this->application, $this->officer, 'Missing certificate.');

    $before = $this->application->snapshots()->count();

    app(RectifyDeficiency::class)->handle($deficiency, $this->application->user, 'Certificate uploaded.');

    // Both are evidence: a decision made against the first must remain
    // reconstructible.
    expect($this->application->refresh()->snapshots()->count())->toBe($before + 1)
        ->and($this->application->snapshots()->where('reason', 'correction_window')->exists())->toBeTrue()
        ->and($this->application->lifecycle_state)->toBe(LifecycleState::UnderScrutiny);
});

it('refuses a rectification after the window has closed', function (): void {
    $deficiency = app(RaiseDeficiency::class)->handle($this->application, $this->officer, 'Missing certificate.');
    $deficiency->forceFill(['rectification_window_closes_at' => now()->subDay()])->save();

    expect(fn () => app(RectifyDeficiency::class)->handle($deficiency, $this->application->user, 'Too late.'))
        ->toThrow(RuntimeException::class, 'window for correcting this deficiency has closed');
});

it('turns an expired window into a stated rejection, not a silence', function (): void {
    $deficiency = app(RaiseDeficiency::class)->handle($this->application, $this->officer, 'Missing certificate.');
    $deficiency->forceFill(['rectification_window_closes_at' => now()->subDay()])->save();

    $expired = app(ExpireDeficiencies::class)->handle();

    // A window that simply lapses leaves the candidate in limbo and the
    // University with an application nobody decided.
    expect($expired)->toBe(1)
        ->and($this->application->refresh()->eligibilityDecisions->firstWhere('gate.value', 'scrutiny')->decision)
        ->toBe(GateDecision::Rejected);
});

// The queue.

it('applies the visibility scope before any filter', function (): void {
    $queue = app(BuildQueue::class)->for($this->officer, ['scrutiny' => 'pending']);

    // A scrutiny_officer with no organisational unit is university-wide, so
    // the paid submitted application is in scope.
    expect($queue->count())->toBe(1);
});

it('leaves an unpaid application out of the queue', function (): void {
    $this->application->forceFill(['paid' => false])->save();
    $this->application->post->advertisement->forceFill(['default_fee' => 500])->save();

    // Examining a dossier that cannot proceed either way wastes the officer's
    // time and the candidate's.
    expect(app(BuildQueue::class)->for($this->officer)->count())->toBe(0);
});

it('keeps a fee-exempt application in the queue although unpaid', function (): void {
    $this->application->forceFill(['paid' => false])->save();
    $this->application->post->advertisement->forceFill(['default_fee' => 0])->save();

    expect(app(BuildQueue::class)->for($this->officer)->count())->toBe(1);
});
