<?php

declare(strict_types=1);

use App\Enums\RoleSlug;
use App\Enums\UserStatus;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    // The `uncompromised` rule calls the Have I Been Pwned range API. An empty
    // 200 means "this hash prefix matched nothing", so a strong password
    // passes without the suite depending on outbound network.
    Http::fake(['api.pwnedpasswords.com/*' => Http::response('', 200)]);

    $this->seed(Database\Seeders\RolePermissionSeeder::class);
});

const STRONG_PASSWORD = 'Qu1ll&Anvil#Ridge72';

it('creates a verified super administrator from prompted answers', function (): void {
    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'registrar@example.test')
        ->expectsQuestion('Full name', 'Aisha Rahman')
        ->expectsQuestion('Employee ID (optional, press enter to skip)', 'EMP-0001')
        ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
        ->expectsQuestion('Confirm password', STRONG_PASSWORD)
        ->assertSuccessful();

    $user = User::query()->where('email', 'registrar@example.test')->firstOrFail();

    expect($user->name)->toBe('Aisha Rahman')
        ->and($user->username)->toBe('EMP-0001')
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->status)->toBe(UserStatus::Active)
        ->and($user->must_change_password)->toBeFalse()
        ->and($user->hasRole(RoleSlug::SuperAdmin))->toBeTrue()
        ->and($user->profile)->not->toBeNull();

    // University-wide, which for this role is the only correct scope.
    expect($user->roles->first()->pivot->organisational_unit_id)->toBeNull();
});

it('stores the password hashed and never in plaintext', function (): void {
    $this->artisan('super-admin:create', ['--email' => 'chair@example.test', '--name' => 'R. Iyer'])
        ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
        ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
        ->expectsQuestion('Confirm password', STRONG_PASSWORD)
        ->assertSuccessful();

    $user = User::query()->where('email', 'chair@example.test')->firstOrFail();

    expect($user->password)->not->toBe(STRONG_PASSWORD)
        ->and(Hash::check(STRONG_PASSWORD, $user->password))->toBeTrue();
});

it('refuses a password weaker than the registration policy', function (): void {
    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'weak@example.test')
        ->expectsQuestion('Full name', 'Weak Password')
        ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
        ->expectsQuestion('Password (input hidden)', 'password')
        ->expectsQuestion('Confirm password', 'password')
        ->expectsQuestion('Password (input hidden)', 'password')
        ->expectsQuestion('Confirm password', 'password')
        ->expectsQuestion('Password (input hidden)', 'password')
        ->expectsQuestion('Confirm password', 'password')
        ->assertFailed();

    expect(User::query()->where('email', 'weak@example.test')->exists())->toBeFalse();
});

it('refuses a password that does not match its confirmation', function (): void {
    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'mismatch@example.test')
        ->expectsQuestion('Full name', 'Typo Prone')
        ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
        ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
        ->expectsQuestion('Confirm password', STRONG_PASSWORD.'x')
        ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
        ->expectsQuestion('Confirm password', STRONG_PASSWORD.'x')
        ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
        ->expectsQuestion('Confirm password', STRONG_PASSWORD.'x')
        ->assertFailed();

    expect(User::query()->where('email', 'mismatch@example.test')->exists())->toBeFalse();
});

it('promotes an existing account without touching its password when declined', function (): void {
    $user = User::factory()->create([
        'email' => 'existing@example.test',
        'password' => 'OldP@ssword12345',
    ]);
    $originalHash = $user->password;

    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'existing@example.test')
        ->expectsConfirmation('Grant super_admin to that existing account?', 'yes')
        ->expectsQuestion('Full name', $user->name)
        ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
        ->expectsConfirmation('Set a new password for that account?', 'no')
        ->assertSuccessful();

    $user->refresh();

    expect($user->password)->toBe($originalHash)
        ->and($user->fresh()->hasRole(RoleSlug::SuperAdmin))->toBeTrue();
});

it('aborts when the deployer declines to promote the existing account', function (): void {
    $user = User::factory()->create(['email' => 'other@example.test']);

    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'other@example.test')
        ->expectsConfirmation('Grant super_admin to that existing account?', 'no')
        ->assertFailed();

    expect($user->fresh()->hasRole(RoleSlug::SuperAdmin))->toBeFalse();
});

