<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domain\Access\EndImpersonation;
use App\Domain\Access\StartImpersonation;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class ImpersonationController extends Controller
{
    public function __construct(
        private readonly StartImpersonation $start,
        private readonly EndImpersonation $end,
    ) {}

    public function store(User $user): RedirectResponse
    {
        /** @var User $actor */
        $actor = request()->user();

        $this->authorize('start', $user);

        try {
            $token = $this->start->handle($actor, $user);
            $this->start->consume($token);
        } catch (RuntimeException $e) {
            return back()->withErrors(['impersonation' => $e->getMessage()]);
        }

        return redirect()->route('frontend.dashboard');
    }

    public function destroy(): RedirectResponse
    {
        $actor = $this->end->handle();

        return $actor === null
            ? redirect()->route('frontend.login')
            : redirect()->route('admin.home');
    }
}
