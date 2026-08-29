<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * M03-R29 — every screen in this module completes with JavaScript disabled.
 *
 * DR-021 pays a real cost for this, so it is asserted rather than assumed. The
 * test reads the rendered markup: a control that only works through a fetch
 * call is a control that does not exist for a user on a degraded connection.
 */
it('offers the OTP path as a plain submit on the sign-in form', function (): void {
    $html = $this->get(route('frontend.login'))->assertOk()->getContent();

    // A second submit button on the same form, using formaction -- so the
    // identifier the user already typed is posted with it whether or not
    // Alpine is running. A second form fed by JavaScript would post an empty
    // field with scripting off.
    expect($html)->toContain('formaction="'.route('frontend.login.otp.request').'"')
        ->and(substr_count($html, '<form'))->toBe(1);
});

it('renders the code entry as a real form with a single fallback field', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);

    $html = $this->get(route('frontend.login.otp.verify'))->assertOk()->getContent();

    expect($html)->toContain('<form method="POST"')
        ->toContain('name="code"')
        // The six boxes are the enhancement; the single input is the markup
        // that ships.
        ->toContain('inputmode="numeric"')
        ->toContain('x-show="!enhanced"');
});

// The single field ships `required` for the no-JavaScript path and drops it
// once Alpine hides it. A `required` control that is not focusable makes the
// browser refuse the submit with no message the user can see or act on.
it('drops the requirement from the fallback field once the boxes take over', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);

    $html = $this->get(route('frontend.login.otp.verify'))->assertOk()->getContent();

    expect($html)->toContain(':required="! enhanced"')
        // The handlers sit on the container, so one listener serves every box
        // and its index comes from the box's position, not the loop variable.
        ->toContain('x-ref="boxes"')
        ->toContain('@input="advance($event)"')
        ->toContain('@keydown="navigate($event)"');
});

it('completes an OTP sign-in through the plain form path', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);

    App\Models\OtpCode::query()->delete();
    App\Models\OtpCode::query()->create([
        'user_id' => $user->getKey(),
        'purpose' => App\Enums\OtpPurpose::Login,
        'channel' => 'sms',
        'code_hash' => Illuminate\Support\Facades\Hash::make('482913'),
        'destination_hash' => App\Support\Crypto\BlindIndex::of('9876543210'),
        'attempts' => 0,
        'expires_at' => now()->addMinutes(10),
        'created_at' => now(),
    ]);

    // Exactly what a browser without JavaScript posts: one field, one value.
    $this->post(route('frontend.login.otp.verify'), ['code' => '482913'])
        ->assertRedirect(route('frontend.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('accepts the six separate boxes the enhanced form posts', function (): void {
    // With Alpine running the boxes sync into the single field, but the
    // request must survive the array shape too -- the Form Request joins the
    // parts before validation rather than after.
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);

    App\Models\OtpCode::query()->delete();
    App\Models\OtpCode::query()->create([
        'user_id' => $user->getKey(),
        'purpose' => App\Enums\OtpPurpose::Login,
        'channel' => 'sms',
        'code_hash' => Illuminate\Support\Facades\Hash::make('482913'),
        'destination_hash' => App\Support\Crypto\BlindIndex::of('9876543210'),
        'attempts' => 0,
        'expires_at' => now()->addMinutes(10),
        'created_at' => now(),
    ]);

    $this->post(route('frontend.login.otp.verify'), ['code' => ['4', '8', '2', '9', '1', '3']])
        ->assertRedirect(route('frontend.dashboard'));
});

it('reaches every auth screen through a real form, never through script', function (): void {
    // Asserted against the Blade sources, not the rendered page: the rendered
    // page carries the built asset bundle, so scanning it for `fetch(` would
    // be scanning vendor code. What M03-R29 is about is our own markup.
    $views = glob(resource_path('views/frontend/auth/*.blade.php')) ?: [];
    $components = glob(resource_path('views/components/*.blade.php')) ?: [];

    expect($views)->not->toBeEmpty();

    foreach ([...$views, ...$components] as $view) {
        $source = (string) file_get_contents($view);
        $name = basename($view);

        expect($source)
            ->not->toContain('fetch(')
            ->not->toContain('axios')
            ->not->toContain('XMLHttpRequest');
    }
});

it('gives every posting screen a real form and a submit button', function (): void {
    foreach (glob(resource_path('views/frontend/auth/*.blade.php')) ?: [] as $view) {
        $source = (string) file_get_contents($view);

        if (! str_contains($source, 'method="POST"')) {
            continue;
        }

        expect($source)
            ->toContain('@csrf')
            ->toContain('type="submit"');
    }
});
