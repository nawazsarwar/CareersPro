<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Access\ProvisionSuperAdmin;
use App\Enums\RoleSlug;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use RuntimeException;

/**
 * The one account a fresh deployment cannot start without (M28 §8).
 *
 * Interactive by design. There is no `--password` option and there never will
 * be one: an option lands in the shell history, in `ps` output for the life of
 * the process, and in the deployment log. The prompt echoes nothing and the
 * value is discarded when `handle()` returns.
 *
 * It deliberately does not read the password from the environment either. A
 * credential in `.env` is a credential in the backup of `.env`, and DR-024
 * confines that file to third-party credentials the application must present,
 * not to the university's own administrator password.
 */
class CreateSuperAdmin extends Command
{
    /**
     * `--email`, `--name` and `--username` are conveniences for a deployer
     * re-running the command; none of them is a credential.
     */
    protected $signature = 'super-admin:create
        {--email= : The administrator\'s email address}
        {--name= : The administrator\'s full name}
        {--username= : Employee ID, for staff sign-in by ID rather than email}';

    protected $description = 'Create or promote a super administrator, prompting for the password (never stored).';

    private const PASSWORD_ATTEMPTS = 3;

    public function handle(ProvisionSuperAdmin $provision): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('This command prompts for a password and cannot run non-interactively.');
            $this->line('Run it from a terminal, without --no-interaction.');

            return self::FAILURE;
        }

        if (! Role::query()->where('slug', RoleSlug::SuperAdmin->value)->exists()) {
            $this->error('The super_admin role does not exist yet.');
            $this->line('Run `php artisan db:seed --class=RolePermissionSeeder`, then this command again.');

            return self::FAILURE;
        }

        $email = $this->askEmail();

        if ($email === null) {
            return self::FAILURE;
        }

        $existing = User::query()->withTrashed()->where('email', $email)->first();

        if ($existing !== null && $existing->trashed()) {
            // Restoring is a separate decision with its own audit trail, and
            // doing it silently here would resurrect an account somebody
            // deliberately removed.
            $this->error('That address belongs to a deleted account.');
            $this->line('Restore it deliberately before granting it super_admin.');

            return self::FAILURE;
        }

        if ($existing !== null) {
            $this->warn("An account already exists for {$email} (#{$existing->getKey()}, {$existing->name}).");

            if (! $this->confirm('Grant super_admin to that existing account?', false)) {
                $this->line('Nothing changed.');

                return self::FAILURE;
            }
        }

        $name = $this->askName($existing);

        if ($name === null) {
            return self::FAILURE;
        }

        $username = $this->askUsername($existing);

        if ($username === false) {
            return self::FAILURE;
        }

        // An existing account keeps its password unless the deployer asks
        // otherwise. Resetting it by default would lock out an administrator
        // who is only being granted an extra role.
        $setPassword = $existing === null
            || $this->confirm('Set a new password for that account?', false);

        $password = $setPassword ? $this->askPassword() : null;

        if ($setPassword && $password === null) {
            return self::FAILURE;
        }

        try {
            $user = $provision->handle($email, $name, $password, $username === '' ? null : $username);
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        unset($password);

        $this->info('Super administrator ready.');
        $this->table(
            ['Field', 'Value'],
            [
                ['ID', (string) $user->getKey()],
                ['Name', $user->name],
                ['Email', $user->email],
                ['Employee ID', $user->username ?? '—'],
                ['Email verified', $user->email_verified_at?->toDateTimeString() ?? 'no'],
                ['Role', RoleSlug::SuperAdmin->value.' (university-wide)'],
            ],
        );

        // Staff carry an enforced second factor (M03-R25), so the first
        // sign-in lands on enrolment rather than on the dashboard. A deployer
        // who does not expect that reads it as a broken login.
        $this->line('First sign-in will require enrolling a second factor before anything else is reachable.');

        return self::SUCCESS;
    }

    private function askEmail(): ?string
    {
        $email = $this->option('email') ?? $this->ask('Email address');

        // email:rfc, not email:rfc,dns. A deployment host is frequently
        // firewalled off from outbound DNS, and a resolver timeout there would
        // reject a perfectly valid institutional address.
        $validator = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'string', 'email:rfc', 'max:191']],
        );

        if ($validator->fails()) {
            $this->error((string) $validator->errors()->first('email'));

            return null;
        }

        return (string) $email;
    }

    private function askName(?User $existing): ?string
    {
        $name = $this->option('name') ?? $this->ask('Full name', $existing?->name);

        $validator = Validator::make(
            ['name' => $name],
            ['name' => ['required', 'string', 'max:191']],
        );

        if ($validator->fails()) {
            $this->error((string) $validator->errors()->first('name'));

            return null;
        }

        return (string) $name;
    }

    /**
     * @return string|false the employee ID, '' for none, or false on a validation failure
     */
    private function askUsername(?User $existing): string|false
    {
        $username = $this->option('username')
            ?? $this->ask('Employee ID (optional, press enter to skip)', $existing?->username);

        if ($username === null || $username === '') {
            return '';
        }

        $validator = Validator::make(
            ['username' => $username],
            ['username' => [
                'string',
                'max:191',
                // M03-R04. An employee ID containing `@` is routed to the
                // email branch of CredentialResolver and can never match, so
                // the account would silently be unable to sign in by ID.
                'regex:/^[^@]+$/',
                Rule::unique('users', 'username')->ignore($existing?->getKey()),
            ]],
        );

        if ($validator->fails()) {
            $this->error((string) $validator->errors()->first('username'));

            return false;
        }

        return (string) $username;
    }

    private function askPassword(): ?string
    {
        for ($attempt = 1; $attempt <= self::PASSWORD_ATTEMPTS; $attempt++) {
            $password = (string) $this->secret('Password (input hidden)');
            $confirmation = (string) $this->secret('Confirm password');

            $validator = Validator::make(
                ['password' => $password, 'password_confirmation' => $confirmation],
                ['password' => [
                    'required',
                    'confirmed',
                    // The same policy the registration form applies (M03 §5).
                    // A weaker one for the most privileged account in the
                    // system would be the wrong way round.
                    Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised(),
                ]],
            );

            if ($validator->passes()) {
                return $password;
            }

            foreach ($validator->errors()->get('password') as $message) {
                $this->error((string) $message);
            }
        }

        $this->error('Password not accepted after '.self::PASSWORD_ATTEMPTS.' attempts. Nothing changed.');

        return null;
    }
}
