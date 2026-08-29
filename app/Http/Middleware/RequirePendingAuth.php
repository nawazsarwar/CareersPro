<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Identity\PendingLogin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the window between the first factor and the challenge (M03 §4).
 *
 * The user is identified but not signed in. `auth` would reject them, `guest`
 * would admit anyone, so neither is correct and this exists instead.
 */
class RequirePendingAuth
{
    public function __construct(private readonly PendingLogin $pending) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->pending->exists()) {
            $this->pending->forget();

            return redirect()->route('frontend.login')
                ->withErrors(['login' => __('auth.pending_expired')]);
        }

        return $next($request);
    }
}
