<?php

declare(strict_types=1);

use App\Domain\Communication\RenderTemplate;
use App\Domain\Communication\ResolveSegment;
use App\Domain\Custody\ExecuteDestruction;
use App\Domain\Custody\ScheduleDestruction;
use App\Domain\Grievance\EscalateOverdue;
use App\Domain\Grievance\RaiseGrievance;
use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Enums\LifecycleState;
use App\Enums\RoleSlug;
use App\Models\Application;
use App\Models\Grievance;
use App\Models\HardcopyReceipt;
use App\Models\MessageTemplate;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);
    $this->seed(Database\Seeders\MasterDataSeeder::class);

    $this->admin = User::factory()->staff()->create();
    $this->admin->roles()->attach(Role::query()->where('slug', RoleSlug::RecruitmentAdmin->value)->firstOrFail());
    $this->admin->load('roles');
});

// The template guard.

it('refuses a template that uses an undeclared placeholder', function (): void {
    // Otherwise 78,232 people receive "Dear :name".
    $template = MessageTemplate::query()->create([
        'code' => 'BAD', 'name' => 'Broken', 'channel' => 'email',
        'body' => 'Dear :name, your application :application_no is received.',
        'placeholders' => ['name'],
    ]);

    expect(fn () => (new RenderTemplate)->render($template, ['name' => 'Aisha']))
        ->toThrow(RuntimeException::class, 'undeclared placeholders: application_no');
});

it('refuses to render when a declared value is missing', function (): void {
    $template = MessageTemplate::query()->create([
        'code' => 'OK', 'name' => 'Fine', 'channel' => 'email',
        'body' => 'Dear :name.', 'placeholders' => ['name'],
    ]);

    expect(fn () => (new RenderTemplate)->render($template, ['name' => null]))
        ->toThrow(RuntimeException::class, 'No value was supplied for: name');
});

it('renders a well-formed template', function (): void {
    $template = MessageTemplate::query()->create([
        'code' => 'GOOD', 'name' => 'Fine', 'channel' => 'email',
        'body' => 'Dear :name, your application :app is received.',
        'placeholders' => ['name', 'app'],
    ]);

    expect((new RenderTemplate)->render($template, ['name' => 'Aisha', 'app' => '10087779']))
        ->toBe('Dear Aisha, your application 10087779 is received.');
});

// The segment.

it('distinguishes a pending gate from a rejected one when segmenting', function (): void {
    $post = Post::factory()->create();

    $pending = Application::factory()->create(['post_id' => $post->getKey(), 'advertisement_id' => $post->advertisement_id, 'submitted' => true]);
    $pending->eligibilityDecisions()->create(['gate' => EligibilityGate::Scrutiny, 'decision' => null]);

    $rejected = Application::factory()->create(['post_id' => $post->getKey(), 'advertisement_id' => $post->advertisement_id, 'submitted' => true]);
    $rejected->eligibilityDecisions()->create(['gate' => EligibilityGate::Scrutiny, 'decision' => GateDecision::Rejected]);

    $segment = new ResolveSegment;

    // A mailing to "not eligible" that swept up undecided candidates would
    // tell them they had been refused.
    expect($segment->for($this->admin, ['gate' => 'scrutiny', 'gate_decision' => 'rejected'])->count())->toBe(1)
        ->and($segment->for($this->admin, ['gate' => 'scrutiny', 'gate_decision' => 'pending'])->count())->toBe(1);
});

it('applies the visibility scope to a segment', function (): void {
    Application::factory()->create(['submitted' => true]);

    $candidate = User::factory()->candidate()->create();
    $candidate->roles()->attach(Role::query()->where('slug', RoleSlug::Candidate->value)->firstOrFail());

    // An officer cannot mail another faculty's candidates by widening a
    // filter, and a candidate cannot segment at all.
    expect((new ResolveSegment)->for($candidate->load('roles'), [])->count())->toBe(0);
});

// The grievance desk.

it('opens a grievance with a due date', function (): void {
    $grievance = app(RaiseGrievance::class)->handle(
        User::factory()->candidate()->create(),
        'scrutiny_decision',
        'My category certificate was rejected without a reason.',
    );

    // A desk without a clock is a suggestion box.
    expect($grievance->due_at)->not->toBeNull()
        ->and($grievance->reference)->toStartWith('GRV-')
        ->and($grievance->status)->toBe('open');
});

it('refuses an empty grievance', function (): void {
    expect(fn () => app(RaiseGrievance::class)->handle(User::factory()->create(), 'other', '  '))
        ->toThrow(RuntimeException::class, 'must say what is wrong');
});

it('knows which categories must precede the committee', function (): void {
    // cl. 5.1 VIII(c) requires the selection to be completed on the day of the
    // meeting, so a pre-interview window is the only compatible slot.
    $service = app(RaiseGrievance::class);

    expect($service->mustPrecedeCommittee('scrutiny_decision'))->toBeTrue()
        ->and($service->mustPrecedeCommittee('payment_issue'))->toBeFalse();
});

