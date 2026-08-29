<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\AuthFactor;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Request;

/**
 * Signs a user in once every required factor is satisfied.
 *
 * One place, so the session fixation guard, the last-login stamp and the audit
 * entry cannot be forgotten by one of the several routes that reach here:
 * password, OTP, and the second-factor challenge that follows either.
 */
final class CompleteLogin
{
    public function __construct(
        private readonly StatefulGuard $guard,
        private readonly Session $session,
        private readonly PendingLogin $pending,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(User $user, AuthFactor $firstFactor, bool $remember = false): void
    {
        $this->guard->login($user, $remember);

        // Session fixation: the identifier the browser arrived with must not
        // be the identifier it leaves authenticated with.
        $this->session->regenerate();

        $this->pending->forget();

        $user->forceFill([
            'last_login_at' => CarbonImmutable::now(),
            'last_login_ip' => Request::ip(),
        ])->saveQuietly();

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::UserLoggedIn,
            properties: ['factor' => $firstFactor->value],
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: Request::ip(),
        ));
    }
}
