<?php

declare(strict_types=1);

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('regenerates the session on sign-in, closing session fixation', function (): void {
    $user = User::factory()->candidate()->create();

    $this->get(route('frontend.login'));
    $before = session()->getId();

    $this->post(route('frontend.login'), ['login' => $user->email, 'password' => 'Correct-Horse-9!']);

    expect(session()->getId())->not->toBe($before);
});

it('invalidates the session on sign-out', function (): void {
    $user = User::factory()->candidate()->create();

    $this->actingAs($user)->post(route('frontend.logout'))->assertRedirect(route('frontend.login'));

    $this->assertGuest();
});

// M26-R01 — every state change is audited with actor and IP.

it('audits a successful sign-in with the factor used', function (): void {
    $user = User::factory()->candidate()->create();

    $this->post(route('frontend.login'), ['login' => $user->email, 'password' => 'Correct-Horse-9!']);

    $entry = AuditLog::query()->where('event', 'auth.login')->latest('sequence')->firstOrFail();

    expect($entry->actor_id)->toBe((int) $user->getKey())
        ->and($entry->properties['factor'])->toBe('password')
        ->and($entry->actor_ip)->not->toBeNull();
});

it('audits a failed sign-in without recording the password', function (): void {
    User::factory()->candidate()->create(['email' => 'aisha.khan@gmail.com']);

    $this->post(route('frontend.login'), ['login' => 'aisha.khan@gmail.com', 'password' => 'wrong-password']);

    $entry = AuditLog::query()->where('event', 'auth.login_failed')->firstOrFail();

    expect((string) json_encode($entry->properties))->not->toContain('wrong-password');
});

it('stamps the last sign-in without disturbing the audit chain', function (): void {
    $user = User::factory()->candidate()->create();

    $this->post(route('frontend.login'), ['login' => $user->email, 'password' => 'Correct-Horse-9!']);

    expect($user->fresh()->last_login_at)->not->toBeNull();

    // saveQuietly, so a login stamp does not masquerade as a model update in
    // the record. The login itself is already audited as auth.login.
    expect(AuditLog::query()->where('event', 'model.updated')->count())->toBe(0);
});

it('verifies the chain after a full sign-in and sign-out cycle', function (): void {
    $user = User::factory()->candidate()->create();

    $this->post(route('frontend.login'), ['login' => $user->email, 'password' => 'Correct-Horse-9!']);
    $this->post(route('frontend.logout'));

    $report = app(App\Domain\Audit\VerifyAuditChain::class)->handle();

    expect($report->intact)->toBeTrue();
});
