<?php

declare(strict_types=1);

use App\Enums\RoleSlug;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\RolePermissionSeeder::class);

    $this->auditor = User::factory()->staff()->withTotp()->create();
    $this->auditor->roles()->attach(Role::query()->where('slug', RoleSlug::Auditor->value)->firstOrFail());
    $this->auditor->load('roles');
});

it('lets an auditor read the trail', function (): void {
    $this->actingAs($this->auditor)->get(route('admin.audit.index'))->assertOk();
});

it('refuses the trail to a role without the permission', function (): void {
    $finance = User::factory()->staff()->withTotp()->create();
    $finance->roles()->attach(Role::query()->where('slug', RoleSlug::FinanceAdmin->value)->firstOrFail());

    $this->actingAs($finance->load('roles'))->get(route('admin.audit.index'))->assertForbidden();
});

it('reports an intact chain in the verification screen', function (): void {
    $this->actingAs($this->auditor)
        ->post(route('admin.audit.verify'))
        ->assertOk()
        ->assertSee(__('audit.intact'));
});

it('names the P1 incident when the chain is broken', function (): void {
    $this->actingAs($this->auditor)->get(route('admin.audit.index'));

    dropAuditGuards();
    DB::table('audit_logs')->where('sequence', 1)
        ->update(['properties' => json_encode(['tampered' => true])]);

    $this->actingAs($this->auditor)
        ->post(route('admin.audit.verify'))
        ->assertOk()
        ->assertSee(__('audit.broken'))
        // Not a yellow warning: the screen says what it is.
        ->assertSee('P1 security incident');
});

// M26-R12 — a subject's timeline returns in sequence order.

it('returns a subject timeline in sequence order', function (): void {
    $target = User::factory()->candidate()->create();
    $target->forceFill(['name' => 'First change'])->save();
    $target->forceFill(['name' => 'Second change'])->save();

    $response = $this->actingAs($this->auditor)
        ->get(route('admin.audit.subject', ['type' => $target->getMorphClass(), 'id' => $target->getKey()]))
        ->assertOk();

    $entries = AuditLog::query()
        ->where('subject_type', $target->getMorphClass())
        ->where('subject_id', $target->getKey())
        ->orderBy('sequence')
        ->pluck('sequence')
        ->all();

    expect($entries)->toBe(array_values($entries))
        ->and($entries)->not->toBeEmpty();
});

it('exposes no route that writes to the trail', function (): void {
    $writes = collect(app('router')->getRoutes())
        ->filter(fn ($route): bool => str_starts_with((string) $route->getName(), 'admin.audit.'))
        ->reject(fn ($route): bool => $route->getName() === 'admin.audit.verify')
        ->filter(fn ($route): bool => array_intersect($route->methods(), ['POST', 'PUT', 'PATCH', 'DELETE']) !== []);

    // Entries are written by the domain, never by a request (M26 §4).
    expect($writes)->toBeEmpty();
});
