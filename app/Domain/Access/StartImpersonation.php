<?php

declare(strict_types=1);

namespace App\Domain\Access;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\RoleSlug;
use App\Models\ImpersonationToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * An administrator assumes another user's session (M25-R08, R09, R15).
 *
 * Three properties the design insists on:
 *
 *   - The token is single-use and expiring. A reusable impersonation token is
 *     a standing credential for somebody else's account.
 *   - The session is invalidated on start, so the impersonated session is a
 *     new one and ending it cannot restore a session that saw both.
 *   - **The target's second factor is never consulted.** The actor has already
 *     cleared their own; reading, challenging or enrolling from the target's
 *     `two_factor_methods` would make impersonation a route to somebody else's
 *     authenticator.
 */
final class StartImpersonation
{
    private const TTL_SECONDS = 60;

    public function __construct(
        private readonly Session $session,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(User $actor, User $target): string
    {
        if ($actor->is($target)) {
            throw new RuntimeException('An actor cannot impersonate themselves.');
        }

        // A super administrator is never a target: impersonating one would be
        // a privilege escalation dressed as a support action.
        if ($target->hasRole(RoleSlug::SuperAdmin)) {
            throw new RuntimeException('A super administrator cannot be impersonated.');
        }

        $plain = Str::random(64);

        ImpersonationToken::query()->create([
            'actor_id' => $actor->getKey(),
            'target_id' => $target->getKey(),
            'token_hash' => Hash::make($plain),
            'actor_ip' => Request::ip(),
            'expires_at' => CarbonImmutable::now()->addSeconds(self::TTL_SECONDS),
        ]);

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::ImpersonationStarted,
            properties: ['target_id' => (int) $target->getKey()],
            subject: $target,
            actorId: (int) $actor->getKey(),
            impersonatorId: (int) $actor->getKey(),
            actorIp: Request::ip(),
            actorRole: $actor->auditRole(),
        ));

        return $plain;
    }

    /**
     * Consumes the token and switches the session.
     */
    public function consume(string $plain): User
    {
        $token = ImpersonationToken::query()
            ->whereNull('consumed_at')
            ->where('expires_at', '>', CarbonImmutable::now())
            ->latest('id')
            ->get()
            ->first(static fn (ImpersonationToken $candidate): bool => Hash::check($plain, $candidate->token_hash));

        if ($token === null) {
            throw new RuntimeException('That impersonation token is not usable.');
        }

        $token->forceFill(['consumed_at' => CarbonImmutable::now()])->save();

        /** @var User $target */
        $target = User::query()->findOrFail($token->target_id);

        $actorId = (int) $token->actor_id;

        // A new session, not the actor's with a flag on it.
        $this->session->invalidate();

        Auth::guard('web')->login($target);
        $this->session->regenerate();

        // Recorded so every subsequent entry names both parties (M26-R10).
        $this->session->put('impersonator_id', $actorId);
        $this->session->put('impersonation_token_id', $token->getKey());

        return $target;
    }
}
