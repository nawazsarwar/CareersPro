<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend\Auth;

use App\Domain\Identity\RegisterCandidate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly RegisterCandidate $register) {}

    public function create(): View
    {
        return view('frontend.auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        /** @var array{name: string, email: string, password: string} $data */
        $data = $request->safe()->only(['name', 'email', 'password']);

        $user = $this->register->handle($data);

        Auth::login($user);

        // To the verification notice, not to a dashboard behind `verified`.
        // The previous build logged the user in and redirected them straight
        // into a middleware that logged them back out.
        return redirect()->route('frontend.verification.notice');
    }
}
