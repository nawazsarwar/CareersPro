<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Locked = 'locked';

    public function canSignIn(): bool
    {
        return $this === self::Active;
    }

    /**
     * The message shown when this status blocks a sign-in.
     *
     * Owned by the enum rather than built as 'auth.status_'.$status->value at
     * the call site: a concatenated key cannot be found by a grep, cannot be
     * checked by TranslationTest, and goes missing silently when a case is
     * added.
     */
    public function signInMessage(): string
    {
        return match ($this) {
            self::Active => '',
            self::Suspended => __('auth.status_suspended'),
            self::Locked => __('auth.status_locked'),
        };
    }
}