it('escalates an overdue grievance rather than letting it age quietly', function (): void {
    $grievance = app(RaiseGrievance::class)->handle(User::factory()->create(), 'other', 'Something.');
    $grievance->forceFill(['due_at' => now()->subDay()])->save();

    expect(app(EscalateOverdue::class)->handle())->toBe(1)
        ->and($grievance->refresh()->status)->toBe('escalated')
        ->and($grievance->escalated_at)->not->toBeNull();
});

it('leaves a resolved grievance alone', function (): void {
    $grievance = app(RaiseGrievance::class)->handle(User::factory()->create(), 'other', 'Something.');
    $grievance->forceFill(['due_at' => now()->subDay(), 'resolved_at' => now()])->save();

    expect(app(EscalateOverdue::class)->handle())->toBe(0);
});

it('shows a candidate only their own grievances', function (): void {
    $aisha = User::factory()->candidate()->create();
    $aisha->roles()->attach(Role::query()->where('slug', RoleSlug::Candidate->value)->firstOrFail());

    app(RaiseGrievance::class)->handle($aisha, 'other', 'Mine.');
    app(RaiseGrievance::class)->handle(User::factory()->create(), 'other', 'Theirs.');

    expect(Grievance::query()->visibleTo($aisha->load('roles'))->count())->toBe(1);
});

// DR-011 — physical custody, never data deletion.

it('schedules destruction five years out, for unsuccessful candidates only', function (): void {
    $post = Post::factory()->create();

    $unsuccessful = Application::factory()->create([
        'post_id' => $post->getKey(), 'advertisement_id' => $post->advertisement_id,
        'lifecycle_state' => LifecycleState::NotSelected,
    ]);
    $selected = Application::factory()->create([
        'post_id' => $post->getKey(), 'advertisement_id' => $post->advertisement_id,
        'lifecycle_state' => LifecycleState::Selected,
    ]);

    foreach ([$unsuccessful, $selected] as $application) {
        HardcopyReceipt::query()->create([
            'application_id' => $application->getKey(),
            'received_at' => now(),
            'storage_location' => 'Rack 3',
        ]);
    }

    $scheduled = app(ScheduleDestruction::class)->handle($post);

    // A selected candidate's dossier is retained permanently: CRR Rule 22.4
    // permits verification at any point, even after joining.
    expect($scheduled)->toBe(1)
        ->and(HardcopyReceipt::query()->where('application_id', $selected->getKey())->value('destruction_due_on'))
        ->toBeNull();
});

it('records a destruction batch with a named authorising officer', function (): void {
    $application = Application::factory()->create(['lifecycle_state' => LifecycleState::NotSelected]);

    HardcopyReceipt::query()->create([
        'application_id' => $application->getKey(),
        'received_at' => now()->subYears(6),
        'destruction_due_on' => now()->subDay()->toDateString(),
    ]);

    $batch = app(ExecuteDestruction::class)->handle($this->admin, note: 'Weeding, 2026 cycle.');

    // What an RTI request or a service appeal will ask for, and what the
    // previous system could not answer because it recorded nothing.
    expect($batch->dossier_count)->toBe(1)
        ->and($batch->authorised_by_id)->toBe((int) $this->admin->getKey())
        ->and(App\Models\AuditLog::query()->where('event', 'custody.hardcopy_destroyed')->exists())->toBeTrue();
});

it('never deletes the electronic record', function (): void {
    $application = Application::factory()->create(['lifecycle_state' => LifecycleState::NotSelected]);

    HardcopyReceipt::query()->create([
        'application_id' => $application->getKey(),
        'received_at' => now()->subYears(6),
        'destruction_due_on' => now()->subDay()->toDateString(),
    ]);

    app(ExecuteDestruction::class)->handle($this->admin);

    // DR-011: the weeding is a physical-custody process. Nothing electronic is
    // ever destroyed, and the hash chain stays unbroken.
    expect(Application::query()->whereKey($application->getKey())->exists())->toBeTrue()
        ->and(app(App\Domain\Audit\VerifyAuditChain::class)->handle()->intact)->toBeTrue();
});

it('refuses a destruction run with nothing due', function (): void {
    expect(fn () => app(ExecuteDestruction::class)->handle($this->admin))
        ->toThrow(RuntimeException::class, 'No dossiers are due');
});

it('records a late admission with its postal proof', function (): void {
    // CRR Rule 11 III(d): the Vice-Chancellor may admit a late application on
    // proof of timely posting, and that is a decision, so it is recorded.
    $receipt = HardcopyReceipt::query()->create([
        'application_id' => Application::factory()->create()->getKey(),
        'received_at' => now(),
        'admitted_late' => true,
        'postal_proof_reference' => 'SPEEDPOST EA123456789IN',
    ]);

    expect($receipt->admitted_late)->toBeTrue()
        ->and($receipt->postal_proof_reference)->toContain('EA123456789IN');
});
