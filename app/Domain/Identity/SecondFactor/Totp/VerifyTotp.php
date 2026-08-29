<?php

declare(strict_types=1);

namespace App\Domain\Identity\SecondFactor\Totp;

use App\Enums\AuthFactor;
use App\Models\TwoFactorMethod;
use App\Models\User;
use Carbon\CarbonImmutable;
use PragmaRX\Google2FA\Google2FA;

final class VerifyTotp
{
    public function __construct(private readonly Google2FA $google2fa) {}

    public function handle(User $user, string $code, bool $requireConfirmed = true): bool
    {
        $method = $user->twoFactorMethods()
            ->where('type', AuthFactor::Totp)
            ->when($requireConfirmed, static fn ($query) => $query->whereNotNull('confirmed_at'))
            ->first();

        if (! $method instanceof TwoFactorMethod || $method->secret === null) {
            return false;
        }

        // A one-step window either side, tolerating clock drift between the
        // handset and the server. Wider would meaningfully extend the life of
        // an intercepted code.
        $verified = $this->google2fa->verifyKey($method->secret, $code, 1);

        if ($verified) {
            $method->forceFill(['last_used_at' => CarbonImmutable::now()])->save();
        }

        return $verified;
    }
}
