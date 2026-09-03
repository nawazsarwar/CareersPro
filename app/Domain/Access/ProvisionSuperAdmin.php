<?php

declare(strict_types=1);

namespace App\Domain\Access;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\RoleSlug;
use App\Enums\UserStatus;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Provisions the first `super_admin` on a fresh deployment (M28 §8).
 *
 * A domain service rather than logic inside the console command, because the
 * three things this does — create the account, verify the address, grant the
 * role — must either all happen or none of them. A half-provisioned super
 * administrator is an account that can sign in and do nothing, and the
 * deployer's usual response to that is to run the command again.
 *
 * What this replaces is a seeder holding an email and a password. A credential
 * in `database/seeders` is a credential in the repository, in every clone of
 * it, and in the deployment archive (DR-024) — so the password never reaches
 * this class from configuration, only from a terminal prompt that echoes
 * nothing.
 */
final class ProvisionSuperAdmin
{
    public function __construct(private readonly RecordAuditEvent $audit) {}

    /**
     * @param  string|null  $password  NULL leaves an existing account's password untouched.
     *
     * @throws RuntimeException when the role does not exist, or when a new account is asked for without a password.
     */
    public function handle(string $email, string $name, ?string $password, ?string $username = null): User
    {
        $role = Role::query()->where('slug', RoleSlug::SuperAdmin->value)->first();

        if ($role === null) {
            // The policies name this slug. Attaching a role that does not
            // exist would leave an account that is nominally the administrator
            // and holds no permission at all.
            throw new RuntimeException(
                'The super_admin role is absent. Run `php artisan db:seed --class=RolePermissionSeeder` first.',
            );
        }

        return DB::transaction(function () use ($email, $name, $password, $username, $role): User {
            $user = User::query()->where('email', $email)->first() ?? new User;
            $existed = $user->exists;

            if (! $existed && $password === null) {
                throw new RuntimeException('A new account cannot be created without a password.');
            }

            $user->name = $name;
            $user->email = $email;

            if ($username !== null) {
                $user->username = $username;
            }

            if ($password !== null) {
                // Hashed by the model cast, so the plaintext never reaches a
                // query log or a bound-parameter dump.
                $user->password = $password;
            }

            // Verified here rather than by mail: the deployer typed the address
            // at the console of the server, which is a stronger assertion than
            // a link clicked in an inbox. Without it the account is created and
            // immediately blocked by the verified middleware.
            $user->email_verified_at ??= CarbonImmutable::now();

            $user->status = UserStatus::Active;
            $user->must_change_password = false;

            $user->save();

            // The authentication-facing half of the profile, created eagerly
            // for the same reason RegisterCandidate creates it: the
            // mobile-verification path needs somewhere to write from the first
            // request.
            Profile::query()->firstOrCreate(['user_id' => $user->getKey()]);

            $granted = $this->grant($user, $role);

            // M25-R10. The account may have existed with a cached, narrower
            // permission set; without this the new role does not take effect
            // for up to fifteen minutes.
            ResolvePermissions::invalidate($user);

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::RoleAssigned,
                properties: [
                    'role' => RoleSlug::SuperAdmin->value,
                    // NULL organisational unit, which for a university-wide
                    // role means the whole university (M25 §2).
                    'scope' => 'university_wide',
                    'email' => $email,
                    'account' => $existed ? 'existing' : 'created',
                    'password_set' => $password !== null,
                    'already_held_role' => ! $granted,
                    // No authenticated actor exists on a fresh deployment. The
                    // channel is what identifies the actor here, so it is
                    // recorded rather than left to be inferred from a NULL.
                    'channel' => 'console',
                ],
                subject: $user,
                actorId: null,
                actorRole: 'console',
            ));

            return $user->refresh();
        });
    }

    /**
     * @return bool whether the role was newly attached
     */
    private function grant(User $user, Role $role): bool
    {
        $held = $user->roles()
            ->wherePivot('organisational_unit_id', null)
            ->whereKey($role->getKey())
            ->exists();

        if ($held) {
            return false;
        }

        // NULL organisational unit. `super_admin` is UniversityWide
        // (RoleSlug::scope()), so scoping it to a unit would narrow the one
        // role that must not be narrowed.
        $user->roles()->attach($role->getKey(), ['organisational_unit_id' => null]);

        $user->unsetRelation('roles');

        return true;
    }
}
