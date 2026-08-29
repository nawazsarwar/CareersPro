<?php

declare(strict_types=1);

use App\Models\ConsentRecord;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

function registrationPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Aisha Khan',
        'email' => 'aisha.khan@gmail.com',
        'password' => 'Correct-Horse-9!',
        'password_confirmation' => 'Correct-Horse-9!',
        'consent' => '1',
    ], $overrides);
}

// M03-R01 — the flow that terminates. It did not before: two half-built
// verification paths, neither finishing, behind a middleware that logged out
// anybody unverified.

it('registers a candidate and sends a verification link', function (): void {
    Notification::fake();

    $this->post(route('frontend.register'), registrationPayload())
        ->assertRedirect(route('frontend.verification.notice'));

    $user = User::query()->where('email', 'aisha.khan@gmail.com')->firstOrFail();

    Notification::assertSentTo($user, VerifyEmail::class);

    expect($user->username)->toBeNull()
        ->and($user->hasVerifiedEmail())->toBeFalse();
});

it('lets a verified user reach the dashboard, and does not log them out', function (): void {
    $user = User::factory()->candidate()->create();

    $this->actingAs($user)->get(route('frontend.dashboard'))->assertOk();

    $this->assertAuthenticatedAs($user);
});

it('sends an unverified user to the notice rather than logging them out', function (): void {
    $user = User::factory()->candidate()->unverified()->create();

    $this->actingAs($user)->get(route('frontend.dashboard'))
        ->assertRedirect(route('frontend.verification.notice'));

    // The distinction that matters: still signed in, so the verification link
    // is reachable. The previous build logged them out here, permanently.
    $this->assertAuthenticatedAs($user);
});

it('creates a profile so the mobile path has somewhere to write', function (): void {
    $this->post(route('frontend.register'), registrationPayload());

    $user = User::query()->where('email', 'aisha.khan@gmail.com')->firstOrFail();

    expect(Profile::query()->where('user_id', $user->getKey())->exists())->toBeTrue();
});

// M03-R10 — DPDP 2023: the version of the notice, not merely the fact.

it('records consent against the notice version', function (): void {
    $this->post(route('frontend.register'), registrationPayload());

    $consent = ConsentRecord::query()->firstOrFail();

    expect($consent->notice_version)->toBe(config('app.privacy_notice_version'))
        ->and($consent->purposes)->toContain('recruitment');
});

it('refuses registration without consent', function (): void {
    $this->post(route('frontend.register'), registrationPayload(['consent' => null]))
        ->assertSessionHasErrors('consent');

    expect(User::query()->count())->toBe(0);
});

// M03 §5 — the policy is 12 characters with mixed case, a number and a symbol.
// The previous build used Password::defaults(), which is eight and nothing else.

it('refuses a password that meets the framework default but not ours', function (): void {
    $this->post(route('frontend.register'), registrationPayload([
        'password' => 'password1',
        'password_confirmation' => 'password1',
    ]))->assertSessionHasErrors('password');

    expect(User::query()->count())->toBe(0);
});

it('refuses a duplicate email with a message that helps', function (): void {
    User::factory()->candidate()->create(['email' => 'aisha.khan@gmail.com']);

    $this->post(route('frontend.register'), registrationPayload())
        ->assertSessionHasErrors(['email' => __('auth.email_taken')]);
});
