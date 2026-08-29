<?php

declare(strict_types=1);

namespace App\Domain\Identity\SecondFactor;

use App\Domain\Identity\SecondFactor\Totp\VerifyTotp;
use App\Domain\Identity\VerifyOtp;
use App\Enums\AuthFactor;
use App\Enums\OtpPurpose;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class VerifySecondFactor
{
    public function __construct(
        private readonly VerifyTotp $verifyTotp,
        private readonly VerifyOtp $verifyOtp,
    ) {}

    public function handle(User $user, AuthFactor $factor, string $code): bool
    {
        if ($factor === AuthFactor::Totp) {
            return $this->verifyTotp->handle($user, $code)
                || $this->consumeRecoveryCode($user, $code);
        }

        return $this->verifyOtp->handle($user, OtpPurpose::TwoFactor, $code)
            || $this->consumeRecoveryCode($user, $code);
    }

    /**
     * A recovery code satisfies any second factor.
     *
     * That is the point of it: the user who reaches for one has lost the
     * handset that would have satisfied the others. Each is single-use and
     * marked the moment it is spent.
     */
    private function consumeRecoveryCode(User $user, string $code): bool
    {
        $candidates = $user->recoveryCodes()->whereNull('used_at')->get();

        foreach ($candidates as $candidate) {
            if (Hash::check($code, $candidate->code_hash)) {
                $candidate->forceFill(['used_at' => now()])->save();

                return true;
            }
        }

        return false;
    }
}
