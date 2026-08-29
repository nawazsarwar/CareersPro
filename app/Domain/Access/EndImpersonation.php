<?php

declare(strict_types=1);

namespace App\Domain\Access;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Models\ImpersonationToken;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

final class EndImpersonation
{
    public function __construct(
        private readonly Session $session,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(): ?User
    {
        $actorId = $this->session->get('impersonator_id');
        $tokenId = $this->session->get('impersonation_token_id');

        if (! is_numeric($actorId)) {
            return null;
        }

        $actor = User::query()->find((int) $actorId);
        $target = Auth::user();

        if ($tokenId !== null) {
            ImpersonationToken::query()->whereKey($tokenId)
                ->update(['ended_at' => CarbonImmutable::now()]);
        }

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::ImpersonationEnded,
            properties: ['target_id' => $target === null ? null : (int) $target->getAuthIdentifier()],
            actorId: (int) $actorId,
            impersonatorId: (int) $actorId,
            actorIp: Request::ip(),
        ));

        $this->session->invalidate();

        if ($actor !== null) {
            Auth::guard('web')->login($actor);
            $this->session->regenerate();
        }

        return $actor;
    }
}
