<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Identity\CompleteLogin;
use App\Domain\Identity\CredentialResolver;
use App\Domain\Identity\PendingLogin;
use App\Domain\Identity\SecondFactor\ChallengeSecondFactor;
use App\Domain\Identity\SecondFactor\ResolveRequiredFactor;
use App\Enums\AuditEventName;
use App\Enums\AuthFactor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly CredentialResolver $resolver,
        private readonly ResolveRequiredFactor $requiredFactor,
        private readonly ChallengeSecondFactor $challenge,
        private readonly CompleteLogin $completeLogin,
        private readonly PendingLogin $pending,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function create(): View
    {
        return view('frontend.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $login = (string) $request->string('login');
        $credentials = $this->resolver->credentials($login, (string) $request->string('password'));

        if (! Auth::validate($credentials)) {
            $this->audit->handle(new AuditEvent(
                event: AuditEventName::LoginFailed,
                properties: ['field' => $this->resolver->resolve($login)],
                actorIp: $request->ip(),
            ));

            // One message for a wrong password and for an account that does
            // not exist. Distinguishing them turns this form into an
            // enumeration oracle.
            throw ValidationException::withMessages(['login' => __('auth.failed')]);
        }

        /** @var User $user */
        $user = User::query()->where($this->resolver->resolve($login), $login)->firstOrFail();

        if (! $user->status->canSignIn()) {
            throw ValidationException::withMessages(['login' => $user->status->signInMessage()]);
        }

        $required = $this->requiredFactor->for($user, AuthFactor::Password);

        if ($required !== null) {
            $this->pending->start($user, AuthFactor::Password, $request->boolean('remember'));
            $this->challenge->handle($user, $required);

            return redirect()->route('frontend.two-factor.challenge');
        }

        $this->completeLogin->handle($user, AuthFactor::Password, $request->boolean('remember'));

        return redirect()->intended(route('frontend.dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User) {
            $this->audit->handle(new AuditEvent(
                event: AuditEventName::UserLoggedOut,
                subject: $user,
                actorId: (int) $user->getKey(),
                actorIp: $request->ip(),
            ));
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.login');
    }
}
