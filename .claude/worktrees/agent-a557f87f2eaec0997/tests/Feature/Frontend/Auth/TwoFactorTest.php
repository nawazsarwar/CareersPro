<?php

declare(strict_types=1);

use App\Domain\Identity\SecondFactor\ResolveRequiredFactor;
use App\Enums\AuthFactor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    // Enforced for staff, not for candidates — the shipped default.
    config([
        'auth_channels.classes.staff.second_factor_required' => true,
        'auth_channels.classes.candidate.second_factor_required' => false,
    ]);
});

// M03-R19 — a password sign-in by a staff account with a second factor is
// challenged for it.

it('demands the second factor after a password sign-in', function (): void {
    $user = User::factory()->staff()->withTotp()->create();

    $this->post(route('frontend.login'), ['login' => $user->username, 'password' => 'Correct-Horse-9!'])
        ->assertRedirect(route('frontend.two-factor.challenge'));

    // Identified, not signed in. This is the state neither `auth` nor `guest`
    // describes, and why `auth.pending` exists.
    $this->assertGuest();
});

// M03-R20 — DR-023's arithmetic. The channel that served as the FIRST factor
// is excluded from what may serve as the second.

it('excludes the SMS channel that just served as the first factor', function (): void {
    $user = User::factory()->staff()->withVerifiedMobile()->withTotp()->withSmsFactor()->create();

    $resolver = app(ResolveRequiredFactor::class);

    // After a password login, either enrolled method is a valid second factor.
    expect($resolver->for($user, AuthFactor::Password))->toBe(AuthFactor::Totp);

    // After an SMS login, SMS is not — asking for a second code on the same
    // channel proves nothing the first one did not.
    expect($resolver->for($user, AuthFactor::Sms))->toBe(AuthFactor::Totp);
});

it('asks for one prompt after a password login and two after an OTP login', function (): void {
    $user = User::factory()->staff()->withVerifiedMobile()->withSmsFactor()->create();

    $resolver = app(ResolveRequiredFactor::class);

    // Password first: the SMS code IS the second factor. One prompt.
    expect($resolver->for($user, AuthFactor::Password))->toBe(AuthFactor::Sms);

    // SMS first: nothing is left to ask for, so OTP login is refused before a
    // code is sent rather than after (M03-R21).
    expect($resolver->for($user, AuthFactor::Sms))->toBeNull()
        ->and($resolver->permitsOtpLogin($user, AuthFactor::Sms))->toBeFalse();
});

// M03-R21 — a user whose only method is SMS cannot sign in by OTP.

it('refuses OTP login when SMS is the only enrolled method', function (): void {
    $user = User::factory()->staff()->withVerifiedMobile()->withSmsFactor()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->username])
        ->assertSessionHas('otp_result', App\Domain\Identity\OtpIssueResult::UNVERIFIED_MOBILE);

    expect(App\Models\OtpCode::query()->count())->toBe(0);
});

// M03-R22 — where 2FA is enforced and nothing is enrolled, protected routes
// send the user to enrol rather than logging them out.

it('sends a staff user with no method to the enrolment screen', function (): void {
    $user = User::factory()->staff()->create();

    $this->actingAs($user)->get(route('admin.home'))
        ->assertRedirect(route('frontend.two-factor.index'));
});

it('lets a staff user with a confirmed method through', function (): void {
    $user = User::factory()->staff()->withTotp()->create();

    $this->actingAs($user)->get(route('admin.home'))->assertOk();
});

// M03-R25 — email is refused as a second factor for staff.

it('refuses email as a second factor for staff but allows it for a candidate', function (): void {
    $policy = app(App\Domain\Identity\TwoFactorPolicy::class);

    expect($policy->permits(User::factory()->staff()->create(), AuthFactor::Email))->toBeFalse()
        ->and($policy->permits(User::factory()->candidate()->create(), AuthFactor::Email))->toBeTrue();
});

// M03-R23 — the last method may go only where 2FA is not enforced.

it('refuses removal of the last method where 2FA is enforced', function (): void {
    $policy = app(App\Domain\Identity\TwoFactorPolicy::class);

    $staff = User::factory()->staff()->withTotp()->create()->load('twoFactorMethods');
    $candidate = User::factory()->candidate()->withTotp()->create()->load('twoFactorMethods');

    expect($policy->mayRemove($staff, AuthFactor::Totp))->toBeFalse()
        ->and($policy->mayRemove($candidate, AuthFactor::Totp))->toBeTrue();
});

it('allows removal where another method remains', function (): void {
    $policy = app(App\Domain\Identity\TwoFactorPolicy::class);

    $staff = User::factory()->staff()->withVerifiedMobile()->withTotp()->withSmsFactor()
        ->create()->load('twoFactorMethods');

    expect($policy->mayRemove($staff, AuthFactor::Totp))->toBeTrue();
});
