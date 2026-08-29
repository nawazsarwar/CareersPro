<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\OtpPurpose;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

final class VerifyOtp
{
    public function __construct(private readonly RecordAuditEvent $audit) {}

    /**
     * The purpose is part of the lookup, so a code issued to sign in cannot
     * satisfy a second-factor challenge and vice versa (M03 §3).
     */
    public function handle(User $user, OtpPurpose $purpose, string $code): bool
    {
        $record = OtpCode::query()
            ->where('user_id', $user->getKey())
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->orderByDesc('created_at')
            ->first();

        if ($record === null || ! $record->isUsable()) {
            $this->auditFailure($user, $purpose, 'no_usable_code');

            return false;
        }

        // Counted before the comparison, so a crash mid-verification cannot
        // hand back a free attempt.
        $record->increment('attempts');

        if (! Hash::check($code, $record->code_hash)) {
            $this->auditFailure($user, $purpose, 'wrong_code');

            return false;
        }

        // Single-use (M03-R09): consumed the moment it succeeds, so a replay
        // of the same code finds nothing usable.
        $record->forceFill(['consumed_at' => CarbonImmutable::now()])->save();

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::OtpVerified,
            properties: ['purpose' => $purpose->value],
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: Request::ip(),
        ));

        return true;
    }

    /**
     * How many attempts remain against the code currently outstanding, so the
     * message can say "you have 2 attempts left" rather than a vague refusal.
     */
    public function remainingAttempts(User $user, OtpPurpose $purpose): int
    {
        $record = OtpCode::query()
            ->where('user_id', $user->getKey())
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->orderByDesc('created_at')
            ->first();

        if ($record === null) {
            return 0;
        }

        return max(0, (int) config('otp.max_attempts', 3) - $record->attempts);
    }

    private function auditFailure(User $user, OtpPurpose $purpose, string $stage): void
    {
        $this->audit->handle(new AuditEvent(
            event: AuditEventName::OtpFailed,
            properties: ['purpose' => $purpose->value, 'stage' => $stage],
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: Request::ip(),
        ));
    }
}
