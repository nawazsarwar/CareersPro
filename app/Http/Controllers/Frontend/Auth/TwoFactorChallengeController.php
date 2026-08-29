<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Identity\CompleteLogin;
use App\Domain\Identity\PendingLogin;
use App\Domain\Identity\SecondFactor\ChallengeSecondFactor;
use App\Domain\Identity\SecondFactor\ResolveRequiredFactor;
use App\Domain\Identity\SecondFactor\VerifySecondFactor;
use App\Enums\AuthFactor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\VerifyCodeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorChallengeController extends Controller
{
    public function __construct(
        private readonly PendingLogin $pending,
        private readonly ResolveRequiredFactor $requiredFactor,
        private readonly ChallengeSecondFactor $challenge,
        private readonly VerifySecondFactor $verify,
        private readonly CompleteLogin $completeLogin,
    ) {}

    public function create(): View
    {
        $user = $this->pending->user();

        return view('frontend.auth.two-factor-challenge', [
            'factor' => $this->demandedFactor(),
            'destination' => $user?->profile?->maskedMobile(),
        ]);
    }

    public function store(VerifyCodeRequest $request): RedirectResponse
    {
        $user = $this->pending->user();
        $factor = $this->demandedFactor();

        if (! $user instanceof User || $factor === null) {
            return redirect()->route('frontend.login');
        }

        if (! $this->verify->handle($user, $factor, (string) $request->string('code'))) {
            throw ValidationException::withMessages(['code' => __('auth.second_factor_wrong')]);
        }

        $this->completeLogin->handle(
            $user,
            $this->pending->factorUsed() ?? AuthFactor::Password,
            $this->pending->remember(),
        );

        return redirect()->intended(route('frontend.dashboard', absolute: false));
    }

    public function resend(): RedirectResponse
    {
        $user = $this->pending->user();
        $factor = $this->demandedFactor();

        if (! $user instanceof User || $factor === null) {
            return redirect()->route('frontend.login');
        }

        $result = $this->challenge->handle($user, $factor);

        return back()
            ->with('otp_result', $result?->reason)
            ->with('otp_retry_at', $result?->retryAt?->toIso8601String());
    }

    private function demandedFactor(): ?AuthFactor
    {
        $user = $this->pending->user();
        $used = $this->pending->factorUsed();

        if (! $user instanceof User || $used === null) {
            return null;
        }

        return $this->requiredFactor->for($user, $used);
    }
}
