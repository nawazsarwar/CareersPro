<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerAuthRoutes();
        $this->registerRateLimiters();
    }

    /**
     * The framework's notifications build URLs from unprefixed route names --
     * `verification.verify`, `password.reset`. Ours live under the `frontend.`
     * prefix required by engineering-standards §2.1, so each is pointed at the
     * real name here rather than the routes being un-prefixed to suit the
     * framework. The `verified` middleware is handled the same way, in
     * App\Http\Middleware\EnsureEmailIsVerified.
     */
    private function registerAuthRoutes(): void
    {
        VerifyEmail::createUrlUsing(static fn (mixed $notifiable): string => URL::temporarySignedRoute(
            'frontend.verification.verify',
            now()->addMinutes((int) config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1((string) $notifiable->getEmailForVerification()),
            ],
        ));

        ResetPassword::createUrlUsing(static fn (mixed $notifiable, string $token): string => route(
            'frontend.password.reset',
            ['token' => $token, 'email' => $notifiable->getEmailForPasswordReset()],
        ));
    }

    /**
     * M03 §4 — the password-reset limiter.
     *
     * Laravel's broker already throttles one request per address per 60
     * seconds. That stops rapid repeats; this stops slow enumeration across an
     * hour, which the broker does not.
     */
    private function registerRateLimiters(): void
    {
        RateLimiter::for('password-reset', static fn (Request $request): Limit => Limit::perHour(5)
            ->by($request->input('email').'|'.$request->ip()));

        RateLimiter::for('api', static fn (Request $request): Limit => Limit::perMinute(60)
            ->by($request->user()?->getAuthIdentifier() ?? $request->ip()));
    }
}
