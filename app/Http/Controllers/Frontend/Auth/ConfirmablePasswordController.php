<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * The gate in front of second-factor enrolment and, in M25, impersonation.
 * A live session is not sufficient authority to change how the account is
 * secured.
 */
class ConfirmablePasswordController extends Controller
{
    public function show(): View
    {
        return view('frontend.auth.confirm-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $user = $request->user();

        if ($user === null || ! Auth::guard('web')->validate([
            'email' => $user->getAuthIdentifierName() === 'email' ? $user->email : $user->email,
            'password' => $request->string('password'),
        ])) {
            throw ValidationException::withMessages(['password' => __('auth.password')]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('frontend.two-factor.index', absolute: false));
    }
}
