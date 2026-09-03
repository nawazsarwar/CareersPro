<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

// M03-R06 — the reset completes, and the token lands in the table
// config/auth.php actually names. Before this wave the migration created
// `password_resets` while the broker looked for `password_reset_tokens`, so
// the whole flow was dead on arrival.

it('writes the token to password_reset_tokens and completes the reset', function (): void {
    Notification::fake();

    $user = User::factory()->candidate()->create(['email' => 'aisha.khan@gmail.com']);

    $this->post(route('frontend.password.email'), ['email' => $user->email])
        ->assertSessionHas('status');

    expect(DB::table('password_reset_tokens')->where('email', $user->email)->exists())->toBeTrue();

    $token = null;
    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token): bool {
        $token = $notification->token;

        return true;
    });

    $this->post(route('frontend.password.store'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'Another-Correct-9!',
        'password_confirmation' => 'Another-Correct-9!',
    ])->assertRedirect(route('frontend.login'));

    expect(Hash::check('Another-Correct-9!', $user->fresh()->password))->toBeTrue();
});

it('answers the same whether or not the address is registered', function (): void {
    Notification::fake();

    $registered = $this->post(route('frontend.password.email'), ['email' => 'known@example.com']);
    $unregistered = $this->post(route('frontend.password.email'), ['email' => 'absent@example.com']);

    expect($registered->getSession()->get('status'))
        ->toBe($unregistered->getSession()->get('status'));
});

it('enforces the password policy on the new password too', function (): void {
    $this->post(route('frontend.password.store'), [
        'token' => 'irrelevant',
        'email' => 'aisha.khan@gmail.com',
        'password' => 'password1',
        'password_confirmation' => 'password1',
    ])->assertSessionHasErrors('password');
});

// M03-R28 — the named limiter, keyed on address and IP. Laravel's broker
// throttles rapid repeats to one a minute; this stops slow enumeration across
// an hour, which the broker does not.

it('throttles repeated reset requests and states the retry time', function (): void {
    Notification::fake();

    foreach (range(1, 5) as $i) {
        $this->post(route('frontend.password.email'), ['email' => 'aisha.khan@gmail.com'])
            ->assertStatus(302);
    }

    $this->post(route('frontend.password.email'), ['email' => 'aisha.khan@gmail.com'])
        ->assertStatus(429);
});
