<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as BaseEnsureEmailIsVerified;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The framework's `verified` middleware, with our route name as its default.
 *
 * Its parent falls back to a route called `verification.notice`; ours is
 * `frontend.verification.notice`, because engineering-standards §2.1 requires
 * every HTTP artefact to sit under an Admin or Frontend prefix. Supplying the
 * parameter at each of the several usages would work until somebody forgot
 * one, and the failure would be a 500 on the one path an unverified user is
 * guaranteed to take.
 */
class EnsureEmailIsVerified extends BaseEnsureEmailIsVerified
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        return parent::handle($request, $next, $redirectToRoute ?? 'frontend.verification.notice');
    }
}
