<?php

declare(strict_types=1);

use App\Enums\GateDecision;
use App\Enums\LifecycleState;
use App\Enums\RoleSlug;
use App\Models\Application;
use App\Models\OrganisationalUnit;
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
        'submitted' => true, 'paid' => true, 'lifecycle_state' => LifecycleState::Submitted,
    ]);

    foreach (['scrutiny', 'interview'] as $gate) {
        $this->application->eligibilityDecisions()->create(['gate' => $gate, 'decision' => null]);
    }
});

// DR-021 §10.1 — one route, two representations.

it('returns JSON when the request asks for it', function (): void {
    $response = $this->actingAs($this->officer)
        ->postJson(route('admin.scrutiny.gates', $this->application), [
            'gate' => 'scrutiny',
            'decision' => 'eligible',
            'remark' => 'Documents verified.',
        ])
        ->assertOk();

    expect($response->json('decision'))->toBe('eligible')
        ->and($response->json('label'))->toContain(__('application.decision_eligible'));
});

it('redirects back when the same route is posted as a form', function (): void {
    // With JavaScript off the officer loses nothing but the absence of a page
    // reload. One route, one Form Request, one policy check, one audit entry.
    $this->actingAs($this->officer)
        ->post(route('admin.scrutiny.gates', $this->application), [
            'gate' => 'scrutiny',
            'decision' => 'eligible',
            'remark' => 'Documents verified.',
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    expect($this->application->refresh()->eligibilityDecisions->firstWhere('gate.value', 'scrutiny')->decision)
        ->toBe(GateDecision::Eligible);
});

it('renders the workbench without a page-level fetch dependency', function (): void {
    $html = $this->actingAs($this->officer)
        ->get(route('admin.scrutiny.show', $this->application))
        ->assertOk()
        ->getContent();

    // Every control is a real form; Alpine only intercepts.
    expect(substr_count($html, '<form method="POST"'))->toBeGreaterThanOrEqual(3)
        ->and($html)->toContain('@csrf' === '' ? '' : 'name="_token"');
});

it('refuses the workbench to a role without the permission', function (): void {
    $finance = User::factory()->staff()->withTotp()->create();
    $finance->roles()->attach(Role::query()->where('slug', RoleSlug::FinanceAdmin->value)->firstOrFail());

    $this->actingAs($finance->load('roles'))
        ->get(route('admin.scrutiny.index'))
        ->assertForbidden();
});

// DR-015 — viewing, deciding and creating are different people.

it('lets a dean office viewer read but not decide', function (): void {
    $unit = OrganisationalUnit::factory()->create();

    $viewer = User::factory()->staff()->withTotp()->create();
    $viewer->roles()->attach(
        Role::query()->where('slug', RoleSlug::DeanOfficeView->value)->firstOrFail(),
        ['organisational_unit_id' => $unit->getKey()],
    );
    $viewer->load('roles');

    $this->application->post->forceFill(['ou_path_snapshot' => $unit->fresh()->path])->save();
    $this->application->refresh();

    expect($viewer->can('scrutinise', $this->application))->toBeTrue()
        ->and($viewer->can('decideGate', $this->application))->toBeFalse();
});

it('states the reason back to the officer when a rejection lacks one', function (): void {
    $this->actingAs($this->officer)
        ->post(route('admin.scrutiny.gates', $this->application), [
            'gate' => 'scrutiny',
            'decision' => 'rejected',
            'remark' => '',
        ])
        ->assertSessionHasErrors(['remark' => __('scrutiny.remark_required')]);
});

it('answers a refused decision with a message rather than silence over JSON', function (): void {
    // A gate that silently fails to save is worse than one that cannot be
    // reached at all.
    $this->actingAs($this->officer)
        ->postJson(route('admin.scrutiny.gates', $this->application), [
            'gate' => 'interview',
            'decision' => 'eligible',
        ])
        ->assertStatus(422)
        ->assertJsonPath('message', 'The interview gate cannot be decided until scrutiny has cleared.');
});
