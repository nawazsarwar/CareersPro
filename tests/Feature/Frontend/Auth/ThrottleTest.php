<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// M03-R05 — sign-in is throttled and the retry time is stated.

it('throttles repeated sign-in attempts', function (): void {
    User::factory()->candidate()->create(['email' => 'aisha.khan@gmail.com']);

    foreach (range(1, 6) as $i) {
        $this->post(route('frontend.login'), ['login' => 'aisha.khan@gmail.com', 'password' => 'wrong'])
            ->assertStatus(302);
    }

    $this->post(route('frontend.login'), ['login' => 'aisha.khan@gmail.com', 'password' => 'wrong'])
        ->assertStatus(429);
});

it('throttles code verification separately from sign-in', function (): void {
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);

    foreach (range(1, 6) as $i) {
        $this->post(route('frontend.login.otp.verify'), ['code' => '000000']);
    }

    $this->post(route('frontend.login.otp.verify'), ['code' => '000000'])->assertStatus(429);
});
