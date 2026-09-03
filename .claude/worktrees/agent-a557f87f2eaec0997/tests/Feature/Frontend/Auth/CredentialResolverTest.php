<?php

declare(strict_types=1);

use App\Domain\Identity\CredentialResolver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// DR-008, M03-R02 through R04.

it('matches an email against the email column', function (): void {
    $user = User::factory()->candidate()->create(['email' => 'aisha.khan@gmail.com']);

    $this->post(route('frontend.login'), [
        'login' => 'aisha.khan@gmail.com',
        'password' => 'Correct-Horse-9!',
    ])->assertRedirect(route('frontend.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('matches an employee ID against the username column', function (): void {
    $user = User::factory()->staff()->create(['username' => 'EMP04821']);

    $this->post(route('frontend.login'), [
        'login' => 'EMP04821',
        'password' => 'Correct-Horse-9!',
    ])->assertRedirect(route('frontend.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('reaches the same row by either identifier', function (): void {
    $user = User::factory()->staff()->create([
        'username' => 'EMP04821',
        'email' => 'dyregistrar@amu.ac.in',
    ]);

    foreach (['EMP04821', 'dyregistrar@amu.ac.in'] as $identifier) {
        $this->post(route('frontend.login'), ['login' => $identifier, 'password' => 'Correct-Horse-9!']);
        $this->assertAuthenticatedAs($user);
        $this->post(route('frontend.logout'));
    }
});

it('resolves the field from the value, not from a fixed column', function (): void {
    $resolver = new CredentialResolver;

    expect($resolver->resolve('aisha.khan@gmail.com'))->toBe(CredentialResolver::EMAIL)
        ->and($resolver->resolve('EMP04821'))->toBe(CredentialResolver::USERNAME);
});

it('gives one message whether the password is wrong or the account is absent', function (): void {
    User::factory()->candidate()->create(['email' => 'known@example.com']);

    $wrongPassword = $this->from(route('frontend.login'))
        ->post(route('frontend.login'), ['login' => 'known@example.com', 'password' => 'wrong-password'])
        ->assertSessionHasErrors('login');

    $noSuchAccount = $this->from(route('frontend.login'))
        ->post(route('frontend.login'), ['login' => 'absent@example.com', 'password' => 'wrong-password'])
        ->assertSessionHasErrors('login');

    // Identical, deliberately: distinguishing them is the cheapest account
    // enumeration there is.
    expect(session('errors')->get('login'))->toBe([__('auth.failed')]);
});

it('refuses a suspended account', function (): void {
    User::factory()->candidate()->suspended()->create(['email' => 'suspended@example.com']);

    $this->post(route('frontend.login'), ['login' => 'suspended@example.com', 'password' => 'Correct-Horse-9!'])
        ->assertSessionHasErrors('login');

    $this->assertGuest();
});
