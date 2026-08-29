<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Identity\TwoFactorPolicy;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * M03-R22 — where a second factor is enforced for a user's class, routes
 * behind this middleware are refused until they enrol one.
 *
 * It sends them to the enrolment screen rather than logging them out: an
 * administrator who cannot reach the page that would fix the problem is an
 * administrator who cannot fix it.
 */
class RequireTwoFactor
{
    public function __construct(private readonly TwoFactorPolicy $policy) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return $next($request);
        }

        if (! $this->policy->requiredFor($user)) {
            return $next($request);
        }

        if ($user->confirmedFactors() !== []) {
            return $next($request);
        }

        return redirect()->route('frontend.two-factor.index')
            ->with('status', __('auth.two_factor_required'));
    }
}
