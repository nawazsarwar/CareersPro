<?php

declare(strict_types=1);

use App\Enums\OtpPurpose;
use App\Models\OtpCode;
use App\Models\User;
use App\Support\Crypto\BlindIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

// M03-R24 — mobile_verified_at is set when the code comes back, and not before.

it('records a mobile number without verifying it', function (): void {
    $user = User::factory()->candidate()->withProfile()->create();

    $this->actingAs($user)
        ->post(route('frontend.profile.mobile.otp'), ['mobile' => '9876543210'])
        ->assertRedirect();

    $profile = $user->fresh()->profile;

    expect($profile->mobile)->toBe('9876543210')
        ->and($profile->mobile_verified_at)->toBeNull()
        ->and($profile->hasVerifiedMobile())->toBeFalse();
});

it('verifies the mobile once the code comes back', function (): void {
    $user = User::factory()->candidate()->withProfile()->create();

    $this->actingAs($user)->post(route('frontend.profile.mobile.otp'), ['mobile' => '9876543210']);

    OtpCode::query()->delete();
    OtpCode::query()->create([
        'user_id' => $user->getKey(),
        'purpose' => OtpPurpose::MobileVerify,
        'channel' => 'sms',
        'code_hash' => Hash::make('482913'),
        'destination_hash' => BlindIndex::of('9876543210'),
        'attempts' => 0,
        'expires_at' => now()->addMinutes(10),
        'created_at' => now(),
    ]);

    $this->actingAs($user)->post(route('frontend.profile.mobile.verify'), ['code' => '482913']);

    expect($user->fresh()->profile->hasVerifiedMobile())->toBeTrue();
});

it('rejects a malformed mobile number', function (): void {
    $user = User::factory()->candidate()->withProfile()->create();

    $this->actingAs($user)
        ->post(route('frontend.profile.mobile.otp'), ['mobile' => '12345'])
        ->assertSessionHasErrors(['mobile' => __('auth.mobile_invalid')]);
});

// DR-023 — a shared family handset is legitimate and is not blocked.

it('permits the same mobile on two accounts', function (): void {
    $first = User::factory()->candidate()->withProfile()->create();
    $second = User::factory()->candidate()->withProfile()->create();

    $this->actingAs($first)->post(route('frontend.profile.mobile.otp'), ['mobile' => '9876543210'])
        ->assertSessionHasNoErrors();

    $this->actingAs($second)->post(route('frontend.profile.mobile.otp'), ['mobile' => '9876543210'])
        ->assertSessionHasNoErrors();

    expect($first->fresh()->profile->mobile)->toBe('9876543210')
        ->and($second->fresh()->profile->mobile)->toBe('9876543210');
});

// data-protection.md §2 — the number is encrypted at rest and matched through
// a blind index, so equality lookups work without decrypting a single row.

it('stores the mobile encrypted with a searchable blind index', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile('9876543210')->create();

    $raw = DB::table('profiles')->where('user_id', $user->getKey())->first();

    expect($raw->mobile)->not->toBe('9876543210')
        ->and($raw->mobile_blind_index)->toBe(BlindIndex::of('9876543210'));
});

it('normalises before indexing, so formatting cannot bypass the cap', function (): void {
    expect(BlindIndex::of(' 9876543210 '))->toBe(BlindIndex::of('9876543210'));
});
