<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Identity\IssueOtp;
use App\Domain\Identity\VerifyOtp;
use App\Enums\AuditEventName;
use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\MobileRequest;
use App\Http\Requests\Frontend\Auth\VerifyCodeRequest;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class MobileVerificationController extends Controller
{
    public function __construct(
        private readonly IssueOtp $issueOtp,
        private readonly VerifyOtp $verifyOtp,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function send(MobileRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $profile = $user->profile ?? new Profile;

        // Recording the number does not verify it: mobile_verified_at is set
        // only once a code sent to it comes back (M03-R24). Setting both here
        // would let anyone claim any handset.
        $profile->forceFill([
            'user_id' => $user->getKey(),
            'mobile' => (string) $request->string('mobile'),
            'mobile_verified_at' => null,
        ])->save();

        $user->setRelation('profile', $profile);

        $result = $this->issueOtp->handle($user, OtpPurpose::MobileVerify, OtpChannel::Sms);

        return back()
            ->with('otp_result', $result->reason)
            ->with('otp_retry_at', $result->retryAt?->toIso8601String())
            ->with('otp_destination', $result->maskedDestination);
    }

    public function verify(VerifyCodeRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user === null || ! $this->verifyOtp->handle($user, OtpPurpose::MobileVerify, (string) $request->string('code'))) {
            throw ValidationException::withMessages(['code' => __('auth.otp_wrong', [
                'attempts' => $user === null ? 0 : $this->verifyOtp->remainingAttempts($user, OtpPurpose::MobileVerify),
            ])]);
        }

        $user->profile?->forceFill(['mobile_verified_at' => now()])->save();

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::MobileVerified,
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: $request->ip(),
        ));

        return back()->with('status', __('auth.mobile_verified'));
    }
}
