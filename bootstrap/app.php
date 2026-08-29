<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\RequirePendingAuth;
use App\Http\Middleware\RequireTwoFactor;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ShareImpersonation;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            // engineering-standards.md §2.1 — each side gets its own file,
            // prefix, name prefix and middleware stack, so no route repeats
            // them and no controller can be reached from the wrong side.
            Route::middleware(['web', 'auth', 'verified', 'two-factor'])
                ->prefix('admin')
                ->name('admin.')
                ->group(__DIR__.'/../routes/admin.php');

            Route::middleware('web')
                ->name('frontend.')
                ->group(__DIR__.'/../routes/frontend.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            SetLocale::class,
            ShareImpersonation::class,
        ]);

        $middleware->alias([
            // The half-authenticated window between the first factor and the
            // challenge: `auth` would reject these users, `guest` would admit
            // anyone.
            'auth.pending' => RequirePendingAuth::class,

            // Standardised from the `2fa` alias earlier drafts used
            // (M03 §4).
            'two-factor' => RequireTwoFactor::class,

            // Overrides the framework's binding so the fallback redirect names
            // our prefixed route rather than `verification.notice`.
            'verified' => EnsureEmailIsVerified::class,
        ]);

        $middleware->redirectGuestsTo(static fn (): string => route('frontend.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
