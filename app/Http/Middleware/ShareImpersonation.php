<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Makes an impersonated session impossible to mistake for a real one.
 *
 * The banner is persistent and names both parties. An administrator who
 * forgets they are impersonating will act as somebody else and the record will
 * say so -- the record is right, but the action was a mistake, and the banner
 * is what prevents it.
 */
class ShareImpersonation
{
    public function handle(Request $request, Closure $next): Response
    {
        $actorId = $request->session()->get('impersonator_id');

        View::share('impersonator', is_numeric($actorId) ? User::query()->find((int) $actorId) : null);

        return $next($request);
    }
}
