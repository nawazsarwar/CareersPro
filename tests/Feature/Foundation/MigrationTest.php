<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;

// M00-R01 — migrate:fresh must complete from an empty database.
// It could not before this wave: a migration called ->after('salary') on a
// column that does not exist, and the chain was left half applied.

it('migrates from empty without error', function (): void {
    $this->artisan('migrate:fresh')->assertExitCode(0);
});

it('creates the framework tables the previous schema was missing', function (): void {
    $this->artisan('migrate:fresh')->run();

    expect(Schema::hasTable('sessions'))->toBeTrue()
        ->and(Schema::hasTable('cache'))->toBeTrue()
        ->and(Schema::hasTable('jobs'))->toBeTrue()
        ->and(Schema::hasTable('failed_jobs'))->toBeTrue()
        ->and(Schema::hasTable('personal_access_tokens'))->toBeTrue();
});

it('names the password reset table what config/auth.php actually expects', function (): void {
    // The previous migration created `password_resets` while the broker looked
    // for `password_reset_tokens`, so password reset was dead on arrival.
    $this->artisan('migrate:fresh')->run();

    expect(Schema::hasTable(config('auth.passwords.users.table')))->toBeTrue()
        ->and(config('auth.passwords.users.table'))->toBe('password_reset_tokens');
});

it('seeds without error', function (): void {
    $this->artisan('migrate:fresh', ['--seed' => true])->assertExitCode(0);
});
