<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Models\Profile;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

final class RegisterCandidate
{
    public function __construct(private readonly RecordAuditEvent $audit) {}

    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                // Always NULL for a candidate. The employee ID belongs to
                // staff and is what the credential resolver keys on.
                'username' => null,
            ]);

            // Created here rather than lazily, so the mobile-verification path
            // has somewhere to write from the first request.
            Profile::query()->create(['user_id' => $user->getKey()]);

            // DPDP 2023: the notice version is recorded, not merely the fact of
            // consent, so what was agreed to is identifiable years later.
            $user->consentRecords()->create([
                'notice_version' => (string) config('app.privacy_notice_version', '2026-01'),
                'purposes' => ['recruitment', 'communication'],
                'ip' => Request::ip(),
                'recorded_at' => CarbonImmutable::now(),
            ]);

            // Sends the verification mail, because User implements
            // MustVerifyEmail. The previous build fired this event on a model
            // that did not, so nothing was ever sent and every new account was
            // locked out.
            event(new Registered($user));

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::UserRegistered,
                properties: ['email' => $data['email']],
                subject: $user,
                actorId: (int) $user->getKey(),
                actorIp: Request::ip(),
            ));

            return $user;
        });
    }
}
