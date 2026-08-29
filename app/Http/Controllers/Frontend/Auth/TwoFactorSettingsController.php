<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Identity\IssueRecoveryCodes;
use App\Domain\Identity\SecondFactor\Totp\ConfirmTotp;
use App\Domain\Identity\SecondFactor\Totp\EnrolTotp;
use App\Domain\Identity\TwoFactorPolicy;
use App\Enums\AuditEventName;
use App\Enums\AuthFactor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\VerifyCodeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorSettingsController extends Controller
{
    public function __construct(
        private readonly TwoFactorPolicy $policy,
        private readonly EnrolTotp $enrol,
        private readonly ConfirmTotp $confirm,
        private readonly IssueRecoveryCodes $recoveryCodes,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('frontend.auth.two-factor', [
            'user' => $user,
            'methods' => $user->twoFactorMethods,
            'available' => array_values(array_filter(
                AuthFactor::cases(),
                fn (AuthFactor $factor): bool => $this->policy->permits($user, $factor),
            )),
            'enforced' => $this->policy->requiredFor($user),
            'recoveryCodes' => session('recovery_codes'),
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $factor = $this->factor($type);

        if (! $this->policy->permits($user, $factor)) {
            throw ValidationException::withMessages(['factor' => __('auth.factor_unavailable')]);
        }

        if ($factor !== AuthFactor::Totp) {
            $method = $user->twoFactorMethods()->updateOrCreate(['type' => $factor], ['confirmed_at' => now()]);

            $this->auditEnrolment($user, $factor);

            return back()->with('status', __('auth.factor_added'))
                ->with('recovery_codes', $this->recoveryCodes->handle($user));
        }

        $method = $this->enrol->handle($user);

        // The secret is shown once, as a QR code and as text, and enrolment is
        // not complete until a code derived from it comes back.
        return back()
            ->with('totp_uri', $this->enrol->provisioningUri($user, $method))
            ->with('totp_svg', $this->enrol->qrCodeSvg($user, $method))
            ->with('totp_secret', $method->secret);
    }

    public function confirm(VerifyCodeRequest $request, string $type): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($this->factor($type) !== AuthFactor::Totp) {
            throw ValidationException::withMessages(['factor' => __('auth.factor_unavailable')]);
        }

        if (! $this->confirm->handle($user, (string) $request->string('code'))) {
            throw ValidationException::withMessages(['code' => __('auth.second_factor_wrong')]);
        }

        $this->auditEnrolment($user, AuthFactor::Totp);

        return back()
            ->with('status', __('auth.factor_added'))
            ->with('recovery_codes', $this->recoveryCodes->handle($user));
    }

    public function destroy(Request $request, string $type): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $factor = $this->factor($type);

        // M03-R23. Where an administrator has enforced 2FA, the last remaining
        // method cannot be removed -- otherwise the enforcement is advisory.
        if (! $this->policy->mayRemove($user, $factor)) {
            throw ValidationException::withMessages(['factor' => __('auth.factor_last_enforced')]);
        }

        $user->twoFactorMethods()->where('type', $factor)->delete();

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::SecondFactorRemoved,
            properties: ['factor' => $factor->value],
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: $request->ip(),
        ));

        return back()->with('status', __('auth.factor_removed'));
    }

    private function factor(string $type): AuthFactor
    {
        $factor = AuthFactor::tryFrom($type);

        if ($factor === null || ! $factor->isSecondFactorCandidate()) {
            throw ValidationException::withMessages(['factor' => __('auth.factor_unavailable')]);
        }

        return $factor;
    }

    private function auditEnrolment(User $user, AuthFactor $factor): void
    {
        $this->audit->handle(new AuditEvent(
            event: AuditEventName::SecondFactorEnrolled,
            properties: ['factor' => $factor->value],
            subject: $user,
            actorId: (int) $user->getKey(),
            actorIp: request()->ip(),
        ));
    }
}
