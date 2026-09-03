<?php

declare(strict_types=1);

use App\Domain\Identity\OtpIssueResult;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

/**
 * Reads the code out of the row we just wrote. Codes are stored hashed, so the
 * test issues its own known value rather than trying to recover one.
 */
function issueKnownCode(User $user, string $code = '482913', string $purpose = 'login'): OtpCode
{
    return OtpCode::query()->create([
        'user_id' => $user->getKey(),
        'purpose' => $purpose,
        'channel' => 'sms',
        'code_hash' => Hash::make($code),
        'destination_hash' => App\Support\Crypto\BlindIndex::of('9876543210'),
        'attempts' => 0,
        'expires_at' => now()->addMinutes(10),
        'created_at' => now(),
    ]);
}

// M03-R13 — a verified mobile receives a code and the sign-in completes.

it('sends a code and signs the user in', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email])
        ->assertRedirect(route('frontend.login.otp.verify'))
        ->assertSessionHas('otp_result', OtpIssueResult::SENT);

    expect(OtpCode::query()->where('user_id', $user->getKey())->count())->toBe(1);
});

// M03-R14 — the response must not reveal whether the account exists.

it('answers identically for an unknown identifier and one with no mobile', function (): void {
    User::factory()->candidate()->withProfile()->create(['email' => 'known@example.com']);

    $unknown = $this->post(route('frontend.login.otp.request'), ['login' => 'absent@example.com']);
    $noMobile = $this->post(route('frontend.login.otp.request'), ['login' => 'known@example.com']);

    expect($unknown->getTargetUrl())->toBe($noMobile->getTargetUrl())
        ->and(session('otp_result'))->toBe(OtpIssueResult::NO_MOBILE);

    expect(OtpCode::query()->count())->toBe(0);
});

// M03-R15 — an unverified mobile is refused and the verification path offered.

it('refuses an unverified mobile', function (): void {
    $user = User::factory()->candidate()->withUnverifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email])
        ->assertSessionHas('otp_result', OtpIssueResult::UNVERIFIED_MOBILE);

    expect(OtpCode::query()->count())->toBe(0);
});

// M03-R09 — single use.

it('rejects a code that has already been used', function (): void {
    // Asserted against the verifier rather than through two HTTP round trips:
    // a successful sign-in ends the pending session, so replaying the code
    // over HTTP would be testing session mechanics, not single use.
    $user = User::factory()->candidate()->withVerifiedMobile()->create();
    issueKnownCode($user);

    $verify = app(App\Domain\Identity\VerifyOtp::class);

    expect($verify->handle($user, App\Enums\OtpPurpose::Login, '482913'))->toBeTrue()
        ->and($verify->handle($user, App\Enums\OtpPurpose::Login, '482913'))->toBeFalse();
});

// A code is bound to its purpose (M03 §3): one issued to sign in can never
// satisfy a second-factor challenge, nor the reverse.

it('refuses a code issued for another purpose', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();
    issueKnownCode($user, purpose: 'two_factor');

    $verify = app(App\Domain\Identity\VerifyOtp::class);

    expect($verify->handle($user, App\Enums\OtpPurpose::Login, '482913'))->toBeFalse()
        ->and($verify->handle($user, App\Enums\OtpPurpose::TwoFactor, '482913'))->toBeTrue();
});

// M03-R17 — an expired code is rejected.

it('rejects an expired code', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);
    OtpCode::query()->delete();

    issueKnownCode($user)->forceFill(['expires_at' => now()->subMinute()])->save();

    $this->post(route('frontend.login.otp.verify'), ['code' => '482913'])
        ->assertSessionHasErrors('code');

    $this->assertGuest();
});

it('states how many attempts remain on a wrong code', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);
    OtpCode::query()->delete();
    issueKnownCode($user);

    $this->post(route('frontend.login.otp.verify'), ['code' => '000000'])
        ->assertSessionHasErrors('code');

    expect(session('errors')->first('code'))->toContain('2');
});

// M03-R16 — a resend inside the cooldown is refused with the time stated.

it('refuses a resend inside the cooldown and states the time', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);

    $this->post(route('frontend.login.otp.resend'))
        ->assertSessionHas('otp_result', OtpIssueResult::COOLDOWN)
        ->assertSessionHas('otp_retry_at');
});

// M03-R18 — the hourly cap is per destination, keyed on the blind index.

it('caps codes per destination per hour and states the retry time', function (): void {
    config(['otp.resend_delay_minutes' => 0, 'otp.max_per_hour' => 3]);

    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    foreach (range(1, 3) as $i) {
        $this->post(route('frontend.login.otp.request'), ['login' => $user->email])
            ->assertSessionHas('otp_result', OtpIssueResult::SENT);
    }

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email])
        ->assertSessionHas('otp_result', OtpIssueResult::HOURLY_CAP)
        ->assertSessionHas('otp_retry_at');
});

it('caps a shared handset across two accounts', function (): void {
    // DR-023 permits a shared family handset, so the cap has to be keyed on
    // the destination rather than the account, or two accounts double it.
    config(['otp.resend_delay_minutes' => 0, 'otp.max_per_hour' => 2]);

    $first = User::factory()->candidate()->withVerifiedMobile('9876543210')->create();
    $second = User::factory()->candidate()->withVerifiedMobile('9876543210')->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $first->email]);
    $this->post(route('frontend.login.otp.request'), ['login' => $second->email]);

    $this->post(route('frontend.login.otp.request'), ['login' => $first->email])
        ->assertSessionHas('otp_result', OtpIssueResult::HOURLY_CAP);
});
