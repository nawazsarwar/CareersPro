<?php

declare(strict_types=1);

namespace App\Domain\Identity\SecondFactor\Totp;

use App\Enums\AuthFactor;
use App\Models\User;
use Carbon\CarbonImmutable;

final class ConfirmTotp
{
    public function __construct(private readonly VerifyTotp $verify) {}

    /**
     * Confirms enrolment only on a code the authenticator produced, proving it
     * holds the secret.
     */
    public function handle(User $user, string $code): bool
    {
        if (! $this->verify->handle($user, $code, requireConfirmed: false)) {
            return false;
        }

        $user->twoFactorMethods()
            ->where('type', AuthFactor::Totp)
            ->update(['confirmed_at' => CarbonImmutable::now()]);

        return true;
    }
}
