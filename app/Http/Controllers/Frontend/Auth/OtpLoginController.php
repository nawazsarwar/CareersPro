<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Identity\CompleteLogin;
use App\Domain\Identity\IssueOtp;
use App\Domain\Identity\OtpIssueResult;
use App\Domain\Identity\PendingLogin;
use App\Domain\Identity\SecondFactor\ChallengeSecondFactor;
use App\Domain\Identity\SecondFactor\ResolveRequiredFactor;
use App\Domain\Identity\StartOtpLogin;
use App\Domain\Identity\VerifyOtp;
use App\Enums\AuthFactor;
use App\Enums\OtpChannel;
use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\OtpLoginRequest;
use App\Http\Requests\Frontend\Auth\VerifyCodeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Passwordless sign-in (DR-023).
 *
 * Every outcome of `store` lands on the same screen. The reason is stated
 * there, never in response to the first submit, or the form would report
 * whether an account exists.
 */
class OtpLoginController extends Controller
{
    public function __construct(
        private readonly StartOtpLogin $start,
        private readonly IssueOtp $issueOtp,
        private readonly VerifyOtp $verifyOtp,
        private readonly PendingLogin $pending,
        private readonly ResolveRequiredFactor $requiredFactor,
        private readonly ChallengeSecondFactor $challenge,
        private readonly CompleteLogin $completeLogin,
    ) {}

    public function store(OtpLoginRequest $request): RedirectResponse
    {
        $result = $this->start->handle((string) $request->string('login'));

        return redirect()
            ->route('frontend.login.otp.verify')
            ->with('otp_result', $result->reason)
            ->with('otp_retry_at', $result->retryAt?->toIso8601String())
            ->with('otp_destination', $result->maskedDestination);
    }

    public function create(): View
    {
        return view('frontend.auth.otp-verify', [
            'user' => $this->pending->user(),
            'reason' => session('otp_result', OtpIssueResult::SENT),
            'retryAt' => session('otp_retry_at'),
            'destination' => session('otp_destination'),
        ]);
    }

    public function verify(VerifyCodeRequest $request): RedirectResponse
    {
        $user = $this->pending->user();

        if (! $user instanceof User) {
            return redirect()->route('frontend.login');
        }

        if (! $this->verifyOtp->handle($user, OtpPurpose::Login, (string) $request->string('code'))) {
            $remaining = $this->verifyOtp->remainingAttempts($user, OtpPurpose::Login);

            throw ValidationException::withMessages([
                'code' => $remaining > 0
                    ? __('auth.otp_wrong', ['attempts' => $remaining])
                    : __('auth.otp_expired'),
            ]);
        }

        // DR-023's arithmetic: the SMS channel has just served as the FIRST
        // factor, so it is excluded from what may serve as the second.
        $required = $this->requiredFactor->for($user, AuthFactor::Sms);

        if ($required !== null) {
            $this->pending->start($user, AuthFactor::Sms);
            $this->challenge->handle($user, $required);

            return redirect()->route('frontend.two-factor.challenge');
        }

        $this->completeLogin->handle($user, AuthFactor::Sms);

        return redirect()->intended(route('frontend.dashboard', absolute: false));
    }

    public function resend(): RedirectResponse
    {
        $user = $this->pending->user();

        if (! $user instanceof User) {
            return redirect()->route('frontend.login');
        }

        $result = $this->issueOtp->handle($user, OtpPurpose::Login, OtpChannel::Sms);

        return back()
            ->with('otp_result', $result->reason)
            ->with('otp_retry_at', $result->retryAt?->toIso8601String())
            ->with('otp_destination', $result->maskedDestination);
    }
}
