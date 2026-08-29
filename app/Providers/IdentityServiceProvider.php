<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Identity\CompleteLogin;
use App\Domain\Identity\PendingLogin;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // PragmaRX\Google2FA is deliberately NOT named here. Its constructor
        // takes no required arguments, so the container resolves it on its
        // own, and DR-022's fence -- the library reachable only from
        // App\Domain\Identity\SecondFactor\Totp -- stays intact.

        $this->app->bind(PendingLogin::class, static fn ($app) => new PendingLogin($app['session.store']));

        $this->app->bind(CompleteLogin::class, function ($app): CompleteLogin {
            /** @var StatefulGuard $guard */
            $guard = Auth::guard('web');

            return new CompleteLogin(
                $guard,
                $app['session.store'],
                $app->make(PendingLogin::class),
                $app->make(\App\Domain\Audit\RecordAuditEvent::class),
            );
        });
    }
}
