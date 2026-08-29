<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The flow that terminates.
 *
 * The previous build had two half-built verification paths and neither
 * finished: Breeze's, which sent nothing because the user model did not
 * implement MustVerifyEmail, and a legacy `verified` column whose token was
 * never issued, behind a global middleware that logged out anybody with
 * `verified = 0`. Every new account was locked out permanently.
 */
class EmailVerificationController extends Controller
{
    public function __construct(private readonly RecordAuditEvent $audit) {}

    public function prompt(Request $request): View|RedirectResponse
    {
        return $request->user()?->hasVerifiedEmail() === true
            ? redirect()->route('frontend.dashboard')
            : view('frontend.auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('frontend.dashboard');
        }

        $request->fulfill();

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::UserEmailVerified,
            subject: $request->user(),
            actorId: (int) $request->user()->getKey(),
            actorIp: $request->ip(),
        ));

        return redirect()->route('frontend.dashboard')->with('status', __('auth.email_verified'));
    }

    public function send(Request $request): RedirectResponse
    {
        if ($request->user()?->hasVerifiedEmail() === true) {
            return redirect()->route('frontend.dashboard');
        }

        $request->user()?->sendEmailVerificationNotification();

        return back()->with('status', __('auth.verification_sent'));
    }
}
