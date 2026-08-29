<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function __construct(private readonly RecordAuditEvent $audit) {}

    public function create(Request $request, string $token): View
    {
        return view('frontend.auth.reset-password', ['token' => $token, 'email' => $request->string('email')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(12)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                    'must_change_password' => false,
                ])->save();

                event(new PasswordReset($user));

                $this->audit->handle(new AuditEvent(
                    event: AuditEventName::PasswordReset,
                    subject: $user,
                    actorId: (int) $user->getKey(),
                    actorIp: request()->ip(),
                ));
            }
        );

        if ($status !== Password::PasswordReset) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return redirect()->route('frontend.login')->with('status', __($status));
    }
}
