<?php

declare(strict_types=1);

namespace App\Domain\Identity;

/**
 * Decides which column a submitted identifier is matched against (DR-008).
 *
 * Not Laravel's `username()` override, which returns a single fixed column
 * name and therefore cannot express "email for applicants, email or employee
 * ID for staff". One form, one field, no branch in the UI.
 */
final class CredentialResolver
{
    public const EMAIL = 'email';

    public const USERNAME = 'username';

    public function resolve(string $login): string
    {
        return filter_var($login, FILTER_VALIDATE_EMAIL) !== false
            ? self::EMAIL
            : self::USERNAME;
    }

    /**
     * @return array{email?: string, username?: string, password: string}
     */
    public function credentials(string $login, string $password): array
    {
        return [$this->resolve($login) => $login, 'password' => $password];
    }
}