it('is idempotent: a second run grants no duplicate role row', function (): void {
    $run = function (): void {
        $this->artisan('super-admin:create')
            ->expectsQuestion('Email address', 'twice@example.test')
            ->expectsQuestion('Full name', 'Twice Run')
            ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
            ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
            ->expectsQuestion('Confirm password', STRONG_PASSWORD)
            ->assertSuccessful();
    };

    $run();

    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'twice@example.test')
        ->expectsConfirmation('Grant super_admin to that existing account?', 'yes')
        ->expectsQuestion('Full name', 'Twice Run')
        ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
        ->expectsConfirmation('Set a new password for that account?', 'no')
        ->assertSuccessful();

    $user = User::query()->where('email', 'twice@example.test')->firstOrFail();

    expect($user->roles()->count())->toBe(1);
});

it('refuses an employee ID containing an at sign', function (): void {
    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'atsign@example.test')
        ->expectsQuestion('Full name', 'At Sign')
        ->expectsQuestion('Employee ID (optional, press enter to skip)', 'emp@university')
        ->assertFailed();

    expect(User::query()->where('email', 'atsign@example.test')->exists())->toBeFalse();
});

it('refuses to run without an interactive terminal', function (): void {
    $this->artisan('super-admin:create --no-interaction')->assertFailed();

    expect(User::query()->count())->toBe(0);
});

it('refuses to run before the roles are seeded', function (): void {
    Role::query()->where('slug', RoleSlug::SuperAdmin->value)->delete();

    $this->artisan('super-admin:create')->assertFailed();
});

it('refuses an address belonging to a deleted account', function (): void {
    $user = User::factory()->create(['email' => 'gone@example.test']);
    $user->delete();

    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'gone@example.test')
        ->assertFailed();

    expect($user->fresh()->hasRole(RoleSlug::SuperAdmin))->toBeFalse();
});

it('records the grant in the audit chain', function (): void {
    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'audited@example.test')
        ->expectsQuestion('Full name', 'Audited Admin')
        ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
        ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
        ->expectsQuestion('Confirm password', STRONG_PASSWORD)
        ->assertSuccessful();

    $entry = AuditLog::query()->where('event', 'access.role_assigned')->latest('sequence')->firstOrFail();

    expect($entry->actor_role)->toBe('console')
        ->and($entry->properties['role'])->toBe(RoleSlug::SuperAdmin->value)
        ->and($entry->properties['scope'])->toBe('university_wide')
        ->and($entry->properties['channel'])->toBe('console');
});

it('never writes the password into the audit properties', function (): void {
    $this->artisan('super-admin:create')
        ->expectsQuestion('Email address', 'secret@example.test')
        ->expectsQuestion('Full name', 'Secret Keeper')
        ->expectsQuestion('Employee ID (optional, press enter to skip)', '')
        ->expectsQuestion('Password (input hidden)', STRONG_PASSWORD)
        ->expectsQuestion('Confirm password', STRONG_PASSWORD)
        ->assertSuccessful();

    $properties = AuditLog::query()->pluck('properties')->map(
        static fn ($value): string => json_encode($value, JSON_THROW_ON_ERROR),
    )->implode(' ');

    expect($properties)->not->toContain(STRONG_PASSWORD);
});

it('keeps the password out of the command signature', function (): void {
    // Not a style preference. An option is visible in `ps` output for the life
    // of the process and is written to the deployer's shell history.
    $definition = $this->app->make(Illuminate\Contracts\Console\Kernel::class)
        ->all()['super-admin:create']->getDefinition();

    expect($definition->hasOption('password'))->toBeFalse();
    expect($definition->hasArgument('password'))->toBeFalse();
});

it('leaves no credential in the seeders or configuration', function (): void {
    // The practice this command replaces.
    $tree = array_merge(
        glob(base_path('database/seeders/*.php')) ?: [],
        glob(base_path('config/*.php')) ?: [],
    );

    foreach ($tree as $file) {
        expect(file_get_contents($file))->not->toMatch('/super[_-]?admin.{0,80}password/is');
    }
});

it('does not require a schema change', function (): void {
    // The command provisions into the existing identity and access tables.
    expect(Schema::hasTable('users'))->toBeTrue()
        ->and(Schema::hasTable('role_user'))->toBeTrue();
});
