<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * What a user proved, and what they may still be asked to prove.
 *
 * The password case matters as much as the others: DR-023 requires that an OTP
 * counts as the second factor after a password login but never after an OTP
 * login, which is only expressible if the factor already used is a value the
 * resolver can be handed.
 */
enum AuthFactor: string
{
    case Password = 'password';
    case Totp = 'totp';
    case Sms = 'sms';
    case Email = 'email';

    public function isSecondFactorCandidate(): bool
    {
        return $this !== self::Password;
    }

    /**
     * Owned here for the same reason as UserStatus::signInMessage(): a key
     * built by concatenation is invisible to a grep and to TranslationTest.
     */
    public function label(): string
    {
        return match ($this) {
            self::Password => __('auth.factor_password'),
            self::Totp => __('auth.factor_totp'),
            self::Sms => __('auth.factor_sms'),
            self::Email => __('auth.factor_email'),
        };
    }

    public function otpChannel(): ?OtpChannel
    {
        return match ($this) {
            self::Sms => OtpChannel::Sms,
            self::Email => OtpChannel::Email,
            default => null,
        };
    }
}
