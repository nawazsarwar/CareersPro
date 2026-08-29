<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Notification\Sms\SendSms;
use App\Domain\Notification\Sms\SmsDispatchFailed;
use App\Enums\AuditEventName;
use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Models\OtpCode;
use App\Models\User;
use App\Support\Crypto\BlindIndex;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

/**
 * Issues a one-time code (M03 §3).
 *
 * Three caps, each closing a different abuse: a resend cooldown stops a user
 * hammering the button, an hourly cap keyed on the destination stops one
 * handset being used to bill an SMS flood, and an attempt limit on the code
 * itself stops it being guessed. The destination cap is keyed on a blind index
 * so it holds without decrypting a single row.
 */
final class IssueOtp
{
    public function __construct(
        private readonly SendSms $sms,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(User $user, OtpPurpose $purpose, OtpChannel $channel): OtpIssueResult
    {
        $destination = $this->destinationFor($user, $channel);

        if ($destination === null) {
            return new OtpIssueResult(OtpIssueResult::NO_MOBILE);
        }

        $index = BlindIndex::of($destination);

        if (($retryAt = $this->cooldownUntil($index)) !== null) {
            return new OtpIssueResult(OtpIssueResult::COOLDOWN, retryAt: $retryAt);
        }

        if (($retryAt = $this->hourlyCapUntil($index)) !== null) {
            return new OtpIssueResult(OtpIssueResult::HOURLY_CAP, retryAt: $retryAt);
        }

        $code = $this->generateCode();

        $record = OtpCode::query()->create([
            'user_id' => $user->getKey(),
            'purpose' => $purpose,
            'channel' => $channel,
            'code_hash' => Hash::make($code),
            'destination_hash' => $index,
            'ip' => Request::ip(),
            'attempts' => 0,
            'expires_at' => CarbonImmutable::now()->addMinutes((int) config('otp.valid_minutes', 10)),
            'created_at' => CarbonImmutable::now(),
        ]);

        try {
            $this->sms->handle($destination, $this->body($code));
        } catch (SmsDispatchFailed) {
            // Fail closed. The code is burned rather than left usable, because
            // a code the user never received is a code someone else might.
            $record->forceFill(['consumed_at' => CarbonImmutable::now()])->save();

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::OtpFailed,
                properties: ['purpose' => $purpose->value, 'channel' => $channel->value, 'stage' => 'dispatch'],
                subject: $user,
                actorId: (int) $user->getKey(),
                actorIp: Request::ip(),
            ));

            return new OtpIssueResult(OtpIssueResult::GATEWAY_FAILED);
        }

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::OtpIssued,
            properties: ['purpose' => $purpose->value, 'channel' => $channel->value],
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: Request::ip(),
        ));

        return new OtpIssueResult(
            OtpIssueResult::SENT,
            maskedDestination: '•••••• '.mb_substr($destination, -4),
        );
    }

    private function destinationFor(User $user, OtpChannel $channel): ?string
    {
        return match ($channel) {
            OtpChannel::Sms => $user->profile?->mobile,
            OtpChannel::Email => $user->email,
        };
    }

    private function cooldownUntil(string $index): ?CarbonImmutable
    {
        $latest = OtpCode::query()
            ->where('destination_hash', $index)
            ->orderByDesc('created_at')
            ->value('created_at');

        if ($latest === null) {
            return null;
        }

        $until = CarbonImmutable::parse($latest)
            ->addMinutes((int) config('otp.resend_delay_minutes', 3));

        return $until->isFuture() ? $until : null;
    }

    private function hourlyCapUntil(string $index): ?CarbonImmutable
    {
        $windowStart = CarbonImmutable::now()->subHour();

        $issued = OtpCode::query()
            ->where('destination_hash', $index)
            ->where('created_at', '>=', $windowStart)
            ->orderBy('created_at')
            ->get(['created_at']);

        if ($issued->count() < (int) config('otp.max_per_hour', 5)) {
            return null;
        }

        // The window is rolling, so the retry time is one hour after the
        // oldest code still inside it -- a real time the user can act on,
        // which is what lets the message state it rather than say "later".
        return CarbonImmutable::parse($issued->first()?->created_at)->addHour();
    }

    private function generateCode(): string
    {
        $length = (int) config('otp.length', 6);

        return str_pad((string) random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    private function body(string $code): string
    {
        return __('auth.otp_sms', [
            'code' => $code,
            'minutes' => (int) config('otp.valid_minutes', 10),
        ]);
    }
}
